<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\MasterDepartement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskController extends Controller
{
    // Index - Menampilkan semua tugas dengan filter
    public function index(Request $request)
    {

        // Mulai query
        $query = Task::with(['departement', 'assignees']);

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority != 'all') {
            $query->where('priority', $request->priority); // Logic filter
        }

        // Filter by departement (created_by)
        if ($request->filled('departement') && $request->departement !== 'all') {
            $query->where('created_by', $request->departement);
        }

        // SORTING - dengan handle jika kosong
        if ($request->filled('sort_by') && $request->sort_by !== '') {
            switch ($request->sort_by) {
                case 'due_date_asc':
                    $query->orderBy('due_date', 'asc');
                    break;
                case 'due_date_desc':
                    $query->orderBy('due_date', 'desc');
                    break;
                case 'created_at_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'created_at_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'priority_desc':
                    $query->orderBy('priority', 'desc');
                    break;
                case 'priority_asc':
                    $query->orderBy('priority', 'asc');
                    break;
            }
        } else {
            // Default sorting jika tidak dipilih
            $query->orderBy('created_at', 'desc');
        }
        // Pagination dengan query string (agar filter tetap saat paging)
        $tasks = $query->paginate()->withQueryString();

        // Stats (tidak terpengaruh filter)
        $stats = [
            'total' => Task::count(),
            'pending' => Task::where('status', 'pending')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
            'review' => Task::where('status', 'review')->count(),
            'my_tasks' => DB::table('task_user')
                ->where('user_id', Auth::id())
                ->count(),
        ];

        // Hitung jumlah task yang overdue dan due soon untuk dashboard summary
        $overdueCount = Task::where('due_date', '<', Carbon::now())
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $dueSoonCount = Task::whereBetween('due_date', [Carbon::now(), Carbon::now()->addDays(3)])
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        $stats['overdue'] = $overdueCount;
        $stats['due_soon'] = $dueSoonCount;

        // Ambil data departemen untuk dropdown filter
        $departements = MasterDepartement::where('active', 1)->get();

        return view('dashboard', compact('tasks', 'stats', 'request', 'departements'));
    }

    // Create - Form buat tugas baru
    public function create()
    {
        $users = User::all();
        $departements = MasterDepartement::where('active', 1)->get();
        return view('tasks.create', compact('users', 'departements'));
    }

    // Store - Simpan tugas baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,review,completed',
            'priority' => 'required|integer|min:1|max:4',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'departement_id' => 'required|exists:master_department,id',
            'assignees' => 'required|array|min:1',
            'assignees.*' => 'exists:users,id'
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'start_date' => $validated['start_date'],
            'due_date' => $validated['due_date'],
            // 'departement_id' => $validated['departement_id'], // PERBAIKAN DI SINI
            'created_by' => $validated['departement_id'],
            // 'created_by' => Auth::id(), // Tambahkan created_by dengan user yang login
        ]);

        $task->assignees()->sync($validated['assignees']);

        return redirect()->route('tasks.index')
            ->with('success', 'Tugas berhasil dibuat!');
    }

    // Show - Detail tugas
    public function show(Task $task)
    {
        $task->load(['departement', 'assignees']);

        // Hitung hari tersisa untuk view detail
        $daysRemaining = null;
        $isOverdue = false;
        $isDueSoon = false;

        if ($task->due_date) {
            $now = Carbon::now();
            $dueDate = Carbon::parse($task->due_date);

            if ($dueDate->isPast() && in_array($task->status, ['pending', 'in_progress'])) {
                $isOverdue = true;
                $daysRemaining = -$dueDate->diffInDays($now);
            } elseif ($dueDate->isFuture()) {
                $daysRemaining = $now->diffInDays($dueDate, false);
                $isDueSoon = $daysRemaining <= 3;
            }
        }

        return view('tasks.show', compact('task', 'daysRemaining', 'isOverdue', 'isDueSoon'));
    }

    // Edit - Form edit tugas
    public function edit(Task $task)
    {
        // Cek di method edit juga
        if ($task->status === 'completed') {
            return redirect()->route('tasks.index')
                ->with('error', 'Cannot edit completed tasks.');
        }

        $users = User::all();
        $departements = MasterDepartement::where('active', 1)->get();
        $task->load(['departement', 'assignees']);
        return view('tasks.edit', compact('task', 'users', 'departements'));
    }


    // Update - Update tugas
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|integer|in:1,2,3,4',
            'status' => 'required|in:pending,in_progress,review,completed',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'departement_id' => 'required|exists:master_department,id',
            'assignees' => 'required|array|min:1',
            'assignees.*' => 'exists:users,id'
        ]);

        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'start_date' => $validated['start_date'],
            'due_date' => $validated['due_date'],
            'created_by' => $validated['departement_id'],
        ]);


        $task->assignees()->sync($validated['assignees']);

        return redirect()->route('tasks.index')

            ->with('success', 'Task updated successfully!');
    }

    // Destroy - Hapus tugas
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Task Successfully Deleted!');
    }

    // API untuk mendapatkan task yang overdue (opsional)
    public function getOverdueTasks()
    {
        $overdueTasks = Task::where('due_date', '<', Carbon::now())
            ->whereIn('status', ['pending', 'in_progress'])
            ->with(['departement', 'assignees'])
            ->get();

        return response()->json([
            'count' => $overdueTasks->count(),
            'tasks' => $overdueTasks
        ]);
    }
}
