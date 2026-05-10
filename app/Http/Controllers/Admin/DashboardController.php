<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIssues = Issue::count();

        $byStatus = Issue::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $byCategory = Issue::selectRaw('category, COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $recentIssues = Issue::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalIssues', 'byStatus', 'byCategory', 'recentIssues'
        ));
    }
}
