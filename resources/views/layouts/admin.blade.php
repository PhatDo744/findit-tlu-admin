<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FindIt@TLU Admin')</title>
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
            --tlu-success: #4CAF50;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--tlu-dark-gray);
            background-color: var(--tlu-light-gray);
        }

        .bg-tlu-blue {
            background-color: var(--tlu-blue);
        }

        .text-tlu-blue {
            color: var(--tlu-blue);
        }

        .text-tlu-dark-gray {
            color: var(--tlu-dark-gray);
        }

        .bg-tlu-success {
            background-color: var(--tlu-success);
        }

        .bg-tlu-error {
            background-color: var(--tlu-error);
        }

        .border-tlu {
            border-color: #e5e7eb;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--tlu-light-gray);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--tlu-blue);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #003a70;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: var(--tlu-overlay);
        }

        .modal-content {
            background-color: var(--tlu-white);
            margin: 10% auto;
            padding: 20px;
            border: 1px solid var(--tlu-border);
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .btn {
            padding: 16px;
            @apply px-4 py-2 rounded-md font-medium transition duration-200 inline-flex items-center;
        }

        .btn-primary {
            background-color: var(--tlu-blue);
            color: var(--tlu-white);
            @apply text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500;
        }

        .btn-secondary {
            @apply bg-gray-500 text-white hover:bg-gray-600 focus:ring-2 focus:ring-gray-400;
        }

        .btn-success {
            @apply bg-green-600 text-white hover:bg-green-700 focus:ring-2 focus:ring-green-500;
        }

        .btn-danger {
            @apply bg-red-600 text-white hover:bg-red-700 focus:ring-2 focus:ring-red-500;
        }

        .btn-warning {
            @apply bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-400;
        }

        .btn-icon {
            @apply p-2 rounded-md hover:bg-gray-100 transition duration-200;
        }

        .btn-sm {
            @apply px-3 py-1 text-sm;
        }

        .badge {
            @apply px-2 py-1 text-xs font-medium rounded-full;
        }

        .sidebar-active {
            @apply bg-blue-700 text-white;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-tlu-blue text-white">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-tlu-blue"></i>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold">FindIt@TLU</h1>
                        <p class="text-xs text-blue-200">Admin Panel</p>
                    </div>
                </div>
            </div>

            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>Dashboard
                </a>
                <a href="{{ route('admin.posts.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('admin.posts.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-file-alt mr-3"></i>Quản lý bài đăng
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('admin.users.*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-users mr-3"></i>Người dùng App
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-6 py-3 text-white hover:bg-blue-700 {{ request()->routeIs('admin.settings*') ? 'sidebar-active' : '' }}">
                    <i class="fas fa-cog mr-3"></i>Cài đặt hệ thống
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">@yield('page-title')</h2>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900" onclick="toggleDropdown()">
                                <div class="w-8 h-8 bg-tlu-blue rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="text-sm">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div id="user-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Hồ sơ
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const button = event.target.closest('button');

            if (!button || !button.onclick) {
                dropdown.classList.add('hidden');
            }
        });

        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check' : 'fa-exclamation-triangle'} mr-2"></i>${message}`;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>

    @stack('scripts')
</body>

</html>