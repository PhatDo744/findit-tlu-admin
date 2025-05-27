@extends('layouts.admin')

@section('title', 'Cài Đặt Hệ Thống - FindIt@TLU Admin')
@section('page-title', 'Cài Đặt Hệ Thống')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-xl font-semibold text-tlu-dark-gray">Cài Đặt Hệ Thống</h3>
    <div class="flex space-x-2">
        <button onclick="openModal('backup-modal')" class="btn btn-secondary">
            <i class="fas fa-download mr-2"></i>Sao lưu
        </button>
        <button onclick="openModal('import-modal')" class="btn btn-secondary">
            <i class="fas fa-upload mr-2"></i>Khôi phục
        </button>
        <button onclick="resetSettings()" class="btn btn-warning">
            <i class="fas fa-undo mr-2"></i>Đặt lại mặc định
        </button>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    @foreach($settings as $group => $groupSettings)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h4 class="text-lg font-semibold mb-4 text-tlu-blue border-b pb-2">
            {{ 
                $group === 'general' ? 'Cài đặt chung' : 
                ($group === 'email' ? 'Cài đặt email' : 
                ($group === 'ui' ? 'Giao diện' : 'Hệ thống'))
            }}
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($groupSettings as $setting)
            <div class="space-y-2">
                <label for="{{ $setting->key }}" class="block text-sm font-medium text-gray-700">
                    {{ $setting->label }}
                </label>
                
                @if($setting->type === 'textarea')
                    <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue"
                        placeholder="{{ $setting->description }}">{{ old($setting->key, $setting->value) }}</textarea>
                
                @elseif($setting->type === 'boolean')
                    <div class="flex items-center">
                        <input type="checkbox" name="{{ $setting->key }}" id="{{ $setting->key }}" value="1"
                            class="h-4 w-4 text-tlu-blue border-gray-300 rounded focus:ring-tlu-blue"
                            {{ old($setting->key, $setting->value) ? 'checked' : '' }}>
                        <label for="{{ $setting->key }}" class="ml-2 text-sm text-gray-600">
                            {{ $setting->description }}
                        </label>
                    </div>
                
                @elseif($setting->type === 'color')
                    <div class="flex items-center space-x-2">
                        <input type="color" name="{{ $setting->key }}" id="{{ $setting->key }}"
                            value="{{ old($setting->key, $setting->value) }}"
                            class="h-10 w-20 border border-gray-300 rounded-md">
                        <input type="text" value="{{ old($setting->key, $setting->value) }}"
                            class="flex-1 p-2 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue"
                            readonly>
                    </div>
                
                @elseif($setting->type === 'number')
                    <input type="number" name="{{ $setting->key }}" id="{{ $setting->key }}"
                        value="{{ old($setting->key, $setting->value) }}" min="1"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue"
                        placeholder="{{ $setting->description }}">
                
                @elseif($setting->type === 'email')
                    <input type="email" name="{{ $setting->key }}" id="{{ $setting->key }}"
                        value="{{ old($setting->key, $setting->value) }}"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue"
                        placeholder="{{ $setting->description }}">
                
                @else
                    <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}"
                        value="{{ old($setting->key, $setting->value) }}"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue"
                        placeholder="{{ $setting->description }}">
                @endif
                
                @if($setting->description && $setting->type !== 'boolean')
                    <p class="text-xs text-gray-500">{{ $setting->description }}</p>
                @endif
                
                @error($setting->key)
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div class="flex justify-end space-x-4">
        <button type="button" onclick="window.location.reload()" class="btn btn-secondary">
            <i class="fas fa-times mr-2"></i>Hủy
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-2"></i>Lưu Cài Đặt
        </button>
    </div>
</form>

<!-- Backup Modal -->
<div id="backup-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Sao lưu Cài đặt</h3>
            <button onclick="closeModal('backup-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <div class="mb-4">
            <p class="text-gray-600 mb-4">Tải xuống file sao lưu cài đặt hiện tại của hệ thống.</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    File sao lưu sẽ được tải xuống dưới định dạng JSON và có thể được sử dụng để khôi phục cài đặt sau này.
                </p>
            </div>
        </div>
        <div class="flex justify-end space-x-2">
            <button type="button" onclick="closeModal('backup-modal')" class="btn btn-secondary">Hủy</button>
            <a href="{{ route('admin.settings.export') }}" class="btn btn-primary">
                <i class="fas fa-download mr-2"></i>Tải xuống
            </a>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div id="import-modal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-tlu-blue">Khôi phục Cài đặt</h3>
            <button onclick="closeModal('import-modal')" class="text-gray-500 hover:text-tlu-accent">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.settings.import') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <p class="text-gray-600 mb-4">Chọn file sao lưu để khôi phục cài đặt.</p>
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Thao tác này sẽ ghi đè lên tất cả cài đặt hiện tại. Hãy chắc chắn bạn đã sao lưu trước khi thực hiện.
                    </p>
                </div>
                <input type="file" name="settings_file" accept=".json" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring-tlu-blue focus:border-tlu-blue">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal('import-modal')" class="btn btn-secondary">Hủy</button>
                <button type="submit" class="btn btn-warning" onclick="return confirm('Bạn có chắc chắn muốn khôi phục cài đặt? Thao tác này không thể hoàn tác.')">
                    <i class="fas fa-upload mr-2"></i>Khôi phục
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Color picker sync
document.addEventListener('DOMContentLoaded', function() {
    const colorInputs = document.querySelectorAll('input[type="color"]');
    colorInputs.forEach(function(colorInput) {
        const textInput = colorInput.nextElementSibling;
        
        colorInput.addEventListener('change', function() {
            textInput.value = this.value;
        });
    });
});

function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

function resetSettings() {
    if (confirm('Bạn có chắc chắn muốn đặt lại tất cả cài đặt về mặc định? Thao tác này không thể hoàn tác.')) {
        fetch('{{ route("admin.settings.reset") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                showToast('Cài đặt đã được đặt lại về mặc định!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showToast('Có lỗi xảy ra khi đặt lại cài đặt!', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra!', 'error');
        });
    }
}
</script>
@endpush
