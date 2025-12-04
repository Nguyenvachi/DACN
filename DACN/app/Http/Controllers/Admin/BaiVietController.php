<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\DanhMuc;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BaiVietController extends Controller
{
    public function index()
    {
        $posts = BaiViet::with(['danhMuc', 'author'])->latest()->paginate(12);
        return view('admin.baiviet.index', compact('posts'));
    }

    public function create()
    {
        $danhMucs = DanhMuc::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.baiviet.create', compact('danhMucs', 'tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'danh_muc_id' => 'nullable|exists:danh_mucs,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:255',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $data['user_id'] = Auth::id();
        $data['slug'] = $this->makeUniqueSlug($data['title']);

        $post = BaiViet::create($data);
        if (!empty($data['tags'])) {
            $post->tags()->sync($data['tags']);
        }

        return redirect()->route('admin.baiviet.index')->with('success', 'Tạo bài viết thành công');
    }

    public function edit(BaiViet $baiviet)
    {
        $danhMucs = DanhMuc::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.baiviet.edit', compact('baiviet', 'danhMucs', 'tags'));
    }

    public function update(Request $request, BaiViet $baiviet)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'danh_muc_id' => 'nullable|exists:danh_mucs,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:255',
            'tags' => 'array',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        if ($baiviet->title !== $data['title']) {
            $data['slug'] = $this->makeUniqueSlug($data['title'], $baiviet->id);
        }

        $baiviet->update($data);
        $baiviet->tags()->sync($data['tags'] ?? []);

        return redirect()->route('admin.baiviet.index')->with('success', 'Cập nhật bài viết thành công');
    }

    public function destroy(BaiViet $baiviet)
    {
        $baiviet->delete();
        return redirect()->route('admin.baiviet.index')->with('success', 'Đã xóa bài viết');
    }

    private function makeUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;
        while (BaiViet::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
