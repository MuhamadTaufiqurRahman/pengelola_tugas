<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Task - Task Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>

<body class="p-4">
    <div class="max-w mx-auto">
        <!-- Header -->
        <!-- Navigation -->
        <nav class="bg-gray-800 rounded-2xl shadow-lg mb-6">
            <div class="max-w mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex flex-wrap justify-between items-center gap-3 py-3">
                    <!-- Logo & Title -->
                    <div class="flex items-center min-w-0 flex-1">
                        <div
                            class="flex-shrink-0 w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-lg md:rounded-xl overflow-hidden mr-3">
                            <img src="{{ asset('img/logo.webp') }}" class="w-full h-full object-contain p-1"
                                alt="Logo">
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

        <!-- Form Card -->
        <div class="form-card p-8">
            <div class="flex items-center">
                <i class="bi bi-plus-circle mr-2 text-white" style="font-size: 25px;"></i>
                <h1 class="text-2xl font-bold text-white">Create New Task</h1><br>
            </div>
            <p class="text-white mt-2">Fill out the form below to create a new task</p>
            <br>

            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf

                <!-- Title -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-2">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Example: Update the company website" value="{{ old('title') }}">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-2">
                        Description
                    </label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Detailed task description...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status, Priority, Progress -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Priority Field -->
                    <div class="mb-4">
                        <label for="priority" class="block text-white font-medium mb-2">
                            <i class="bi bi-flag mr-2"></i>Priority
                        </label>
                        <select name="priority" id="priority"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            required>
                            <option value="">Choose Priority</option>
                            <option value="1" {{ old('priority', $task->priority ?? '') == 1 ? 'selected' : '' }}>
                                Low (1)
                            </option>
                            <option value="2" {{ old('priority', $task->priority ?? '') == 2 ? 'selected' : '' }}>
                                Medium (2) - Default
                            </option>
                            <option value="3" {{ old('priority', $task->priority ?? '') == 3 ? 'selected' : '' }}>
                                High (3)
                            </option>
                            <option value="4" {{ old('priority', $task->priority ?? '') == 4 ? 'selected' : '' }}>
                                Urgent (4)
                            </option>
                        </select>
                        <p class="text-xs text-white mt-1">
                            1 = Low, 2 = Medium, 3 = High, 4 = Urgent
                        </p>
                    </div>
                    <!-- Status -->
                    <div>
                        <label class="block text-white font-medium mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Start Date -->
                    <div>
                        <label class="block text-white font-medium mb-2">
                            Start Date
                        </label>
                        <input type="date" name="start_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            value="{{ old('start_date') }}">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-white font-medium mb-2">
                            Due Date
                        </label>
                        <input type="date" name="due_date"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            value="{{ old('due_date') }}">
                        @error('due_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Departement -->
                <div class="mb-6">
                    <label class="block text-white font-medium mb-2">
                        Department <span class="text-red-500">*</span>
                    </label>
                    <select name="departement_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Choose Department</option>
                        @foreach ($departements as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('departement_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('departement_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Assignees -->
                <div class="mb-8">
                    <label class="block text-white font-medium mb-2">
                        Assigned to <span class="text-red-500">*</span>
                        <span class="text-sm text-white font-normal">(Select at least one user)</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach ($users as $user)
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-purple-50 cursor-pointer">
                                <input type="checkbox" name="assignees[]" value="{{ $user->id }}"
                                    class="mr-3 h-5 w-5 text-purple-600 rounded focus:ring-purple-500"
                                    {{ in_array($user->id, old('assignees', [])) ? 'checked' : '' }}>
                                <span class="text-white">{{ $user->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('assignees')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('tasks.index') }}"
                        class="px-6 py-3 border border-gray-300 text-white rounded-lg hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary px-6 py-3 text-white rounded-lg font-semibold">
                        <i class="bi bi-save mr-2"></i>Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Alert -->
    @if (session('success'))
        <div
            class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg">
            <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg">
            <i class="bi bi-exclamation-triangle mr-2"></i>Please correct the errors in the form
        </div>
    @endif

    <script src="{{ asset('js/create.js') }}"></script>

</body>

</html>
