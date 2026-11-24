@extends('student-portal.layouts.master')

@section('title', __('Interests'))
@section('page-title', __('Program interests'))

@section('content')
    <div class="row g-2">
        @forelse($interests as $i)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm"><div class="card-body">{{ $i->name }}</div></div>
            </div>
        @empty
            <p class="text-muted">{{ __('No interests saved.') }}</p>
        @endforelse
    </div>
@endsection
