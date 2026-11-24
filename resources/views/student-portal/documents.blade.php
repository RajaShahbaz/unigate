@extends('student-portal.layouts.master')

@section('title', __('Documents'))
@section('page-title', __('Student documents'))

@section('content')
    <div class="table-responsive card border-0 shadow-sm">
        <table class="table mb-0 align-middle">
            <thead class="table-light">
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('File') }}</th>
                <th>{{ __('Size') }}</th>
                <th>{{ __('Uploaded') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($documents as $d)
                <tr>
                    <td>{{ $d->document_name }}</td>
                    <td>
                        @if($d->file_url)
                            <a href="{{ $d->file_url }}" target="_blank" rel="noopener">{{ $d->original_filename }}</a>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $d->file_size_formatted ?? $d->file_size }}</td>
                    <td>{{ $d->created_at->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('No documents uploaded.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
