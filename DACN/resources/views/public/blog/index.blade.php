@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@push('meta')
    <meta name="description" content="Tin tức và kiến thức y khoa từ phòng khám">
    <meta property="og:title" content="Blog phòng khám">
    <meta property="og:description" content="Tin tức và kiến thức y khoa từ phòng khám">
@endpush

@section('content')
<div class="container mt-3">
    <h1>{{ $heading ?? 'Blog' }}</h1>
    <form method="get" class="mb-3">
        <input type="text" name="q" value="{{ $q }}" placeholder="Tìm bài viết...">
        <button type="submit">Tìm</button>
    </form>

    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                @if($post->thumbnail)
                    <img src="{{ $post->thumbnail }}" class="card-img-top" alt="{{ $post->title }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title"><a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a></h5>
                    <p class="card-text">{{ $post->excerpt }}</p>
                </div>
                <div class="card-footer">
                    <small class="text-muted">{{ optional($post->published_at??$post->created_at)->format('d/m/Y') }}</small>
                    @if($post->danhMuc)
                        <a href="{{ route('blog.category', $post->danhMuc) }}" class="ms-2">#{{ $post->danhMuc->name }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $posts->links() }}
</div>
@endsection
