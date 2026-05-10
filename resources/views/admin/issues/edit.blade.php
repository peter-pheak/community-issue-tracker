@extends('layouts.app')

@section('title', 'Edit Issue #' . $issue->id)

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-4">Edit Issue #{{ $issue->id }}</h1>

            <form action="{{ route('admin.issues.update', $issue) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Title --}}
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $issue->title) }}" maxlength="200" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror"
                              minlength="20" required>{{ old('description', $issue->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Category --}}
                <div class="mb-3">
                    <label for="category" class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category" id="category"
                            class="form-select @error('category') is-invalid @enderror" required>
                        <option value="Road"     {{ old('category', $issue->category) === 'Road' ? 'selected' : '' }}>Road</option>
                        <option value="Lighting" {{ old('category', $issue->category) === 'Lighting' ? 'selected' : '' }}>Lighting</option>
                        <option value="Waste"    {{ old('category', $issue->category) === 'Waste' ? 'selected' : '' }}>Waste</option>
                        <option value="Other"    {{ old('category', $issue->category) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="status"
                            class="form-select @error('status') is-invalid @enderror" required>
                        <option value="Open"        {{ old('status', $issue->status) === 'Open' ? 'selected' : '' }}>Open</option>
                        <option value="In Progress" {{ old('status', $issue->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Resolved"    {{ old('status', $issue->status) === 'Resolved' ? 'selected' : '' }}>Resolved</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Coordinates --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude"
                               class="form-control @error('latitude') is-invalid @enderror"
                               value="{{ old('latitude', $issue->latitude) }}">
                        @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-6">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude"
                               class="form-control @error('longitude') is-invalid @enderror"
                               value="{{ old('longitude', $issue->longitude) }}">
                        @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Address --}}
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address"
                           class="form-control @error('address') is-invalid @enderror"
                           value="{{ old('address', $issue->address) }}" maxlength="255">
                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.issues.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </form>
        </div>

        {{-- Sidebar: Image + Comments --}}
        <div class="col-lg-4">
            @if ($issue->image)
                <div class="card mb-4">
                    <div class="card-header"><h6 class="mb-0">Attached Image</h6></div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $issue->image) }}" alt="Issue photo"
                             class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Comments ({{ $issue->comments->count() }})</h6>
                </div>
                <div class="card-body p-0">
                    @forelse ($issue->comments as $comment)
                        <div class="border-bottom p-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $comment->author }}</strong>
                                    <small class="text-muted ms-1">{{ $comment->created_at->diffForHumans() }}</small>
                                    <p class="mb-0 small">{{ $comment->text }}</p>
                                </div>
                                <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST"
                                      onsubmit="return confirm('Delete this comment?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted p-2 mb-0">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
