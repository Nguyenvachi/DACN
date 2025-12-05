<?php
/**
 * Parent file: app/Http/Controllers/Admin/MediaController.php
 * Quản lý upload và library media (ảnh) cho CKEditor và hệ thống
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Upload ảnh cho CKEditor 5
     * CKEditor yêu cầu response format: { "url": "path/to/image.jpg" }
     */
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Max 5MB
        ]);

        try {
            $file = $request->file('upload');

            // Tạo tên file unique
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                      . '.' . $file->getClientOriginalExtension();

            // Lưu vào storage/app/public/uploads/posts
            $path = $file->storeAs('uploads/posts', $filename, 'public');

            // URL đầy đủ
            $url = Storage::url($path);

            // Response theo format CKEditor 5
            return response()->json([
                'url' => $url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => [
                    'message' => 'Upload failed: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    /**
     * Hiển thị Media Library
     */
    public function index()
    {
        $images = $this->getUploadedImages();
        return view('admin.media.index', compact('images'));
    }

    /**
     * Xóa ảnh
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        $path = $request->path;

        // Remove /storage/ prefix để lấy đúng path
        $storagePath = str_replace('/storage/', '', $path);

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa ảnh thành công'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy ảnh'
        ], 404);
    }

    /**
     * Lấy danh sách ảnh đã upload
     */
    protected function getUploadedImages()
    {
        $files = Storage::disk('public')->files('uploads/posts');

        return collect($files)->map(function ($file) {
            return [
                'path' => Storage::url($file),
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'modified' => Storage::disk('public')->lastModified($file),
            ];
        })->sortByDesc('modified')->values()->all();
    }

    /**
     * AJAX: Copy URL ảnh
     */
    public function getUrl(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        return response()->json([
            'url' => $request->path,
            'full_url' => url($request->path)
        ]);
    }
}
