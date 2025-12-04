@extends('layouts.app')

@push('meta')
    @if($baiViet->meta_title)
        <meta property="og:title" content="{{ $baiViet->meta_title }}">
    @endif
    @if($baiViet->meta_description)
        <meta name="description" content="{{ $baiViet->meta_description }}">
        <meta property="og:description" content="{{ $baiViet->meta_description }}">
    @endif
@endpush

@section('content')
<div class="container mt-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
            @if($baiViet->danhMuc)
                <li class="breadcrumb-item"><a href="{{ route('blog.category', $baiViet->danhMuc) }}">{{ $baiViet->danhMuc->name }}</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">{{ $baiViet->title }}</li>
        </ol>
    </nav>

    <h1>{{ $baiViet->title }}</h1>
    @if($baiViet->thumbnail)
        <img src="{{ $baiViet->thumbnail }}" alt="{{ $baiViet->title }}" class="img-fluid mb-3">
    @endif
    <p class="text-muted">{{ optional($baiViet->published_at??$baiViet->created_at)->format('d/m/Y H:i') }}</p>

    <article class="mb-4">
        {!! nl2br(e($baiViet->content)) !!}
    </article>

    <div class="mb-3">
        @foreach($baiViet->tags as $tag)
            <a href="{{ route('blog.tag', $tag) }}" class="badge bg-secondary">#{{ $tag->name }}</a>
        @endforeach
    </div>

    <hr>
    <h5>Bài viết liên quan</h5>
    <ul>
        @foreach($related as $r)
            <li><a href="{{ route('blog.show', $r) }}">{{ $r->title }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
