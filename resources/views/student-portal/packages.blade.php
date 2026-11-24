@extends('student-portal.layouts.master')

@section('title', __('Application packages'))
@section('page-title', __('Application packages'))

@section('content')
    <p class="text-muted">{{ __('Available packages and your purchases (same data as the mobile API). Package checkout is completed in the app via Stripe.') }}</p>

    <h6 class="mt-4">{{ __('Your purchases') }}</h6>
    <div class="table-responsive card border-0 shadow-sm mb-4">
        <table class="table mb-0">
            <thead class="table-light">
            <tr>
                <th>{{ __('Package') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($purchases as $p)
                <tr>
                    <td>{{ $p->package->name ?? '—' }}</td>
                    <td><span class="badge bg-secondary">{{ $p->payment_status }}</span></td>
                    <td>{{ $p->amount_paid }} {{ $p->currency }}</td>
                    <td>{{ $p->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted text-center py-4">{{ __('No purchases yet.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <h6>{{ __('Available packages') }}</h6>
    <div class="row g-3">
        @forelse($packages as $pkg)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="card-title">{{ $pkg->name }}</h6>
                        <p class="small text-muted mb-2">{{ \Illuminate\Support\Str::limit($pkg->description ?? '', 120) }}</p>
                        <strong>{{ $pkg->price }} {{ $pkg->currency }}</strong>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">{{ __('No packages configured.') }}</p>
        @endforelse
    </div>
@endsection
