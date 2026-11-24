@extends('user-portal.layouts.master')

@section('title', __('Documents'))
@section('page-title', __('Document Management'))

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
            @forelse($documents as $document)
                <tr>
                    <td>{{ $document->document_name }}</td>
                    <td>
                        @if($document->file_url)
                            <a href="{{ $document->file_url }}" target="_blank" rel="noopener">{{ $document->original_filename }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $document->file_size_formatted ?? $document->file_size }}</td>
                    <td>{{ $document->created_at?->format('Y-m-d') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">{{ __('No documents uploaded.') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
