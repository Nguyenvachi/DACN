<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function download(HoaDon $hoaDon)
    {
        $this->authorizeAccess($hoaDon);

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.hoa_don', ['hoaDon' => $hoaDon]);
            return $pdf->download('bien-lai-' . $hoaDon->id . '.pdf');
        }

        return response()->view('pdf.hoa_don', ['hoaDon' => $hoaDon])
            ->header('Content-Disposition', 'attachment; filename=bien-lai-' . $hoaDon->id . '.html');
    }

    /**
     * Tải các loại hóa đơn/phiếu thu cụ thể theo loại (thêm chức năng mà không sửa chức năng hiện có).
     * Loại hợp lệ: phieu-thu, dich-vu, thuoc, tong-hop
     * Parent file: `app/Http/Controllers/ReceiptController.php` (thêm method)
     * Child views: các file mới trong `resources/views/pdf/` (tạo bên dưới)
     */
    public function downloadByType(HoaDon $hoaDon, string $type)
    {
        $this->authorizeAccess($hoaDon);

        $map = [
            'phieu-thu' => 'pdf.phieu_thu_kham',
            'dich-vu' => 'pdf.hoa_don_dich_vu',
            'thuoc' => 'pdf.hoa_don_thuoc',
            'tong-hop' => 'pdf.hoa_don_tong_hop',
        ];

        if (! isset($map[$type])) {
            abort(404);
        }

        $view = $map[$type];

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, ['hoaDon' => $hoaDon]);
            return $pdf->download($type . '-' . $hoaDon->id . '.pdf');
        }

        return response()->view($view, ['hoaDon' => $hoaDon])
            ->header('Content-Disposition', 'attachment; filename=' . $type . '-' . $hoaDon->id . '.html');
    }

    private function authorizeAccess(HoaDon $hoaDon): void
    {
        $user = auth()->user();
        if (!$user) abort(401);

        if ($user instanceof \App\Models\User && $user->hasRole('admin')) return;

        if ($hoaDon->user_id !== $user->id) abort(403);
    }
}
