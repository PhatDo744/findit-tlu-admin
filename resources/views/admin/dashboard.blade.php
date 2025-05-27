@extends('layouts.admin')

@section('title', 'Dashboard - FindIt@TLU Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Tổng người dùng</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
            </div>
        </div>
    </div>

    <!-- Total Posts -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Tổng bài đăng</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_posts']) }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Posts -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Chờ duyệt</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['pending_posts']) }}</p>
            </div>
        </div>
    </div>

    <!-- Approved Posts -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Đã duyệt</p>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['approved_posts']) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Posts -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Bài đăng gần đây</h3>
        </div>
        <div class="p-6">
            @forelse($recent_posts as $post)
            <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 40) }}</p>
                    <p class="text-xs text-gray-500">{{ $post->user->name }} - {{ $post->created_at->diffForHumans() }}</p>
                </div>
                <span class="p-3 badge {{ $post->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                    {{ $post->status_label }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Chưa có bài đăng nào</p>
            @endforelse
        </div>
        @if($recent_posts->count() > 0)
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.posts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Xem tất cả bài đăng →
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Người dùng mới</h3>
        </div>
        <div class="p-6">
            @forelse($recent_users as $user)
            <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600 text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="p-3 badge {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $user->role_label }}
                </span>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Chưa có người dùng nào</p>
            @endforelse
        </div>
        @if($recent_users->count() > 0)
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
            <a href="{{ route('admin.accounts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                Xem tất cả người dùng →
            </a>
        </div>
        @endif
    </div>
</div>
@endsection