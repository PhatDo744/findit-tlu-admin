<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập - FindIt@TLU Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --tlu-blue: #004E95;
            --tlu-white: #FFFFFF;
            --tlu-light-gray: #F5F5F5;
            --tlu-dark-gray: #333333;
            --tlu-accent: #FF5722;
            --tlu-error: #E53935;
        }
        .bg-tlu-blue { background-color: var(--tlu-blue); }
        .text-tlu-blue { color: var(--tlu-blue); }
        .bg-tlu-error { background-color: var(--tlu-error); }
        .border-tlu-blue { border-color: var(--tlu-blue); }
        .focus\:ring-tlu-blue:focus { --tw-ring-color: var(--tlu-blue); }
        .focus\:border-tlu-blue:focus { border-color: var(--tlu-blue); }
        .hover\:bg-blue-700:hover { background-color: #1d4ed8; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Header với logo TLU -->
        <div class="text-center mb-8">
            <div class="mx-auto w-20 h-20 bg-tlu-blue rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-graduation-cap text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-tlu-blue mb-2">FindIt@TLU</h1>
            <p class="text-gray-600">Hệ Thống Quản Trị</p>
        </div>

        <!-- Success Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-600 p-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope mr-2 text-gray-400"></i>Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tlu-blue focus:border-tlu-blue transition duration-200"
                    placeholder="Nhập email của bạn">
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-2 text-gray-400"></i>Mật khẩu
                </label>
                <div class="relative">
                    <input id="password" type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-tlu-blue focus:border-tlu-blue transition duration-200"
                        placeholder="Nhập mật khẩu">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye" id="password-toggle-icon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                        class="h-4 w-4 text-tlu-blue border-gray-300 rounded focus:ring-tlu-blue">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">Ghi nhớ đăng nhập</label>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-tlu-blue text-white py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-tlu-blue focus:ring-offset-2 transition duration-200 font-medium">
                <i class="fas fa-sign-in-alt mr-2"></i>Đăng Nhập
            </button>
        </form>

        <!-- Demo Accounts Info -->
        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-sm font-medium text-blue-800 mb-2">
                <i class="fas fa-info-circle mr-2"></i>Tài khoản demo:
            </h3>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Admin:</strong> admin@tlu.edu.vn / 123</p>
                <p><strong>User:</strong> user@tlu.edu.vn / user123</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-xs text-gray-500">
                © {{ date('Y') }} Thủy Lợi University. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-fill demo account on click
        document.addEventListener('DOMContentLoaded', function() {
            const demoInfo = document.querySelector('.bg-blue-50');
            if (demoInfo) {
                const adminDemo = demoInfo.querySelector('p:first-of-type');
                const userDemo = demoInfo.querySelector('p:last-of-type');
                
                adminDemo.style.cursor = 'pointer';
                userDemo.style.cursor = 'pointer';
                
                adminDemo.addEventListener('click', function() {
                    document.getElementById('email').value = 'admin@tlu.edu.vn';
                    document.getElementById('password').value = '123';
                });
                
                userDemo.addEventListener('click', function() {
                    document.getElementById('email').value = 'user@tlu.edu.vn';
                    document.getElementById('password').value = 'user123';
                });
            }
        });
    </script>
</body>
</html>
