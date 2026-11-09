@extends('layouts.app')

@section('title', 'Health Records')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Health Records</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Health Records Database</h4>
        <p class="text-muted small mb-0">Overview of all resident health profiles.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
            <i class="bi bi-plus-circle me-1"></i> Add Health Record
        </button>
    </div>
</div>

<div class="table-card mb-4">
    <div class="p-3 border-bottom bg-light">
        <form method="GET" id="filterForm" action="{{ route('health-records.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by Resident Name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex justify-content-between">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
                    @if(request('search'))
                        <a href="{{ route('health-records.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
                    @endif
                </div>
            </div>
            <div class="col-12 mt-2 pt-2 border-top">
                <x-pagination-limit formId="filterForm" default="20" />
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-3">Resident</th>
                    <th>Blood Type</th>
                    <th>Height (cm)</th>
                    <th>Weight (kg)</th>
                    <th>Conditions</th>
                    <th>Allergies</th>
                    <th class="pe-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('residents.show', $record->resident) }}" class="text-decoration-none fw-semibold text-primary">
                                {{ $record->resident->full_name }}
                            </a>
                        </td>
                        <td class="small">{{ $record->blood_type ?? '—' }}</td>
                        <td class="small">{{ $record->height ?? '—' }}</td>
                        <td class="small">{{ $record->weight ?? '—' }}</td>
                        <td class="small">{{ str()->limit($record->medical_conditions ?? '—', 20) }}</td>
                        <td class="small">{{ str()->limit($record->allergies ?? '—', 20) }}</td>
                        <td class="pe-3 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRecordModal{{ $record->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('health-records.destroy', $record->id) }}" method="POST" class="d-inline swal-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editRecordModal{{ $record->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <form action="{{ route('health-records.update', $record->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-primary">Edit Health Profile</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pb-0">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Resident</label>
                                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $record->resident->full_name }}" readonly>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-bold">Blood Type</label>
                                                <input type="text" name="blood_type" class="form-control form-control-sm" value="{{ $record->blood_type }}" placeholder="e.g. O+, A-">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label small fw-bold">Height (cm)</label>
                                                <input type="number" step="0.01" name="height" class="form-control form-control-sm" value="{{ $record->height }}" placeholder="e.g. 170.5">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label small fw-bold">Weight (kg)</label>
                                                <input type="number" step="0.01" name="weight" class="form-control form-control-sm" value="{{ $record->weight }}" placeholder="e.g. 68.2">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label small fw-bold">Medical Conditions</label>
                                                <textarea name="medical_conditions" class="form-control form-control-sm" rows="2" placeholder="e.g. Hypertension, Asthma...">{{ $record->medical_conditions }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label small fw-bold">Allergies</label>
                                                <textarea name="allergies" class="form-control form-control-sm" rows="2" placeholder="e.g. Peanuts, Penicillin...">{{ $record->allergies }}</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label small fw-bold">Medications</label>
                                                <textarea name="medications" class="form-control form-control-sm" rows="2" placeholder="e.g. Losartan, Maintenance meds...">{{ $record->medications }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">Save Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-muted small">No health records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($records->hasPages())
    <div class="p-3 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }}
        </div>
        {{ $records->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form action="{{ route('health-records.store-global') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary">Add Health Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-0">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Resident <span class="text-danger">*</span></label>
                        <div class="search-container position-relative" id="residentSearchContainer">
                            <div class="input-group input-group-sm mb-2" id="searchInputGroup">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control resident-search shadow-none" placeholder="Search by name..." autocomplete="off" required>
                            </div>
                            <input type="hidden" name="resident_id" class="related-id">
                            
                            <div class="search-results d-none position-absolute w-100 bg-white border rounded shadow-sm" style="max-height: 200px; overflow-y: auto; top: 100%; z-index: 1060;"></div>

                            <div class="selected-info p-2 bg-primary-subtle rounded d-none">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="small fw-bold text-primary">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        <span class="selected-name"></span>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger btn-sm p-0 clear-selected">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Blood Type</label>
                            <input type="text" name="blood_type" class="form-control form-control-sm" placeholder="e.g. O+, A-">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control form-control-sm" placeholder="e.g. 170.5">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label small fw-bold">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control form-control-sm" placeholder="e.g. 68.2">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Medical Conditions</label>
                            <textarea name="medical_conditions" class="form-control form-control-sm" rows="2" placeholder="e.g. Hypertension, Asthma..."></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Allergies</label>
                            <textarea name="allergies" class="form-control form-control-sm" rows="2" placeholder="e.g. Peanuts, Penicillin..."></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small fw-bold">Medications</label>
                            <textarea name="medications" class="form-control form-control-sm" rows="2" placeholder="e.g. Losartan..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('residentSearchContainer');
    if (!container) return;
    
    const searchInput = container.querySelector('.resident-search');
    const inputGroup = container.querySelector('#searchInputGroup');
    const searchResults = container.querySelector('.search-results');
    const hiddenIdInput = container.querySelector('.related-id');
    const selectedInfo = container.querySelector('.selected-info');
    const selectedName = container.querySelector('.selected-name');
    const clearBtn = container.querySelector('.clear-selected');

    let timeout = null;
    searchInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const query = this.value;

        if (query.length < 2) {
            searchResults.classList.add('d-none');
            return;
        }

        searchResults.innerHTML = '<div class="p-2 small text-muted text-center"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
        searchResults.classList.remove('d-none');

        timeout = setTimeout(() => {
            fetch(`{{ route('residents.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'p-2 border-bottom small';
                            div.style.cursor = 'pointer';
                            div.innerHTML = `<strong>${item.text}</strong>`;
                            
                            div.onclick = () => {
                                hiddenIdInput.value = item.id;
                                selectedName.textContent = item.text;
                                inputGroup.classList.add('d-none');
                                searchResults.classList.add('d-none');
                                selectedInfo.classList.remove('d-none');
                                searchInput.value = '';
                                searchInput.removeAttribute('required');
                            };
                            
                            div.onmouseover = () => div.style.backgroundColor = '#f8f9fa';
                            div.onmouseout = () => div.style.backgroundColor = '';
                            searchResults.appendChild(div);
                        });
                    } else {
                        searchResults.innerHTML = '<div class="p-2 small text-muted text-center">No residents found</div>';
                    }
                });
        }, 300);
    });

    clearBtn.addEventListener('click', function () {
        hiddenIdInput.value = '';
        selectedName.textContent = '';
        selectedInfo.classList.add('d-none');
        inputGroup.classList.remove('d-none');
        searchInput.setAttribute('required', 'required');
        searchInput.focus();
    });

    document.addEventListener('click', function (e) {
        if (!container.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });
});
</script>
@endpush
