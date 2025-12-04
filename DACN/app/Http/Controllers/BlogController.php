<?php

namespace App\Http\Controllers;

use App\Models\BaiViet;
use App\Models\DanhMuc;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $query = BaiViet::with(['danhMuc','author'])->published()->latest();
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }
        $posts = $query->paginate(9)->withQueryString();
        return view('public.blog.index', compact('posts', 'q'));
    }

    public function show(BaiViet $baiViet)
    {
        abort_unless($baiViet->status === 'published', 404);
        $related = BaiViet::published()
            ->where('id','!=',$baiViet->id)
            ->when($baiViet->danh_muc_id, fn($q)=>$q->where('danh_muc_id',$baiViet->danh_muc_id))
            ->latest()->take(5)->get();
        return view('public.blog.show', compact('baiViet','related'));
    }

    public function category(DanhMuc $danhMuc)
    {
        $posts = BaiViet::published()->where('danh_muc_id', $danhMuc->id)->latest()->paginate(9);
        return view('public.blog.index', [
            'posts' => $posts,
            'q' => null,
            'heading' => 'Danh mục: '.$danhMuc->name,
            'danhMuc' => $danhMuc,
        ]);
    }

    public function tag(Tag $tag)
    {
        $posts = BaiViet::published()->whereHas('tags', fn($q)=>$q->where('tags.id', $tag->id))->latest()->paginate(9);
        return view('public.blog.index', [
            'posts' => $posts,
            'q' => null,
            'heading' => 'Thẻ: '.$tag->name,
            'tag' => $tag,
        ]);
    }
}
