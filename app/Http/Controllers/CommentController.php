<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'author' => ['required', 'string', 'max:100'],
            'text'   => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $issue->comments()->create($validated);

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Comment posted.');
    }
}
