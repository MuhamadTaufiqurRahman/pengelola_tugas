<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="p-4">
    <!-- Success Alert -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Error Alert -->
    @if (session('error'))
        <div class="max-w-7xl mx-auto mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w mx-auto">
        <!-- Navigation -->
        <nav class="bg-gray-100 rounded-2xl shadow-lg mb-6">
            <div class="max-w mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex flex-wrap justify-between items-center gap-3 py-3">
                    <!-- Logo & Title -->
                    <div class="flex items-center min-w-0 flex-1">
                        <div
                            class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-lg md:rounded-xl overflow-hidden mr-3">
                            <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-contain p-1"
                                alt="Logo">
                        </div>
                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-Black truncate">
                            Task Manager
                        </h1>
                    </div>

                    <!-- User & Logout -->
                    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                        <!-- User Name (hide on very small screens) -->
                        <span
                            class="xs:inline text-black font-medium text-sm sm:text-base truncate max-w-[120px] sm:max-w-none">
                            <i class="bi bi-person-circle mr-1 sm:mr-2"></i>
                            {{ Str::limit(Auth::user()->name, 15) }}
                        </span>

                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm sm:text-base">
                                <i class="bi bi-box-arrow-right mr-1 sm:mr-2"></i>
                                <span class="hidden sm:inline">Logout</span>
                                <span class="sm:hidden"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <div class="dashboard-card p-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                <!-- Title & Subtitle -->
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl sm:text-3xl font-bold text-black flex items-center">
                        <i class="bi bi-list-task text-black mr-2 sm:mr-3 flex-shrink-0"></i>
                        <span class="truncate">Task Dashboard</span>

                    </h2>
                    <p class="text-black mt-1 sm:mt-2 sm:ml-12 text-sm sm:text-base">
                        Manage and track your task progress
                    </p>

                </div>

                <!-- Create Button -->
                <div class="w-full sm:w-auto flex-shrink-0">
                    <a href="{{ route('tasks.create') }}"
                        class="btn-primary-custom w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-6 py-3 text-white rounded-xl font-semibold text-sm sm:text-base">
                        <i class="bi bi-plus-circle mr-2 flex-shrink-0"></i>
                        <span class="truncate">Create New Task</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="stat-card border-purple-500">
                    <div class="p-4 text-center">
                        <div class="text-3xl font-bold text-purple-600 mb-1">{{ $stats['total'] }}</div>
                        <p class="text-purple-600 text-sm">Task Count</p>
                    </div>
                </div>
                <div class="stat-card border-gray-600">
                    <div class="p-4 text-center">
                        <div class="text-3xl font-bold text-gray-600 mb-1">{{ $stats['pending'] }}</div>
                        <p class="text-gray-600 text-sm">Pending</p>
                    </div>
                </div>
                <div class="stat-card border-blue-500">
                    <div class="p-4 text-center">
                        <div class="text-3xl font-bold text-blue-600 mb-1">{{ $stats['in_progress'] }}</div>
                        <p class="text-blue-600 text-sm">In Progress</p>
                    </div>
                </div>
                <div class="stat-card border-yellow-600">
                    <div class="p-4 text-center">
                        <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $stats['review'] }}</div>
                        <p class="text-yellow-600 text-sm">Review</p>
                    </div>
                </div>
                <div class="stat-card border-green-500">
                    <div class="p-4 text-center">
                        <div class="text-3xl font-bold text-green-600 mb-1">{{ $stats['completed'] }}</div>
                        <p class="text-green-600 text-sm">Completed</p>
                    </div>
                </div>

            </div>

            <!-- Deadline Warning Summary -->
            @php
                $overdueCount = 0;
                $dueTodayCount = 0;
                $dueSoonCount = 0;

                foreach ($tasks as $task) {
                    if ($task->due_date) {
                        $now = now()->startOfDay();
                        $dueDate = $task->due_date->startOfDay();
                        $daysDiff = $now->diffInDays($dueDate, false);

                        if ($daysDiff < 0 && in_array($task->status, ['pending', 'in_progress', 'review'])) {
                            $overdueCount++;
                        } elseif ($daysDiff == 0 && in_array($task->status, ['pending', 'in_progress', 'review'])) {
                            $dueTodayCount++;
                        } elseif (
                            $daysDiff > 0 &&
                            $daysDiff <= 3 &&
                            in_array($task->status, ['pending', 'in_progress', 'review'])
                        ) {
                            $dueSoonCount++;
                        }
                    }
                }
            @endphp

            @if ($overdueCount > 0 || $dueTodayCount > 0 || $dueSoonCount > 0)
                <div class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        @if ($dueSoonCount > 0)
                            <div class="bg-yellow-200 border border-yellow-800 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="bg-yellow-300 p-3 rounded-lg mr-4">
                                        <i class="bi bi-exclamation-circle text-yellow-800 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-yellow-800">{{ $dueSoonCount }} Tasks nearing their
                                            deadline</h4>
                                        <p class="text-yellow-600 text-sm">Tasks with Due in the next 3 days</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($dueTodayCount > 0)
                            <div class="bg-yellow-200 border border-yellow-800 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="bg-yellow-300 p-3 rounded-lg mr-4">
                                        <i class="bi bi-clock text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-yellow-800">{{ $dueTodayCount }} Task Due To Day
                                        </h4>
                                        <p class="text-yellow-600 text-sm">Tasks to be completed today</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($overdueCount > 0)
                            <div class="bg-red-200 border border-red-800 rounded-xl p-4">
                                <div class="flex items-center">
                                    <div class="bg-red-300 p-3 rounded-lg mr-4">
                                        <i class="bi bi-exclamation-triangle text-red-800 text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-red-800">{{ $overdueCount }} Overdue task</h4>
                                        <p class="text-red-600 text-sm">Tasks past their due date</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="mb-6 p-4 bg-gray-200 rounded-xl shadow">
                <form method="GET" action="{{ route('tasks.index') }}" id="filterForm" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-1">Status</label>
                            <select name="status" id="statusSelect"
                                class="w-full px-3 py-3 border rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-transparent">
                                <option value="all">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="in_progress"
                                    {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                    In Progress</option>
                                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-1">Priority</label>
                            <select name="priority" id="prioritySelect"
                                class="w-full px-3 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="all">All Priority</option>
                                <option value="1" {{ request('priority') == '1' ? 'selected' : '' }}>Low</option>
                                <option value="2" {{ request('priority') == '2' ? 'selected' : '' }}>Medium
                                </option>
                                <option value="3" {{ request('priority') == '3' ? 'selected' : '' }}>High
                                </option>
                                <option value="4" {{ request('priority') == '4' ? 'selected' : '' }}>Urgent
                                </option>
                            </select>
                        </div>

                        <!-- Departemen Filter - STYLE SAMA DENGAN EDIT.BLADE -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-1">
                                Department
                            </label>
                            <select name="departement" id="departementSelect"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">-- Select Department --</option>
                                @foreach ($departements as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ old('departement_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }} {{-- NAMA yang ditampilkan --}}
                                    </option>
                                @endforeach
                            </select>
                            @error('departement_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            {{-- <option value="all">All Department</option>
                                <option value="1" {{ request('departement') == '1' ? 'selected' : '' }}>Super
                                    Admin</option>
                                <option value="2" {{ request('departement') == '2' ? 'selected' : '' }}>Legal
                                </option>
                                <option value="3" {{ request('departement') == '3' ? 'selected' : '' }}>Tax
                                </option>
                                <option value="4" {{ request('departement') == '4' ? 'selected' : '' }}>Finance
                                    Accounting
                                </option>
                                <option value="5" {{ request('departement') == '5' ? 'selected' : '' }}>
                                    Information Technology
                                </option>
                                <option value="6" {{ request('departement') == '6' ? 'selected' : '' }}>Marketing
                                </option>
                                <option value="7" {{ request('departement') == '7' ? 'selected' : '' }}>HRGA
                                </option>
                                <option value="8" {{ request('departement') == '8' ? 'selected' : '' }}>
                                    Procurement
                                </option>
                                <option value="9" {{ request('departement') == '9' ? 'selected' : '' }}>Boutique
                                    Store
                                </option>
                                <option value="10" {{ request('departement') == '10' ? 'selected' : '' }}>Board of
                                    Director
                                </option> --}}
                            </select>

                        </div>

                        <!-- SORTING SELECT - TAMBAHKAN INI -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-1">Sort By</label>
                            <select name="sort_by" id="sortSelect"
                                class="w-full px-3 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="">-- Default --</option>
                                <option value="created_at_desc"
                                    {{ request('sort_by') == 'created_at_desc' ? 'selected' : '' }}>
                                    Start Date (New/Old)
                                </option>
                                <option value="created_at_asc"
                                    {{ request('sort_by') == 'created_at_asc' ? 'selected' : '' }}>
                                    Start Date (Old/new)
                                </option>
                                <option value="due_date_desc"
                                    {{ request('sort_by') == 'due_date_desc' ? 'selected' : '' }}>
                                    Due Date (Nearest/Farthest)
                                </option>
                                <option value="due_date_asc"
                                    {{ request('sort_by') == 'due_date_asc' ? 'selected' : '' }}>
                                    Due Date Asc (Farthest/Nearest)
                                </option>
                            </select>
                        </div>

                        <!-- Search Input (jika ada di URL, simpan sebagai hidden) -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <!-- Reset Button -->
                        <div class="flex items-end">
                            <a href="{{ route('tasks.index') }}"
                                class="w-full px-4 py-3 bg-gray-50 text-black rounded-lg hover:bg-red-300 transition text-center">
                                <i class="bi bi-arrow-clockwise mr-2"></i>Reset All
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex justify-between items-center">
                <div class="text-sm text-black font-semibold">
                    Total found: <span class="font-bold">{{ $tasks->total() }}</span> Task Entries
                </div>
            </div>
            </form>
        </div>
        <br>

        <!-- Tasks List -->
        @if ($tasks->isEmpty())
            <div class="text-center py-12 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-2xl">
                <i class="bi bi-inbox text-5xl text-gray-400 mb-4"></i>
                <h4 class="text-2xl font-bold text-gray-700 mb-2">No tasks found</h4>
                <p class="text-gray-600 mb-6">Try changing the filter or create a new task</p>
                <a href="{{ route('tasks.create') }}"
                    class="btn-primary-custom px-6 py-3 text-white rounded-xl font-semibold">
                    <i class="bi bi-plus-circle mr-2"></i>Create New Task
                </a>
            </div>
        @else
            <!-- Tasks Table -->
            <div class="mb-8 bg-white text-black font-bold rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="tasks-table" class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-xl text-center font-semibold">No</th>
                                <th class="py-4 px-6 text-left text-xl font-semibold">Task Title</th>
                                <th class="py-4 px-6 text-left text-xl font-semibold">Department</th>
                                <th class="py-4 px-6 text-center text-xl font-semibold">Priority</th>
                                <th class="py-4 px-6 text-center text-xl font-semibold">Status</th>
                                <th class="py-4 px-6 text-center text-xl font-semibold">Start Date</th>
                                <th class="py-4 px-6 text-center text-xl font-semibold">Due Date</th>
                                <th class="py-4 px-6 text-center text-xl font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = 1; // Inisialisasi counter
                            @endphp

                            @foreach ($tasks as $task)
                                @php
                                    // Cek status deadline
                                    $isOverdue = false;
                                    $isDueSoon = false;
                                    $isToday = false;
                                    $daysRemaining = null;

                                    if ($task->due_date) {
                                        $now = now()->startOfDay();
                                        $dueDate = $task->due_date->startOfDay();
                                        $daysDiff = $now->diffInDays($dueDate, false);

                                        if (
                                            $daysDiff < 0 &&
                                            in_array($task->status, ['pending', 'in_progress', 'review'])
                                        ) {
                                            $isOverdue = true;
                                            $daysRemaining = abs($daysDiff);
                                        } elseif (
                                            $daysDiff == 0 &&
                                            in_array($task->status, ['pending', 'in_progress', 'review'])
                                        ) {
                                            $isToday = true;
                                            $daysRemaining = 0;
                                        } elseif (
                                            $daysDiff > 0 &&
                                            $daysDiff <= 3 &&
                                            in_array($task->status, ['pending', 'in_progress', 'review'])
                                        ) {
                                            $isDueSoon = true;
                                            $daysRemaining = $daysDiff;
                                        }
                                    }

                                    // Warna berdasarkan prioritas
                                    $priorityColor = 'text-white';
                                    if ($task->priority == 1) {
                                        $priorityColor = 'text-green-800 bg-green-400 border-green-800';
                                    } elseif ($task->priority == 2) {
                                        $priorityColor = 'text-blue-800 bg-blue-400 border-blue-800';
                                    } elseif ($task->priority == 3) {
                                        $priorityColor = 'text-orange-800 bg-orange-400 border-orange-800';
                                    } elseif ($task->priority == 4) {
                                        $priorityColor = 'text-red-800 bg-red-400 border-red-800';
                                    }

                                    // Warna berdasarkan status
                                    $statusColor = 'text-white';
                                    if ($task->status == 'pending') {
                                        $statusColor = 'text-white bg-gray-400 border-gray-800';
                                    } elseif ($task->status == 'in_progress') {
                                        $statusColor = 'text-white bg-blue-600 border-blue-800';
                                    } elseif ($task->status == 'review') {
                                        $statusColor = 'text-white bg-purple-600 border-purple-800';
                                    } elseif ($task->status == 'completed') {
                                        $statusColor = 'text-white bg-green-600 border-green-800';
                                    }
                                @endphp

                                <tr class="border border-gray-800 hover:bg-gray-400 transition duration-150">
                                    <!-- Nomor Urut -->
                                    <td class="py-4 px-6 text-center text-xl border-b-4 border-gray-400">
                                        <div class="font-medium">{{ $counter }}</div>
                                    </td>

                                    <!-- Task Title -->
                                    <td class="py-4 px-6 text-x border-b-4 border-gray-400">
                                        <div class="font-medium">{{ $task->title }}</div>
                                    </td>

                                    <!-- Department -->
                                    <td class="py-4 px-6 text-x border-b-4 border-gray-400">
                                        <div class="text-x max-w-xs">
                                            {{ $task->departement->name ?? '-' }}
                                        </div>
                                    </td>

                                    <!-- Priority -->
                                    <td class="py-4 px-6 border-b-4 border-gray-400">
                                        <span
                                            class="px-3 py-1 rounded-full text-x border font-semibold font-medium {{ $priorityColor }} min-w-[80px] text-center inline-block">
                                            @if ($task->priority == 1)
                                                Low
                                            @elseif($task->priority == 2)
                                                Medium
                                            @elseif($task->priority == 3)
                                                High
                                            @elseif($task->priority == 4)
                                                Urgent
                                            @else
                                                Not Set
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td class="py-4 px-6 border-b-4 border-gray-400">
                                        <span
                                            class="px-3 py-1 rounded-full text-x font-medium {{ $statusColor }} min-w-[100px] text-center inline-block">
                                            @if ($task->status == 'pending')
                                                Pending
                                            @elseif($task->status == 'in_progress')
                                                Progress
                                            @elseif($task->status == 'review')
                                                Review
                                            @else
                                                Completed
                                            @endif
                                        </span>
                                    </td>

                                    <!-- Start Date -->
                                    <td class="py-4 px-6 border-b-4 border-gray-400">
                                        <div class="text-black font-semibold text-x">
                                            {{ $task->start_date ? $task->start_date->format('d-m-Y') : '-' }}
                                        </div>
                                    </td>

                                    <!-- Due Date -->
                                    <td class="py-4 px-6 border-b-4 border-gray-400">
                                        @if ($task->status == 'completed')
                                            <div class="text-green-600 text-x font-medium font-semibold">
                                                {{ $task->due_date ? $task->due_date->format('d-m-Y') : '-' }}
                                            </div>
                                        @else
                                            <div
                                                class="{{ $isOverdue ? 'text-red-600 text-x font-medium font-semibold' : ($isToday ? 'text-yellow-600 text-x font-medium font-semibold' : ($isDueSoon ? 'text-yellow-600 text-x font-medium font-semibold' : 'text-blue-800 text-x font-semibold')) }}">
                                                {{ $task->due_date ? $task->due_date->format('d-m-Y') : '-' }}
                                            </div>
                                        @endif

                                    </td>

                                    <!-- Actions -->
                                    <td class="py-4 px-6 border-b-4 border-gray-400">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="rounded-lg border border-blue-600 px-3 py-1 text-blue-600 hover:bg-blue-600 hover:text-white text-x font-medium"
                                                title="View Details">
                                                <i class="bi bi-eye text-xl"></i>
                                            </a>

                                            @if ($task->status == 'completed')
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="rounded-lg border border-green-700 px-3 py-1 text-green-700 hover:bg-green-700 hover:text-white text-x font-medium"
                                                    title="Edit">
                                                    <i class="bi bi-pencil text-xl"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('tasks.edit', $task) }}"
                                                    class="rounded-lg border border-yellow-600 px-3 py-1 text-yellow-600 hover:bg-yellow-600 hover:text-white text-x font-medium"
                                                    title="Edit">
                                                    <i class="bi bi-pencil text-xl"></i>
                                                </a>
                                            @endif

                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                                onsubmit="return confirm('Delete this task?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg border border-red-600 px-3 py-1 text-red-600 hover:bg-red-600 hover:text-white text-x font-medium"
                                                    title="Delete">
                                                    <i class="bi bi-trash text-xl"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                @php
                                    $counter++; // Increment counter
                                @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tambahkan CSS dan JS DataTables -->
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        @endif
    </div>
    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>
