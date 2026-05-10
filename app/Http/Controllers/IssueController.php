<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Public issue list with optional filters + pagination.
     * Map data is loaded asynchronously via /api/issues/map.
     */
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

        return view('home', compact('issues'));
    }

    /**
     * Lightweight JSON endpoint for the Leaflet map.
     * Returns only id, title, coordinates and status for issues with coordinates.
     * Capped at 500, ordered by latest.
     */
    public function mapData()
    {
        return Issue::select('id', 'title', 'latitude', 'longitude', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->latest()
            ->limit(500)
            ->get();
    }

    /**
     * Public issue detail with eager-loaded comments and status history.
     * Added by M4 via PR onto this file.
     */
    public function show(Issue $issue)
    {
        $issue->load(['comments', 'statusHistory']);
        return view('issues.show', compact('issue'));
    }
}
