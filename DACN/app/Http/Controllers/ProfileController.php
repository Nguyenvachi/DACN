<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Models\PatientProfile;
use App\Models\NotificationPreference;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Load hoặc tạo patient profile
        $profile = $user->patientProfile ?? new PatientProfile(['user_id' => $user->id]);

        // Load hoặc tạo notification preferences
        $preferences = $user->notificationPreference ?? new NotificationPreference(['user_id' => $user->id]);

        return view('profile.edit', compact('user', 'profile', 'preferences'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Cập nhật hồ sơ y tế
     */
    public function updateMedical(Request $request)
    {
        $validated = $request->validate([
            'nhom_mau' => 'nullable|string|max:10',
            'chieu_cao' => 'nullable|numeric|min:0|max:300',
            'can_nang' => 'nullable|numeric|min:0|max:500',
            'allergies' => 'nullable|array',
            'allergies.*' => 'string|max:255',
            'tien_su_benh' => 'nullable|string|max:2000',
            'thuoc_dang_dung' => 'nullable|string|max:2000',
            'benh_man_tinh' => 'nullable|string|max:2000',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        $user = $request->user();
        $profile = $user->patientProfile ?? new PatientProfile(['user_id' => $user->id]);

        $profile->fill($validated);
        $profile->save();

        // Return JSON cho AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin y tế thành công'
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'medical-profile-updated');
    }

    /**
     * Upload ảnh đại diện
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();
        $profile = $user->patientProfile ?? new PatientProfile(['user_id' => $user->id]);

        // Xóa ảnh cũ
        if ($profile->avatar && Storage::disk('public')->exists($profile->avatar)) {
            Storage::disk('public')->delete($profile->avatar);
        }

        // Upload ảnh mới
        $path = $request->file('avatar')->store('avatars', 'public');
        $profile->avatar = $path;
        $profile->save();

        // Return JSON cho AJAX
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã tải ảnh lên thành công',
                'avatar_url' => asset('storage/' . $path)
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Cập nhật tùy chọn thông báo
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email_appointment_reminder' => 'boolean',
            'email_appointment_confirmed' => 'boolean',
            'email_appointment_cancelled' => 'boolean',
            'email_test_results' => 'boolean',
            'email_promotions' => 'boolean',
            'sms_appointment_reminder' => 'boolean',
            'sms_appointment_confirmed' => 'boolean',
            'reminder_hours_before' => 'integer|min:1|max:168',
        ]);

        $user = $request->user();
        $preferences = $user->notificationPreference ?? new NotificationPreference(['user_id' => $user->id]);

        $preferences->fill($validated);
        $preferences->save();

        return Redirect::route('profile.edit')->with('status', 'notification-preferences-updated');
    }
}
