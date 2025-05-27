@extends('layouts.admin')

@section('title', 'Quản Lý Người Dùng App - FindIt@TLU Admin')
@section('page-title', 'Quản Lý Người Dùng Ứng Dụng Mobile')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-xl font-semibold text-tlu-dark-gray">Danh Sách Người Dùng Ứng Dụng</h3>
        <p class="text-sm text-gray-600 mt-1">Quản lý tài khoản người dùng sử dụng ứng dụng mobile FindIt@TLU</p>
    </div>
    <button onclick="openModal('create-user-modal')" class=" btn btn-primary">
        <i class="text-xl px-2 fas fa-plus mr-2"></i>Thêm Người Dùng
    </button>
</div>

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên, email..."
            class="p-3 border border-tlu rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
        <select name="status" class="p-3 border border-tlu rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Bị khóa</option>
        </select>
        <button type="submit" class="btn btn-primary">
            <i class="text-xl px-2 fas fa-search mr-2"></i>Tìm kiếm
        </button>
    </form>
</div>

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-tlu-blue text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tên người dùng</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Số bài đăng</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ngày đăng ký</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($users as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="text-xl px-2 fas fa-user text-blue-600"></i>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">App Mobile User</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="p-3 badge {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $user->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex flex-col">
                        <span class="text-sm">Tổng: {{ $user->posts->count() }}</span>
                        <span class="text-xs text-green-600">Đã duyệt: {{ $user->posts->where('status', 'approved')->count() }}</span>
                        <span class="text-xs text-yellow-600">Chờ duyệt: {{ $user->posts->where('status', 'pending')->count() }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="editUser({{ $user->id }})" class="btn btn-icon text-tlu-blue hover:text-blue-700" title="Chỉnh Sửa">
                        <i class="text-xl px-2 fas fa-edit"></i>
                    </button>
                    <a href="{{ route('admin.posts.index', ['user_id' => $user->id]) }}" class="btn btn-icon text-green-600 hover:text-green-700" title="Xem bài đăng">
                        <i class="text-xl px-2 fas fa-file-alt"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-icon {{ $user->status === 'active' ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }}"
                            title="{{ $user->status === 'active' ? 'Khóa Tài Khoản' : 'Mở Khóa Tài Khoản' }}">
                            <i class="text-xl px-2 fas {{ $user->status === 'active' ? 'fa-lock' : 'fa-unlock' }}"></i>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-icon text-tlu-error hover:text-red-700" title="Xóa"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này? Tất cả bài đăng của người dùng cũng sẽ bị xóa.')">
                            <i class="text-xl px-2 fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="text-xl px-2 fas fa-users text-gray-300 text-4xl mb-4"></i>
                        <p class="text-lg text-gray-500">Chưa có người dùng nào đăng ký</p>
                        <p class="text-sm text-gray-400 mt-2">Người dùng sẽ đăng ký thông qua ứng dụng mobile</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->appends(request()->query())->links() }}
</div>

<!-- Create User Modal -->
<div id="create-user-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Thêm Người Dùng Mới</h3>
            <button onclick="closeModal('create-user-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên người dùng</label>
                    <input type="text" name="name" id="name" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" id="status" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                        <option value="active">Hoạt động</option>
                        <option value="locked">Bị khóa</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeModal('create-user-modal')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Chỉnh Sửa Thông Tin Người Dùng</h3>
            <button onclick="closeModal('edit-user-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <form id="edit-user-form" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">Tên người dùng</label>
                    <input type="text" name="name" id="edit-name" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="edit-email" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="edit-password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu mới (để trống nếu không đổi)</label>
                    <input type="password" name="password" id="edit-password"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="edit-status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                    <select name="status" id="edit-status" required
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                        <option value="active">Hoạt động</option>
                        <option value="locked">Bị khóa</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" onclick="closeModal('edit-user-modal')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function editUser(userId) {
        fetch(`/admin/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-email').value = data.email;
                document.getElementById('edit-status').value = data.status;
                document.getElementById('edit-user-form').action = `/admin/users/${data.id}`;
                openModal('edit-user-modal');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi tải thông tin người dùng', 'error');
            });
    }
</script>
@endpush