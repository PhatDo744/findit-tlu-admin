@extends('layouts.admin')

@section('title','Quản lý Danh mục')
@section('page-title','Quản lý Danh mục')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-semibold text-tlu-dark-gray">Danh sách Danh mục</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>Thêm Danh mục
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-tlu-blue text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tên</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mô tả</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($categories as $cat)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration + ($categories->currentPage()-1)*$categories->perPage() }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $cat->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $cat->description ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="badge {{ $cat->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $cat->is_active ? 'Hoạt động' : 'Vô hiệu' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.categories.edit', $cat) }}" 
                               class="btn btn-secondary btn-sm" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Xác nhận xóa?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Chưa có danh mục nào
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>
@endsection
