@extends('layouts.app')

@section('title', 'Report an Issue')

@push('styles')
<style>
    #picker-map { height: 300px; border-radius: 8px; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Report an Issue</h1>

            <form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" maxlength="200" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              minlength="20" required>{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" id="category"
                            class="form-select @error('category') is-invalid @enderror" required>
                        <option value="">-- Select --</option>
                        <option value="Road"     {{ old('category') === 'Road' ? 'selected' : '' }}>Road</option>
                        <option value="Lighting" {{ old('category') === 'Lighting' ? 'selected' : '' }}>Lighting</option>
                        <option value="Waste"    {{ old('category') === 'Waste' ? 'selected' : '' }}>Waste</option>
                        <option value="Other"    {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Coordinate Picker --}}
                <div class="mb-3">
                    <label class="form-label">Location (click map to set)</label>
                    <div id="picker-map"></div>
                    <div class="row g-2 mt-1">
                        <div class="col-6">
                            <label for="latitude" class="form-label small">Latitude</label>
                            <input type="number" step="any" name="latitude" id="latitude"
                                   class="form-control @error('latitude') is-invalid @enderror"
                                   value="{{ old('latitude') }}" placeholder="e.g. 11.55">
                            @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label for="longitude" class="form-label small">Longitude</label>
                            <input type="number" step="any" name="longitude" id="longitude"
                                   class="form-control @error('longitude') is-invalid @enderror"
                                   value="{{ old('longitude') }}" placeholder="e.g. 104.92">
                            @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address"
                           class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address') }}" maxlength="255">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Image --}}
                <div class="mb-3">
                    <label for="image" class="form-label">Photo (JPG/PNG/WebP, max 2MB)</label>
                    <input type="file" name="image" id="image"
                           class="form-control @error('image') is-invalid @enderror"
                           accept="image/jpeg,image/png,image/webp">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Reported By --}}
                <div class="mb-3">
                    <label for="reported_by" class="form-label">Your Name</label>
                    <input type="text" name="reported_by" id="reported_by"
                           class="form-control @error('reported_by') is-invalid @enderror"
                           value="{{ old('reported_by') }}" maxlength="100">
                    @error('reported_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit Report</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const pickerMap = L.map('picker-map').setView([11.55, 104.92], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(pickerMap);

    let marker;

    pickerMap.on('click', function(e) {
        if (marker) { pickerMap.removeLayer(marker); }
        marker = L.marker(e.latlng).addTo(pickerMap);
        document.getElementById('latitude').value = e.latlng.lat.toFixed(6);
        document.getElementById('longitude').value = e.latlng.lng.toFixed(6);
    });

    // Pre-fill if old values exist
    @if (old('latitude') && old('longitude'))
        const oldLat = {{ old('latitude') }};
        const oldLng = {{ old('longitude') }};
        marker = L.marker([oldLat, oldLng]).addTo(pickerMap);
        pickerMap.setView([oldLat, oldLng], 15);
    @endif
</script>
@endpush
