@extends('layouts.app')

@section('title', $purok->name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('puroks.index') }}" class="text-decoration-none text-muted">Puroks</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">{{ $purok->name }}</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">{{ $purok->name }} Overview</h4>
        <p class="text-muted small mb-0">{{ $purok->description ?? 'Manage residents and households within this area.' }}</p>
    </div>
    <div class="d-flex gap-2">
        <x-back-button url="{{ route('puroks.index') }}" />
        <a href="{{ route('puroks.edit', $purok) }}" class="btn btn-primary btn-sm d-flex align-items-center">
            <i class="bi bi-pencil me-1"></i> Edit Purok
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($purok->residents_count) }}</div>
                    <div class="text-muted small">Residents</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-houses"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($purok->households_count) }}</div>
                    <div class="text-muted small">Households</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-heart"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['seniors_count']) }}</div>
                    <div class="text-muted small">Seniors</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-person-badge"></i></div>
                <div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['pwds_count']) }}</div>
                    <div class="text-muted small">PWDs</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($purok->coordinates)
<div class="card shadow-sm border-0 mb-4 overflow-hidden">
    <div class="card-header bg-white py-3">
        <h6 class="mb-0 fw-bold"><i class="bi bi-map me-2 text-primary"></i>Purok Boundary Map</h6>
    </div>
    <div id="showMap" style="height: 400px; width: 100%;"></div>
</div>
@endif

<div class="row g-4">
    {{-- Households List --}}
    <div class="col-xl-4">
        <div class="table-card h-100">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-bold">Households</h6>
                    <span class="badge bg-secondary">{{ $households->total() }}</span>
                </div>
                <x-pagination-limit paramName="limit_households" default="10" />
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Household No.</th>
                            <th>Head</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($households as $household)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('households.show', $household) }}" class="fw-bold text-decoration-none">
                                    {{ $household->household_no }}
                                </a>
                            </td>
                            <td class="small">{{ $household->head ? $household->head->full_name : 'No Head Assigned' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-4">No households found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($households->hasPages())
            <div class="p-3 border-top">
                {{ $households->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Residents List --}}
    <div class="col-xl-8">
        <div class="table-card h-100">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="mb-0 fw-bold">Residents</h6>
                    <span class="badge bg-primary">{{ $residents->total() }}</span>
                </div>
                <x-pagination-limit paramName="limit_residents" default="10" />
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th>Household</th>
                            <th>Age | Sex</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($residents as $resident)
                        <tr>
                            <td class="ps-3">
                                <a href="{{ route('residents.show', $resident) }}" class="fw-semibold text-decoration-none d-flex align-items-center gap-2">
                                    <div class="avatar bg-primary-subtle text-primary extra-small">
                                        {{ strtoupper(substr($resident->first_name, 0, 1)) }}
                                    </div>
                                    {{ $resident->full_name }}
                                </a>
                            </td>
                            <td>
                                @if($resident->household)
                                    <a href="{{ route('households.show', $resident->household) }}" class="small text-muted text-decoration-none hover-primary">
                                        <i class="bi bi-house me-1"></i>{{ $resident->household->household_no }}
                                    </a>
                                @else
                                    <span class="text-muted small">None</span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                {{ $resident->age }} yrs <span class="mx-1">•</span> {{ ucfirst($resident->sex) }}
                            </td>
                            <td>
                                @if($resident->is_senior_citizen)
                                    <span class="badge bg-warning-subtle text-warning extra-small">Senior</span>
                                @endif
                                @if($resident->is_pwd)
                                    <span class="badge bg-info-subtle text-info extra-small">PWD</span>
                                @endif
                                @if(!$resident->is_senior_citizen && !$resident->is_pwd)
                                    <span class="text-muted extra-small">Regular</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">No residents registered in this purok yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($residents->hasPages())
            <div class="p-3 border-top">
                {{ $residents->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@if($purok->coordinates)
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var points = @json($purok->coordinates);
        var color = "{{ $purok->color ?? '#0d6efd' }}";

        if (points && points.length > 0) {
            var map = L.map('showMap', {
                attributionControl: false
            }).setView(points[0], 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            var polygon = L.polygon(points, {
                color: color,
                weight: 3,
                fillOpacity: 0.2
            }).addTo(map);

            // Fit map bounds to the polygon
            map.fitBounds(polygon.getBounds());
        }
    });
</script>
@endpush
@endif
