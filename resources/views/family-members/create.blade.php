@extends('layouts.app')

@section('title', 'Add Family Member')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('residents.index') }}"
            class="text-decoration-none text-muted">Residents</a>
    </li>
    <li class="breadcrumb-item"><a href="{{ route('family-members.index') }}" class="text-decoration-none text-muted">Family
            Members</a></li>
    @if(!empty($preResident))
        <li class="breadcrumb-item"><a href="{{ route('family-members.show', $preResident->id) }}"
                class="text-decoration-none text-muted">{{ $preResident->full_name }}</a></li>
    @endif
    <li class="breadcrumb-item active text-muted" aria-current="page">Add Member</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h4 class="mb-0 fw-bold">Add Family Member</h4>
                    <p class="text-muted small mb-0">Link a primary resident to one or more family members.</p>
                </div>
                <a href="{{ route('family-members.index') }}" class="btn btn-light border">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <form action="{{ route('family-members.store') }}" method="POST" id="familyForm">
                @csrf

                {{-- Primary Resident --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Primary Resident <span class="text-danger">*</span></h6>
                        <div class="search-container position-relative" id="primaryContainer">
                            <div class="input-group mb-2" id="primaryInputGroup">
                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control resident-search shadow-none" id="primarySearchInput"
                                    placeholder="Start typing resident name..." autocomplete="off" required>
                            </div>
                            <input type="hidden" name="resident_id" id="primary_resident_id">
                            <div class="search-results d-none position-absolute w-100 bg-white border rounded shadow-sm"
                                id="primaryResults" style="max-height:250px;overflow-y:auto;top:100%;z-index:1060;"></div>
                            <div class="selected-info p-3 bg-primary-subtle rounded d-none border border-primary-subtle"
                                id="primarySelectedInfo">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="fw-bold text-primary">
                                        <i class="bi bi-check-circle-fill me-2"></i>Selected: <span
                                            id="primarySelectedName"></span>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="clearPrimary">
                                        <i class="bi bi-x-circle me-1"></i> Change
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Members List --}}
                <div id="connectionsWrapper">
                    <div class="connection-item card border-0 shadow-sm mb-3" data-index="0">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0">Related Resident #<span class="item-number">1</span></h6>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-connection d-none">
                                    <i class="bi bi-trash me-1"></i> Remove
                                </button>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Related Resident <span
                                            class="text-danger">*</span></label>
                                    <div class="related-search-container position-relative">
                                        <div class="input-group related-input-group">
                                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                            <input type="text" class="form-control related-search shadow-none"
                                                placeholder="Search resident..." autocomplete="off" required>
                                        </div>
                                        <input type="hidden" name="connections[0][related_resident_id]"
                                            class="related-hidden-id">
                                        <div class="related-results d-none position-absolute w-100 bg-white border rounded shadow-sm"
                                            style="max-height:220px;overflow-y:auto;top:100%;z-index:1060;"></div>
                                        <div
                                            class="related-selected-info p-2 bg-success-subtle rounded d-none border border-success-subtle mt-1">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="small fw-bold text-success">
                                                    <i class="bi bi-check-circle-fill me-1"></i><span
                                                        class="related-selected-name"></span>
                                                </div>
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm py-0 clear-related">
                                                    <i class="bi bi-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Relationship <span
                                            class="text-danger">*</span></label>
                                    <select name="connections[0][relationship]" class="form-select shadow-none" required>
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
                                    <small class="text-muted">Primary is the <em>X</em> of the related resident.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2 mb-4">
                    <button type="button" class="btn btn-outline-primary" id="addMoreBtn">
                        <i class="bi bi-plus-circle me-1"></i> Add Another Related Resident
                    </button>

                    <div class="d-flex gap-2">
                        <a href="{{ route('family-members.index') }}" class="btn btn-light px-4 border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check2 me-1"></i> Save Members
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Dynamic Existing Members Panel (for manually-searched primary) --}}
    @if(!$preResident)
        <div class="row justify-content-center mt-2" id="existingConnectionsPanel" style="display:none!important">
            <div class="col-lg-9">
                <div class="table-card">
                    <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                        <span class="small fw-semibold text-muted text-uppercase">Existing Members for <span
                                id="existingForName"></span></span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle"
                            id="existingCount"></span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Related Resident</th>
                                    <th>Relationship</th>
                                    <th class="pe-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody id="existingConnectionsBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Pass pre-filled resident data to JS --}}
    @if($preResident)
        <script>
            window.__preFillResident = { id: {{ $preResident->id }}, text: @js($preResident->full_name) };
        </script>
    @endif

    {{-- Existing Members Table --}}
    @if($preResident && $existingConnections->isNotEmpty())
        <div class="row justify-content-center mt-2">
            <div class="col-lg-9">
                <div class="table-card">
                    <div class="p-3 border-bottom bg-light d-flex align-items-center justify-content-between">
                        <span class="small fw-semibold text-muted text-uppercase">Existing Members for
                            {{ $preResident->first_name }}</span>
                        <span
                            class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $existingConnections->count() }}
                            found</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3">Related Resident</th>
                                    <th>Relationship</th>
                                    <th class="pe-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($existingConnections as $conn)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $conn->relatedResident->full_name }}</td>
                                        <td>
                                            @if($conn->relationship)
                                                <span class="badge bg-light text-dark border">{{ ucfirst($conn->relationship) }}</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                        <td class="pe-3 text-end">
                                            <div class="d-flex justify-content-end gap-1">
                                                <a href="{{ route('family-members.edit', $conn->id) }}"
                                                    class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                                <form action="{{ route('family-members.destroy', $conn->id) }}" method="POST"
                                                    class="d-inline swal-confirm" data-message="Remove this connection?">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                            class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const RELATIONSHIPS = [
                'spouse', 'child', 'father', 'mother', 'sibling', 'grandparent',
                'grandchild', 'aunt', 'uncle', 'cousin', 'in-law', 'partner', 'other'
            ];

            let primaryResidentId = null;
            let connectionCount = 1;
            let debounce = {};

            /* ─── Auto-fill primary resident if passed from URL ─── */
            if (window.__preFillResident) {
                const res = window.__preFillResident;
                primaryResidentId = String(res.id);
                document.getElementById('primary_resident_id').value = res.id;
                document.getElementById('primarySelectedName').textContent = res.text;
                document.getElementById('primaryInputGroup').classList.add('d-none');
                document.getElementById('primarySelectedInfo').classList.remove('d-none');
                document.getElementById('primarySearchInput').removeAttribute('required');
            }

            /* ─── Primary Resident Search ─── */
            const primaryInput = document.getElementById('primarySearchInput');
            const primaryHidden = document.getElementById('primary_resident_id');
            const primaryResults = document.getElementById('primaryResults');
            const primaryInfo = document.getElementById('primarySelectedInfo');
            const primaryName = document.getElementById('primarySelectedName');
            const primaryGroup = document.getElementById('primaryInputGroup');
            const clearPrimary = document.getElementById('clearPrimary');

            primaryInput.addEventListener('input', function () {
                clearTimeout(debounce['primary']);
                const q = this.value.trim();
                if (q.length < 2) { primaryResults.classList.add('d-none'); return; }

                primaryResults.innerHTML = '<div class="p-3 text-center small text-muted"><span class="spinner-border spinner-border-sm"></span></div>';
                primaryResults.classList.remove('d-none');

                debounce['primary'] = setTimeout(() => {
                    fetch(`{{ route('residents.search') }}?q=${encodeURIComponent(q)}`)
                        .then(r => r.json())
                        .then(data => {
                            primaryResults.innerHTML = '';
                            if (!data.length) {
                                primaryResults.innerHTML = '<div class="p-3 small text-muted text-center">No residents found</div>';
                                return;
                            }
                            data.forEach(res => {
                                const div = document.createElement('div');
                                div.className = 'p-3 border-bottom small';
                                div.style.cursor = 'pointer';
                                div.innerHTML = `<strong>${res.text}</strong>`;
                                div.onmouseover = () => div.style.background = '#f8f9fa';
                                div.onmouseout = () => div.style.background = '';
                                div.onclick = () => {
                                    primaryResidentId = String(res.id);
                                    primaryHidden.value = res.id;
                                    primaryName.textContent = res.text;
                                    primaryGroup.classList.add('d-none');
                                    primaryInfo.classList.remove('d-none');
                                    primaryResults.classList.add('d-none');
                                    primaryInput.removeAttribute('required');
                                    // Refresh all related dropdowns to exclude this resident
                                    refreshRelatedDropdowns();
                                    // Load existing connections dynamically
                                    loadExistingConnections(res.id, res.text);
                                };
                                primaryResults.appendChild(div);
                            });
                        });
                }, 300);
            });

            clearPrimary.addEventListener('click', () => {
                primaryResidentId = null;
                primaryHidden.value = '';
                primaryName.textContent = '';
                primaryInfo.classList.add('d-none');
                primaryGroup.classList.remove('d-none');
                primaryInput.setAttribute('required', 'required');
                primaryInput.value = '';
                primaryInput.focus();
                refreshRelatedDropdowns();
                // Hide dynamic connections panel
                const panel = document.getElementById('existingConnectionsPanel');
                if (panel) panel.style.setProperty('display', 'none', 'important');
            });

            document.addEventListener('click', e => {
                if (!document.getElementById('primaryContainer').contains(e.target)) {
                    primaryResults.classList.add('d-none');
                }
            });

            /* ─── Add More Members ─── */
            document.getElementById('addMoreBtn').addEventListener('click', () => {
                const idx = connectionCount++;
                const wrapper = document.getElementById('connectionsWrapper');
                const tpl = buildConnectionCard(idx);
                wrapper.insertAdjacentHTML('beforeend', tpl);
                initRelatedSearch(wrapper.lastElementChild);
                updateRemoveButtons();
            });

            // Init first row
            initRelatedSearch(document.querySelector('.connection-item'));

            /* ─── Build connection card HTML ─── */
            function buildConnectionCard(idx) {
                const opts = RELATIONSHIPS.map(r => `<option value="${r}">${r.charAt(0).toUpperCase() + r.slice(1)}</option>`).join('');
                return `
                                                                                                                                                    <div class="connection-item card border-0 shadow-sm mb-3" data-index="${idx}">
                                                                                                                                                        <div class="card-body p-4">
                                                                                                                                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                                                                                                                                <h6 class="fw-bold mb-0">Related Resident #<span class="item-number">${idx + 1}</span></h6>
                                                                                                                                                                <button type="button" class="btn btn-sm btn-outline-danger remove-connection">
                                                                                                                                                                    <i class="bi bi-trash me-1"></i> Remove
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                            <div class="row g-3">
                                                                                                                                                                <div class="col-md-6">
                                                                                                                                                                    <label class="form-label small fw-bold">Relationship <span class="text-danger">*</span></label>
                                                                                                                                                                    <select name="connections[${idx}][relationship]" class="form-select shadow-none" required>
                                                                                                                                                                        <option value="">Select Relationship</option>
                                                                                                                                                                        ${opts}
                                                                                                                                                                    </select>
                                                                                                                                                                    <small class="text-muted">Primary is the <em>X</em> of the related resident.</small>
                                                                                                                                                                </div>
                                                                                                                                                                <div class="col-md-6">
                                                                                                                                                                    <label class="form-label small fw-bold">Related Resident <span class="text-danger">*</span></label>
                                                                                                                                                                    <div class="related-search-container position-relative">
                                                                                                                                                                        <div class="input-group related-input-group">
                                                                                                                                                                            <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                                                                                                                                                            <input type="text" class="form-control related-search shadow-none" placeholder="Search resident..." autocomplete="off" required>
                                                                                                                                                                        </div>
                                                                                                                                                                        <input type="hidden" name="connections[${idx}][related_resident_id]" class="related-hidden-id">
                                                                                                                                                                        <div class="related-results d-none position-absolute w-100 bg-white border rounded shadow-sm" style="max-height:220px;overflow-y:auto;top:100%;z-index:1060;"></div>
                                                                                                                                                                        <div class="related-selected-info p-2 bg-success-subtle rounded d-none border border-success-subtle mt-1">
                                                                                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                                                                                <div class="small fw-bold text-success">
                                                                                                                                                                                    <i class="bi bi-check-circle-fill me-1"></i><span class="related-selected-name"></span>
                                                                                                                                                                                </div>
                                                                                                                                                                                <button type="button" class="btn btn-outline-danger btn-sm py-0 clear-related">
                                                                                                                                                                                    <i class="bi bi-x"></i>
                                                                                                                                                                                </button>
                                                                                                                                                                            </div>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </div>`;
            }

            /* ─── Init related search on a card ─── */
            function initRelatedSearch(card) {
                const searchInput = card.querySelector('.related-search');
                const hiddenId = card.querySelector('.related-hidden-id');
                const results = card.querySelector('.related-results');
                const selectedInfo = card.querySelector('.related-selected-info');
                const selectedName = card.querySelector('.related-selected-name');
                const inputGroup = card.querySelector('.related-input-group');
                const clearBtn = card.querySelector('.clear-related');
                const removeBtn = card.querySelector('.remove-connection');

                const key = 'related_' + card.dataset.index;

                searchInput.addEventListener('input', function () {
                    clearTimeout(debounce[key]);
                    const q = this.value.trim();
                    hiddenId.value = '';
                    this.setAttribute('required', 'required');

                    if (q.length < 2) { results.classList.add('d-none'); return; }

                    results.innerHTML = '<div class="p-3 text-center small text-muted"><span class="spinner-border spinner-border-sm"></span></div>';
                    results.classList.remove('d-none');

                    debounce[key] = setTimeout(() => {
                        fetch(`{{ route('residents.search') }}?q=${encodeURIComponent(q)}`)
                            .then(r => r.json())
                            .then(data => {
                                // Filter out primary resident
                                const filtered = primaryResidentId
                                    ? data.filter(r => String(r.id) !== primaryResidentId)
                                    : data;

                                results.innerHTML = '';
                                if (!filtered.length) {
                                    results.innerHTML = '<div class="p-3 small text-muted text-center">No residents found</div>';
                                    return;
                                }
                                filtered.forEach(res => {
                                    const div = document.createElement('div');
                                    div.className = 'p-3 border-bottom small';
                                    div.style.cursor = 'pointer';
                                    div.innerHTML = `<strong>${res.text}</strong>`;
                                    div.onmouseover = () => div.style.background = '#f8f9fa';
                                    div.onmouseout = () => div.style.background = '';
                                    div.onclick = () => {
                                        hiddenId.value = res.id;
                                        selectedName.textContent = res.text;
                                        inputGroup.classList.add('d-none');
                                        results.classList.add('d-none');
                                        selectedInfo.classList.remove('d-none');
                                        searchInput.value = '';
                                        searchInput.removeAttribute('required');
                                    };
                                    results.appendChild(div);
                                });
                            });
                    }, 300);
                });

                clearBtn.addEventListener('click', () => {
                    hiddenId.value = '';
                    selectedName.textContent = '';
                    selectedInfo.classList.add('d-none');
                    inputGroup.classList.remove('d-none');
                    searchInput.setAttribute('required', 'required');
                    searchInput.value = '';
                    searchInput.focus();
                });

                if (removeBtn) {
                    removeBtn.addEventListener('click', () => {
                        card.remove();
                        renumberCards();
                        updateRemoveButtons();
                    });
                }

                // Close on outside click
                document.addEventListener('click', e => {
                    if (!card.contains(e.target)) results.classList.add('d-none');
                });
            }

            /* ─── Renumber cards after removal ─── */
            function renumberCards() {
                document.querySelectorAll('.connection-item').forEach((card, i) => {
                    card.dataset.index = i;
                    card.querySelector('.item-number').textContent = i + 1;
                    // Update field names
                    const rel = card.querySelector('select[name^="connections"]');
                    const hid = card.querySelector('input[name^="connections"]');
                    if (rel) rel.name = `connections[${i}][relationship]`;
                    if (hid) hid.name = `connections[${i}][related_resident_id]`;
                });
            }

            /* ─── Show/hide remove buttons ─── */
            function updateRemoveButtons() {
                const cards = document.querySelectorAll('.connection-item');
                cards.forEach(card => {
                    const btn = card.querySelector('.remove-connection');
                    if (btn) btn.classList.toggle('d-none', cards.length === 1);
                });
            }

            /* ─── Clear selected related residents when primary changes ─── */
            function refreshRelatedDropdowns() {
                // Nothing to force-clear; existing selections are kept but new searches filter correctly
            }

            /* ─── Load existing connections dynamically via AJAX ─── */
            function loadExistingConnections(residentId, residentName) {
                const panel = document.getElementById('existingConnectionsPanel');
                const body = document.getElementById('existingConnectionsBody');
                const nameEl = document.getElementById('existingForName');
                const countEl = document.getElementById('existingCount');
                if (!panel) return;

                fetch(`/family-members/${residentId}/connections`)
                    .then(r => r.json())
                    .then(data => {
                        if (!data.length) {
                            panel.style.setProperty('display', 'none', 'important');
                            return;
                        }

                        nameEl.textContent = residentName;
                        countEl.textContent = data.length + ' found';
                        body.innerHTML = data.map(c => `
                                                                                                                                                    <tr>
                                                                                                                                                        <td class="ps-3 fw-semibold">${c.related_name}</td>
                                                                                                                                                        <td><span class="badge bg-light text-dark border">${c.relationship ? c.relationship.charAt(0).toUpperCase() + c.relationship.slice(1) : 'Unknown'}</span></td>
                                                                                                                                                        <td class="pe-3 text-end">
                                                                                                                                                            <div class="d-flex justify-content-end gap-1">
                                                                                                                                                                <a href="${c.edit_url}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                                                                                                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteConnection(this, '${c.destroy_url}')">
                                                                                                                                                                    <i class="bi bi-trash"></i>
                                                                                                                                                                </button>
                                                                                                                                                            </div>
                                                                                                                                                        </td>
                                                                                                                                                    </tr>
                                                                                                                                                `).join('');

                        panel.style.removeProperty('display');
                    })
                    .catch(() => {
                        panel.style.setProperty('display', 'none', 'important');
                    });
            }
        });

        /* ─── Delete a connection inline ─── */
        function deleteConnection(btn, url) {
            if (!confirm('Remove this family connection?')) return;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/x-www-form-urlencoded' }, body: '_method=DELETE' })
                .then(r => {
                    if (r.ok || r.redirected) {
                        const row = btn.closest('tr');
                        row.remove();
                        const body = document.getElementById('existingConnectionsBody');
                        const countEl = document.getElementById('existingCount');
                        const panel = document.getElementById('existingConnectionsPanel');
                        const remaining = body.querySelectorAll('tr').length;
                        countEl.textContent = remaining + ' found';
                        if (!remaining) panel.style.setProperty('display', 'none', 'important');
                    }
                });
        }
    </script>
@endpush