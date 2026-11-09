@extends('layouts.app')

@section('title', 'Add Health Record')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('health-records.index') }}">Health Records</a></li>
    <li class="breadcrumb-item active" aria-current="page">Add Record</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0 fw-bold">Add Health Record</h4>
                <p class="text-muted small mb-0">Create a new health profile for a resident.</p>
            </div>
            <a href="{{ route('health-records.index') }}" class="btn btn-light border">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('health-records.store-global') }}" method="POST">
                    @csrf
                    <div class="mb-4 pb-3 border-bottom">
                        <label class="form-label fw-bold">Resident Selection <span class="text-danger">*</span></label>
                        <div class="search-container position-relative" id="residentSearchContainer">
                            <div class="input-group mb-2" id="searchInputGroup">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control resident-search shadow-none" placeholder="Start typing resident name..." autocomplete="off" required>
                            </div>
                            <input type="hidden" name="resident_id" class="related-id">
                            
                            <div class="search-results d-none position-absolute w-100 bg-white border rounded shadow-sm" style="max-height: 250px; overflow-y: auto; top: 100%; z-index: 1060;"></div>

                            <div class="selected-info p-3 bg-primary-subtle rounded d-none border border-primary-subtle">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-primary">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Selected: <span class="selected-name"></span>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm clear-selected">
                                        <i class="bi bi-x-circle me-1"></i> Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary">Vitals & Background</h6>
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Blood Type</label>
                            <input type="text" name="blood_type" class="form-control" placeholder="e.g. O+, A-, B+">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control" placeholder="170.5">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" placeholder="65.0">
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-secondary">Medical Information</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Medical Conditions</label>
                            <textarea name="medical_conditions" class="form-control" rows="3" placeholder="e.g. Hypertension, Asthma, Type 2 Diabetes..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Allergies</label>
                            <textarea name="allergies" class="form-control" rows="3" placeholder="e.g. Penicillin, Peanuts, Seafood..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Medications</label>
                            <textarea name="medications" class="form-control" rows="3" placeholder="e.g. Losartan 50mg, Maintenance meds..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="{{ route('health-records.index') }}" class="btn btn-light px-4 border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2 me-1"></i> Save Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

        searchResults.innerHTML = '<div class="p-3 small text-muted text-center"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
        searchResults.classList.remove('d-none');

        timeout = setTimeout(() => {
            fetch(`{{ route('residents.search') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'p-3 border-bottom small';
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
                        searchResults.innerHTML = '<div class="p-3 small text-muted text-center">No residents found matching query</div>';
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
