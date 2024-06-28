<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Tournament $tournament)
    {
        $validatedData = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = new Comment();
        $comment->content = $validatedData['content'];
        $comment->user_id = Auth::id();
        $comment->tournament_id = $tournament->id;
        $comment->save();

        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Comment added successfully!');
    }

    public function destroy(Comment $comment)
    {
        if (!Auth::user()->is_admin) {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this comment.');
        }
    
        $comment->delete();
        return redirect()->route('home')->with('success', 'Comment deleted successfully.');
    }


}
