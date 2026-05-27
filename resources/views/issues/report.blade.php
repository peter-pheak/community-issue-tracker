@extends('layouts.app')

@section('title', 'Report an Issue')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">

    {{-- Header --}}
    <div class="text-center mb-4 reveal visible">
      <h1 class="fw-bold" style="color: var(--primary);">Report an Issue</h1>
      <p class="lead" style="color: var(--text);">Help improve your community by reporting local problems</p>
    </div>

    {{-- Form --}}
    <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data" id="reportForm"
          class="rounded-4 p-4 reveal" style="background: rgba(255,255,255,0.7); backdrop-filter: blur(8px); border: 1px solid var(--border);">
      @csrf

      {{-- Title --}}
      <div class="mb-3">
        <label for="issueTitle" class="form-label fw-semibold">What&rsquo;s the problem?</label>
        <input type="text" name="title" id="issueTitle" maxlength="200" required
               class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title') }}" placeholder="Issue title * (at least 10 characters)">
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text" id="titleError" style="display:none;color:#dc3545;">Please enter a title (at least 10 characters).</div>
      </div>

      {{-- Description --}}
      <div class="mb-3">
        <label for="issueDescription" class="form-label fw-semibold">Description</label>
        <textarea name="description" id="issueDescription" rows="4" minlength="20" required
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Describe the problem in detail (at least 20 characters).">{{ old('description') }}</textarea>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text" id="descError" style="display:none;color:#dc3545;">Description must be at least 20 characters.</div>
      </div>

      {{-- Category --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Category *</label>
        <input type="hidden" name="category" id="categoryInput" value="{{ old('category') }}">
        <div class="d-flex flex-wrap gap-2 mb-1">
          @foreach (['Road', 'Lighting', 'Waste', 'Other'] as $cat)
            <button type="button" class="category-chip {{ old('category') === $cat ? 'active' : '' }}" data-category="{{ $cat }}">{{ $cat }}</button>
          @endforeach
        </div>
        @error('category') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        <div class="form-text" id="categoryError" style="display:none;color:#dc3545;">Please select a category.</div>
      </div>

      {{-- Address --}}
      <div class="mb-3">
        <label for="issueAddress" class="form-label fw-semibold">Where is it?</label>
        <input type="text" name="address" id="issueAddress" maxlength="255"
               class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address') }}" placeholder="Address or landmark">
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      {{-- Map picker --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Click on the map to select location</label>
        <div id="picker-map" class="shadow-sm"></div>
        <p class="text-muted small mt-1"><i class="bi bi-info-circle"></i> You can also drag the marker to adjust the exact position.</p>
        <div class="row g-2">
          <div class="col-6">
            <label for="latitude" class="form-label small">Latitude</label>
            <input type="number" step="any" name="latitude" id="latitude"
                   class="form-control form-control-sm @error('latitude') is-invalid @enderror"
                   value="{{ old('latitude') }}" placeholder="e.g. 11.55">
            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="col-6">
            <label for="longitude" class="form-label small">Longitude</label>
            <input type="number" step="any" name="longitude" id="longitude"
                   class="form-control form-control-sm @error('longitude') is-invalid @enderror"
                   value="{{ old('longitude') }}" placeholder="e.g. 104.92">
            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>

      {{-- File upload --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Upload a photo</label>
        <div id="uploadDropzone"
             class="text-center p-4 rounded-3"
             style="border: 2px dashed var(--border); cursor: pointer; transition: border-color 0.3s; background: rgba(255,255,255,0.5);">
          <i class="bi bi-cloud-upload" style="font-size:2rem;color:var(--border);"></i>
          <p class="mb-1 text-muted">Drag &amp; drop a photo or click to browse</p>
          <span class="small text-muted">JPEG, PNG or WebP, max 2MB</span>
          <div id="uploadPreview" class="mt-2"></div>
        </div>
        <input type="file" name="image" id="issueImage" accept="image/jpeg,image/png,image/webp" style="display:none;">
        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
      </div>

      {{-- Reported by --}}
      <div class="mb-3">
        <label for="reportedBy" class="form-label fw-semibold">Your name</label>
        <input type="text" name="reported_by" id="reportedBy" maxlength="100"
               class="form-control @error('reported_by') is-invalid @enderror"
               value="{{ old('reported_by') }}" placeholder="Leave blank to report anonymously.">
        @error('reported_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>

      <button type="submit" class="btn text-white px-4 py-2" style="background-color: var(--accent); border-radius: 50px;">
        <i class="bi bi-send-fill me-1"></i> Submit Report
      </button>
    </form>

  </div>
</div>
@endsection

@push('styles')
<style>
  .category-chip {
    background: var(--white); border: 1px solid var(--border); border-radius: 50px;
    padding: 0.4rem 1.2rem; font-size: 0.9rem; cursor: pointer;
    transition: all 0.2s ease; color: var(--text);
  }
  .category-chip:hover { border-color: var(--primary); color: var(--primary); }
  .category-chip.active { background: var(--primary); color: var(--white); border-color: var(--primary); }
  #uploadPreview img { max-height: 120px; border-radius: 8px; margin-top: 0.5rem; }
</style>
@endpush

@push('scripts')
<script>
(function() {
  // Navbar scroll (handled in layout, but needed here for picker map offset)
  // Map picker
  var pickerMap = L.map('picker-map').setView([11.55, 104.92], 7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'
  }).addTo(pickerMap);
  var marker;
  pickerMap.on('click', function(e) {
    if (marker) pickerMap.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(pickerMap);
    document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
    document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
  });
  @if (old('latitude') && old('longitude'))
    var olat = {{ old('latitude') }}, olng = {{ old('longitude') }};
    marker = L.marker([olat, olng]).addTo(pickerMap);
    pickerMap.setView([olat, olng], 15);
  @endif
  setTimeout(function() { pickerMap.invalidateSize(); }, 200);

  // Category chips
  var chips = document.querySelectorAll('.category-chip');
  var catInput = document.getElementById('categoryInput');
  chips.forEach(function(chip) {
    chip.addEventListener('click', function() {
      chips.forEach(function(c) { c.classList.remove('active'); });
      this.classList.add('active');
      catInput.value = this.getAttribute('data-category');
    });
  });

  // File upload
  var dropzone = document.getElementById('uploadDropzone');
  var fileInput = document.getElementById('issueImage');
  var preview = document.getElementById('uploadPreview');
  dropzone.addEventListener('click', function() { fileInput.click(); });
  fileInput.addEventListener('change', function(e) { handleFiles(e); });
  dropzone.addEventListener('dragover', function(e) { e.preventDefault(); dropzone.style.borderColor = 'var(--accent)'; });
  dropzone.addEventListener('dragleave', function() { dropzone.style.borderColor = 'var(--border)'; });
  dropzone.addEventListener('drop', function(e) {
    e.preventDefault(); dropzone.style.borderColor = 'var(--border)';
    handleFiles({ target: { files: e.dataTransfer.files } });
  });
  function handleFiles(e) {
    var file = e.target.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function(ev) {
      preview.innerHTML = '<img src="' + ev.target.result + '" alt="Preview"><span class="small text-muted d-block">' + file.name + '</span>';
    };
    reader.readAsDataURL(file);
  }

  // Form validation (client-side shake)
  var form = document.getElementById('reportForm');
  form.addEventListener('submit', function(e) {
    var title = document.getElementById('issueTitle');
    var desc = document.getElementById('issueDescription');
    var catVal = catInput.value;
    var valid = true;

    if (title.value.trim().length < 10) { title.classList.add('shake'); valid = false; }
    title.addEventListener('animationend', function() { title.classList.remove('shake'); }, { once: true });

    if (desc.value.trim().length < 20) { desc.classList.add('shake'); valid = false; }
    desc.addEventListener('animationend', function() { desc.classList.remove('shake'); }, { once: true });

    if (!catVal) { valid = false; }

    if (!valid) e.preventDefault();
  });
})();
</script>
@endpush
