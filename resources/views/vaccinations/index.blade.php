@extends('layouts.app')

@section('title', 'Vaccinations')
@section('breadcrumb')
    <li class="breadcrumb-item active text-muted" aria-current="page">Vaccinations</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold">Vaccinations Registry</h4>
        <p class="text-muted small mb-0">Overview of all vaccination records.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRecordModal">
            <i class="bi bi-plus-circle me-1"></i> Add Vaccination
        </button>
    </div>
</div>

<div class="table-card mb-4">
    <div class="p-3 border-bottom bg-light">
        <form method="GET" id="filterForm" action="{{ route('vaccinations.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by Resident or Vaccine..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2 d-flex justify-content-between">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Search</button>
                    @if(request('search'))
                        <a href="{{ route('vaccinations.index') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
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
                    <th>Vaccine Name</th>
                    <th>Type</th>
                    <th>Dose</th>
                    <th>Date Administered</th>
                    <th class="pe-3 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vaccinations as $vax)
                    <tr>
                        <td class="ps-3">
                            <a href="{{ route('residents.show', $vax->resident) }}" class="text-decoration-none fw-semibold text-primary">
                                {{ $vax->resident->full_name }}
                            </a>
                        </td>
                        <td class="small fw-medium">{{ $vax->vaccine_name }}</td>
                        <td class="small">{{ $vax->vaccine_type ?? '—' }}</td>
                        <td class="small">{{ $vax->dose_number ?? '—' }}</td>
                        <td class="small">{{ $vax->date_administered?->format('M d, Y') ?? '—' }}</td>
                        <td class="pe-3 text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editRecordModal{{ $vax->id }}" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('vaccinations.destroy', $vax->id) }}" method="POST" class="d-inline swal-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editRecordModal{{ $vax->id }}" tabindex="-1" aria-hidden="true" style="display: none;">
                        <div class="modal-dialog">
                            <form action="{{ route('vaccinations.update', $vax->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-bold text-primary">Edit Vaccination</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body pb-0">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Resident</label>
                                            <input type="text" class="form-control form-control-sm bg-light" value="{{ $vax->resident->full_name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Vaccine Name <span class="text-danger">*</span></label>
                                            <input type="text" name="vaccine_name" class="form-control form-control-sm" required value="{{ $vax->vaccine_name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Vaccine Type</label>
                                            <input type="text" name="vaccine_type" class="form-control form-control-sm" value="{{ $vax->vaccine_type }}">
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-bold">Dose Number</label>
                                                <input type="number" name="dose_number" class="form-control form-control-sm" min="1" value="{{ $vax->dose_number }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-bold">Date Administered</label>
                                                <input type="date" name="date_administered" class="form-control form-control-sm" value="{{ $vax->date_administered?->format('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">Notes</label>
                                            <textarea name="notes" class="form-control form-control-sm" rows="2">{{ $vax->notes }}</textarea>
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
                        <td colspan="6" class="text-center p-4 text-muted small">No vaccination records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vaccinations->hasPages())
    <div class="p-3 border-top d-flex align-items-center justify-content-between">
        <div class="text-muted small">
            Showing {{ $vaccinations->firstItem() }}–{{ $vaccinations->lastItem() }} of {{ $vaccinations->total() }}
        </div>
        {{ $vaccinations->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="addRecordModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <form action="{{ route('vaccinations.store-global') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-primary">Add Vaccination</h5>
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
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vaccine Name <span class="text-danger">*</span></label>
                        <input type="text" name="vaccine_name" class="form-control form-control-sm" required placeholder="e.g. Pfizer...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vaccine Type</label>
                        <input type="text" name="vaccine_type" class="form-control form-control-sm" placeholder="e.g. COVID-19...">
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Dose Number</label>
                            <input type="number" name="dose_number" class="form-control form-control-sm" min="1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Date Administered</label>
                            <input type="date" name="date_administered" class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Notes</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2"></textarea>
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
