@extends('student-portal.layouts.master')

@section('title', __('Favorites'))
@section('page-title', __('Favorite universities'))

@section('content')
    <div class="list-group shadow-sm">
        @forelse($favorites as $u)
            <a href="{{ route('university.detail', $u->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <span>{{ $u->name }}</span>
                <i class="fas fa-chevron-right text-muted small"></i>
            </a>
        @empty
            <div class="list-group-item text-muted">{{ __('No favorites.') }} <a href="{{ route('universities') }}">{{ __('Browse universities') }}</a></div>
        @endforelse
    </div>
@endsection
