@extends('layouts.app')

@section('title', $issue->title)

@section('content')
<div class="row g-4">
  <div class="col-lg-8">

    {{-- Badge row --}}
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
      <span class="badge badge-{{ $issue->category }} fs-6">{{ $issue->category }}</span>
      <span class="badge status-{{ str_replace(' ', '-', $issue->status) }} fs-6">{{ $issue->status }}</span>
    </div>

    <h1 class="fw-bold mb-3" style="color: var(--primary);">{{ $issue->title }}</h1>

    {{-- Meta info --}}
    <div class="d-flex flex-wrap gap-3 mb-3 text-muted small">
      @if ($issue->address)
        <span><i class="bi bi-geo-alt-fill" style="color: var(--accent);"></i> {{ $issue->address }}</span>
      @endif
      <span><i class="bi bi-person"></i> Reported by {{ $issue->reported_by ?? 'Anonymous' }}</span>
      <span><i class="bi bi-calendar"></i> {{ $issue->created_at->format('M d, Y') }}</span>
    </div>

    {{-- Description --}}
    <div class="rounded-4 p-4 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
      <p style="font-size: 1.05rem; line-height: 1.7;">{{ $issue->description }}</p>
      @if ($issue->image)
        <img src="{{ asset('storage/' . $issue->image) }}" alt="Issue photo"
             class="img-fluid rounded-3 mt-2 shadow-sm" style="max-height: 300px;">
      @endif
    </div>

    {{-- Mini Map --}}
    @if ($issue->latitude && $issue->longitude)
      <div class="rounded-4 p-3 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
        <div id="detail-map"></div>
      </div>
    @endif

    {{-- Status History --}}
    @if ($issue->statusHistory->isNotEmpty())
      <div class="rounded-4 p-4 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
        <h5 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-clock-history me-2"></i>Status History</h5>
        @foreach ($issue->statusHistory as $entry)
          <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--border) !important;">
            <div>
              <strong style="color: var(--primary);">{{ $entry->status }}</strong>
              <small class="text-muted ms-2">by {{ $entry->changed_by }}</small>
            </div>
            <small class="text-muted">{{ $entry->changed_at->diffForHumans() }}</small>
          </div>
        @endforeach
      </div>
    @endif

    {{-- Comments --}}
    <div class="rounded-4 p-4 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
      <h5 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-chat-dots-fill me-2"></i>Comments ({{ $issue->comments->count() }})</h5>
      @forelse ($issue->comments as $comment)
        <div class="pb-2 mb-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color: var(--border) !important;">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width:32px;height:32px;font-size:0.8rem;">
              {{ substr($comment->author, 0, 1) }}
            </div>
            <div>
              <strong style="color: var(--primary);">{{ $comment->author }}</strong>
              <small class="text-muted ms-1">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
          </div>
          <p class="mb-0 mt-1 ms-4">{{ $comment->text }}</p>
        </div>
      @empty
        <p class="text-muted mb-0"><i class="bi bi-chat me-1"></i>No comments yet.</p>
      @endforelse
    </div>

    {{-- Comment Form --}}
    <div class="rounded-4 p-4 mb-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
      <h5 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-pencil-fill me-2"></i>Leave a comment</h5>
      <form action="{{ route('comments.store', $issue) }}" method="POST" id="commentForm">
        @csrf
        <div class="mb-3">
          <label for="commentAuthor" class="form-label fw-semibold">Your name *</label>
          <input type="text" name="author" id="commentAuthor" maxlength="100" required
                 class="form-control @error('author') is-invalid @enderror"
                 value="{{ old('author') }}" placeholder="Please enter your name (at least 2 characters).">
          @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text" id="authorError" style="display:none;color:#dc3545;">Please enter your name (at least 2 characters).</div>
        </div>
        <div class="mb-3">
          <label for="commentText" class="form-label fw-semibold">Comment *</label>
          <textarea name="text" id="commentText" rows="3" minlength="10" maxlength="2000" required
                    class="form-control @error('text') is-invalid @enderror"
                    placeholder="Comment must be at least 10 characters.">{{ old('text') }}</textarea>
          @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
          <div class="form-text" id="commentTextError" style="display:none;color:#dc3545;">Comment must be at least 10 characters.</div>
        </div>
        <button type="submit" class="btn text-white px-4" style="background-color: var(--accent); border-radius: 50px;">
          <i class="bi bi-send-fill me-1"></i> Post Comment
        </button>
      </form>
    </div>

  </div>

  {{-- Sidebar --}}
  <div class="col-lg-4">
    <div class="rounded-4 p-4" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border); position: sticky; top: 100px;">
      <h6 class="fw-bold mb-3" style="color: var(--primary);"><i class="bi bi-info-circle me-2"></i>Details</h6>
      <div class="mb-3">
        <small class="text-muted d-block">Reported by</small>
        <strong>{{ $issue->reported_by ?? 'Anonymous' }}</strong>
      </div>
      <div class="mb-3">
        <small class="text-muted d-block">Date</small>
        <strong>{{ $issue->created_at->format('M d, Y') }}</strong>
      </div>
      <div class="mb-3">
        <small class="text-muted d-block">Last updated</small>
        <strong>{{ $issue->updated_at->diffForHumans() }}</strong>
      </div>
      <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100">
        <i class="bi bi-arrow-left me-1"></i> Back to Map
      </a>
    </div>
  </div>
</div>
@endsection

@push('scripts')
@if ($issue->latitude && $issue->longitude)
<script>
  setTimeout(function() {
    var dm = L.map('detail-map').setView([{{ $issue->latitude }}, {{ $issue->longitude }}], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(dm);
    L.marker([{{ $issue->latitude }}, {{ $issue->longitude }}]).addTo(dm);
    dm.invalidateSize();
  }, 100);
</script>
@endif
<script>
(function() {
  // Comment form validation shake
  var form = document.getElementById('commentForm');
  if (form) {
    form.addEventListener('submit', function(e) {
      var author = document.getElementById('commentAuthor');
      var text = document.getElementById('commentText');
      var valid = true;
      if (author.value.trim().length < 2) { author.classList.add('shake'); valid = false; }
      author.addEventListener('animationend', function() { author.classList.remove('shake'); }, { once: true });
      if (text.value.trim().length < 10) { text.classList.add('shake'); valid = false; }
      text.addEventListener('animationend', function() { text.classList.remove('shake'); }, { once: true });
      if (!valid) e.preventDefault();
    });
  }
})();
</script>
@endpush
