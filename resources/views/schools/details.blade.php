@extends('layouts.master')

@section('title')
    {{ __('school') }} {{ __('details') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="page-header d-flex justify-content-between align-items-center">
            <h3 class="page-title">{{ $school->name }} - {{ __('details') }}</h3>
            <a href="{{ route('schools.index') }}" class="btn btn-secondary btn-sm">{{ __('back') }}</a>
        </div>

        <div class="row mb-3">
            <div class="col-md-3"><div class="card"><div class="card-body"><h6>{{ __('students') }}</h6><h4>{{ $summary['total_students'] }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><h6>{{ __('payments') }}</h6><h4>{{ $summary['total_payments'] }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><h6>{{ __('total') }} {{ __('discount') }}</h6><h4>{{ number_format($summary['total_discount'], 2) }}</h4></div></div></div>
            <div class="col-md-3"><div class="card"><div class="card-body"><h6>{{ __('total') }} {{ __('profit') }}</h6><h4>{{ number_format($summary['total_profit'], 2) }}</h4></div></div></div>
        </div>

        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="school-detail-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#profile-tab">{{ __('profile') }}</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#students-tab">{{ __('students') }}</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#payments-tab">{{ __('payments') }}</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profit-tab">{{ __('profit') }}</a></li>
                </ul>

                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="profile-tab">
                        <div class="row">
                            <div class="col-md-3"><img src="{{ $school->logo }}" class="img-fluid rounded" alt="logo"></div>
                            <div class="col-md-9">
                                <p><strong>{{ __('name') }}:</strong> {{ $school->name }}</p>
                                <p><strong>{{ __('email') }}:</strong> {{ $school->support_email }}</p>
                                <p><strong>{{ __('phone') }}:</strong> {{ $school->support_phone }}</p>
                                <p><strong>{{ __('address') }}:</strong> {{ $school->address }}</p>
                                <p><strong>{{ __('promo_code') }}:</strong> {{ $school->promo_code ?? '-' }}</p>
                                <p><strong>{{ __('discount') }} %:</strong> {{ $school->discount_percentage ?? 0 }}</p>
                                <p><strong>{{ __('profit') }} %:</strong> {{ $school->profit_percentage ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="students-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th><th>{{ __('name') }}</th><th>{{ __('email') }}</th><th>{{ __('phone') }}</th><th>{{ __('admission_no') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}</td>
                                        <td>{{ $student->user->email ?? '-' }}</td>
                                        <td>{{ $student->user->mobile ?? '-' }}</td>
                                        <td>{{ $student->admission_no }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">{{ __('no_data_found') }}</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $students->links() }}
                    </div>

                    <div class="tab-pane fade" id="payments-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th><th>{{ __('date') }}</th><th>{{ __('student') }}</th><th>{{ __('promo_code') }}</th><th>{{ __('amount') }}</th><th>{{ __('discount') }}</th><th>{{ __('status') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->created_at }}</td>
                                        <td>{{ $payment->payer->first_name ?? '' }} {{ $payment->payer->last_name ?? '' }}</td>
                                        <td>{{ $payment->promo_code ?? '-' }}</td>
                                        <td>{{ number_format((float)$payment->paid_amount, 2) }}</td>
                                        <td>{{ number_format((float)$payment->discount_amount, 2) }} ({{ $payment->discount_percentage }}%)</td>
                                        <td>{{ $payment->transaction_status }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">{{ __('no_data_found') }}</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $payments->links() }}
                    </div>

                    <div class="tab-pane fade" id="profit-tab">
                        <p><strong>{{ __('total') }} {{ __('paid_amount') }}:</strong> {{ number_format($summary['total_paid_amount'], 2) }}</p>
                        <p><strong>{{ __('total') }} {{ __('discount') }}:</strong> {{ number_format($summary['total_discount'], 2) }}</p>
                        <p><strong>{{ __('total') }} {{ __('profit') }}:</strong> {{ number_format($summary['total_profit'], 2) }}</p>
                        <p><strong>{{ __('profit') }} %:</strong> {{ $school->profit_percentage ?? 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

