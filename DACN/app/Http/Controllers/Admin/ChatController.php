<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $query = Conversation::with(['benhNhan', 'bacSi.user', 'latestMessage'])
                            ->orderBy('last_message_at', 'desc');

        // Filters
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('benhNhan', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('bacSi', function ($bacSiQuery) use ($search) {
                    $bacSiQuery->where('ho_ten', 'like', "%{$search}%");
                });
            });
        }

        $conversations = $query->get();

        // Statistics
        $stats = [
            'total' => Conversation::count(),
            'active' => Conversation::where('trang_thai', 'Đang hoạt động')->count(),
            'closed' => Conversation::where('trang_thai', 'Đã đóng')->count(),
            'total_messages' => Message::count(),
        ];

        return view('admin.chat.index', compact('conversations', 'stats'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['benhNhan', 'bacSi.user', 'lichHen', 'messages.user'])
                                     ->findOrFail($id);

        return view('admin.chat.show', compact('conversation'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:Đang hoạt động,Đã đóng,Bị khóa',
        ]);

        $conversation = Conversation::findOrFail($id);
        $conversation->update(['trang_thai' => $request->trang_thai]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái cuộc hội thoại');
    }

    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->delete();

        return redirect()->route('admin.chat.index')->with('success', 'Đã xóa cuộc hội thoại');
    }

    public function getMessages($id)
    {
        $conversation = Conversation::findOrFail($id);

        $messages = $conversation->messages()
                                  ->with('user')
                                  ->orderBy('created_at', 'asc')
                                  ->get();

        return response()->json($messages);
    }
}
