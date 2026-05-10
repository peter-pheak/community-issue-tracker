<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;

class CommentController extends Controller
{
    public function destroy(Comment $comment)
    {
        $issueId = $comment->issue_id;
        $comment->delete();

        return redirect()
            ->route('admin.issues.edit', $issueId)
            ->with('success', 'Comment deleted.');
    }
}
