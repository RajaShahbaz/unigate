@extends('student-portal.layouts.master')

@section('title', __('Notifications'))
@section('page-title', __('Notifications'))

@section('content')
    @if($notifications->where('is_read', false)->count())
        <form method="post" action="{{ route('student.portal.notifications.mark-all-read') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('Mark all as read') }}</button>
        </form>
    @endif

    <div class="list-group shadow-sm">
        @forelse($notifications as $n)
            <div class="list-group-item {{ $n->is_read ? '' : 'bg-light' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>{{ $n->title }}</strong>
                        <div class="small text-muted">{{ $n->sent_at?->format('Y-m-d H:i') ?? $n->created_at->format('Y-m-d H:i') }}</div>
                        <p class="mb-0 mt-2">{{ $n->message }}</p>
                    </div>
                    @if(!$n->is_read)
                        <form method="post" action="{{ route('student.portal.notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">{{ __('Read') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="list-group-item text-muted">{{ __('No notifications.') }}</div>
        @endforelse
    </div>
@endsection
