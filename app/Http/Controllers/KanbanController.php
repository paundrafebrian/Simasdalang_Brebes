<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Models\KanbanColumn;
use App\Models\KanbanCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Notifications\MentionedInCommentNotification;
use App\Models\CommentMention;

class KanbanController extends Controller
{
    // Tampilkan Kanban board
    public function show(Activity $activity)
    {
        // Pastikan relasi eager loading sesuai
        $activity->load('kanbanColumns.kanbanCards');

        return view('kanban.show', compact('activity'));
    }

    // Simpan kolom baru
    public function storeColumn(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = KanbanColumn::where('activity_id', $activity->id)->max('order') + 1;

        $column = KanbanColumn::create([
            'name' => $request->name,
            'activity_id' => $activity->id,
            'order' => $order,
        ]);

        return response()->json(['column' => $column], 201);
    }

    // Simpan kartu baru
    public function storeCard(Request $request, KanbanColumn $column)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = KanbanCard::where('kanban_column_id', $column->id)->max('order') + 1;

        $card = KanbanCard::create([
            'title' => $request->title,
            'notes' => null, // default kosong
            'activity_id' => $column->activity_id, // otomatis ambil dari kolom
            'kanban_column_id' => $column->id,
            'order' => $order,
            'user_id' => auth()->id() ?? null,
        ]);

        return response()->json(['card' => $card], 201);
    }


    // Pindahkan kartu ke kolom lain (drag & drop)
    public function moveCard(Request $request, KanbanCard $card)
    {
        $validator = Validator::make($request->all(), [
            'column_id' => 'required|exists:kanban_columns,id',
            'order' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $card->update([
            'kanban_column_id' => $request->column_id,
            'order' => $request->order
        ]);

        return response()->json(['message' => 'Card moved successfully']);
    }

    public function getCard(KanbanCard $card)
    {
        $card->load('comments.user'); // Ambil semua komentar & nama user
        return response()->json(['card' => $card]);
    }

    public function updateNotes(Request $request, KanbanCard $card)
    {
        $request->validate(['notes' => 'nullable|string']);
        $card->update(['notes' => $request->notes]);
        return response()->json(['message' => 'Notes updated']);
    }

    public function storeComment(Request $request, KanbanCard $card)
    {
        try {
            $request->validate([
                'comment' => 'required|string|max:1000',
            ]);

            // Simpan komentar
            $comment = $card->comments()->create([
                'user_id' => auth()->id(),
                'comment' => $request->comment,
            ]);

            // Ambil semua mention dari @ hingga koma
            preg_match_all('/@([^,]+)/', $request->comment, $matches);

            if (!empty($matches[1])) {
                foreach ($matches[1] as $name) {
                    $cleanName = trim($name);
                    $user = \App\Models\User::where('name', $cleanName)->first();

                    if ($user) {
                        CommentMention::create([
                            'kanban_comment_id' => $comment->id,
                            'mentioned_user_id' => $user->id,
                        ]);
                    }
                }
            }

            $comment->load('user');

            return response()->json([
                'message' => 'Komentar berhasil ditambahkan',
                'comment' => $comment
            ]);
        } catch (\Throwable $e) {
            \Log::error('Store Comment Error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Terjadi kesalahan server.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function autocompleteUsers(Request $request)
    {
        $query = $request->get('query');

        // Cari pengguna berdasarkan username atau nama yang mengandung query
        $users = User::where('username', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%")  // Pencarian berdasarkan nama atau username
            ->limit(10)  // Batasi hasil pencarian untuk mencegah query yang terlalu banyak
            ->get(['id', 'name', 'username']);  // Ambil id, name, dan username pengguna

        // Mengembalikan hasil pencarian dalam format JSON
        return response()->json($users);
    }

}
