<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NhaCungCap;
use App\Models\Thuoc;
use App\Models\ThuocKho;
use App\Models\PhieuNhap;
use App\Models\PhieuNhapItem;
use App\Models\PhieuXuat;
use App\Models\PhieuXuatItem;
use App\Models\Coupon;

class KhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1) Suppliers (15)
            $supplierNames = [
                'Zuellig Pharma Vietnam',
                'DKSH Vietnam',
                'Dược Hậu Giang',
                'Traphaco',
                'Vinapharm',
                'Công ty Vật tư Y tế Sài Gòn',
                'Công ty Vật tư Y tế Hà Nội',
                'Công ty Vật tư Y tế Trung Tâm',
                'Hóa chất Roche Vietnam',
                'Hóa chất Abbott Vietnam',
                'Hóa chất Biomed',
                'Công ty Thiết bị Y tế MedTech',
                'Công ty Thiết bị Y tế VN',
                'Nhà thuốc An Bình',
                'Nhà thuốc Bảo An'
            ];

            $suppliers = [];
            foreach ($supplierNames as $i => $name) {
                $suppliers[] = NhaCungCap::updateOrCreate(
                    ['ten' => $name],
                    ['dia_chi' => 'TP.HCM', 'so_dien_thoai' => '0909' . (1000 + $i), 'email' => 'supplier' . ($i + 1) . '@vietcare.com']
                );
            }

            // 2) Medicines (150) - build base + generated variants
            $baseMeds = [
                'Paracetamol 500mg', 'Ibuprofen 200mg', 'Amoxicillin 500mg', 'Azithromycin 250mg', 'Doxycycline 100mg',
                'Metronidazole 500mg', 'Cefuroxime 250mg', 'Cefixime 400mg', 'Cefadroxil 500mg', 'Ceftriaxone 1g',
                'Omeprazole 20mg', 'Pantoprazole 40mg', 'Ranitidine 150mg', 'Tranexamic 500mg', 'Ferrous sulfate 325mg',
                'Vitamin C 500mg', 'Vitamin D3 2000IU', 'Calcium 600mg', 'Utrogestan 200mg', 'Duphaston 10mg',
                'Levothyroxine 50mcg', 'Progesterone 100mg', 'Nitrofurantoin 100mg', 'Fluconazole 150mg', 'Ketoconazole 200mg'
            ];

            // We'll create 150 meds by replicating with small variations
            $thuocs = [];
            $idx = 1;
            while (count($thuocs) < 150) {
                foreach ($baseMeds as $m) {
                    if (count($thuocs) >= 150) break;
                    $name = $m;
                    if ($idx > 1) {
                        $name = $m . ' - ' . sprintf('Lot%02d', $idx);
                    }
                    $hoten = explode(' ', $m);
                    $don_vi = strpos($m, 'mg') !== false ? 'viên' : 'ống';
                    $gia = rand(800, 10000);
                    $ton_min = rand(10, 200);
                    $thuoc = Thuoc::updateOrCreate(['ten' => $name], [
                        'hoat_chat' => $hoten[0] ?? $m,
                        'ham_luong' => preg_match('/(\d+mg|\d+g|\d+IU)/i', $m, $matches) ? ($matches[0] ?? null) : null,
                        'don_vi' => $don_vi,
                        'gia_tham_khao' => $gia,
                        'ton_toi_thieu' => $ton_min
                    ]);
                    $thuocs[$name] = $thuoc;
                    $idx++;
                }
            }

            // 3) Consumables (50) as Thuoc with don_vi 'cái' or 'bộ'
            $consumableBase = [
                'Găng tay y tế S', 'Găng tay y tế M', 'Găng tay y tế L', 'Bơm kim tiêm 1ml', 'Bơm kim tiêm 5ml', 'Bơm kim tiêm 10ml',
                'Bông gòn 50g', 'Cồn 70%', 'Gel siêu âm 5L', 'Que thử thai', 'Que thử rụng trứng',
                'Kim tiêm 21G', 'Kim tiêm 23G', 'Khẩu trang N95', 'Khẩu trang y tế 3 lớp', 'Gạc vô trùng 10x10', 'Túi đựng máu',
                'Hộp đựng dụng cụ tiểu phẫu', 'Dao mổ 10', 'Dụng cụ lấy máu', 'Bồn đựng mẫu'
            ];
            $consumables = [];
            $cid = 1;
            while (count($consumables) < 50) {
                foreach ($consumableBase as $c) {
                    if (count($consumables) >= 50) break;
                    $name = $c . ($cid > 1 ? ' - Pack ' . $cid : '');
                    $gia = rand(2000, 50000);
                    $ton_min = rand(5, 50);
                    $thuoc = Thuoc::updateOrCreate(['ten' => $name], [
                        'don_vi' => 'cái', 'gia_tham_khao' => $gia, 'ton_toi_thieu' => $ton_min
                    ]);
                    $consumables[$name] = $thuoc;
                    $cid++;
                }
            }

            // 4) Link random suppliers to medicines
            $allSuppliers = NhaCungCap::all();
            foreach (array_merge($thuocs, $consumables) as $thuoc) {
                // attach 2 random suppliers
                $picked = $allSuppliers->random(2)->pluck('id')->toArray();
                foreach ($picked as $supId) {
                    $sp = NhaCungCap::find($supId);
                    if ($sp && !$sp->thuocs()->wherePivot('thuoc_id', $thuoc->id)->exists()) {
                        $sp->thuocs()->attach($thuoc->id, ['gia_nhap_mac_dinh' => $thuoc->gia_tham_khao ?? 1000]);
                    }
                }
            }

            // 5) Create initial incoming transactions (10 PN) spread across suppliers
            $allThuocs = array_values(array_merge($thuocs, $consumables));
            $pnCount = 10;
            for ($i = 1; $i <= $pnCount; $i++) {
                $supplier = $allSuppliers->random();
                $pn = PhieuNhap::firstOrCreate(['ma_phieu' => 'PN-SEED-' . $i], [
                    'ngay_nhap' => Carbon::now()->subDays(rand(5, 90))->toDateString(),
                    'nha_cung_cap_id' => $supplier->id, 'tong_tien' => 0, 'user_id' => null, 'ghi_chu' => 'Seed PN #' . $i
                ]);

                $items = [];
                $selected = collect($allThuocs)->shuffle()->take(rand(6, 15))->values();
                $total = 0;
                foreach ($selected as $it) {
                    $quantity = rand(20, 500);
                    $don_gia = max(100, rand(500, 10000));
                    $thanh_tien = $don_gia * $quantity;
                    $lotCode = 'LOT-PN-' . $i . '-' . rand(1, 999);
                    PhieuNhapItem::firstOrCreate([
                        'phieu_nhap_id' => $pn->id, 'thuoc_id' => $it->id, 'ma_lo' => $lotCode
                    ], [
                        'han_su_dung' => Carbon::now()->addMonths(rand(6, 36))->toDateString(), 'so_luong' => $quantity, 'don_gia' => $don_gia, 'thanh_tien' => $thanh_tien
                    ]);

                    $total += $thanh_tien;

                    // Update/create ThuocKho
                    //$lotCode = 'LOT-PN-' . $i . '-' . rand(1, 999);
                    $k = ThuocKho::where('thuoc_id', $it->id)->where('ma_lo', $lotCode)->first();
                    if ($k) {
                        $k->increment('so_luong', $quantity);
                    } else {
                        ThuocKho::create(['thuoc_id' => $it->id, 'ma_lo' => $lotCode, 'han_su_dung' => Carbon::now()->addMonths(rand(6, 36))->toDateString(), 'so_luong' => $quantity, 'gia_nhap' => $don_gia, 'gia_xuat' => $don_gia * 1.4, 'nha_cung_cap_id' => $supplier->id]);
                    }
                }

                $pn->update(['tong_tien' => $total]);
            }

            // 6) Create sample outgoing transactions (PX) to emulate sales/internal use (10 PX)
            $pxCount = 10;
            for ($p = 1; $p <= $pxCount; $p++) {
                $px = PhieuXuat::firstOrCreate(['ma_phieu' => 'PX-SEED-' . $p], ['ngay_xuat' => Carbon::now()->subDays(rand(1, 30))->toDateString(), 'doi_tuong' => 'Khach le', 'tong_tien' => 0, 'user_id' => null, 'ghi_chu' => 'Seed PX #' . $p]);

                $selected = collect($allThuocs)->shuffle()->take(rand(3, 8))->values();
                $totalPx = 0;
                foreach ($selected as $tx) {
                    $need = rand(1, 100);
                    $don_gia = max(1000, rand(1000, 12000));
                    $remain = $need;
                    // FIFO: use oldest lots first
                    $lots = ThuocKho::where('thuoc_id', $tx->id)->where('so_luong', '>', 0)->orderBy('han_su_dung')->get();
                    foreach ($lots as $lot) {
                        if ($remain <= 0) break;
                        $take = min($lot->so_luong, $remain);
                        PhieuXuatItem::create(['phieu_xuat_id' => $px->id, 'thuoc_id' => $tx->id, 'so_luong' => $take, 'don_gia' => $don_gia, 'thanh_tien' => $take * $don_gia]);
                        $lot->decrement('so_luong', $take);
                        $remain -= $take;
                        $totalPx += $take * $don_gia;
                    }
                    // if not enough stock, still record remainder as virtual outgoing
                    if ($remain > 0) {
                        PhieuXuatItem::create(['phieu_xuat_id' => $px->id, 'thuoc_id' => $tx->id, 'so_luong' => $remain, 'don_gia' => $don_gia, 'thanh_tien' => $remain * $don_gia]);
                        $totalPx += $remain * $don_gia;
                    }
                }
                $px->update(['tong_tien' => $totalPx]);
            }

            $this->command->info('✅ KhoSeeder: Seeded suppliers, ' . count($thuocs) . ' medicines, ' . count($consumables) . ' consumables, PN/PX transactions.');

            // 7) Add some near-expiry and low-stock lots to trigger background checks
            $nearExpiry = collect(array_values(array_merge($thuocs, $consumables)))->shuffle()->take(5);
            foreach ($nearExpiry as $nx) {
                $lotCode = 'LOT-NEAR-' . rand(1000, 9999);
                $k = ThuocKho::firstOrCreate(['thuoc_id' => $nx->id, 'ma_lo' => $lotCode], [
                    'han_su_dung' => Carbon::now()->addDays(rand(5, 15))->toDateString(),
                    'so_luong' => rand(1, 10),
                    'gia_nhap' => $nx->gia_tham_khao ?? 1000,
                    'gia_xuat' => ($nx->gia_tham_khao ?? 1000) * 1.3,
                    'nha_cung_cap_id' => $allSuppliers->random()->id
                ]);
            }

            // 8) Seed 6 coupons/voucher codes consistent with business rules
            $couponDefs = [
                ['ma' => 'CHAOBANMOI', 'ten' => 'Giảm 10% cho khách mới', 'loai' => 'phan_tram', 'gia_tri' => 10, 'don_toi_thieu' => 0, 'ngay_bat_dau' => Carbon::now()->subDays(30), 'ngay_ket_thuc' => Carbon::now()->addMonths(6)],
                ['ma' => 'KHAMTHAI', 'ten' => 'Giảm 50k cho gói khám thai', 'loai' => 'tien_mat', 'gia_tri' => 50000, 'don_toi_thieu' => 200000, 'ngay_bat_dau' => Carbon::now()->subDays(30), 'ngay_ket_thuc' => Carbon::now()->addMonths(6)],
                ['ma' => 'TRIAN', 'ten' => 'Ưu đãi cho khách cũ', 'loai' => 'phan_tram', 'gia_tri' => 5, 'don_toi_thieu' => 0, 'ngay_bat_dau' => Carbon::now()->subDays(30), 'ngay_ket_thuc' => Carbon::now()->addMonths(6)],
                ['ma' => 'SINHNHAT', 'ten' => 'Quà sinh nhật', 'loai' => 'tien_mat', 'gia_tri' => 100000, 'don_toi_thieu' => 0, 'ngay_bat_dau' => Carbon::now()->subDays(365), 'ngay_ket_thuc' => Carbon::now()->addYears(2)],
                ['ma' => 'TRONGGIO', 'ten' => 'Giảm 10% giờ vàng', 'loai' => 'phan_tram', 'gia_tri' => 10, 'don_toi_thieu' => 0, 'ngay_bat_dau' => Carbon::now()->subDays(30), 'ngay_ket_thuc' => Carbon::now()->addMonths(3)],
                ['ma' => 'DICHVU50', 'ten' => 'Giảm 50% dịch vụ chọn lọc', 'loai' => 'phan_tram', 'gia_tri' => 50, 'don_toi_thieu' => 500000, 'ngay_bat_dau' => Carbon::now()->subDays(30), 'ngay_ket_thuc' => Carbon::now()->addMonths(6)],
            ];
            foreach ($couponDefs as $c) {
                Coupon::updateOrCreate(['ma_giam_gia' => $c['ma']], [
                    'ten' => $c['ten'], 'mo_ta' => $c['ten'], 'loai' => $c['loai'], 'gia_tri' => $c['gia_tri'], 'don_toi_thieu' => $c['don_toi_thieu'], 'ngay_bat_dau' => $c['ngay_bat_dau'], 'ngay_ket_thuc' => $c['ngay_ket_thuc'], 'so_lan_su_dung_toi_da' => null, 'kich_hoat' => true
                ]);
            }
        });
    }
}
