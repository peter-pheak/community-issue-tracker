@extends('layouts.app')

@section('title', $issue->title)

@push('styles')
<style>
    #detail-map { height: 250px; border-radius: 8px; margin-bottom: 1.5rem; }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{-- Issue Header --}}
            <h1>{{ $issue->title }}</h1>
            <div class="mb-3">
                <span class="badge badge-{{ $issue->category }}">{{ $issue->category }}</span>
                <span class="badge status-{{ str_replace(' ', '-', $issue->status) }}">{{ $issue->status }}</span>
            </div>

            {{-- Description --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Description</h5>
                    <p>{{ $issue->description }}</p>
                    @if ($issue->address)
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $issue->address }}</p>
                    @endif
                    @if ($issue->image)
                        <img src="{{ asset('storage/' . $issue->image) }}" alt="Issue photo"
                             class="img-fluid rounded mt-2" style="max-height: 300px;">
                    @endif
                </div>
            </div>

            {{-- Mini Map --}}
            @if ($issue->latitude && $issue->longitude)
                <div id="detail-map"></div>
            @endif

            {{-- Status History --}}
            @if ($issue->statusHistory->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Status History</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($issue->statusHistory as $entry)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>
                                    <strong>{{ $entry->status }}</strong>
                                    <small class="text-muted ms-2">by {{ $entry->changed_by }}</small>
                                </span>
                                <small class="text-muted">{{ $entry->changed_at->diffForHumans() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Comments --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Comments ({{ $issue->comments->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse ($issue->comments as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>{{ $comment->author }}</strong>
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                            <p class="mb-0 mt-1">{{ $comment->text }}</p>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No comments yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Comment Form --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add a Comment</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('comments.store', $issue) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="author" class="form-label">Your Name <span class="text-danger">*</span></label>
                            <input type="text" name="author" id="author"
                                   class="form-control @error('author') is-invalid @enderror"
                                   value="{{ old('author') }}" maxlength="100" required>
                            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Comment <span class="text-danger">*</span></label>
                            <textarea name="text" id="text" rows="3"
                                      class="form-control @error('text') is-invalid @enderror"
                                      minlength="10" maxlength="2000" required>{{ old('text') }}</textarea>
                            @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Post Comment</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Details</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Reported by:</strong> {{ $issue->reported_by ?? 'Anonymous' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Date:</strong> {{ $issue->created_at->format('M d, Y') }}
                    </li>
                    <li class="list-group-item">
                        <strong>Last updated:</strong> {{ $issue->updated_at->diffForHumans() }}
                    </li>
                    <li class="list-group-item">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm w-100">
                            &larr; Back to Map
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@if ($issue->latitude && $issue->longitude)
<script>
    setTimeout(function() {
        const detailMap = L.map('detail-map').setView(
            [{{ $issue->latitude }}, {{ $issue->longitude }}], 15
        );
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(detailMap);
        L.marker([{{ $issue->latitude }}, {{ $issue->longitude }}]).addTo(detailMap);
        detailMap.invalidateSize();
    }, 100);
</script>
@endif
@endpush
