@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="mb-4">Admin Dashboard</h1>

    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-bg-primary">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $totalIssues }}</h2>
                    <small>Total Issues</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-danger">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $byStatus['Open'] ?? 0 }}</h2>
                    <small>Open</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-warning">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $byStatus['In Progress'] ?? 0 }}</h2>
                    <small>In Progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-success">
                <div class="card-body text-center">
                    <h2 class="mb-0">{{ $byStatus['Resolved'] ?? 0 }}</h2>
                    <small>Resolved</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">By Category</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-3 text-center">
                    <h4>{{ $byCategory['Road'] ?? 0 }}</h4>
                    <small class="text-muted">Road</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <h4>{{ $byCategory['Lighting'] ?? 0 }}</h4>
                    <small class="text-muted">Lighting</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <h4>{{ $byCategory['Waste'] ?? 0 }}</h4>
                    <small class="text-muted">Waste</small>
                </div>
                <div class="col-6 col-md-3 text-center">
                    <h4>{{ $byCategory['Other'] ?? 0 }}</h4>
                    <small class="text-muted">Other</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Issues --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Issues</h5>
            <a href="{{ route('admin.issues.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Reported</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentIssues as $issue)
                        <tr>
                            <td>
                                <a href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a>
                            </td>
                            <td>{{ $issue->category }}</td>
                            <td>
                                <span class="badge status-{{ str_replace(' ', '-', $issue->status) }}">
                                    {{ $issue->status }}
                                </span>
                            </td>
                            <td>{{ $issue->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
