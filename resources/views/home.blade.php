@extends('layouts.app')

@section('title', 'Home')

@section('content')
{{-- Hero Card with Stats --}}
<div class="hero-card rounded-4 p-4 p-md-5 mb-4 text-center reveal visible" style="
  background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(244,241,234,0.8) 100%);
  backdrop-filter: blur(10px); border: 1px solid var(--border); box-shadow: 0 4px 24px rgba(0,0,0,0.06);
">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <h1 class="display-4 fw-bold mb-3" style="color: var(--primary);">Your community, your voice.</h1>
      <p class="lead mb-4" style="color: var(--text); font-size: 1.2rem;">
        Track and resolve issues together in your neighborhood
      </p>
    </div>
  </div>

  {{-- Stat counters --}}
  <div class="row justify-content-center g-3 mb-2">
    <div class="col-6 col-md-3 col-lg-2">
      <div class="p-3 rounded-3" style="background: linear-gradient(135deg, var(--primary), #1a6a8a);">
        <div class="counter-number display-5 fw-bold text-white" data-target="{{ $stats['total'] ?? 0 }}">{{ $stats['total'] ?? 0 }}</div>
        <div class="text-white-50 small">Total Issues</div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #dc3545, #e05a6a);">
        <div class="counter-number display-5 fw-bold text-white" data-target="{{ $stats['open'] ?? 0 }}">{{ $stats['open'] ?? 0 }}</div>
        <div class="text-white-50 small">Open</div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #fd7e14, #f8a45c);">
        <div class="counter-number display-5 fw-bold text-white" data-target="{{ $stats['in_progress'] ?? 0 }}">{{ $stats['in_progress'] ?? 0 }}</div>
        <div class="text-white-50 small">In Progress</div>
      </div>
    </div>
    <div class="col-6 col-md-3 col-lg-2">
      <div class="p-3 rounded-3" style="background: linear-gradient(135deg, #198754, #3cba7a);">
        <div class="counter-number display-5 fw-bold text-white" data-target="{{ $stats['resolved'] ?? 0 }}">{{ $stats['resolved'] ?? 0 }}</div>
        <div class="text-white-50 small">Resolved</div>
      </div>
    </div>
  </div>
</div>

{{-- Filter Section --}}
<div class="rounded-4 p-4 mb-4 reveal" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
  <div class="row g-3 align-items-end">
    <div class="col-md-5">
      <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-search"></i> Search</label>
      <input type="text" id="filterSearch" class="form-control" placeholder="Search issues..." value="{{ request('search') }}">
    </div>
    <div class="col-md-7">
      <label class="form-label fw-semibold small text-muted mb-1"><i class="bi bi-tags"></i> Category</label>
      <div class="d-flex flex-wrap gap-1">
        <button class="filter-chip active" data-category="Road" aria-pressed="true">Road</button>
        <button class="filter-chip" data-category="Lighting" aria-pressed="false">Lighting</button>
        <button class="filter-chip" data-category="Waste" aria-pressed="false">Waste</button>
        <button class="filter-chip" data-category="Other" aria-pressed="false">Other</button>
      </div>
    </div>
  </div>

  {{-- Status filter radios --}}
  <div class="mt-3 d-flex flex-wrap align-items-center gap-3">
    <span class="fw-semibold small text-muted"><i class="bi bi-flag"></i> Status:</span>
    <div class="d-flex flex-wrap gap-2">
      <label class="status-radio">
        <input type="radio" name="statusFilter" value="Open">
        <span class="status-radio-label">Open</span>
      </label>
      <label class="status-radio">
        <input type="radio" name="statusFilter" value="In Progress">
        <span class="status-radio-label">In Progress</span>
      </label>
      <label class="status-radio">
        <input type="radio" name="statusFilter" value="Resolved">
        <span class="status-radio-label">Resolved</span>
      </label>
      <button id="filterAllBtn" class="status-radio-label" style="background:#6c757d;color:#fff;border:none;padding:0.25rem 0.75rem;border-radius:50px;font-size:0.85rem;cursor:pointer;">All</button>
    </div>
  </div>
</div>

{{-- Map --}}
<div id="map" class="rounded-4 shadow-sm mb-4 reveal"></div>

{{-- Issue Cards --}}
<div id="issueList" class="row g-4">
  @forelse ($issues as $issue)
    <div class="col-12 col-md-6 col-lg-4 reveal">
      <div class="card card-issue h-100 shadow-sm" data-category="{{ $issue->category }}" data-status="{{ $issue->status }}">
        <div class="card-body">
          <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge badge-{{ $issue->category }}">{{ $issue->category }}</span>
            <span class="badge status-{{ str_replace(' ', '-', $issue->status) }}">{{ $issue->status }}</span>
          </div>
          <h5 class="card-title issue-title" style="color: var(--primary);">
            <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none" style="color: inherit;">{{ $issue->title }}</a>
          </h5>
          <p class="card-text text-muted small issue-description">{{ Str::limit($issue->description, 100) }}</p>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center text-muted small border-top-0 pt-0">
          <span><i class="bi bi-geo-alt" style="color: var(--accent);"></i> {{ $issue->address ?? 'Location set' }}</span>
          <span><i class="bi bi-clock"></i> {{ $issue->created_at->diffForHumans() }}</span>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12">
      <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size:3rem;color:var(--border);"></i>
        <p class="mt-2 text-muted">No issues reported yet. Be the first!</p>
      </div>
    </div>
  @endforelse
