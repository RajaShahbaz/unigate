@extends('student-portal.layouts.master')

@section('title', __('Applications'))
@section('page-title', __('My applications'))

@section('content')
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>{{ __('Number') }}</th>
                <th>{{ __('University') }}</th>
                <th>{{ __('Program') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Updated') }}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($applications as $app)
                <tr>
                    <td>{{ $app->application_number }}</td>
                    <td>{{ $app->university->name ?? '—' }}</td>
                    <td>{{ $app->interest->name ?? '—' }}</td>
                    <td><span class="badge bg-secondary">{{ $app->status }}</span></td>
                    <td>{{ $app->updated_at->format('Y-m-d') }}</td>
                    <td><a class="btn btn-sm btn-outline-primary" href="{{ route('student.portal.applications.show', $app->id) }}">{{ __('View') }}</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">{{ __('No applications.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
