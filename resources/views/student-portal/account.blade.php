@extends('student-portal.layouts.master')

@section('title', __('Account'))
@section('page-title', __('Account management'))

@section('content')
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="card-title">{{ __('What happens when you delete your account') }}</h6>
            <ul class="mb-0 small">
                <li>{{ __('Draft applications') }}: <strong>{{ $draftApplications }}</strong></li>
                <li>{{ __('Submitted applications (retained for records)') }}: <strong>{{ $submittedApplications }}</strong></li>
                <li>{{ __('Payment records (retained where required)') }}: <strong>{{ $paymentsCount }}</strong></li>
            </ul>
            <p class="small text-muted mt-3 mb-0">{{ __('This matches the mobile app deletion policy.') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm border-danger">
        <div class="card-header bg-danger text-white">{{ __('Delete account') }}</div>
        <div class="card-body">
            <form method="post" action="{{ route('student.portal.account.delete') }}" onsubmit="return confirm('{{ __('This cannot be undone. Continue?') }}');">
                @csrf
                <div class="mb-3">
                    <label class="form-label">{{ __('Type DELETE to confirm') }}</label>
                    <input type="text" name="confirmation" class="form-control" required autocomplete="off" placeholder="DELETE">
                </div>
                <div class="mb-3">
                    <label class="form-label">{{ __('Current password') }}</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-danger">{{ __('Permanently delete my account') }}</button>
            </form>
        </div>
    </div>
@endsection
