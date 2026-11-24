@extends('student-portal.layouts.master')

@section('title', __('Education'))
@section('page-title', __('Education'))

@section('content')
    <p class="text-muted">{{ __('Records from your profile (same as mobile app). Manage details in the app or contact support to edit on web.') }}</p>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                <tr>
                    <th>{{ __('Level') }}</th>
                    <th>{{ __('Institution') }}</th>
                    <th>{{ __('Field') }}</th>
                    <th>{{ __('Years') }}</th>
                    <th>{{ __('Result') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($records as $r)
                    <tr>
                        <td>{{ $r->education_level }}</td>
                        <td>{{ $r->institution_name }}</td>
                        <td>{{ $r->field_of_study }}</td>
                        <td>{{ $r->start_year }} — {{ $r->end_year ?? __('Current') }}</td>
                        <td>{{ $r->percentage_cgpa }} @if($r->grade_type)({{ $r->grade_type }})@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No education records.') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
