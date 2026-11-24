@extends('student-portal.layouts.master')

@section('title', __('Preferred cities'))
@section('page-title', __('Preferred cities'))

@section('content')
    <div class="row g-2">
        @forelse($cities as $c)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm"><div class="card-body">{{ $c->name }}</div></div>
            </div>
        @empty
            <p class="text-muted">{{ __('No preferred cities.') }}</p>
        @endforelse
    </div>
@endsection
