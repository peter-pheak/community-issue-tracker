<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\StatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::latest()->paginate(15);
        return view('admin.issues.index', compact('issues'));
    }

    public function edit(Issue $issue)
    {
        return view('admin.issues.edit', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:200'],
            'description' => ['required', 'string', 'min:20'],
            'category'    => ['required', 'in:Road,Lighting,Waste,Other'],
            'status'      => ['required', 'in:Open,In Progress,Resolved'],
            'latitude'    => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'   => ['nullable', 'numeric', 'between:-180,180'],
            'address'     => ['nullable', 'string', 'max:255'],
        ]);

        // Log status change
        if ($validated['status'] !== $issue->status) {
            StatusHistory::create([
                'issue_id'   => $issue->id,
                'status'     => $validated['status'],
                'changed_by' => Auth::guard('admin')->user()->username,
            ]);
        }

        $issue->update($validated);

        return redirect()
            ->route('admin.issues.index')
            ->with('success', 'Issue updated.');
    }

    public function destroy(Issue $issue)
    {
        // Clean up stored image
        if ($issue->image) {
            Storage::disk('public')->delete($issue->image);
        }

        $issue->delete();

        return redirect()
            ->route('admin.issues.index')
            ->with('success', 'Issue deleted.');
    }
}
