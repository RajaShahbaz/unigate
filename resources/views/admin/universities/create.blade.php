@extends('layouts.master')

@section('title')
    {{ __('Create University') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-theme text-white mr-2">
                    <i class="fa fa-university"></i>
                </span> {{ __('Create University') }}
            </h3>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body custom-card-body">
                        <div class="mb-3">
                            <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> {{ __('Back') }}
                            </a>
                        </div>

                        <form action="{{ route('admin.universities.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('University Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <select name="city_id" class="form-control @error('city_id') is-invalid @enderror" required>
                                        <option value="">{{ __('Select City') }}</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                                    <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach($types as $value => $label)
                                            <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="0" {{ old('status') == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Email') }}</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Phone') }}</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Website') }}</label>
                                    <input type="url" name="website" value="{{ old('website') }}" class="form-control @error('website') is-invalid @enderror">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Established Year') }}</label>
                                    <input type="number" name="established_year" value="{{ old('established_year') }}" 
                                           min="1800" max="{{ date('Y') }}" class="form-control @error('established_year') is-invalid @enderror">
                                    @error('established_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <textarea name="address" rows="2" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Logo') }}</label>
                                    <input type="file" name="logo" accept="image/*" class="form-control @error('logo') is-invalid @enderror">
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Cover Image') }}</label>
                                    <input type="file" name="cover_image" accept="image/*" class="form-control @error('cover_image') is-invalid @enderror">
                                    @error('cover_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('Programs/Interests') }}</label>
                                    <select name="interest_ids[]" id="interest_ids" multiple class="form-control select2 @error('interest_ids') is-invalid @enderror" style="width:100%;">
                                        @foreach($interests as $interest)
                                            <option value="{{ $interest->id }}" {{ in_array($interest->id, old('interest_ids', [])) ? 'selected' : '' }}>
                                                {{ $interest->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('interest_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">
                                <i class="fa fa-user-shield"></i> {{ __('University Admin') }}
                                <small class="text-muted">({{ __('Each university has its own admin') }})</small>
                            </h5>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> {{ __('Create an admin account for this university. The admin will be able to manage applications and settings for this university.') }}
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="admin_name" value="{{ old('admin_name') }}" class="form-control @error('admin_name') is-invalid @enderror" required>
                                    @error('admin_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Email') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" class="form-control @error('admin_email') is-invalid @enderror" required>
                                    @error('admin_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Password') }} <span class="text-danger">*</span></label>
                                    <input type="password" name="admin_password" class="form-control @error('admin_password') is-invalid @enderror" minlength="8" required>
                                    <small class="text-muted">{{ __('Minimum 8 characters') }}</small>
                                    @error('admin_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Phone') }}</label>
                                    <input type="text" name="admin_phone" value="{{ old('admin_phone') }}" class="form-control @error('admin_phone') is-invalid @enderror">
                                    @error('admin_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Position') }}</label>
                                    <input type="text" name="admin_position" value="{{ old('admin_position') }}" class="form-control @error('admin_position') is-invalid @enderror" placeholder="{{ __('e.g., Admissions Director') }}">
                                    @error('admin_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('Admin Status') }}</label>
                                    <select name="admin_status" class="form-control">
                                        <option value="1" {{ old('admin_status', 1) == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="0" {{ old('admin_status') == 0 ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-theme">
                                    <i class="fa fa-save"></i> {{ __('Create University') }}
                                </button>
                                <a href="{{ route('admin.universities.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#interest_ids').select2({
                placeholder: '{{ __('Select Programs/Interests') }}',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection

