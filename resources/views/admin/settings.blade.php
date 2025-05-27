@extends('layouts.admin')

@section('title', 'Cài Đặt Hệ Thống - FindIt@TLU Admin')
@section('page-title', 'Cài Đặt Hệ Thống')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label for="site-name" class="block text-sm font-medium text-gray-700">Tên Trang Web</label>
            <input type="text" name="site_name" id="site-name" value="FindIt@TLU - Quản Trị"
                class="mt-1 block w-full p-3 border border-tlu rounded-md shadow-sm focus:ring-tlu-blue focus:border-tlu-blue">
        </div>
        
        <div class="mb-4">
            <label for="admin-email" class="block text-sm font-medium text-gray-700">Email Quản Trị</label>
            <input type="email" name="admin_email" id="admin-email" value="admin@tlu.edu.vn"
                class="mt-1 block w-full p-3 border border-tlu rounded-md shadow-sm focus:ring-tlu-blue focus:border-tlu-blue">
        </div>
        
        <div class="mb-4">
            <label for="items-per-page" class="block text-sm font-medium text-gray-700">Số mục hiển thị mỗi trang</label>
            <select id="items-per-page" name="items_per_page"
                class="mt-1 block w-full p-3 border border-tlu rounded-md shadow-sm focus:ring-tlu-blue focus:border-tlu-blue">
                <option value="10">10</option>
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        
        <div class="flex items-center mb-4">
            <input id="email-notifications" name="email_notifications" type="checkbox" checked value="1"
                class="h-4 w-4 text-tlu-blue border-gray-300 rounded focus:ring-tlu-blue">
            <label for="email-notifications" class="ml-2 block text-sm text-gray-900">Bật thông báo qua email</label>
        </div>
        
        <div>
            <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
        </div>
    </form>
</div>
@endsection
