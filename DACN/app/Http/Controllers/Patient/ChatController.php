<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\BacSi;
use App\Models\LichHen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::with(['bacSi.user', 'latestMessage'])
                                      ->where('benh_nhan_id', $userId)
                                      ->orderBy('last_message_at', 'desc')
                                      ->get();

        return view('patient.chat.index', compact('conversations'));
    }

    public function show($id)
    {
        $userId = auth()->id();

        $conversation = Conversation::with(['bacSi.user', 'messages.user'])
                                     ->where('benh_nhan_id', $userId)
                                     ->findOrFail($id);

        // Đánh dấu tất cả tin nhắn là đã đọc
        $conversation->markAsReadForUser($userId);

        return view('patient.chat.show', compact('conversation'));
    }

    public function create($bacSiId)
    {
        $userId = auth()->id();
        $bacSi = BacSi::findOrFail($bacSiId);

        // Kiểm tra xem bệnh nhân có lịch hẹn với bác sĩ không
        $lichHen = LichHen::where('user_id', $userId)
                          ->where('bac_si_id', $bacSiId)
                          ->whereIn('trang_thai', ['Đã xác nhận', 'Hoàn thành'])
                          ->first();

        if (!$lichHen) {
            return redirect()->back()->with('error', 'Bạn cần có lịch hẹn với bác sĩ để bắt đầu chat');
        }

        // Tìm hoặc tạo conversation
        $conversation = Conversation::firstOrCreate(
            [
                'benh_nhan_id' => $userId,
                'bac_si_id' => $bacSiId,
            ],
            [
                'lich_hen_id' => $lichHen->id,
                'tieu_de' => 'Tư vấn với ' . $bacSi->ho_ten,
                'trang_thai' => 'Đang hoạt động',
                'last_message_at' => now(),
            ]
        );

        return redirect()->route('patient.chat.show', $conversation->id);
    }

    public function sendMessage(Request $request, $id)
    {
        $userId = auth()->id();

        $conversation = Conversation::where('benh_nhan_id', $userId)
                                     ->findOrFail($id);

        $request->validate([
            'noi_dung' => 'required_without:file|string|max:2000',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf,doc,docx',
        ], [
            'noi_dung.required_without' => 'Vui lòng nhập tin nhắn hoặc đính kèm file',
            'file.max' => 'File không được vượt quá 10MB',
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'user_id' => $userId,
            'noi_dung' => $request->noi_dung,
        ];

        // Xử lý file đính kèm
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('chat_files', 'public');

            $data['file_path'] = $path;
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_type'] = $this->getFileType($file->getMimeType());
        }

        $message = Message::create($data);
        $conversation->updateLastMessageTime();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }

        return redirect()->back()->with('success', 'Đã gửi tin nhắn');
    }

    public function getMessages($id)
    {
        $userId = auth()->id();

        $conversation = Conversation::where('benh_nhan_id', $userId)
                                     ->findOrFail($id);

        $messages = $conversation->messages()
                                  ->with('user')
                                  ->orderBy('created_at', 'asc')
                                  ->get();

        return response()->json($messages);
    }

    private function getFileType($mimeType)
    {
        if (strpos($mimeType, 'image') !== false) {
            return 'image';
        } elseif (strpos($mimeType, 'pdf') !== false) {
            return 'pdf';
        }
        return 'document';
    }
}
