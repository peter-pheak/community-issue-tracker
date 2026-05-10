@extends('layouts.app')

@section('title', 'Manage Issues')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Manage Issues</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">&larr; Dashboard</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Reported</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($issues as $issue)
                    <tr>
                        <td>{{ $issue->id }}</td>
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
                        <td>
                            <a href="{{ route('admin.issues.edit', $issue) }}"
                               class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.issues.destroy', $issue) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Delete this issue?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center">
        {{ $issues->links() }}
    </div>
@endsection
