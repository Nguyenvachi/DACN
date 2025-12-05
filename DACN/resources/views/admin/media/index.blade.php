@extends('layouts.admin')

@section('content')

<style>
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }
    .media-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .media-item:hover {
        transform: translateY(-5px);
    }
    .media-img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .media-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        padding: 10px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .media-item:hover .media-overlay {
        opacity: 1;
    }
    .btn-delete-img {
        position: absolute;
        top: 10px;
        right: 10px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .media-item:hover .btn-delete-img {
        opacity: 1;
    }
</style>

<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">
            <i class="bi bi-images text-primary"></i>
            Thư viện Media
        </h1>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="bi bi-upload"></i> Upload Ảnh
            </button>
            <a href="{{ route('admin.baiviet.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Bài viết
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <h3 class="text-primary">{{ count($images) }}</h3>
                    <small class="text-muted">Tổng số ảnh</small>
                </div>
                <div class="col-md-4">
                    <h3 class="text-success">{{ number_format(array_sum(array_column($images, 'size')) / 1024 / 1024, 2) }} MB</h3>
                    <small class="text-muted">Dung lượng</small>
                </div>
                <div class="col-md-4">
                    <h3 class="text-info">uploads/posts</h3>
                    <small class="text-muted">Thư mục</small>
                </div>
            </div>
        </div>
    </div>

    {{-- GRID ẢNH --}}
    <div class="card">
        <div class="card-body">
            @if(count($images) > 0)
                <div class="media-grid">
                    @foreach($images as $img)
                    <div class="media-item" data-path="{{ $img['path'] }}">
                        <img src="{{ $img['path'] }}" alt="{{ $img['name'] }}" class="media-img">

                        <button class="btn btn-danger btn-sm btn-delete-img" onclick="deleteImage('{{ $img['path'] }}')">
                            <i class="bi bi-trash"></i>
                        </button>

                        <div class="media-overlay">
                            <div class="text-white">
                                <small class="d-block text-truncate">{{ $img['name'] }}</small>
                                <small class="d-block">{{ number_format($img['size'] / 1024, 1) }} KB</small>
                            </div>
                            <div class="mt-2">
                                <button class="btn btn-sm btn-light w-100" onclick="copyUrl('{{ $img['path'] }}')">
                                    <i class="bi bi-clipboard"></i> Copy URL
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-images fs-1 text-muted"></i>
                    <p class="text-muted mt-3">Chưa có ảnh nào. Click "Upload Ảnh" để thêm.</p>
                </div>
            @endif
        </div>
    </div>

</div>

{{-- UPLOAD MODAL --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Ảnh Mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn ảnh (JPEG, PNG, GIF, WebP - Max 5MB)</label>
                        <input type="file" name="upload" class="form-control" accept="image/*" required>
                    </div>
                    <div id="uploadPreview" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Upload form
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang upload...';

    try {
        const response = await fetch('{{ route("admin.media.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const data = await response.json();

        if (data.url) {
            alert('Upload thành công!');
            location.reload();
        } else {
            alert('Upload thất bại: ' + (data.error?.message || 'Unknown error'));
        }
    } catch (error) {
        alert('Lỗi: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-upload"></i> Upload';
    }
});

// Preview ảnh
document.querySelector('input[name="upload"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('uploadPreview').innerHTML =
                `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">`;
        };
        reader.readAsDataURL(file);
    }
});

// Copy URL
function copyUrl(path) {
    const fullUrl = window.location.origin + path;
    navigator.clipboard.writeText(fullUrl).then(() => {
        alert('Đã copy URL: ' + fullUrl);
    });
}

// Delete image
async function deleteImage(path) {
    if (!confirm('Xóa ảnh này?')) return;

    try {
        const response = await fetch('{{ route("admin.media.destroy") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ path: path })
        });

        const data = await response.json();

        if (data.success) {
            alert('Đã xóa ảnh');
            location.reload();
        } else {
            alert('Xóa thất bại: ' + data.message);
        }
    } catch (error) {
        alert('Lỗi: ' + error.message);
    }
}
</script>

@endsection
