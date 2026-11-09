<div class="row g-3">
    <div class="col-md-6">
        <label for="purok_id" class="form-label">Purok <span class="text-danger">*</span></label>
        <select name="purok_id" id="purok_id" class="form-select @error('purok_id') is-invalid @enderror" required>
            <option value="">Select Purok</option>
            @foreach($puroks as $purok)
                <option value="{{ $purok->id }}" {{ old('purok_id', $household->purok_id ?? '') == $purok->id ? 'selected' : '' }}>
                    {{ $purok->name }}
                </option>
            @endforeach
        </select>
        @error('purok_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="head_resident_id" class="form-label">Head of Household</label>
        <select name="head_resident_id" id="head_resident_id" class="form-select @error('head_resident_id') is-invalid @enderror">
            <option value="">Select Head</option>
            @if(isset($household))
                @foreach($household->residents as $member)
                    <option value="{{ $member->id }}" {{ old('head_resident_id', $household->head_resident_id ?? '') == $member->id ? 'selected' : '' }}>
                        {{ $member->full_name }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('head_resident_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text extra-small">Add residents to the list below to see them in this dropdown.</div>
    </div>

    <div class="col-12">
        <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
        <textarea name="address" id="address" rows="2" class="form-control @error('address') is-invalid @enderror" required placeholder="House No., Street name, etc.">{{ old('address', $household->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="ownership_status" class="form-label">Ownership Status <span class="text-danger">*</span></label>
        <select name="ownership_status" id="ownership_status" class="form-select @error('ownership_status') is-invalid @enderror" required>
            <option value="">Select Status</option>
            <option value="owned" {{ old('ownership_status', $household->ownership_status ?? '') == 'owned' ? 'selected' : '' }}>Owned</option>
            <option value="rented" {{ old('ownership_status', $household->ownership_status ?? '') == 'rented' ? 'selected' : '' }}>Rented</option>
            <option value="shared" {{ old('ownership_status', $household->ownership_status ?? '') == 'shared' ? 'selected' : '' }}>Shared</option>
            <option value="informal_settler" {{ old('ownership_status', $household->ownership_status ?? '') == 'informal_settler' ? 'selected' : '' }}>Informal Settler</option>
        </select>
        @error('ownership_status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="housing_type" class="form-label">Housing Type <span class="text-danger">*</span></label>
        <select name="housing_type" id="housing_type" class="form-select @error('housing_type') is-invalid @enderror" required>
            <option value="">Select Type</option>
            <option value="concrete" {{ old('housing_type', $household->housing_type ?? '') == 'concrete' ? 'selected' : '' }}>Concrete</option>
            <option value="semi_concrete" {{ old('housing_type', $household->housing_type ?? '') == 'semi_concrete' ? 'selected' : '' }}>Semi-Concrete</option>
            <option value="wood" {{ old('housing_type', $household->housing_type ?? '') == 'wood' ? 'selected' : '' }}>Wood</option>
            <option value="makeshift" {{ old('housing_type', $household->housing_type ?? '') == 'makeshift' ? 'selected' : '' }}>Makeshift</option>
        </select>
        @error('housing_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="income_class" class="form-label">Income Class</label>
        <select name="income_class" id="income_class" class="form-select @error('income_class') is-invalid @enderror">
            <option value="">Select Class</option>
            <option value="poor" {{ old('income_class', $household->income_class ?? '') == 'poor' ? 'selected' : '' }}>Poor</option>
            <option value="low_income" {{ old('income_class', $household->income_class ?? '') == 'low_income' ? 'selected' : '' }}>Low Income</option>
            <option value="middle" {{ old('income_class', $household->income_class ?? '') == 'middle' ? 'selected' : '' }}>Middle Class</option>
            <option value="high" {{ old('income_class', $household->income_class ?? '') == 'high' ? 'selected' : '' }}>High Income</option>
        </select>
        @error('income_class')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="monthly_income" class="form-label">Approx. Monthly Income</label>
        <div class="input-group">
            <span class="input-group-text">₱</span>
            <input type="number" step="0.01" name="monthly_income" id="monthly_income" class="form-control @error('monthly_income') is-invalid @enderror" value="{{ old('monthly_income', $household->monthly_income ?? '') }}">
            @error('monthly_income')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12 mt-4 pt-3 border-top">
        <h6 class="fw-bold mb-3"><i class="bi bi-people me-2 text-primary"></i>Household Members</h6>
        <div class="table-card border-0 shadow-none bg-light p-3 rounded">
            <div class="alert alert-warning py-2 mb-3 border-0 bg-warning-subtle text-warning-emphasis small d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-6"></i>
                <div>
                    <strong>Notice:</strong> Selecting residents who already belong to another household will automatically transfer them to this household upon saving.
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Search and Add Residents</label>
                <div class="position-relative">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" id="residentSearch" class="form-control border-start-0 ps-0 shadow-none" placeholder="Search by name (min. 2 chars)..." autocomplete="off">
                    </div>
                    <div id="searchResults" class="search-results d-none"></div>
                </div>
                <div class="form-text extra-small">Add residents who live in this household. Assigning them here will update their individual profiles.</div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" id="membersTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="width: 40px;"></th>
                            <th>Resident Name</th>
                            <th>Current Household</th>
                            <th class="text-end pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody id="membersList">
                        @if(isset($household))
                            @foreach($household->residents as $member)
                                <tr data-id="{{ $member->id }}">
                                    <td class="ps-3"><input type="hidden" name="resident_ids[]" value="{{ $member->id }}"></td>
                                    <td class="fw-semibold small">{{ $member->full_name }}</td>
                                    <td><span class="badge bg-primary-subtle text-primary extra-small">Current Member</span></td>
                                    <td class="text-end pe-3">
                                        <button type="button" class="btn btn-link link-danger p-0 remove-member"><i class="bi bi-x-circle"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr id="noMembersRow" class="{{ isset($household) && $household->residents->count() > 0 ? 'd-none' : '' }}">
                            <td colspan="4" class="text-center py-4 text-muted small italic">No members added to this list yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .search-results {
        position: absolute;
        width: 100%;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        z-index: 1050;
        max-height: 250px;
        overflow-y: auto;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .search-results .item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
        transition: background 0.2s;
    }
    .search-results .item:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }
    .search-results .item .age-label {
        font-size: 0.75rem;
        color: #6c757d;
    }
    .search-results .item .hh-badge {
        font-size: 0.65rem;
        padding: 2px 6px;
        border-radius: 10px;
        background: #e9ecef;
    }
    .search-results .item .hh-badge.current {
        background: #d1f2e4;
        color: #0d6832;
    }
    .extra-small { font-size: 0.75rem; }
    .remove-member { font-size: 1.1rem; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('residentSearch');
    const resultsContainer = document.getElementById('searchResults');
    const membersList = document.getElementById('membersList');
    const noMembersRow = document.getElementById('noMembersRow');
    const headSelect = document.getElementById('head_resident_id');
    const currentHouseholdId = '{{ $household->id ?? "" }}';

    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value;

        if (query.length < 2) {
            resultsContainer.classList.add('d-none');
            return;
        }

        timeout = setTimeout(() => {
            // Get currently added IDs to exclude them from search
            const excludeIds = Array.from(document.querySelectorAll('input[name="resident_ids[]"]'))
                                    .map(input => input.value);
            
            const params = new URLSearchParams({
                q: query,
                household_id: currentHouseholdId
            });
            excludeIds.forEach(id => params.append('exclude_ids[]', id));

            fetch(`{{ route('residents.search') }}?${params.toString()}`)
                .then(res => res.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'item d-flex align-items-center justify-content-between';
                            div.innerHTML = `
                                <div>
                                    <div class="fw-bold">${item.text}</div>
                                    <div class="age-label">${item.household}</div>
                                </div>
                                <i class="bi bi-plus-circle text-primary fs-5"></i>
                            `;
                            div.onclick = () => addMember(item);
                            resultsContainer.appendChild(div);
                        });
                        resultsContainer.classList.remove('d-none');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-3 text-center text-muted small">No residents found.</div>';
                        resultsContainer.classList.remove('d-none');
                    }
                });
        }, 300);
    });

    function addMember(item) {
        const row = document.createElement('tr');
        row.dataset.id = item.id;
        row.innerHTML = `
            <td class="ps-3"><input type="hidden" name="resident_ids[]" value="${item.id}"></td>
            <td class="fw-semibold small">${item.text}</td>
            <td><span class="badge bg-secondary-subtle text-secondary extra-small">${item.household}</span></td>
            <td class="text-end pe-3">
                <button type="button" class="btn btn-link link-danger p-0 remove-member"><i class="bi bi-x-circle"></i></button>
            </td>
        `;
        
        membersList.appendChild(row);
        updateUI();
        
        // Clear search
        searchInput.value = '';
        resultsContainer.classList.add('d-none');
        searchInput.focus();

        // If headSelect exists, add this person as an option
        if (headSelect) {
            const option = new Option(item.text, item.id);
            headSelect.add(option);
        }
    }

    membersList.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            
            // Remove from head dropdown if selected or present
            if (headSelect) {
                const option = headSelect.querySelector(`option[value="${id}"]`);
                if (option) option.remove();
            }
            
            row.remove();
            updateUI();
        }
    });

    function updateUI() {
        const hasMembers = membersList.querySelectorAll('tr[data-id]').length > 0;
        noMembersRow.classList.toggle('d-none', hasMembers);
    }

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('d-none');
        }
    });
});
</script>
@endpush
