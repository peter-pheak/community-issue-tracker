<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Models\Issue;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create()
    {
        return view('issues.report');
    }

    public function store(IssueRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('issues', 'public');
        }

        Issue::create($data);

        return redirect('/')
            ->with('success', 'Issue reported successfully. Thank you!');
    }
}
