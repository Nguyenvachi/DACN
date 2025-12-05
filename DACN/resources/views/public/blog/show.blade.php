@extends('layouts.app')

@push('meta')
    {{-- Meta Title --}}
    @if($baiViet->meta_title)
        <title>{{ $baiViet->meta_title }}</title>
        <meta property="og:title" content="{{ $baiViet->meta_title }}">
        <meta name="twitter:title" content="{{ $baiViet->meta_title }}">
    @else
        <title>{{ $baiViet->title }}</title>
        <meta property="og:title" content="{{ $baiViet->title }}">
        <meta name="twitter:title" content="{{ $baiViet->title }}">
    @endif

    {{-- Meta Description --}}
    @if($baiViet->meta_description)
        <meta name="description" content="{{ $baiViet->meta_description }}">
        <meta property="og:description" content="{{ $baiViet->meta_description }}">
        <meta name="twitter:description" content="{{ $baiViet->meta_description }}">
    @elseif($baiViet->excerpt)
        <meta name="description" content="{{ $baiViet->excerpt }}">
        <meta property="og:description" content="{{ $baiViet->excerpt }}">
        <meta name="twitter:description" content="{{ $baiViet->excerpt }}">
    @endif

    {{-- OG Image --}}
    @if($baiViet->thumbnail)
        <meta property="og:image" content="{{ url($baiViet->thumbnail) }}">
        <meta name="twitter:image" content="{{ url($baiViet->thumbnail) }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ route('blog.show', $baiViet->slug) }}">
    <meta property="og:url" content="{{ route('blog.show', $baiViet->slug) }}">

    {{-- Additional OG --}}
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="article:published_time" content="{{ optional($baiViet->published_at)->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $baiViet->updated_at->toIso8601String() }}">
    @if($baiViet->author)
        <meta property="article:author" content="{{ $baiViet->author->name }}">
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
        {!! $baiViet->content !!}
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
