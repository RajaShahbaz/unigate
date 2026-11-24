@php
    $user = auth()->user();
    $dashboardHref = $user
        ? ((method_exists($user, 'hasRole') && $user->hasRole('Student')) && empty($user->school_id)
            ? route('user.portal.dashboard')
            : route('dashboard'))
        : route('login');
    $offcanvasDismiss = !empty($offcanvasDismiss);
@endphp
@auth
    <a href="{{ $dashboardHref }}" class="btn btn-secondary commonBtn">{{ __('Dashboard') }}</a>
@else
    <a href="{{ route('login') }}" class="btn btn-secondary commonBtn mt-0 py-2 @if($offcanvasDismiss) w-100 mb-2 @endif" @if($offcanvasDismiss) data-bs-dismiss="offcanvas" @endif>{{ __('login') }}</a>
    <button type="button"
        class="btn btn-outline-secondary rounded-lg fw-semibold px-3 py-2 @if($offcanvasDismiss) w-100 @endif"
        style="border-width: 2px;"
        data-bs-toggle="modal"
        data-bs-target="#staticBackdrop"
        @if($offcanvasDismiss) data-bs-dismiss="offcanvas" @endif>{{ __('create_account') }}</button>
@endauth
