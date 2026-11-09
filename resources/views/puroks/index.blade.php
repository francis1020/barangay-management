@extends('layouts.app')

@section('title', 'Puroks')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Puroks</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Puroks</h4>
        <p class="text-muted small mb-0">Manage geographical sub-divisions of the barangay</p>
    </div>
    <a href="{{ route('puroks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Purok
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="bi bi-geo-alt fs-4"></i></div>
                <div>
                    <div class="fs-4 fw-bold lh-1 mb-1">{{ number_format($stats['total_puroks']) }}</div>
                    <div class="text-muted small">Total Puroks</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success bg-opacity-10 text-success rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="bi bi-houses fs-4"></i></div>
                <div>
                    <div class="fs-4 fw-bold lh-1 mb-1">{{ number_format($stats['total_households']) }}</div>
                    <div class="text-muted small">Total Households</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card border-0 shadow-sm rounded-3 p-3 bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;"><i class="bi bi-people fs-4"></i></div>
                <div>
                    <div class="fs-4 fw-bold lh-1 mb-1">{{ number_format($stats['total_residents']) }}</div>
                    <div class="text-muted small">Total Residents</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="table-card">
            <div class="p-3 border-bottom bg-light">
                <form method="GET" id="filterFormPurok" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control border-start-0 ps-0" placeholder="Search purok name...">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end gap-1">
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        @if(request('search'))
                            <a href="{{ route('puroks.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                        @endif
                    </div>
                    <div class="col-12 mt-2 pt-2 border-top">
                        <x-pagination-limit formId="filterFormPurok" default="10" />
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Purok Name</th>
                            <th>Residents</th>
                            <th>Households</th>
                            <th>Description</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($puroks as $purok)
                        <tr>
                            <td class="fw-bold">
                                <span class="d-inline-block rounded-circle me-1" style="width: 12px; height: 12px; background-color: {{ $purok->color ?? '#0d6efd' }}; border: 1px solid rgba(0,0,0,0.1);"></span>
                                <a href="{{ route('puroks.show', $purok) }}" class="text-decoration-none text-primary fw-bold">
                                    {{ $purok->name }}
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ number_format($purok->residents_count) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info">
                                    {{ number_format($purok->households_count) }}
                                </span>
                            </td>
                            <td class="small text-muted">{{ $purok->description ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('puroks.show', $purok) }}" class="btn btn-sm btn-outline-primary" title="Details">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('puroks.edit', $purok) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('puroks.destroy', $purok) }}" method="POST" 
                                        class="swal-confirm" data-message="Are you sure you want to delete this purok?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">No puroks registered.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($puroks->hasPages())
            <div class="p-3 border-top">
                {{ $puroks->links() }}
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="table-card overflow-hidden">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <h6 class="mb-0 fw-bold">Geographical Overview</h6>
                <span class="badge bg-primary rounded-pill">{{ $allPuroks->count() }} Mapped</span>
            </div>
            <div id="overviewMap" style="height: 450px; width: 100%;"></div>
            <div class="p-3 border-top bg-white">
                <p class="small text-muted mb-0">
                    <i class="bi bi-info-circle me-1"></i> Click on a colored area to view specific Purok details.
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #overviewMap { background: #f8f9fa; }
    .leaflet-popup-content { font-family: inherit; }
    .purok-popup h6 { margin: 0 0 5px; color: #0d6efd; font-weight: bold; }
    .purok-popup a { font-size: 0.8rem; text-decoration: none; font-weight: 500; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var puroks = @json($allPuroks);
        
        if (puroks.length > 0) {
            // Default center if no puroks have valid coordinates
            var map = L.map('overviewMap', {
                attributionControl: false
            }).setView([0, 0], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            var bounds = L.latLngBounds();
            var hasLayer = false;

            puroks.forEach(function(purok) {
                if (purok.coordinates && purok.coordinates.length > 0) {
                    var color = purok.color || '#0d6efd';
                    var polygon = L.polygon(purok.coordinates, {
                        color: color,
                        weight: 2,
                        fillOpacity: 0.35
                    }).addTo(map);

                    // Add Popup
                    var showUrl = "{{ route('puroks.show', ':id') }}".replace(':id', purok.id);
                    var popupContent = `
                        <div class="purok-popup">
                            <h6>${purok.name}</h6>
                            <a href="${showUrl}" class="btn btn-sm btn-primary text-white py-0 px-2 mt-1">
                                <i class="bi bi-eye me-1"></i> View Details
                            </a>
                        </div>
                    `;
                    polygon.bindPopup(popupContent);

                    // Add to bounds
                    bounds.extend(polygon.getBounds());
                    hasLayer = true;
                }
            });

            if (hasLayer) {
                map.fitBounds(bounds, { padding: [20, 20] });
            }
        } else {
            document.getElementById('overviewMap').innerHTML = `
                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                    <div class="text-center">
                        <i class="bi bi-map fs-2 d-block mb-2"></i>
                        <p class="small mb-0">No geographical data available yet.</p>
                    </div>
                </div>
            `;
        }
    });
</script>
@endpush
@endsection
