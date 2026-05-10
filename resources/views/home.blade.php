@extends('layouts.app')

@section('title', 'Community Issue Tracker')

@push('styles')
<style>
    #map {
        height: 420px;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    @media (max-width: 576px) {
        #map { height: 250px; }
    }
    .card { margin-bottom: 1rem; }
    .badge-Road     { background-color: #6c757d; }
    .badge-Lighting { background-color: #ffc107; color: #000; }
    .badge-Waste    { background-color: #198754; }
    .badge-Other    { background-color: #0d6efd; }
    .status-Open        { background-color: #dc3545; }
    .status-In-Progress { background-color: #fd7e14; }
    .status-Resolved    { background-color: #198754; }
</style>
@endpush

@section('content')
    {{-- Filters --}}
    <form method="GET" action="{{ route('home') }}" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search issues..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="Road"     {{ request('category') === 'Road' ? 'selected' : '' }}>Road</option>
                <option value="Lighting" {{ request('category') === 'Lighting' ? 'selected' : '' }}>Lighting</option>
                <option value="Waste"    {{ request('category') === 'Waste' ? 'selected' : '' }}>Waste</option>
                <option value="Other"    {{ request('category') === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="Open"         {{ request('status') === 'Open' ? 'selected' : '' }}>Open</option>
                <option value="In Progress"  {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                <option value="Resolved"     {{ request('status') === 'Resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    {{-- Map --}}
    <div id="map"></div>

    {{-- Issue Cards --}}
    <div class="row">
        @forelse ($issues as $issue)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                {{ $issue->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small">
                            {{ Str::limit($issue->description, 100) }}
                        </p>
                        <span class="badge badge-{{ $issue->category }}">{{ $issue->category }}</span>
                        <span class="badge status-{{ str_replace(' ', '-', $issue->status) }}">
                            {{ $issue->status }}
                        </span>
                    </div>
                    <div class="card-footer text-muted small">
                        Reported {{ $issue->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No issues reported yet. Be the first!</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $issues->links() }}
    </div>
@endsection

@push('scripts')
<script>
    const map = L.map('map').setView([11.55, 104.92], 7); // Cambodia default center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch('{{ route("api.issues.map") }}')
        .then(res => res.json())
        .then(issues => {
            issues.forEach(issue => {
                const marker = L.marker([issue.latitude, issue.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <strong>${escapeHtml(issue.title)}</strong><br>
                        <span class="badge status-${issue.status.replace(' ', '-')}">${issue.status}</span>
                        <br><a href="/issues/${issue.id}">View details</a>
                    `);
            });
        })
        .catch(err => console.error('Map data fetch failed:', err));

    window.addEventListener('resize', () => map.invalidateSize());

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endpush
