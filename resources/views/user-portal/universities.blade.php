@extends('user-portal.layouts.master')

@section('title', __('Universities'))
@section('page-title', __('Universities'))

@section('content')
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('City') }}</th>
                <th>{{ __('Country') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($universities as $university)
                <tr>
                    <td>{{ $university->name }}</td>
                    <td>{{ $university->city->name ?? '-' }}</td>
                    <td>{{ $university->country ?? '-' }}</td>
                    <td>
                        <a href="{{ route('university.detail', $university->id) }}" class="btn btn-sm btn-outline-primary">
                            {{ __('View') }}
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('No universities available.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
