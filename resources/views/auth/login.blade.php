<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        .forms-wrapper {
            position: relative;
            overflow: hidden;
        }

        .form-container {
            transition: transform 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55), opacity 0.5s ease;
            width: 100%;
        }

        .slide-left {
            transform: translateX(-100%);
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .slide-right {
            transform: translateX(100%);
            opacity: 0;
            position: absolute;
            top: 0;
            left: 0;
        }

        .slide-center {
            transform: translateX(0);
            opacity: 1;
            position: relative;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen gradient-bg flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <!-- Logo/Brand -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">{{ config('app.name', 'Laravel') }}</h1>
                <p class="text-purple-200">Welcome back! Please login to your account.</p>
            </div>

            <!-- Card Container -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 relative overflow-hidden">
                <!-- Decorative Element -->
                <div class="absolute top-0 left-0 w-full h-2 gradient-bg"></div>

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Toggle Buttons -->
                <div class="flex mb-8 bg-gray-100 rounded-lg p-1">
                    <button id="loginTabBtn" onclick="showLogin()"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-300 text-white gradient-bg">
                        Login
                    </button>
                    <button id="registerTabBtn" onclick="showRegister()"
                        class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all duration-300 text-gray-600 hover:text-gray-800">
                        Register
                    </button>
                </div>

                <!-- Forms Wrapper -->
                <div class="forms-wrapper" style="min-height: 500px;">
                    <!-- Login Form -->
                    <div id="loginForm" class="form-container slide-center">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email Address -->
                            <div class="mb-6">
                                <label for="login_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input id="login_email" type="email" name="email" value="{{ old('email') }}"
                                    required autofocus autocomplete="username"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="you@example.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="login_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <input id="login_password" type="password" name="password" required
                                    autocomplete="current-password"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="••••••••">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="mb-6 flex justify-center">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                            @error('g-recaptcha-response')
                                <p class="mb-4 text-sm text-red-600 text-center">{{ $message }}</p>
                            @enderror

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between mb-6">
                                <label class="flex items-center">
                                    <input type="checkbox" name="remember"
                                        class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-purple-600 hover:text-purple-800 transition-colors duration-300">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div id="registerForm" class="form-container slide-right">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-6">
                                <label for="register_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Full Name
                                </label>
                                <input id="register_name" type="text" name="name" value="{{ old('name') }}"
                                    required autofocus autocomplete="name"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="John Doe">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="mb-6">
                                <label for="register_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input id="register_email" type="email" name="email"
                                    value="{{ old('email') }}" required autocomplete="username"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="you@example.com">
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-6">
                                <label for="register_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password
                                </label>
                                <input id="register_password" type="password" name="password" required
                                    autocomplete="new-password"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="••••••••">
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                    autocomplete="new-password"
                                    class="input-focus w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                    placeholder="••••••••">
                                @error('password_confirmation')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <button type="submit"
                                class="w-full gradient-bg text-white py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                                Create Account
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Footer Text -->
                <div class="mt-6 text-center text-sm text-gray-600">
                    <p>By continuing, you agree to our Terms of Service and Privacy Policy.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLogin() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('loginTabBtn');
            const registerBtn = document.getElementById('registerTabBtn');

            // Slide register form to the right
            registerForm.classList.remove('slide-center');
            registerForm.classList.add('slide-right');

            // Slide login form from the left to center
            loginForm.classList.remove('slide-left');
            loginForm.classList.add('slide-center');

            // Toggle button styles
            loginBtn.classList.add('text-white', 'gradient-bg');
            loginBtn.classList.remove('text-gray-600');

            registerBtn.classList.remove('text-white', 'gradient-bg');
            registerBtn.classList.add('text-gray-600');
        }

        function showRegister() {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('loginTabBtn');
            const registerBtn = document.getElementById('registerTabBtn');

            // Slide login form to the left
            loginForm.classList.remove('slide-center');
            loginForm.classList.add('slide-left');

            // Slide register form from the right to center
            registerForm.classList.remove('slide-right');
            registerForm.classList.add('slide-center');

            // Toggle button styles
            registerBtn.classList.add('text-white', 'gradient-bg');
            registerBtn.classList.remove('text-gray-600');

            loginBtn.classList.remove('text-white', 'gradient-bg');
            loginBtn.classList.add('text-gray-600');
        }

        // Check if there are registration errors and show register form
        @if ($errors->has('name') || (old('name') && $errors->any()))
            document.addEventListener('DOMContentLoaded', function() {
                showRegister();
            });
        @endif
    </script>
</body>

</html>