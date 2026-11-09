@extends('layouts.app')

@section('title', $resident->full_name)
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}"
            class="text-decoration-none text-muted">Residents</a></li>
    <li class="breadcrumb-item active text-muted" aria-current="page">Profile</li>
@endsection

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-bold">Resident Profile</h4>
            <p class="text-muted small mb-0">Resident ID #{{ $resident->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <x-back-button url="{{ route('residents.index') }}" />
            <a href="{{ route('residents.edit', $resident) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Edit
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-gender-male"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['male']) }}</div>
                        <div class="text-muted small">Male in this Resident Family</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-gender-female"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['female']) }}</div>
                        <div class="text-muted small">Female in this Resident Family</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info bg-opacity-10 text-info"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['pwds']) }}</div>
                        <div class="text-muted small">PWD in this Resident Family</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-person-heart"></i></div>
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($stats['seniors']) }}</div>
                        <div class="text-muted small">Senior in this Resident Family</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left: Profile Card --}}
        <div class="col-md-4">
            <div class="table-card p-4 text-center mb-3">
                <img src="{{ $resident->profile_photo_url }}" alt="" class="rounded-circle mb-3"
                    style="width:100px;height:100px;object-fit:cover;border:3px solid #e3e6ea">
                <h5 class="fw-bold mb-0">{{ $resident->full_name }}</h5>
                <p class="text-muted small mb-2">{{ $resident->occupation ?? 'No occupation listed' }}</p>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span
                        class="badge {{ $resident->sex === 'male' ? 'badge-male' : ($resident->sex === 'female' ? 'badge-female' : 'badge-other') }}">
                        {{ $resident->sex === 'other' ? 'Other' : ucfirst($resident->sex) }}
                    </span>
                    <span class="badge badge-active">{{ ucfirst($resident->status) }}</span>
                </div>

                <div class="text-start">
                    <div class="row g-2 small">
                        <div class="col-6 text-muted">Age</div>
                        <div class="col-6 fw-semibold">{{ $resident->age }} years</div>

                        <div class="col-6 text-muted">Birthdate</div>
                        <div class="col-6 fw-semibold">{{ $resident->birthdate->format('M d, Y') }}</div>

                        <div class="col-6 text-muted">Civil Status</div>
                        <div class="col-6 fw-semibold">{{ ucfirst($resident->civil_status) }}</div>

                        <div class="col-6 text-muted">Purok</div>
                        <div class="col-6 fw-semibold">{{ $resident->purok->name ?? '—' }}</div>

                        <div class="col-6 text-muted">Household</div>
                        <div class="col-6 fw-semibold">
                            @if($resident->household)
                                <a href="{{ route('households.show', $resident->household) }}" class="text-decoration-none">
                                    {{ $resident->household->household_no }}
                                </a>
                            @else
                                —
                            @endif
                        </div>

                        <div class="col-6 text-muted">Contact</div>
                        <div class="col-6 fw-semibold">{{ $resident->contact_number ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Tags --}}
            @if($resident->is_senior_citizen || $resident->is_pwd || $resident->is_4ps_beneficiary || $resident->is_solo_parent)
                <div class="table-card p-3">
                    <div class="small fw-semibold text-muted mb-2 text-uppercase">Special Categories</div>
                    <div class="d-flex flex-wrap gap-1">
                        @if($resident->is_senior_citizen)
                            <span class="badge bg-warning-subtle text-warning">Senior Citizen</span>
                        @endif
                        @if($resident->is_pwd)
                            <span class="badge bg-info-subtle text-info">PWD</span>
                        @endif
                        @if($resident->is_4ps_beneficiary)
                            <span class="badge" style="background:#f3e8ff;color:#6b21a8">4Ps Beneficiary</span>
                        @endif
                        @if($resident->is_solo_parent)
                            <span class="badge bg-success-subtle text-success">Solo Parent</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Health Profile --}}
            <div class="table-card mb-3 mt-3">
                <div
                    class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light d-flex align-items-center justify-content-between">
                    Health Profile
                    <a href="{{ route('health-records.index', ['search' => $resident->full_name]) }}"
                        class="btn btn-primary btn-extra-small" target="_blank">
                        <i class="bi bi-pencil me-1"></i> Manage
                    </a>
                </div>
                <div class="p-3">
                    <div class="row g-2 small">
                        <div class="col-6 text-muted">Blood Type</div>
                        <div class="col-6 fw-semibold">{{ $resident->healthRecord->blood_type ?? '—' }}</div>
                        <div class="col-6 text-muted">Height (cm)</div>
                        <div class="col-6 fw-semibold">{{ $resident->healthRecord->height ?? '—' }}</div>
                        <div class="col-6 text-muted">Weight (kg)</div>
                        <div class="col-6 fw-semibold">{{ $resident->healthRecord->weight ?? '—' }}</div>
                        <div class="col-12 mt-2 text-muted">Medical Conditions</div>
                        <div class="col-12 fw-semibold">{{ $resident->healthRecord->medical_conditions ?? 'None reported' }}
                        </div>
                        <div class="col-12 mt-2 text-muted">Allergies</div>
                        <div class="col-12 fw-semibold">{{ $resident->healthRecord->allergies ?? 'None reported' }}</div>
                        <div class="col-12 mt-2 text-muted">Medications</div>
                        <div class="col-12 fw-semibold">{{ $resident->healthRecord->medications ?? 'None reported' }}</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Details --}}
        <div class="col-md-8">

            {{-- Employment & Education --}}
            <div class="table-card mb-3">
                <div class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light">
                    Employment & Education
                </div>
                <div class="p-3">
                    <div class="row g-2 small">
                        <div class="col-md-4 text-muted">Employment Status</div>
                        <div class="col-md-8">{{ ucwords(str_replace('_', ' ', $resident->employment_status ?? '—')) }}
                        </div>
                        <div class="col-md-4 text-muted">Education</div>
                        <div class="col-md-8">{{ $resident->educational_attainment ?? '—' }}</div>
                        <div class="col-md-4 text-muted">Religion</div>
                        <div class="col-md-8">{{ $resident->religion ?? '—' }}</div>
                        <div class="col-md-4 text-muted">Citizenship</div>
                        <div class="col-md-8">{{ $resident->citizenship }}</div>
                    </div>
                </div>
            </div>

            {{-- Government IDs --}}
            <div class="table-card mb-3">
                <div class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light">
                    Government ID Numbers
                </div>
                <div class="p-3">
                    <div class="row g-2 small">
                        <div class="col-md-4 text-muted">PhilHealth</div>
                        <div class="col-md-8 font-monospace">{{ $resident->philhealth_no ?? '—' }}</div>
                        <div class="col-md-4 text-muted">SSS</div>
                        <div class="col-md-8 font-monospace">{{ $resident->sss_no ?? '—' }}</div>
                        <div class="col-md-4 text-muted">TIN</div>
                        <div class="col-md-8 font-monospace">{{ $resident->tin_no ?? '—' }}</div>
                    </div>
                </div>
            </div>

            {{-- Voter Info --}}
            <div class="table-card mb-3">
                <div
                    class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light d-flex align-items-center justify-content-between">
                    Voter Information
                    @if($resident->voterInfo?->is_registered)
                        <span class="badge bg-success">Registered Voter</span>
                    @else
                        <span class="badge bg-secondary">Not Registered</span>
                    @endif
                </div>
                <div class="p-3">
                    @if($resident->voterInfo?->is_registered)
                        <div class="row g-2 small">
                            <div class="col-md-4 text-muted">Precinct No.</div>
                            <div class="col-md-8">{{ $resident->voterInfo->precinct_no ?? '—' }}</div>
                            <div class="col-md-4 text-muted">Voter's ID No.</div>
                            <div class="col-md-8 font-monospace">{{ $resident->voterInfo->voters_id_no ?? '—' }}</div>
                            <div class="col-md-4 text-muted">Registration Date</div>
                            <div class="col-md-8">{{ $resident->voterInfo->registration_date?->format('M d, Y') ?? '—' }}</div>
                            <div class="col-md-4 text-muted">Voting Center</div>
                            <div class="col-md-8">{{ $resident->voterInfo->voting_center ?? '—' }}</div>
                        </div>
                    @else
                        <p class="text-muted small mb-0">No voter registration on record.</p>
                    @endif
                </div>
            </div>

            {{-- Family Members --}}
            <div class="table-card mb-3">
                <div
                    class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light d-flex align-items-center justify-content-between">
                    <span>Family Members ({{ $resident->familyMembers->count() }})</span>
                    <a href="{{ route('family-members.show', $resident->id) }}" class="btn btn-primary btn-extra-small"
                        target="_blank">
                        <i class="bi bi-eye me-1"></i> View Family
                    </a>
                </div>
                @if($resident->familyMembers->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Name</th>
                                    <th>Relationship</th>
                                    <th>Household</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resident->familyMembers as $member)
                                    <tr>
                                        <td class="ps-3">
                                            @if($member->relatedResident)
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ $member->relatedResident->profile_photo_url }}" class="avatar"
                                                        style="width:24px;height:24px" alt="">
                                                    <a href="{{ route('residents.show', $member->relatedResident) }}"
                                                        class="text-decoration-none fw-semibold small">
                                                        {{ $member->display_name }}
                                                    </a>
                                                </div>
                                            @else
                                                <span class="small">{{ $member->display_name }}</span>
                                            @endif
                                        </td>
                                        <td class="small text-muted text-capitalize">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($member->relationship)
                                                    <span>{{ $member->relationship }}</span>
                                                    <button type="button"
                                                        class="btn btn-link link-primary p-0 extra-small edit-relationship-btn"
                                                        data-id="{{ $member->id }}" data-name="{{ $member->display_name }}"
                                                        data-relationship="{{ $member->relationship }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                @else
                                                    <button type="button"
                                                        class="btn btn-primary btn-sm extra-small py-0 px-2 edit-relationship-btn"
                                                        data-id="{{ $member->id }}" data-name="{{ $member->display_name }}"
                                                        data-relationship="">
                                                        Connect
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="small">
                                            @if($member->relatedResident?->household)
                                                @if($member->relatedResident->household_id == $resident->household_id)
                                                    <span class="badge bg-success-subtle text-success extra-small">Same Household</span>
                                                @else
                                                    <a href="{{ route('households.show', $member->relatedResident->household) }}"
                                                        class="text-decoration-none extra-small">
                                                        {{ $member->relatedResident->household->household_no }}
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-muted extra-small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('family-members.destroy', $member) }}" method="POST"
                                                class="d-inline swal-confirm" data-message="Remove this Member?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove Member">
                                                    <i class="bi bi-trash small"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="p-4 text-center text-muted small mb-0">No family members connected yet.</p>
                @endif
            </div>

            {{-- Documents --}}
            <div class="table-card">
                <div class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light">
                    Documents ({{ $resident->documents->count() }})
                </div>
                @if($resident->documents->count())
                    <div class="p-3 d-flex flex-wrap gap-2">
                        @foreach($resident->documents as $doc)
                            <a href="{{ $doc->url }}" target="_blank"
                                class="d-flex align-items-center gap-2 p-2 border rounded text-decoration-none text-dark small"
                                style="background:#f8f9fb">
                                <i
                                    class="bi bi-file-earmark-{{ Str::endsWith($doc->file_path, '.pdf') ? 'pdf text-danger' : 'image text-primary' }} fs-5"></i>
                                <div>
                                    <div class="fw-semibold">{{ $doc->document_type_label }}</div>
                                    <div class="text-muted" style="font-size:.72rem">{{ $doc->original_name }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="p-3 text-muted small mb-0">No documents uploaded.</p>
                @endif
            </div>

            {{-- Vaccinations --}}
            <div class="table-card mb-3 mt-3">
                <div
                    class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light d-flex align-items-center justify-content-between">
                    Vaccinations ({{ $resident->vaccinations->count() }})
                    <a href="{{ route('vaccinations.index', ['search' => $resident->full_name]) }}"
                        class="btn btn-primary btn-extra-small" target="_blank">
                        <i class="bi bi-pencil me-1"></i> Manage
                    </a>
                </div>
                @if($resident->vaccinations->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Vaccine</th>
                                    <th>Type</th>
                                    <th>Dose</th>
                                    <th>Date Administered</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resident->vaccinations as $vax)
                                    <tr>
                                        <td class="ps-3 fw-medium small">{{ $vax->vaccine_name }}</td>
                                        <td class="small">{{ $vax->vaccine_type ?? '—' }}</td>
                                        <td class="small">{{ $vax->dose_number ?? '—' }}</td>
                                        <td class="small">{{ $vax->date_administered?->format('M d, Y') ?? '—' }}</td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('vaccinations.destroy', $vax) }}" method="POST"
                                                class="d-inline swal-confirm" data-message="Remove vaccination record?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove"><i
                                                        class="bi bi-trash small"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="p-3 text-muted small mb-0 text-center">No vaccination records.</p>
                @endif
            </div>

            {{-- Medical Assistance --}}
            <div class="table-card mb-3">
                <div
                    class="p-3 border-bottom small fw-semibold text-muted text-uppercase bg-light d-flex align-items-center justify-content-between">
                    Medical Assistance ({{ $resident->medicalAssistances->count() }})
                    <a href="{{ route('medical-assistances.index', ['search' => $resident->full_name]) }}"
                        class="btn btn-primary btn-extra-small" target="_blank">
                        <i class="bi bi-pencil me-1"></i> Manage
                    </a>
                </div>
                @if($resident->medicalAssistances->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th class="ps-3">Assistance</th>
                                    <th>Amount</th>
                                    <th>Provider</th>
                                    <th>Date</th>
                                    <th class="text-end pe-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($resident->medicalAssistances as $assist)
                                    <tr>
                                        <td class="ps-3 fw-medium small">{{ $assist->assistance_type }}</td>
                                        <td class="small">{{ $assist->amount ? '₱' . number_format($assist->amount, 2) : '—' }}</td>
                                        <td class="small">{{ $assist->provider ?? '—' }}</td>
                                        <td class="small">{{ $assist->date_given?->format('M d, Y') ?? '—' }}</td>
                                        <td class="text-end pe-3">
                                            <form action="{{ route('medical-assistances.destroy', $assist) }}" method="POST"
                                                class="d-inline swal-confirm" data-message="Remove medical assistance record?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove"><i
                                                        class="bi bi-trash small"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="p-3 text-muted small mb-0 text-center">No medical assistance records.</p>
                @endif
            </div>

        </div>
    </div>

    {{-- Danger Zone --}}
    <div class="mt-4 p-3 border border-danger rounded" style="background:#fff8f8">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-semibold text-danger small">Archive Resident</div>
                <div class="text-muted small">This soft-deletes the record. It can be restored later.</div>
            </div>
            <form method="POST" action="{{ route('residents.destroy', $resident) }}" class="swal-confirm"
                data-message="Archive this resident?">
                @csrf @method('DELETE')
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-archive me-1"></i> Archive
                </button>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .btn-extra-small {
                padding: 0.15rem 0.5rem;
                font-size: 0.75rem;
                line-height: 1.5;
                border-radius: 0.2rem;
            }

            .search-results {
                position: absolute;
                width: 100%;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                z-index: 1050;
                max-height: 200px;
                overflow-y: auto;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

            .search-results .item .hh-label {
                font-size: 0.7rem;
                padding: 1px 4px;
                border-radius: 3px;
                background: #f1f3f5;
            }

            .search-results .item .hh-label.same-hh {
                background: #d1f2e4;
                color: #0d6832;
            }

            .connection-row {
                position: relative;
                padding-bottom: 1rem;
                margin-bottom: 1rem;
                border-bottom: 1px dashed #eee;
            }

            .connection-row:last-child {
                border-bottom: none;
                margin-bottom: 0;
            }
        </style>
    @endpush

    <!-- Add Family Member Modal -->
    <div class="modal fade" id="addFamilyMemberModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('family-members.store') }}" method="POST">
                @csrf
                <input type="hidden" name="resident_id" value="{{ $resident->id }}">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-primary">Add Family Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="connectionsContainer">
                            <!-- First Row -->
                            <div class="connection-row" data-index="0">
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Search Resident <span
                                                class="text-danger">*</span></label>
                                        <div class="search-container position-relative">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                                <input type="text" class="form-control resident-search shadow-none"
                                                    placeholder="Search by name..." autocomplete="off">
                                            </div>
                                            <input type="hidden" name="connections[0][related_resident_id]"
                                                class="related-id" required>
                                            <div class="search-results d-none"></div>

                                            <div class="selected-info mt-2 p-2 bg-primary-subtle rounded d-none">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="small fw-bold text-primary">
                                                        <i class="bi bi-check-circle-fill me-1"></i>
                                                        <span class="selected-name"></span>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-link text-danger btn-sm p-0 clear-selected">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-sm-10">
                                        <label class="form-label small fw-bold">Relationship <span
                                                class="text-danger">*</span></label>
                                        <select name="connections[0][relationship]"
                                            class="form-select form-select-sm shadow-none" required>
                                            <option value="">Select Relationship</option>
                                            <option value="spouse">Spouse</option>
                                            <option value="child">Child</option>
                                            <option value="father">Father</option>
                                            <option value="mother">Mother</option>
                                            <option value="sibling">Sibling</option>
                                            <option value="grandparent">Grandparent</option>
                                            <option value="grandchild">Grandchild</option>
                                            <option value="aunt">Aunt</option>
                                            <option value="uncle">Uncle</option>
                                            <option value="cousin">Cousin</option>
                                            <option value="in-law">In-law</option>
                                            <option value="partner">Partner</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-2 text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-row d-none">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100" id="addMoreConnections">
                                <i class="bi bi-plus-lg me-1"></i> Add Another Family Member
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Save All Family Members</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Relationship Modal -->
    <div class="modal fade" id="editRelationshipModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <form id="editRelationshipForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-primary">Edit Relationship</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">Relationship to <strong id="editMemberName"></strong>.</p>
                        <div class="mb-3">
                            <label for="edit_rel_select" class="form-label small fw-bold">Relationship <span
                                    class="text-danger">*</span></label>
                            <select name="relationship" id="edit_rel_select" class="form-select form-select-sm shadow-none"
                                required>
                                <option value="">Select Relationship</option>
                                <option value="spouse">Spouse</option>
                                <option value="child">Child</option>
                                <option value="father">Father</option>
                                <option value="mother">Mother</option>
                                <option value="sibling">Sibling</option>
                                <option value="grandparent">Grandparent</option>
                                <option value="grandchild">Grandchild</option>
                                <option value="aunt">Aunt</option>
                                <option value="uncle">Uncle</option>
                                <option value="cousin">Cousin</option>
                                <option value="in-law">In-law</option>
                                <option value="partner">Partner</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Add Modals moved to global index views -->
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const container = document.getElementById('connectionsContainer');
                const addMoreBtn = document.getElementById('addMoreConnections');
                const currentHouseholdId = '{{ $resident->household_id }}';
                const existingFamilyMemberIds = @json($resident->familyMembers->pluck('related_resident_id')->filter()->values());
                const currentResidentId = '{{ $resident->id }}';
                let rowIndex = 1;

                function initSearch(row) {
                    const searchInput = row.querySelector('.resident-search');
                    const searchResults = row.querySelector('.search-results');
                    const hiddenIdInput = row.querySelector('.related-id');
                    const selectedInfo = row.querySelector('.selected-info');
                    const selectedName = row.querySelector('.selected-name');
                    const clearBtn = row.querySelector('.clear-selected');

                    let timeout = null;
                    searchInput.addEventListener('input', function () {
                        clearTimeout(timeout);
                        const query = this.value;

                        if (query.length < 2) {
                            searchResults.classList.add('d-none');
                            return;
                        }

                        searchResults.innerHTML = '<div class="p-2 small text-muted text-center"><div class="spinner-border spinner-border-sm me-2" role="status"></div>Searching...</div>';
                        searchResults.classList.remove('d-none');

                        timeout = setTimeout(() => {


                            // Collect IDs to exclude
                            const excludeIds = [currentResidentId, ...existingFamilyMemberIds];
                            document.querySelectorAll('.related-id').forEach(input => {
                                if (input.value) excludeIds.push(input.value);
                            });

                            const params = new URLSearchParams({
                                q: query,
                                household_id: currentHouseholdId
                            });
                            excludeIds.forEach(id => params.append('exclude_ids[]', id));

                            fetch(`{{ route('residents.search') }}?${params.toString()}`)
                                .then(res => res.json())
                                .then(data => {
                                    searchResults.innerHTML = '';
                                    if (data.length > 0) {
                                        data.forEach(item => {
                                            const div = document.createElement('div');
                                            div.className = 'item d-flex align-items-center justify-content-between';

                                            const nameSpan = document.createElement('span');
                                            nameSpan.textContent = item.text;

                                            const hhSpan = document.createElement('span');
                                            hhSpan.className = 'hh-label ' + (item.is_same_household ? 'same-hh' : '');
                                            hhSpan.textContent = item.household;

                                            div.appendChild(nameSpan);
                                            div.appendChild(hhSpan);

                                            div.onclick = () => {
                                                hiddenIdInput.value = item.id;
                                                selectedName.textContent = item.text;
                                                selectedInfo.classList.remove('d-none');
                                                searchResults.classList.add('d-none');
                                                searchInput.parentElement.classList.add('d-none');
                                                searchInput.value = '';
                                            };
                                            searchResults.appendChild(div);
                                        });
                                        searchResults.classList.remove('d-none');
                                    } else {
                                        searchResults.innerHTML = '<div class="p-2 small text-muted text-center">No residents found.</div>';
                                        searchResults.classList.remove('d-none');
                                    }
                                });
                        }, 300);
                    });

                    clearBtn.addEventListener('click', () => {
                        hiddenIdInput.value = '';
                        selectedInfo.classList.add('d-none');
                        searchInput.parentElement.classList.remove('d-none');
                        searchInput.value = '';
                        searchInput.focus();
                    });

                    // Close results when clicking outside
                    document.addEventListener('click', function (e) {
                        if (!searchInput.parentElement.contains(e.target) && !searchResults.contains(e.target)) {
                            searchResults.classList.add('d-none');
                        }
                    });
                }

                // Initialize first row
                initSearch(container.querySelector('.connection-row'));

                // Add More logic
                addMoreBtn.addEventListener('click', () => {
                    const firstRow = container.querySelector('.connection-row');
                    const newRow = firstRow.cloneNode(true);

                    newRow.dataset.index = rowIndex;

                    // Reset inputs and update names
                    const hiddenId = newRow.querySelector('.related-id');
                    hiddenId.name = `connections[${rowIndex}][related_resident_id]`;
                    hiddenId.value = '';

                    const select = newRow.querySelector('select');
                    select.name = `connections[${rowIndex}][relationship]`;
                    select.value = '';

                    const searchInput = newRow.querySelector('.resident-search');
                    searchInput.parentElement.classList.remove('d-none');
                    searchInput.value = '';

                    const selectedInfo = newRow.querySelector('.selected-info');
                    selectedInfo.classList.add('d-none');

                    const searchResults = newRow.querySelector('.search-results');
                    searchResults.innerHTML = '';
                    searchResults.classList.add('d-none');

                    const removeBtn = newRow.querySelector('.remove-row');
                    removeBtn.classList.remove('d-none');
                    removeBtn.addEventListener('click', () => {
                        newRow.remove();
                    });

                    container.appendChild(newRow);
                    initSearch(newRow);
                    rowIndex++;
                });

                // Edit Relationship Logic
                const editModal = new bootstrap.Modal(document.getElementById('editRelationshipModal'));
                const editForm = document.getElementById('editRelationshipForm');
                const editMemberName = document.getElementById('editMemberName');
                const editRelSelect = document.getElementById('edit_rel_select');

                document.querySelectorAll('.edit-relationship-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const id = this.dataset.id;
                        const name = this.dataset.name;
                        const rel = this.dataset.relationship;

                        editMemberName.textContent = name;
                        editRelSelect.value = rel;
                        editForm.action = `/family-members/${id}`;
                        editModal.show();
                    });
                });
            });
        </script>
    @endpush

@endsection