</div>

<div class="d-flex justify-content-center mt-4">
  {{ $issues->links() }}
</div>
@endsection

@push('styles')
<style>
  .filter-chip {
    background: var(--white); border: 1px solid var(--border); border-radius: 50px;
    padding: 0.35rem 1rem; font-size: 0.85rem; cursor: pointer;
    transition: all 0.2s ease; color: var(--text);
  }
  .filter-chip:hover { border-color: var(--primary); color: var(--primary); }
  .filter-chip.active { background: var(--primary); color: var(--white); border-color: var(--primary); }
  .status-radio { cursor: pointer; }
  .status-radio input { position: absolute; opacity: 0; }
  .status-radio-label {
    display: inline-block; padding: 0.25rem 0.75rem; border-radius: 50px;
    font-size: 0.85rem; background: var(--white); border: 1px solid var(--border);
    transition: all 0.2s ease; cursor: pointer;
  }
  .status-radio input:checked + .status-radio-label { color: var(--white); font-weight: 500; }
  .status-radio input[value="Open"]:checked + .status-radio-label { background: #dc3545; border-color: #dc3545; }
  .status-radio input[value="In Progress"]:checked + .status-radio-label { background: #fd7e14; border-color: #fd7e14; }
  .status-radio input[value="Resolved"]:checked + .status-radio-label { background: #198754; border-color: #198754; }
  .counter-number { transition: all 0.3s ease; }
  .hero-card .counter-number { min-height: 3rem; }
</style>
@endpush

@push('scripts')
<script>
(function() {
  // Leaflet map
  const map = L.map('map').setView([11.55, 104.92], 7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  fetch('{{ route("api.issues.map") }}')
    .then(res => res.json())
    .then(issues => {
      issues.forEach(issue => {
        L.marker([issue.latitude, issue.longitude])
          .addTo(map)
          .bindPopup('<strong>' + escHtml(issue.title) + '</strong><br><span class="badge status-' + issue.status.replace(' ', '-') + '">' + issue.status + '</span><br><a href="/issues/' + issue.id + '">View details</a>');
      });
    })
    .catch(err => console.error('Map fetch failed:', err));

  window.addEventListener('resize', () => map.invalidateSize());

  function escHtml(t) {
    var d = document.createElement('div'); d.textContent = t; return d.innerHTML;
  }

  // Scroll reveal
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(e) { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.05 });
  document.querySelectorAll('.reveal').forEach(function(el) { observer.observe(el); });

  // Counter animation
  var counters = document.querySelectorAll('.counter-number');
  var countersStarted = false;
  function startCounters() {
    counters.forEach(function(counter) {
      var target = parseInt(counter.getAttribute('data-target'));
      var duration = 2000, increment = target / (duration / 16), current = 0;
      function update() {
        current += increment;
        if (current < target) { counter.textContent = Math.floor(current).toLocaleString(); requestAnimationFrame(update); }
        else { counter.textContent = target.toLocaleString(); }
      }
      update();
    });
  }
  if (counters.length > 0) {
    var co = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting && !countersStarted) { countersStarted = true; startCounters(); co.unobserve(e.target); }
      });
    }, { threshold: 0.5 });
    var heroCard = document.querySelector('.hero-card');
    if (heroCard) co.observe(heroCard);
  }

  // Client-side filter (search + category chips + status radios)
  var filterSearch = document.getElementById('filterSearch');
  var filterChips = document.querySelectorAll('.filter-chip');
  var statusRadios = document.querySelectorAll('input[name="statusFilter"]');
  var filterAllBtn = document.getElementById('filterAllBtn');
  var issueCards = document.querySelectorAll('.card-issue');
  var activeCategories = new Set();
  var activeStatus = null;

  function filterCards() {
    var term = filterSearch.value.toLowerCase().trim();
    issueCards.forEach(function(card) {
      var cat = card.getAttribute('data-category');
      var st = card.getAttribute('data-status');
      var title = (card.querySelector('.issue-title')?.textContent || '').toLowerCase();
      var desc = (card.querySelector('.issue-description')?.textContent || '').toLowerCase();
      var matchSearch = term === '' || title.includes(term) || desc.includes(term);
      var matchCat = activeCategories.size === 0 || activeCategories.has(cat);
      var matchStatus = activeStatus === null || st === activeStatus;
      card.closest('.col-12').style.display = (matchSearch && matchCat && matchStatus) ? '' : 'none';
    });
  }

  filterChips.forEach(function(chip) {
    chip.addEventListener('click', function() {
      var cat = this.getAttribute('data-category');
      var isActive = this.classList.toggle('active');
      this.setAttribute('aria-pressed', isActive.toString());
      if (isActive) activeCategories.add(cat); else activeCategories.delete(cat);
      filterCards();
    });
  });

  statusRadios.forEach(function(radio) {
    radio.addEventListener('change', function() {
      activeStatus = this.checked ? this.value : null;
      filterCards();
    });
  });

  filterAllBtn.addEventListener('click', function() {
    statusRadios.forEach(function(r) { r.checked = false; });
    activeStatus = null;
    filterCards();
  });

  filterSearch.addEventListener('input', filterCards);
})();
</script>
@endpush
