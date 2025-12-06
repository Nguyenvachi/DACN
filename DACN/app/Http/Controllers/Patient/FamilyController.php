<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FamilyController extends Controller
{
    public function index()
    {
        $familyMembers = FamilyMember::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.family.index', compact('familyMembers'));
    }

    public function create()
    {
        return view('patient.family.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'quan_he' => 'required|string|in:cha,me,vo,chong,con,anh,chi,em,ong,ba,khac',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|string|in:nam,nu,khac',
            'so_dien_thoai' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'dia_chi' => 'nullable|string',
            'nhom_mau' => 'nullable|string|in:A,B,AB,O',
            'chieu_cao' => 'nullable|numeric|min:0',
            'can_nang' => 'nullable|numeric|min:0',
            'tien_su_benh' => 'nullable|string',
            'bhyt_ma_so' => 'nullable|string|max:50',
            'bhyt_ngay_het_han' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        FamilyMember::create($validated);

        return redirect()->route('patient.family.index')
            ->with('success', 'Đã thêm thành viên gia đình thành công');
    }

    public function edit(FamilyMember $member)
    {
        abort_if($member->user_id !== auth()->id(), 403);

        return view('patient.family.edit', compact('member'));
    }

    public function update(Request $request, FamilyMember $member)
    {
        abort_if($member->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'ho_ten' => 'required|string|max:255',
            'quan_he' => 'required|string|in:cha,me,vo,chong,con,anh,chi,em,ong,ba,khac',
            'ngay_sinh' => 'required|date',
            'gioi_tinh' => 'required|string|in:nam,nu,khac',
            'so_dien_thoai' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'dia_chi' => 'nullable|string',
            'nhom_mau' => 'nullable|string|in:A,B,AB,O',
            'chieu_cao' => 'nullable|numeric|min:0',
            'can_nang' => 'nullable|numeric|min:0',
            'tien_su_benh' => 'nullable|string',
            'bhyt_ma_so' => 'nullable|string|max:50',
            'bhyt_ngay_het_han' => 'nullable|date',
        ]);

        $member->update($validated);

        return redirect()->route('patient.family.index')
            ->with('success', 'Đã cập nhật thông tin thành viên thành công');
    }

    public function destroy(FamilyMember $member)
    {
        abort_if($member->user_id !== auth()->id(), 403);

        $member->delete();

        return redirect()->route('patient.family.index')
            ->with('success', 'Đã xóa thành viên gia đình');
    }
}
