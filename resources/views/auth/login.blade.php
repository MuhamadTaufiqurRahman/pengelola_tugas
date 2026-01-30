<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="w-full max-w-md mx-4">
        <div class="login-card p-8">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center mb-4">
                    <div class="flex items-center justify-center w-20 h-20 rounded-xl ">
                        <img src="{{ asset('img/wt_logo.png') }}" class="" alt="Logo">
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">Task Manager</h1>
                <p class="text-gray-600 mt-2">Log in to manage your tasks</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="name">
                        Username
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="form-input w-full px-4 py-3 rounded-lg focus:outline-none @error('name') border-red-500 @enderror"
                        placeholder="Masukkan username">
                    @error('name')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="password">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                        class="form-input w-full px-4 py-3 rounded-lg focus:outline-none @error('password') border-red-500 @enderror"
                        placeholder="••••••••">
                    @error('password')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="btn-primary w-full text-white font-semibold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline">
                    Login
                </button>
            </form>

            <!-- Register Link -->
            {{-- @if (Route::has('register'))
                <div class="text-center mt-8 pt-6 border-t border-gray-200">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            @endif --}}

            <!-- Copyright -->
            <div class="text-center mt-8">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} | Watches Trader
                </p>
            </div>
        </div>
    </div>

    <!-- Background Animation -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div
            class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
        </div>
    </div>

    <style>
        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }

        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</body>

</html>
