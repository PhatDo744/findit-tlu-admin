@extends('layouts.admin')

@section('title', 'Quản Lý Tài Khoản - FindIt@TLU Admin')
@section('page-title', 'Quản Lý Tài Khoản')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-semibold text-tlu-dark-gray">Danh Sách Tài Khoản</h3>
    <button onclick="openModal('create-account-modal')" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>Thêm Tài Khoản
    </button>
</div>

<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" action="{{ route('admin.accounts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên, email..."
            class="p-3 border border-tlu rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
        <select name="role" class="p-3 border border-tlu rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả vai trò</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Quản trị viên</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Người dùng</option>
        </select>
        <select name="status" class="p-3 border border-tlu rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả trạng thái</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
            <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Bị khóa</option>
        </select>
        <button type="submit" class="btn btn-secondary">Tìm kiếm</button>
    </form>
</div>

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-tlu-blue text-white">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tên</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Vai Trò</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng Thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ngày Tạo</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hành Động</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($accounts as $account)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $account->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $account->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $account->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span class="badge {{ $account->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $account->role_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge {{ $account->status === 'active' ? 'bg-tlu-success text-white' : 'bg-tlu-error text-white' }}">
                        {{ $account->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $account->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <button onclick="editAccount({{ $account->id }})" class="btn btn-icon text-tlu-blue hover:text-blue-700" title="Chỉnh Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    @if($account->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.accounts.toggle-status', $account) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-icon {{ $account->status === 'active' ? 'text-orange-500 hover:text-orange-700' : 'text-green-500 hover:text-green-700' }}" 
                                    title="{{ $account->status === 'active' ? 'Khóa Tài Khoản' : 'Mở Khóa Tài Khoản' }}">
                                <i class="fas {{ $account->status === 'active' ? 'fa-lock' : 'fa-unlock' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.accounts.destroy', $account) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-icon text-tlu-error hover:text-red-700" title="Xóa" 
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Không có tài khoản nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $accounts->appends(request()->query())->links() }}
</div>

<!-- Create Account Modal -->
<div id="create-account-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Thêm Tài Khoản Mới</h3>
            <button onclick="closeModal('create-account-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.accounts.store') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Tên</label>
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
                    <input type="password" name="password" id="password" required 
                           class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Vai trò</label>
                    <select name="role" id="role" required 
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                        <option value="user">Người dùng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
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
                <button type="button" onclick="closeModal('create-account-modal')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Account Modal -->
<div id="edit-account-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Chỉnh Sửa Tài Khoản</h3>
            <button onclick="closeModal('edit-account-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <form id="edit-account-form" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">Tên</label>
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
                    <label for="edit-role" class="block text-sm font-medium text-gray-700 mb-2">Vai trò</label>
                    <select name="role" id="edit-role" required 
                            class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
                        <option value="user">Người dùng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
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
                <button type="button" onclick="closeModal('edit-account-modal')" class="btn btn-secondary">Hủy</button>
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

    function editAccount(accountId) {
        fetch(`/admin/accounts/${accountId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-name').value = data.name;
                document.getElementById('edit-email').value = data.email;
                document.getElementById('edit-role').value = data.role;
                document.getElementById('edit-status').value = data.status;
                document.getElementById('edit-account-form').action = `/admin/accounts/${data.id}`;
                openModal('edit-account-modal');
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Có lỗi xảy ra khi tải thông tin tài khoản', 'error');
            });
    }
</script>
@endpush
