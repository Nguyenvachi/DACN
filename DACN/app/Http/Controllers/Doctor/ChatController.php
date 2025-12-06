<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Bạn không có quyền truy cập chức năng này');
        }

        $conversations = Conversation::with(['benhNhan', 'latestMessage'])
                                      ->where('bac_si_id', $bacSi->id)
                                      ->orderBy('last_message_at', 'desc')
                                      ->get();

        return view('doctor.chat.index', compact('conversations'));
    }

    public function show($id)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Bạn không có quyền truy cập chức năng này');
        }

        $userId = auth()->id();

        $conversation = Conversation::with(['benhNhan', 'messages.user'])
                                     ->where('bac_si_id', $bacSi->id)
                                     ->findOrFail($id);

        // Đánh dấu tất cả tin nhắn là đã đọc
        $conversation->markAsReadForUser($userId);

        return view('doctor.chat.show', compact('conversation'));
    }

    public function sendMessage(Request $request, $id)
    {
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Bạn không có quyền truy cập chức năng này');
        }

        $userId = auth()->id();

        $conversation = Conversation::where('bac_si_id', $bacSi->id)
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
        $bacSi = auth()->user()->bacSi;

        if (!$bacSi) {
            abort(403, 'Bạn không có quyền truy cập chức năng này');
        }

        $conversation = Conversation::where('bac_si_id', $bacSi->id)
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
