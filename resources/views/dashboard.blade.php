@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold text-dark">Barangay Dashboard</h4>
            <p class="text-muted small mb-0">Overview of community demographics and geographical distribution</p>
        </div>
        <div class="text-muted small text-end">
            <i class="bi bi-calendar3 me-1"></i> Data as of {{ now()->format('M d, Y') }}
        </div>
    </div>

    {{-- Top Row: High-Level Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['residents']) }}</div>
                        <div class="text-muted small">Total Residents</div>
                    </div>
                </div>
                <a href="{{ route('residents.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success"><i class="bi bi-houses"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['households']) }}</div>
                        <div class="text-muted small">Total Households</div>
                    </div>
                </div>
                <a href="{{ route('households.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['puroks']) }}</div>
                        <div class="text-muted small">Total Puroks</div>
                    </div>
                </div>
                <a href="{{ route('puroks.index') }}" class="stretched-link"></a>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-purple bg-opacity-10" style="background:#f3e8ff;color:#6b21a8"><i
                            class="bi bi-check-circle"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['voters']) }}</div>
                        <div class="text-muted small">Registered Voters</div>
                    </div>
                </div>
                <a href="{{ route('residents.index', ['voter_status' => 'registered']) }}" class="stretched-link"></a>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #dashboardMap {
                height: 450px;
                border-radius: 10px;
                z-index: 1;
            }

            .map-popup-card {
                min-width: 150px;
            }

            .map-popup-card h6 {
                margin-bottom: 8px;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
            }

            .purok-map-label {
                background: rgba(255, 255, 255, 0.85);
                border: 1px solid rgba(0, 0, 0, 0.1);
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 4px 8px;
                white-space: nowrap;
            }

            .leaflet-tooltip-pane .purok-map-label::before {
                display: none;
            }

            /* Hide the pointer arrow */
        </style>
    @endpush

    {{-- Geographical Overview --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Geographical Overview</h6>
                    <span class="badge bg-primary-subtle text-primary">Interactive Map</span>
                </div>
                <div class="card-body">
                    <div id="dashboardMap"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comparative Analysis Row --}}
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Comparative Distribution (Residents vs Households)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="comparisonChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Demographic Breakdown and Purok Population --}}
    <div class="row g-4 mb-4">
        {{-- Demographic Breakdown --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Demographic Breakdown</h6>
                </div>
                <div class="card-body">
                    <div style="height: 250px;">
                        <canvas id="sexDistributionChart"></canvas>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small"><i class="bi bi-person-heart me-1 text-warning"></i> Senior
                                Citizens</span>
                            <span class="fw-bold">{{ number_format($demographics['seniors']) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small"><i class="bi bi-person-badge me-1 text-danger"></i> Persons with
                                Disability</span>
                            <span class="fw-bold">{{ number_format($demographics['pwds']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Population by Purok --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Population per Purok</h6>
                    <a href="{{ route('puroks.index') }}" class="btn btn-sm btn-link text-decoration-none">View All</a>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="purokPopulationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links / Recent Actions --}}
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Quick Management</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('residents.create') }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-person-plus fs-4 text-primary me-3"></i>
                                <div>
                                    <div class="fw-bold">Register New Resident</div>
                                    <div class="text-muted small">Add a new individual to the barangay registry</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                            </div>
                        </a>
                        <a href="{{ route('households.create') }}" class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-house-add fs-4 text-success me-3"></i>
                                <div>
                                    <div class="fw-bold">Register New Household</div>
                                    <div class="text-muted small">Create a new household unit and assign a head</div>
                                </div>
                                <i class="bi bi-chevron-right ms-auto text-muted"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white overflow-hidden" style="min-height: 200px;">
                <div class="card-body p-4 position-relative">
                    <div class="position-absolute end-0 top-0 p-3 opacity-25">
                        <i class="bi bi-buildings" style="font-size: 8rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">System Updates</h5>
                    <p class="mb-4 opacity-75">Barangay Management System is fully functional with mapping and analytics
                        integration.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('residents.index') }}" class="btn btn-light btn-sm text-primary fw-bold">Manage
                            Residents</a>
                        <a href="{{ route('puroks.index') }}" class="btn btn-outline-light btn-sm">Map Overview</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 0. Dashboard Map Logic
                const map = L.map('dashboardMap', {
                    attributionControl: false
                }).setView([12.8797, 121.7740], 6); // Default PH zoom
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19
                }).addTo(map);

                const puroks = @json($purokData);
                const bounds = L.latLngBounds();
                let hasCoords = false;

                puroks.forEach(purok => {
                    if (purok.coordinates && purok.coordinates.length > 0) {
                        const polygon = L.polygon(purok.coordinates, {
                            color: purok.color || '#0d6efd',
                            fillColor: purok.color || '#0d6efd',
                            fillOpacity: 0.4,
                            weight: 2
                        }).addTo(map);

                        const tooltipContent = `
                                    <div class="text-center fw-bold" style="line-height:1.2; padding: 2px;">
                                        <div style="font-size: 11px; text-transform: uppercase; color: #000; letter-spacing: 0.5px;">${purok.name}</div>
                                        <div style="font-size: 15px; color: #0d6efd; margin: 2px 0;">${purok.residents_count} <i class="bi bi-people-fill" style="font-size:11px"></i></div>
                                        <div style="font-size: 11px; color: #333; background: rgba(0,0,0,0.05); border-radius: 3px; display: inline-block; padding: 1px 4px;">${purok.households_count} Households</div>
                                    </div>
                                `;

                        polygon.bindTooltip(tooltipContent, {
                            permanent: true,
                            direction: 'center',
                            className: 'purok-map-label'
                        });

                        // Still keep popup for the "Details" button
                        const popupContent = `
                                    <div class="map-popup-card text-center">
                                        <h6 class="fw-bold text-primary mb-2">${purok.name}</h6>
                                        <a href="/puroks/${purok.id}" class="btn btn-sm btn-primary text-white">View Purok Details</a>
                                    </div>
                                `;
                        polygon.bindPopup(popupContent);

                        // Add to bounds for auto-zoom
                        purok.coordinates.forEach(coord => bounds.extend(L.latLng(coord[0], coord[1])));
                        hasCoords = true;
                    }
                });

                if (hasCoords) {
                    map.fitBounds(bounds);
                }

                // 1. Comparison Chart (Residents vs Households)
                const compCtx = document.getElementById('comparisonChart');
                const compLabels = @json($purokData->pluck('name'));
                const residentData = @json($purokData->pluck('residents_count'));
                const householdData = @json($purokData->pluck('households_count'));

                new Chart(compCtx, {
                    type: 'line',
                    data: {
                        labels: compLabels,
                        datasets: [
                            {
                                label: 'Residents',
                                data: residentData,
                                borderColor: '#0d6efd',
                                backgroundColor: '#0d6efd',
                                tension: 0.3,
                                fill: false,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            },
                            {
                                label: 'Households',
                                data: householdData,
                                borderColor: '#198754',
                                backgroundColor: '#198754',
                                tension: 0.3,
                                fill: false,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { borderDash: [5, 5] },
                                title: { display: true, text: 'Count' }
                            },
                            x: {
                                grid: { display: false },
                                title: { display: true, text: 'Purok' }
                            }
                        },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                padding: 12,
                                backgroundColor: 'rgba(0,0,0,0.8)',
                            }
                        }
                    }
                });

                // 2. Sex Distribution Chart
                const sexCtx = document.getElementById('sexDistributionChart');
                new Chart(sexCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Male', 'Female', 'Other'],
                        datasets: [{
                            data: [{{ $demographics['male'] }}, {{ $demographics['female'] }}, {{ $demographics['other'] }}],
                            backgroundColor: ['#0dcaf0', '#d63384', '#6b21a8'],
                            hoverOffset: 4,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' }
                        },
                        cutout: '70%'
                    }
                });

                // 2. Population by Purok Chart
                const purokCtx = document.getElementById('purokPopulationChart');
                const purokLabels = @json($purokData->pluck('name'));
                const purokValues = @json($purokData->pluck('residents_count'));
                const purokColors = @json($purokData->pluck('color')->map(fn($c) => $c ?? '#0d6efd'));

                new Chart(purokCtx, {
                    type: 'bar',
                    data: {
                        labels: purokLabels,
                        datasets: [{
                            label: 'Residents',
                            data: purokValues,
                            backgroundColor: purokColors.map(c => c + '80'), // Add transparency
                            borderColor: purokColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y', // Horizontal bars
                        scales: {
                            x: { beginAtZero: true, grid: { display: false } },
                            y: { grid: { display: false } }
                        },
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection