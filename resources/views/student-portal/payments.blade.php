@extends('student-portal.layouts.master')

@section('title', __('Payments'))
@section('page-title', __('JazzCash & payments'))

@section('content')
    <div class="alert alert-info small">
        {{ __('Wallet and card payments initiated from the mobile app use the API:') }}
        <code class="d-block mt-1">POST /api/jazzcash/pay-with-wallet</code>
        <code class="d-block">POST /api/jazzcash/card-payment</code>
    </div>

    <h6 class="mt-3">{{ __('Your payment records') }}</h6>
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table table-sm mb-0">
            <thead class="table-light">
            <tr>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Method') }}</th>
                <th>{{ __('Amount') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Trx ID') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($payments as $pay)
                <tr>
                    <td>{{ $pay->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $pay->payment_method ?? '—' }}</td>
                    <td>{{ $pay->paid_amount }} {{ $pay->currency ?? '' }}</td>
                    <td>{{ $pay->transaction_status }}</td>
                    <td class="text-break">{{ $pay->trx_id ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No payments found.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
