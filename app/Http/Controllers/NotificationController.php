<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\KanbanCard;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $notifications = $user->commentMentions()
                ->with(['kanbanComment.user']) // pastikan relasi user diambil
                ->latest()
                ->get();

            $formatted = $notifications->map(function ($mention) {
                $comment = $mention->kanbanComment;
                $commenter = $comment?->user?->name ?? 'Seseorang';
                $commentText = $comment?->comment ?? '(komentar kosong)';
                $cardId = $comment->kanban_card_id ?? null;

                return [
                    'id' => $mention->id,
                    'commenter' => $commenter,
                    'comment' => $commentText,
                    'card_id' => $cardId,
                    'time' => $mention->created_at->diffForHumans(),
                    'link' => route('kanban.cards.get', $comment->kanban_card_id ?? 0),
                ];
            });


            return response()->json([
                'count' => $formatted->count(),
                'notifications' => $formatted,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal ambil notifikasi: ' . $e->getMessage());
            return response()->json(['error' => 'Server error'], 500);
        }
    }

    public function markAsRead($id)
    {
        $user = auth()->user();

        $notification = CommentMention::where('id', $id)
            ->where('mentioned_user_id', $user->id) // Pastikan yang baca memang yang di mention
            ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }


}
