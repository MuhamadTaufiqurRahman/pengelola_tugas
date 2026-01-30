<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>

<body class="p-4">
    <!-- Navigation -->
    <nav class="bg-gray-800 rounded-2xl shadow-lg mb-6">
        <div class="max-w mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-between items-center gap-3 py-3">
                <!-- Logo & Title -->
                <div class="flex items-center min-w-0 flex-1">
                    <div
                        class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-lg md:rounded-xl overflow-hidden mr-3">
                        <img src="{{ asset('img/logo.webp') }}" class="w-full h-full object-contain p-1"
                            alt="Logo">>
                    </div>
                    <h1 class="text-lg sm:text-xl md:text-2xl font-bold text-white truncate">
                        Task Manager
                    </h1>
                </div>

                <!-- User & Logout -->
                <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                    <!-- User Name (hide on very small screens) -->
                    <span
                        class="xs:inline text-white font-medium text-sm sm:text-base truncate max-w-[120px] sm:max-w-none">
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

    <!-- Main Content -->
    <div class="max-w- mx-auto">
        <div class="dashboard-card p-8">
            <!-- Header with Back Button -->
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold">
                        <i class="bi bi-file-text text-purple-600 mr-3">
                            <a class="font-semibold text-white">
                                Details
                            </a>
                        </i>
                    </h1>
                </div>
            </div>

            <!-- Task Title -->
            <div class="mb-8">
                <h2 class="text-4xl font-bold text-purple-600 mb-3">{{ $task->title }}</h2>
                <div class="flex items-center text-white">
                    <i class="bi bi-building mr-2"></i>
                    <span class="font-medium">{{ $task->departement->name ?? 'No departments available' }}</span>
                    <span class="mx-3">•</span>
                    <i class="bi bi-calendar-event mr-2"></i>
                    <span>Created: {{ $task->created_at->format('d F Y') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Description & Timeline -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Description Card -->
                    <div class="bg-gray-600 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center mb-4">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="bi bi-card-text text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Task Description</h3>
                        </div>
                        <div class="bg-gray-400  rounded-xl p-6 min-h-[200px]">
                            @if ($task->description)
                                <p class="text-white text-xl">
                                    {{ $task->description }}</p>
                            @else
                                <div class="text-center py-12">
                                    <i class="bi bi-card-text text-5xl text-gray-300 mb-4"></i>
                                    <p class="text-white text-xl">No description available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="bg-gray-600 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="bi bi-clock-history text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white">Timeline</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <i class="bi bi-calendar3 text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-lg text-white">Start Date</p>
                                    <p class="text-lg font-semibold text-gray-800">
                                        {{ $task->start_date ? $task->start_date->format('d F Y') : 'Belum ditentukan' }}
                                    </p>
                                </div>
                            </div>


                            <div class="flex items-center">
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $hasDeadline = $task->due_date !== null;
                                    $isCompleted = $task->status == 'completed';

                                    if ($hasDeadline) {
                                        // Gunakan startOfDay() untuk perbandingan TANPA jam
                                        $dueDateStart = $task->due_date->copy()->startOfDay();
                                        $nowStart = $now->copy()->startOfDay();

                                        // Perbandingan tanggal (tanpa jam)
                                        $isTodayDate = $dueDateStart->equalTo($nowStart);
                                        $isTomorrowDate = $dueDateStart->equalTo($nowStart->copy()->addDay());
                                        $isPastDate = $dueDateStart->lessThan($nowStart); // hanya tanggal, tanpa jam

                                        // Hitung selisih hari (hanya tanggal)
                                        $daysDiff = $nowStart->diffInDays($dueDateStart, false);
                                        // $daysDiff > 0 = sekarang SETELAH deadline (terlambat)
                                        // $daysDiff = 0 = hari yang sama
                                        // $daysDiff < 0 = sekarang SEBELUM deadline (masih ada waktu)

                                        // Tentukan kondisi
                                        $isOverdue = $daysDiff < 0 && !$isCompleted; // POSITIF = terlambat
                                        $isToday = $isTodayDate; // tanggal sama
                                        $isTomorrow = $isTomorrowDate; // tanggal besok

                                        // Cek apakah sudah lewat JAM-nya hari ini
                                        $isPastTimeToday = $isTodayDate && $task->due_date->isPast();

                                        // Untuk display
                                        if ($isOverdue) {
                                            $displayDays = $daysDiff; // sudah positif
                                            $statusDays = $displayDays;
                                        } elseif ($isPastTimeToday && !$isCompleted) {
                                            // Hari ini sudah lewat jam deadline
                                            $displayDays = 0;
                                            $statusDays = '0'; // terlambat hari ini
                                        } elseif ($isToday) {
                                            $displayDays = 0;
                                            $statusDays = 0;
                                        } elseif ($daysDiff < 0) {
                                            $displayDays = abs($daysDiff); // ubah negatif ke positif
                                            $statusDays = $displayDays;
                                        }
                                    }

                                    // LOGIKA 4 WARNA
                                    if (!$hasDeadline) {
                                        $color = 'gray';
                                        $statusText = null;
                                    } elseif ($isCompleted) {
                                        $color = 'green';
                                        $statusText = 'Completed';
                                    } elseif ($isOverdue) {
                                        // 🔴 MERAH: Telat (hari sebelumnya)
                                        $color = 'red';
                                        $statusText = $statusDays . ' Days Overdue';
                                    } elseif ($isPastTimeToday && !$isCompleted) {
                                        // 🔴 MERAH: Hari ini sudah lewat jam deadline
                                        $color = 'yellow';
                                        $statusText = 'Deadline To Day';
                                    } elseif ($isToday && !$isPastTimeToday) {
                                        // 🔴 MERAH: Hari ini, belum lewat jam
                                        $color = 'yellow';
                                        $statusText = 'Deadline To Day!';
                                    } elseif ($isTomorrow) {
                                        // 🔴 MERAH: Besok
                                        $color = 'yellow';
                                        $statusText = 'Tomorrow deadline!';
                                    } elseif ($daysDiff && abs($daysDiff) <= 3) {
                                        // 🔴 MERAH: 1-3 hari lagi
                                        $color = 'yellow';
                                        $statusText = 'Due in ' . abs($daysDiff) . ' days';
                                    } elseif ($daysDiff < 0 && abs($daysDiff) >= 4 && abs($daysDiff) <= 6) {
                                        // 🟡 KUNING: 4-6 hari lagi
                                        $color = 'blue';
                                        $statusText = 'Due in ' . abs($daysDiff) . ' days';
                                    } elseif ($daysDiff < 0 && abs($daysDiff) >= 7) {
                                        // 🔵 BIRU: ≥ 7 hari lagi
                                        $color = 'purple';
                                        $statusText = 'Due in ' . abs($daysDiff) . ' days';
                                    } else {
                                        $color = 'gray';
                                        $statusText = null;
                                    }

                                    // Config warna
                                    $colorConfig = [
                                        'gray' => [
                                            'bg' => 'bg-gray-100',
                                            'text' => 'text-gray-600',
                                            'icon' => 'bi-calendar-check',
                                            'badge' => 'bg-gray-100 text-gray-700',
                                        ],
                                        'green' => [
                                            'bg' => 'bg-green-100',
                                            'text' => 'text-green-600',
                                            'icon' => 'bi-check-circle',
                                            'badge' => 'bg-green-100 text-green-800',
                                        ],
                                        'blue' => [
                                            'bg' => 'bg-blue-100',
                                            'text' => 'text-blue-600',
                                            'icon' => 'bi-calendar-check',
                                            'badge' => 'bg-blue-100 text-blue-800',
                                        ],
                                        'yellow' => [
                                            'bg' => 'bg-yellow-100',
                                            'text' => 'text-yellow-600',
                                            'icon' => 'bi-exclamation-circle',
                                            'badge' => 'bg-yellow-100 text-yellow-800',
                                        ],
                                        'red' => [
                                            'bg' => 'bg-red-100',
                                            'text' => 'text-red-600',
                                            'icon' =>
                                                $isOverdue || $isPastTimeToday
                                                    ? 'bi-exclamation-triangle'
                                                    : 'bi-exclamation-circle',
                                            'badge' => 'bg-red-100 text-red-800',
                                        ],
                                    ];

                                    $config = $colorConfig[$color];
                                @endphp

                                <div
                                    class="w-12 h-12 {{ $config['bg'] }} rounded-full flex items-center justify-center mr-4">
                                    <i class="bi {{ $config['icon'] }} {{ $config['text'] }} text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-lg text-white">Due Date</p>

                                    <div class="flex items-center flex-wrap gap-2">
                                        <p class="text-lg font-semibold {{ $config['text'] }}">
                                            {{ $task->due_date ? $task->due_date->format('d F Y') : 'Nothing deadline' }}
                                        </p>

                                        @if ($statusText && $hasDeadline)
                                            <span
                                                class="text-sm {{ $config['badge'] }} px-2 py-1 rounded-full flex items-center">
                                                <i class="bi {{ $config['icon'] }} mr-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Keterangan detail -->
                                    @if ($hasDeadline)
                                        <p
                                            class="text-lg mt-1
                @if ($color == 'red' && ($isOverdue || $isPastTimeToday)) text-red-600
                @elseif($color == 'red') text-red-600 font-medium
                @elseif($color == 'yellow') text-yellow-600
                @elseif($color == 'blue') text-blue-600
                @elseif($color == 'green') text-green-600
                @else text-gray-500 @endif">

                                            @if ($isCompleted)
                                                ✅ Task is Completed
                                                @if ($isOverdue)
                                                    (Overdue By {{ $statusDays }} Days)
                                                @endif
                                            @elseif($isOverdue)
                                                ⚠️ Overdue By {{ $statusDays }} Days - Complete Immediately!
                                            @elseif($isPastTimeToday)
                                                ⚠️ Today’s deadline has already passed - Complete Immediately!
                                            @elseif($isToday && !$isPastTimeToday)
                                                ⏰ Deadline To Day - Complete Immediately!
                                            @elseif($isTomorrow)
                                                ⏰ Deadline Tomorrow - Prepare for completion
                                            @elseif($daysDiff >= 1 && abs($daysDiff) <= 3)
                                                ⏰ {{ abs($daysDiff) }} days remaining
                                            @elseif($daysDiff >= 4 && abs($daysDiff) <= 6)
                                                📅 {{ abs($daysDiff) }} days remaining
                                            @elseif($daysDiff >= 7 && abs($daysDiff) >= 7)
                                                📅 {{ abs($daysDiff) }} days remaining
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column: Information & Progress -->
                <div class="space-y-8">
                    <!-- Information Card -->
                    <div class="bg-gray-600 rounded-2xl shadow-lg p-6">
                        <div class="flex items-center mb-6">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                                <i class="bi bi-info-circle text-white text-xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800">Information</h3>
                        </div>

                        <div class="space-y-6">
                            <!-- Status -->
                            <div class="info-card bg-gray-500 border-l-blue-500 p-4">
                                <p class="text-x text-white mb-2 flex items-center">
                                    <i class="bi bi-circle-fill mr-2"></i>Status
                                </p>
                                <span class="status-badge status-{{ $task->status }}">
                                    @if ($task->status == 'pending')
                                        <i class="bi bi-clock"> Pending</i>
                                    @elseif($task->status == 'in_progress')
                                        <i class="bi bi-arrow-clockwise border"> In
                                            Progress</i>
                                    @elseif($task->status == 'review')
                                        <i class="bi bi-eye"> Review</i>
                                    @else
                                        <i class="bi bi-check-circle"> Completed</i>
                                    @endif
                                    {{ $task->status_text }}
                                </span>
                            </div>

                            <!-- Priority Info -->
                            <div class="info-card bg-gray-500 border-l-{{ $task->priority_color }}-500 p-4">
                                <p class="text-x text-white mb-4 flex items-center">
                                    <i class="bi bi-flag mr-2"></i>Priority
                                </p>
                                <span
                                    class="px-4 py-3 border border-yellow-800 rounded-full text-sm font-semibold {{ $task->priority_badge_class }} ">
                                    <i class="bi bi-flag mr-2"></i>
                                    @if ($task->priority == 1)
                                        Low
                                    @elseif($task->priority == 2)
                                        Medium
                                    @elseif($task->priority == 3)
                                        High
                                    @else
                                        Urgent
                                    @endif
                                </span>
                                <p class="text-x text-white mt-4">
                                    @if ($task->priority == 1)
                                        ⏳ Not urgent, can be handled later.
                                    @elseif($task->priority == 2)
                                        ⚡ Normal priority, proceed according to the timeline.
                                    @elseif($task->priority == 3)
                                        🔥 High priority, requires additional attention.
                                    @else
                                        🚨 Critical priority, requires immediate action.
                                    @endif
                                </p>
                            </div>

                            <!-- Department -->
                            <div class="info-card bg-gray-500 border-l-green-500 p-4">
                                <p class="text-sm text-white mb-2 flex items-center">
                                    <i class="bi bi-building mr-2"></i>Department
                                </p>
                                <p class="text-lg font-semibold text-white">
                                    {{ $task->departement->name ?? 'Tidak ada departemen' }}
                                </p>
                            </div>

                            <!-- Assignees -->
                            <div class="info-card bg-gray-500 border-l-purple-500 p-4">
                                <p class="text-sm text-white mb-3 flex items-center">
                                    <i class="bi bi-people mr-2"></i>Assigned to
                                </p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($task->assignees as $assignee)
                                        <div class="flex items-center bg-gray-500 border rounded-lg px-3 py-2">
                                            @if ($assignee->id == Auth::id())
                                                <i class="bi bi-person-check text-white mr-2"></i>
                                            @else
                                                <i class="bi bi-person text-white mr-2"></i>
                                            @endif
                                            <span class="font-medium text-xl text-white">{{ $assignee->name }}</span>
                                        </div>
                                    @endforeach
                                    @if ($task->assignees->isEmpty())
                                        <div class="text-center w-full py-3">
                                            <i class="bi bi-person-x text-3xl text-gray-300 mb-2"></i>
                                            <p class="text-gray-50">No assignee yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-600 rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-white mb-4">Action</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @if ($task->status === 'completed')
                                <div
                                    class="flex items-center justify-center border border-green-800 bg-green-400 text-green-800 rounded-xl p-4 cursor-not-allowed">
                                    <i class="bi bi-lock text-xl mr-3"></i>
                                    <span class="font-medium">Task Completed</span>
                                </div>
                            @else
                                <a href="{{ route('tasks.edit', $task) }}"
                                    class="flex items-center justify-center border border-blue-800 bg-blue-400 text-blue-800 hover:bg-blue-100 rounded-xl p-4 transition">
                                    <i class="bi bi-pencil text-xl mr-3"></i>
                                    <span class="font-medium">Update Task</span>
                                </a>
                            @endif

                            <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this task?')"
                                class="block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full flex items-center justify-center border border-red-800 bg-red-400 text-red-800 hover:bg-red-100 rounded-xl p-4 transition">
                                    <i class="bi bi-trash text-xl mr-3"></i>
                                    <span class="font-medium">Deleted Task</span>
                                </button>
                            </form>
                            <a href="{{ route('tasks.index') }}"
                                class="sm:col-span-2 flex items-center justify-center bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-xl p-4 transition">
                                <i class="bi bi-arrow-left text-xl mr-3"></i>
                                <span class="font-medium">Back to Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/show.js') }}"></script>

</body>

</html>
