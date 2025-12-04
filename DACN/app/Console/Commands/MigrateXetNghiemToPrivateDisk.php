<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\XetNghiem;
use Illuminate\Support\Facades\Storage;

class MigrateXetNghiemToPrivateDisk extends Command
{
    protected $signature = 'xetnghiem:migrate-to-private';
    protected $description = 'Di chuyển file xét nghiệm từ public sang benh_an_private disk';

    public function handle()
    {
        $this->info('Bắt đầu di chuyển file xét nghiệm...');
        
        $xetNghiems = XetNghiem::where('disk', 'public')
            ->orWhereNull('disk')
            ->get();
        
        $this->info("Tìm thấy {$xetNghiems->count()} file cần di chuyển");
        
        $success = 0;
        $failed = 0;
        
        foreach ($xetNghiems as $xn) {
            try {
                // Kiểm tra file tồn tại trong public disk
                if (!Storage::disk('public')->exists($xn->file_path)) {
                    $this->warn("File không tồn tại: {$xn->file_path}");
                    $failed++;
                    continue;
                }
                
                // Copy file sang benh_an_private disk
                $content = Storage::disk('public')->get($xn->file_path);
                Storage::disk('benh_an_private')->put($xn->file_path, $content);
                
                // Xóa file cũ từ public disk
                Storage::disk('public')->delete($xn->file_path);
                
                // Cập nhật database
                $xn->update(['disk' => 'benh_an_private']);
                
                $this->line("✓ Di chuyển thành công: {$xn->file_path}");
                $success++;
                
            } catch (\Exception $e) {
                $this->error("✗ Lỗi khi di chuyển file ID {$xn->id}: {$e->getMessage()}");
                $failed++;
            }
        }
        
        $this->info("\n=== KẾT QUẢ ===");
        $this->info("Thành công: {$success}");
        $this->error("Thất bại: {$failed}");
        
        return Command::SUCCESS;
    }
}
