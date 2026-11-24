@extends('university.layouts.master')

@section('title', 'Gallery Management')
@section('page-title', 'Gallery Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-images"></i> University Gallery
                    </h5>
                    <a href="{{ route('university.gallery.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Item
                    </a>
                </div>
                <div class="card-body">
                    @if($galleries->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-images fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No gallery items yet</h5>
                            <p class="text-muted">Start by adding your first gallery item to showcase your university.</p>
                            <a href="{{ route('university.gallery.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus"></i> Add First Item
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Order</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($galleries as $gallery)
                                        <tr>
                                            <td>
                                                <img src="{{ $gallery->image_url }}"
                                                     alt="{{ $gallery->title }}"
                                                     style="width: 80px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            </td>
                                            <td>
                                                <strong>{{ $gallery->title }}</strong><br>
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($gallery->description, 50) }}</small>
                                            </td>
                                            <td>
                                                @if($gallery->category)
                                                    <span class="badge bg-info">{{ ucfirst($gallery->category) }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst($gallery->image_type) }}</span>
                                            </td>
                                            <td>{{ $gallery->display_order }}</td>
                                            <td>
                                                @if($gallery->is_featured)
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-star"></i> Featured
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($gallery->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('university.gallery.edit', $gallery->id) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('university.gallery.destroy', $gallery->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this gallery item?');"
                                                          style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            {{ $galleries->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-md-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Items</h6>
                            <h3 class="mb-0">{{ $galleries->total() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-images fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Featured</h6>
                            <h3 class="mb-0">{{ $galleries->where('is_featured', true)->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-star fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="mb-0">{{ $galleries->where('status', true)->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Categories</h6>
                            <h3 class="mb-0">{{ $galleries->pluck('category')->filter()->unique()->count() }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-folder fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

