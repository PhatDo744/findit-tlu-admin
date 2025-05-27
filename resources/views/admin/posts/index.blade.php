@extends('layouts.admin')

@section('title', 'Quản Lý Bài Đăng - FindIt@TLU Admin')
@section('page-title', 'Quản Lý Bài Đăng')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-semibold text-tlu-dark-gray">Danh Sách Bài Đăng</h3>
    <div class="flex space-x-2">
        <button onclick="toggleBulkActions()" class="btn btn-secondary">
            <i class="fas fa-check-square mr-2"></i>Chọn nhiều
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white p-4 rounded-lg shadow mb-6">
    <form method="GET" action="{{ route('admin.posts.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tiêu đề, mô tả..."
            class="p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
        
        <select name="status" class="p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả trạng thái</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Đã trả/tìm thấy</option>
        </select>

        <select name="type" class="p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả loại</option>
            <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Mất đồ</option>
            <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>Nhặt được</option>
        </select>

        <select name="category" class="p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                    {{ $category }}
                </option>
            @endforeach
        </select>

        <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Từ ngày"
            class="p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search mr-2"></i>Tìm kiếm
        </button>
    </form>
</div>

<!-- Bulk Actions -->
<div id="bulk-actions" class="bg-blue-50 p-4 rounded-lg mb-6 hidden">
    <form method="POST" action="{{ route('admin.posts.bulk-action') }}" onsubmit="return confirmBulkAction()">
        @csrf
        <div class="flex items-center space-x-4">
            <span class="text-sm text-blue-700">Đã chọn: <span id="selected-count">0</span> bài đăng</span>
            <select name="action" class="p-2 border border-blue-300 rounded-md">
                <option value="">Chọn hành động</option>
                <option value="approve">Duyệt</option>
                <option value="reject">Từ chối</option>
                <option value="delete">Xóa</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Thực hiện</button>
            <button type="button" onclick="toggleBulkActions()" class="btn btn-secondary btn-sm">Hủy</button>
        </div>
        <input type="hidden" name="post_ids" id="selected-post-ids" value="">
    </form>
</div>

<!-- Posts Table -->
<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-tlu-blue text-white">
            <tr>
                <th class="px-4 py-3 text-left">
                    <input type="checkbox" id="select-all" class="rounded" onchange="toggleSelectAll()">
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hình ảnh</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tiêu đề</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Loại</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Danh mục</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Người đăng</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Trạng thái</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Ngày đăng</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Hành động</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($posts as $post)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-4">
                    <input type="checkbox" class="post-checkbox rounded" value="{{ $post->id }}" onchange="updateSelectedCount()">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" alt="Post image" class="w-16 h-16 object-cover rounded-lg">
                    @else
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($post->title, 40) }}</div>
                    <div class="text-sm text-gray-500">{{ Str::limit($post->description, 60) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge {{ $post->type === 'lost' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $post->type_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $post->category }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $post->user->name }}</div>
                    <div class="text-sm text-gray-500">{{ $post->user->email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge {{ 
                        $post->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                        ($post->status === 'approved' ? 'bg-green-100 text-green-700' :
                        ($post->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'))
                    }}">
                        {{ $post->status_label }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $post->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-icon text-blue-600 hover:text-blue-800" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        
                        @if($post->status === 'pending')
                            <button onclick="approvePost({{ $post->id }})" class="btn btn-icon text-green-600 hover:text-green-800" title="Duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                            <button onclick="rejectPost({{ $post->id }})" class="btn btn-icon text-red-600 hover:text-red-800" title="Từ chối">
                                <i class="fas fa-times"></i>
                            </button>
                        @endif

                        @if($post->status === 'approved')
                            <button onclick="markReturnedPost({{ $post->id }})" class="btn btn-icon text-blue-600 hover:text-blue-800" title="Đánh dấu đã trả">
                                <i class="fas fa-handshake"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-4 text-center text-gray-500">Không có bài đăng nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $posts->appends(request()->query())->links() }}
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-red-600">Từ chối bài đăng</h3>
            <button onclick="closeModal('reject-modal')" class="text-gray-500 hover:text-red-600">&times;</button>
        </div>
        <form id="reject-form" onsubmit="submitReject(event)">
            <div class="mb-4">
                <label for="reject-reason" class="block text-sm font-medium text-gray-700 mb-2">
                    Lý do từ chối (không bắt buộc):
                </label>
                <textarea id="reject-reason" name="reason" rows="3" 
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                    placeholder="Nhập lý do từ chối bài đăng..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('reject-modal')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-danger">Từ chối</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedPostIds = [];
let currentPostId = null;

function toggleBulkActions() {
    const bulkActions = document.getElementById('bulk-actions');
    const isHidden = bulkActions.classList.contains('hidden');
    
    if (isHidden) {
        bulkActions.classList.remove('hidden');
        document.querySelectorAll('.post-checkbox, #select-all').forEach(cb => {
            cb.style.display = 'block';
        });
    } else {
        bulkActions.classList.add('hidden');
        document.querySelectorAll('.post-checkbox, #select-all').forEach(cb => {
            cb.style.display = 'none';
            cb.checked = false;
        });
        selectedPostIds = [];
        updateSelectedCount();
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');
    
    postCheckboxes.forEach(cb => {
        cb.checked = selectAll.checked;
    });
    
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkedBoxes = document.querySelectorAll('.post-checkbox:checked');
    selectedPostIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    document.getElementById('selected-count').textContent = selectedPostIds.length;
    document.getElementById('selected-post-ids').value = selectedPostIds.join(',');
}

function confirmBulkAction() {
    if (selectedPostIds.length === 0) {
        showToast('Vui lòng chọn ít nhất một bài đăng', 'error');
        return false;
    }
    
    const action = document.querySelector('select[name="action"]').value;
    if (!action) {
        showToast('Vui lòng chọn hành động', 'error');
        return false;
    }
    
    return confirm(`Bạn có chắc chắn muốn ${action} ${selectedPostIds.length} bài đăng đã chọn?`);
}

function approvePost(postId) {
    if (confirm('Bạn có chắc chắn muốn duyệt bài đăng này?')) {
        fetch(`/admin/posts/${postId}/approve`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                location.reload();
            } else {
                showToast('Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
        });
    }
}

function rejectPost(postId) {
    currentPostId = postId;
    openModal('reject-modal');
}

function submitReject(event) {
    event.preventDefault();
    
    const reason = document.getElementById('reject-reason').value;
    
    fetch(`/admin/posts/${currentPostId}/reject`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            closeModal('reject-modal');
            location.reload();
        } else {
            showToast('Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra', 'error');
    });
}

function markReturnedPost(postId) {
    if (confirm('Bạn có chắc chắn muốn đánh dấu bài đăng này là đã trả/tìm thấy?')) {
        fetch(`/admin/posts/${postId}/mark-returned`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                location.reload();
            } else {
                showToast('Có lỗi xảy ra', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra', 'error');
        });
    }
}

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    if (modalId === 'reject-modal') {
        document.getElementById('reject-reason').value = '';
    }
}
</script>
@endpush
