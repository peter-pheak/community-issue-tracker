<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /** Filtered, paginated issue list. Map data via /api/issues/map. */
    public function index(Request $request)
    {
        $query = Issue::query()->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $issues = $query->paginate(12)->withQueryString();
        $stats = ["total" => Issue::count(), "open" => Issue::where("status", "Open")->count(), "in_progress" => Issue::where("status", "In Progress")->count(), "resolved" => Issue::where("status", "Resolved")->count()];

        return view('home', compact("issues", "stats"));
    }

    /** Leaflet map endpoint — id, title, coords, status. Max 500. */
    public function mapData()
    {
        return Issue::select('id', 'title', 'latitude', 'longitude', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->limit(500)
            ->get();
    }

    /** Issue detail with comments and status history. */
    public function show(Issue $issue)
    {
        $issue->load(['comments', 'statusHistory']);
        return view('issues.show', compact('issue'));
    }
}
