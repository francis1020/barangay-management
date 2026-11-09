<div class="row g-4">
    {{-- Personal Information --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Personal Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" 
                            value="{{ old('first_name', $resident?->first_name ?? '') }}" placeholder="e.g. Juan" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control" 
                            value="{{ old('middle_name', $resident?->middle_name ?? '') }}" placeholder="e.g. Santos">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" 
                            value="{{ old('last_name', $resident?->last_name ?? '') }}" placeholder="e.g. Dela Cruz" required>
                        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">Suffix</label>
                        <input type="text" name="suffix" class="form-control" placeholder="Jr."
                            value="{{ old('suffix', $resident?->suffix ?? '') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Birthdate <span class="text-danger">*</span></label>
                        <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" 
                            value="{{ old('birthdate', isset($resident) ? $resident?->birthdate?->format('Y-m-d') : '') }}" required>
                        @error('birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Birthplace <span class="text-danger">*</span></label>
                        <input type="text" name="birthplace" class="form-control @error('birthplace') is-invalid @enderror" 
                            value="{{ old('birthplace', $resident?->birthplace ?? '') }}" placeholder="City/Municipality" required>
                        @error('birthplace') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sex</label>
                        <select name="sex" class="form-select @error('sex') is-invalid @enderror" required>
                            <option value="">Select</option>
                            <option value="male" {{ old('sex', $resident?->sex ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('sex', $resident?->sex ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('sex', $resident?->sex ?? '') === 'other' ? 'selected' : '' }}>Other / LGBTQ+</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Civil Status</label>
                        <select name="civil_status" class="form-select @error('civil_status') is-invalid @enderror" required>
                            <option value="">Select</option>
                            @foreach(['single', 'married', 'widowed', 'separated', 'annulled'] as $status)
                                <option value="{{ $status }}" {{ old('civil_status', $resident?->civil_status ?? '') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('civil_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Citizenship <span class="text-danger">*</span></label>
                        <input type="text" name="citizenship" class="form-control @error('citizenship') is-invalid @enderror" 
                            value="{{ old('citizenship', $resident?->citizenship ?? 'Filipino') }}" placeholder="e.g. Filipino" required>
                        @error('citizenship') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Religion</label>
                        <input type="text" name="religion" class="form-control" 
                            value="{{ old('religion', $resident?->religion ?? '') }}" placeholder="e.g. Roman Catholic">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Educational Attainment</label>
                        <input type="text" name="educational_attainment" class="form-control" 
                            value="{{ old('educational_attainment', $resident?->educational_attainment ?? '') }}" placeholder="e.g. College Graduate">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact & Government IDs --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Contact Information & Government IDs</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror" 
                            value="{{ old('contact_number', $resident?->contact_number ?? '') }}" placeholder="09123456789">
                        @error('contact_number') <div class="invalid-feedback extra-small">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" 
                            value="{{ old('email', $resident?->email ?? '') }}" placeholder="e.g. juan@example.com">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">PhilHealth No.</label>
                        <input type="text" name="philhealth_no" class="form-control" 
                            value="{{ old('philhealth_no', $resident?->philhealth_no ?? '') }}" placeholder="00-000000000-0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">SSS No.</label>
                        <input type="text" name="sss_no" class="form-control" 
                            value="{{ old('sss_no', $resident?->sss_no ?? '') }}" placeholder="00-0000000-0">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">TIN No.</label>
                        <input type="text" name="tin_no" class="form-control" 
                            value="{{ old('tin_no', $resident?->tin_no ?? '') }}" placeholder="000-000-000-000">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Location and Status --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Location & Profile Status</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Purok <span class="text-danger">*</span></label>
                        <select name="purok_id" class="form-select @error('purok_id') is-invalid @enderror" required>
                            <option value="">Select Purok</option>
                            @foreach($puroks as $purok)
                                <option value="{{ $purok->id }}" {{ old('purok_id', $resident?->purok_id ?? '') == $purok->id ? 'selected' : '' }}>
                                    {{ $purok->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('purok_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Household ID (Optional)</label>
                        <div class="household-search-container position-relative">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" id="householdSearchInput" class="form-control" placeholder="Search by HH # or address..." autocomplete="off">
                            </div>
                            <input type="hidden" name="household_id" id="household_id" value="{{ old('household_id', $resident?->household_id ?? '') }}">
                            <div id="householdSearchResults" class="search-results d-none"></div>
                            
                            <div id="selectedHouseholdInfo" class="mt-2 p-2 bg-primary-subtle rounded {{ (old('household_id', $resident?->household_id ?? '')) ? '' : 'd-none' }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="small fw-bold text-primary">
                                        <i class="bi bi-check-circle-fill me-1"></i>
                                        <span id="selectedHouseholdText">
                                            @if(isset($resident) && $resident->household)
                                                {{ "HH #{$resident->household->household_no} - " . ($resident->household->head ? $resident->household->head->full_name : 'No Head Set') }}
                                            @elseif(old('household_id'))
                                                Household ID: {{ old('household_id') }}
                                            @endif
                                        </span>
                                    </div>
                                    <button type="button" class="btn btn-link text-danger btn-sm p-0" id="clearSelectedHousehold">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('household_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="occupation" class="form-control" 
                            value="{{ old('occupation', $resident?->occupation ?? '') }}" placeholder="e.g. Teacher">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Employment Status</label>
                        <select name="employment_status" class="form-select">
                            <option value="">Select Status</option>
                            @foreach(['employed', 'self_employed', 'unemployed', 'student', 'retired'] as $status)
                                <option value="{{ $status }}" {{ old('employment_status', $resident?->employment_status ?? '') === $status ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Profile Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['active', 'inactive', 'deceased', 'transferred'] as $status)
                                <option value="{{ $status }}" {{ old('status', $resident?->status ?? 'active') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Photo --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Profile Photo</h6>
            </div>
            <div class="card-body text-center">
                @if(isset($resident) && $resident?->profile_photo)
                    <img src="{{ asset('storage/' . $resident?->profile_photo) }}" alt="Current Photo" class="img-thumbnail mb-2" style="max-height: 150px">
                    <div class="form-check form-switch d-flex justify-content-center gap-2 mb-3">
                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="removePhoto">
                        <label class="form-check-label text-danger small" for="removePhoto">Remove current photo</label>
                    </div>
                @endif
                <div class="mb-3">
                    <input class="form-control form-control-sm" type="file" name="profile_photo" accept="image/*">
                    <div class="form-text small">Max 2MB (JPG, PNG)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Special Categories & Voter Info --}}
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">Socio-Economic & Voter Details</h6>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_4ps_beneficiary" value="1" id="switch4ps"
                                {{ old('is_4ps_beneficiary', $resident?->is_4ps_beneficiary ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="switch4ps">4Ps Beneficiary</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_pwd" value="1" id="switchPwd"
                                {{ old('is_pwd', $resident?->is_pwd ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="switchPwd">Person with Disability (PWD)</label>
                        </div>
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" name="is_solo_parent" value="1" id="switchSolo"
                                {{ old('is_solo_parent', $resident?->is_solo_parent ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="switchSolo">Solo Parent</label>
                        </div>
                    </div>
                    <div class="col-md-6 border-start">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_registered" value="1" id="switchVoter"
                                {{ old('is_registered', $resident?->voterInfo?->is_registered ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="switchVoter">Registered Voter</label>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small">Precinct No. <span class="voter-req d-none text-danger">*</span></label>
                                <input type="text" name="precinct_no" id="precinct_no" class="form-control form-control-sm @error('precinct_no') is-invalid @enderror" 
                                    value="{{ old('precinct_no', $resident?->voterInfo?->precinct_no ?? '') }}">
                                @error('precinct_no') <div class="invalid-feedback extra-small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Voter's ID No. <span class="voter-req d-none text-danger">*</span></label>
                                <input type="text" name="voters_id_no" id="voters_id_no" class="form-control form-control-sm @error('voters_id_no') is-invalid @enderror" 
                                    value="{{ old('voters_id_no', $resident?->voterInfo?->voters_id_no ?? '') }}">
                                @error('voters_id_no') <div class="invalid-feedback extra-small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Registration Date</label>
                                <input type="date" name="registration_date" class="form-control form-control-sm" 
                                    value="{{ old('registration_date', isset($resident->voterInfo) && $resident->voterInfo->registration_date ? $resident->voterInfo->registration_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Voting Center</label>
                                <input type="text" name="voting_center" class="form-control form-control-sm"
                                    value="{{ old('voting_center', $resident?->voterInfo?->voting_center ?? '') }}" placeholder="e.g. Central Elementary School">
                            </div>
                        </div>
                    </div>
                </div>
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
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .search-results .item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #eee;
    }
    .search-results .item:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }
    .extra-small { font-size: 0.75rem; }
    .voter-req { font-weight: bold; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Voter Validation Logic
    const switchVoter = document.getElementById('switchVoter');
    const precinctInput = document.getElementById('precinct_no');
    const voterIdInput = document.getElementById('voters_id_no');
    const voterReqs = document.querySelectorAll('.voter-req');

    function toggleVoterRequirements() {
        const isRegistered = switchVoter.checked;
        voterReqs.forEach(el => el.classList.toggle('d-none', !isRegistered));
        if (isRegistered) {
            precinctInput.setAttribute('required', 'required');
            voterIdInput.setAttribute('required', 'required');
        } else {
            precinctInput.removeAttribute('required');
            voterIdInput.removeAttribute('required');
        }
    }

    if (switchVoter) {
        switchVoter.addEventListener('change', toggleVoterRequirements);
        toggleVoterRequirements(); // Initial state
    }

    // Household Search Logic
    const householdSearchInput = document.getElementById('householdSearchInput');
    const householdSearchResults = document.getElementById('householdSearchResults');
    const householdHiddenId = document.getElementById('household_id');
    const selectedHouseholdInfo = document.getElementById('selectedHouseholdInfo');
    const selectedHouseholdText = document.getElementById('selectedHouseholdText');
    const clearSelectedHousehold = document.getElementById('clearSelectedHousehold');

    let searchTimeout = null;

    if (householdSearchInput) {
        householdSearchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value;

            if (query.length < 1) {
                householdSearchResults.classList.add('d-none');
                return;
            }

            householdSearchResults.innerHTML = '<div class="p-2 small text-muted text-center"><div class="spinner-border spinner-border-sm me-2" role="status"></div>Searching...</div>';
            householdSearchResults.classList.remove('d-none');

            searchTimeout = setTimeout(() => {
                fetch(`{{ route('households.search') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        householdSearchResults.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'item';
                                div.innerHTML = `
                                    <div class="fw-bold small text-primary">${item.text}</div>
                                    <div class="text-muted extra-small">${item.address} (Purok ${item.purok})</div>
                                `;
                                div.onclick = () => {
                                    householdHiddenId.value = item.id;
                                    selectedHouseholdText.textContent = item.text;
                                    selectedHouseholdInfo.classList.remove('d-none');
                                    householdSearchResults.classList.add('d-none');
                                    householdSearchInput.parentElement.classList.add('d-none');
                                    householdSearchInput.value = '';
                                };
                                householdSearchResults.appendChild(div);
                            });
                            householdSearchResults.classList.remove('d-none');
                        } else {
                            householdSearchResults.innerHTML = '<div class="p-2 small text-muted text-center">No households found.</div>';
                        }
                    });
            }, 300);
        });

        clearSelectedHousehold.addEventListener('click', () => {
            householdHiddenId.value = '';
            selectedHouseholdInfo.classList.add('d-none');
            householdSearchInput.parentElement.classList.remove('d-none');
            householdSearchInput.value = '';
            householdSearchInput.focus();
        });

        // Close results dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!householdSearchInput.contains(e.target) && !householdSearchResults.contains(e.target)) {
                householdSearchResults.classList.add('d-none');
            }
        });

        // Handle initial state for editing
        if (householdHiddenId.value && householdHiddenId.value !== '') {
            householdSearchInput.parentElement.classList.add('d-none');
        }
    }
});
</script>
@endpush