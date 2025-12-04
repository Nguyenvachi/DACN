@extends('layouts.admin')

@section('content')

{{-- =======================================
     🔥 Header trang
======================================= --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">
        <i class="fas fa-edit me-2"></i> Sửa Bài viết
    </h1>

    <a href="{{ route('admin.baiviet.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>


{{-- =======================================
     🔥 Form đẹp + card layout
======================================= --}}
<div class="card shadow-lg border-0">
    <div class="card-body">

        <form action="{{ route('admin.baiviet.update', $baiviet) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- Tiêu đề --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" name="title"
                           class="form-control form-control-lg @error('title') is-invalid @enderror"
                           value="{{ old('title', $baiviet->title) }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Danh mục --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select name="danh_muc_id" class="form-select form-select-lg">
                        <option value="">-- Không --</option>
                        @foreach($danhMucs as $dm)
                            <option value="{{ $dm->id }}"
                                @selected(old('danh_muc_id', $baiviet->danh_muc_id)==$dm->id)>
                                {{ $dm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Trạng thái --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Trạng thái</label>
                    <select name="status" class="form-select form-select-lg">
                        <option value="draft" @selected(old('status', $baiviet->status)=='draft')>Nháp</option>
                        <option value="published" @selected(old('status', $baiviet->status)=='published')>Xuất bản</option>
                    </select>
                </div>

                {{-- Tóm tắt --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Tóm tắt</label>
                    <input type="text" name="excerpt"
                           class="form-control form-control-lg"
                           value="{{ old('excerpt', $baiviet->excerpt) }}">
                </div>

                {{-- Nội dung --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Nội dung <span class="text-danger">*</span></label>
                    <textarea name="content" rows="8"
                              class="form-control form-control-lg @error('content') is-invalid @enderror">{{ old('content', $baiviet->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Ngày xuất bản --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Ngày xuất bản</label>
                    <input type="datetime-local" name="published_at"
                           class="form-control form-control-lg"
                           value="{{ old('published_at', optional($baiviet->published_at)->format('Y-m-d\TH:i')) }}">
                </div>

                {{-- Thumbnail --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Thumbnail (URL)</label>
                    <input type="text" name="thumbnail"
                           class="form-control form-control-lg"
                           value="{{ old('thumbnail', $baiviet->thumbnail) }}">
                </div>

                {{-- Meta title --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Meta title</label>
                    <input type="text" name="meta_title"
                           class="form-control form-control-lg"
                           value="{{ old('meta_title', $baiviet->meta_title) }}">
                </div>

                {{-- Meta description --}}
                <div class="col-md-6">
                    <label class="form-label fw-bold">Meta description</label>
                    <textarea name="meta_description" rows="3"
                              class="form-control form-control-lg">{{ old('meta_description', $baiviet->meta_description) }}</textarea>
                </div>

                {{-- Tags --}}
                <div class="col-md-12">
                    <label class="form-label fw-bold">Thẻ (Ctrl để chọn nhiều)</label>
                    <select name="tags[]" multiple size="6"
                            class="form-select form-select-lg">
                        @php($selected = $baiviet->tags->pluck('id')->all())
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}"
                                @selected(in_array($tag->id, old('tags', $selected)))>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- Nút --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.baiviet.index') }}" class="btn btn-light me-2">
                    <i class="fas fa-times"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </div>

        </form>

    </div>
</div>


{{-- =======================================
     🔥 CSS riêng cho UI form
======================================= --}}
<style>
    .form-label {
        font-size: 15px;
    }

    .form-control-lg, .form-select-lg {
        padding: 10px 14px;
        border-radius: 10px;
    }

    textarea {
        resize: none;
    }
</style>

@endsection
