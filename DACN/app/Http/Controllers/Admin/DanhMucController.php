<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DanhMucController extends Controller
{
    public function index()
    {
        $items = DanhMuc::latest()->paginate(20);
        return view('admin.danhmuc.index', compact('items'));
    }

    public function create()
    {
        return view('admin.danhmuc.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = $this->makeUniqueSlug($data['name']);

        DanhMuc::create($data);
        return redirect()->route('admin.danhmuc.index')->with('success', 'Tạo danh mục thành công');
    }

    public function edit(DanhMuc $danhmuc)
    {
        return view('admin.danhmuc.edit', compact('danhmuc'));
    }

    public function update(Request $request, DanhMuc $danhmuc)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
        ]);

        if ($danhmuc->name !== $data['name']) {
            $data['slug'] = $this->makeUniqueSlug($data['name'], $danhmuc->id);
        }

        $danhmuc->update($data);
        return redirect()->route('admin.danhmuc.index')->with('success', 'Cập nhật danh mục thành công');
    }

    public function destroy(DanhMuc $danhmuc)
    {
        $danhmuc->delete();
        return redirect()->route('admin.danhmuc.index')->with('success', 'Đã xóa danh mục');
    }

    private function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (DanhMuc::where('slug', $slug)->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }
        return $slug;
    }
}
