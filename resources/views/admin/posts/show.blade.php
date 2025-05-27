@extends('layouts.admin')

@section('title', 'Chi Tiết Bài Đăng - FindIt@TLU Admin')
@section('page-title', 'Chi Tiết Bài Đăng')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-tlu-blue text-white p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $post->title }}</h1>
                <div class="flex items-center space-x-4 text-sm">
                    <span class="badge {{ $post->type === 'lost' ? 'bg-red-500' : 'bg-green-500' }} text-white">
                        {{ $post->type_label }}
                    </span>
                    <span class="badge {{ 
                        $post->status === 'pending' ? 'bg-yellow-500' :
                        ($post->status === 'approved' ? 'bg-green-500' :
                        ($post->status === 'rejected' ? 'bg-red-500' : 'bg-blue-500'))
                    }} text-white">
                        {{ $post->status_label }}
                    </span>
                </div>
            </div>
            <div class="text-right text-sm">
                <p>Ngày đăng: {{ $post->created_at->format('d/m/Y H:i') }}</p>
                <p>ID: #{{ $post->id }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Image -->
            @if($post->image_url)
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Hình ảnh</h3>
                <img src="{{ $post->image_url }}" alt="Post image" class="w-full max-w-md mx-auto rounded-lg shadow-md">
            </div>
            @endif

            <!-- Description -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-3">Mô tả chi tiết</h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed">{{ $post->description }}</p>
                </div>
            </div>

            <!-- Actions for pending posts -->
            @if($post->status === 'pending')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3">Hành động duyệt</h3>
                <div class="flex space-x-3">
                    <button onclick="approvePost({{ $post->id }})" class="btn btn-success">
                        <i class="fas fa-check mr-2"></i>Duyệt bài đăng
                    </button>
                    <button onclick="rejectPost({{ $post->id }})" class="btn btn-danger">
                        <i class="fas fa-times mr-2"></i>Từ chối
                    </button>
                </div>
            </div>
            @endif

            <!-- Actions for approved posts -->
            @if($post->status === 'approved')
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-green-800 mb-3">Bài đăng đã được duyệt</h3>
                <button onclick="markReturnedPost({{ $post->id }})" class="btn btn-primary">
                    <i class="fas fa-handshake mr-2"></i>Đánh dấu đã trả/tìm thấy
                </button>
            </div>
            @endif

            <!-- Rejection reason -->
            @if($post->status === 'rejected' && $post->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-red-800 mb-3">Lý do từ chối</h3>
                <p class="text-red-700">{{ $post->rejection_reason }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Post Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Thông tin bài đăng</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Danh mục:</span>
                        <span class="font-medium">{{ $post->category }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Địa điểm:</span>
                        <span class="font-medium">{{ $post->location }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Loại:</span>
                        <span class="badge {{ $post->type === 'lost' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $post->type_label }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Trạng thái:</span>
                        <span class="badge {{ 
                            $post->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                            ($post->status === 'approved' ? 'bg-green-100 text-green-700' :
                            ($post->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'))
                        }}">
                            {{ $post->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Thông tin người đăng</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-tlu-blue rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $post->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $post->user->email }}</p>
                        </div>
                    </div>
                    <div class="pt-3 border-t">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Vai trò:</span>
                            <span class="badge {{ $post->user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $post->user->role_label }}
                            </span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-gray-600">Trạng thái:</span>
                            <span class="badge {{ $post->user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $post->user->status_label }}
                            </span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <span class="text-gray-600">Tham gia:</span>
                            <span class="text-sm">{{ $post->user->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Thống kê người dùng</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tổng bài đăng:</span>
                        <span class="font-medium">{{ $post->user->posts()->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đã duyệt:</span>
                        <span class="font-medium text-green-600">{{ $post->user->posts()->where('status', 'approved')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Chờ duyệt:</span>
                        <span class="font-medium text-yellow-600">{{ $post->user->posts()->where('status', 'pending')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Đã trả/tìm thấy:</span>
                        <span class="font-medium text-blue-600">{{ $post->user->posts()->where('status', 'returned')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    openModal('reject-modal');
}

function submitReject(event) {
    event.preventDefault();
    
    const reason = document.getElementById('reject-reason').value;
    
    fetch(`/admin/posts/{{ $post->id }}/reject`, {
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
