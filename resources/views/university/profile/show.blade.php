@extends('university.layouts.master')

@section('title', 'University Profile')
@section('page-title', 'University Profile & Data')

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-building"></i> University Information
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('university.university-profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">University Name *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $university->name) }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">University Type *</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="public" {{ $university->type == 'public' ? 'selected' : '' }}>Public</option>
                        <option value="private" {{ $university->type == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="semi-government" {{ $university->type == 'semi-government' ? 'selected' : '' }}>Semi-Government</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="4">{{ old('description', $university->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="city_id" class="form-label">City *</label>
                    <select class="form-select @error('city_id') is-invalid @enderror" id="city_id" name="city_id" required>
                        <option value="">Select City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $university->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="established_year" class="form-label">Established Year</label>
                    <input type="number" 
                           class="form-control @error('established_year') is-invalid @enderror" 
                           id="established_year" 
                           name="established_year" 
                           value="{{ old('established_year', $university->established_year) }}"
                           min="1800" 
                           max="{{ date('Y') }}">
                    @error('established_year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" 
                              id="address" 
                              name="address" 
                              rows="2">{{ old('address', $university->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" 
                           class="form-control @error('phone') is-invalid @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $university->phone) }}">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $university->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label for="website" class="form-label">Website</label>
                    <input type="url" 
                           class="form-control @error('website') is-invalid @enderror" 
                           id="website" 
                           name="website" 
                           value="{{ old('website', $university->website) }}"
                           placeholder="https://">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tuition_fee_range_min" class="form-label">Tuition Fee Range (Min)</label>
                    <div class="input-group">
                        <span class="input-group-text">PKR</span>
                        <input type="number"
                               class="form-control @error('tuition_fee_range_min') is-invalid @enderror"
                               id="tuition_fee_range_min"
                               name="tuition_fee_range_min"
                               value="{{ old('tuition_fee_range_min', $university->tuition_fee_range_min) }}"
                               step="0.01">
                    </div>
                    @error('tuition_fee_range_min')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="tuition_fee_range_max" class="form-label">Tuition Fee Range (Max)</label>
                    <div class="input-group">
                        <span class="input-group-text">PKR</span>
                        <input type="number"
                               class="form-control @error('tuition_fee_range_max') is-invalid @enderror"
                               id="tuition_fee_range_max"
                               name="tuition_fee_range_max"
                               value="{{ old('tuition_fee_range_max', $university->tuition_fee_range_max) }}"
                               step="0.01">
                    </div>
                    @error('tuition_fee_range_max')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="hostel_available" 
                               name="hostel_available"
                               {{ $university->hostel_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="hostel_available">
                            Hostel Available
                        </label>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="scholarship_available" 
                               name="scholarship_available"
                               {{ $university->scholarship_available ? 'checked' : '' }}>
                        <label class="form-check-label" for="scholarship_available">
                            Scholarship Available
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update University Profile
            </button>
        </form>
    </div>
</div>
@endsection

