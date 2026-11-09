@props([
    'formId' => null, // Optional if we want to submit a specific form
    'default' => 10,
    'options' => [10, 25, 50, 100],
    'paramName' => 'limit' // In case a page has multiple tables (e.g. purok show)
])

@php
    $currentLimit = request($paramName, collect($options)->contains(request($paramName)) ? request($paramName) : $default);
@endphp

<div class="d-flex align-items-center gap-2">
    <label for="limit-{{ $paramName }}" class="small text-muted mb-0 d-none d-sm-inline">Show</label>
    <select name="{{ $paramName }}" id="limit-{{ $paramName }}" class="form-select form-select-sm shadow-none" style="width: 70px;" 
        @if($formId) form="{{ $formId }}" @endif
        onchange="this.form ? this.form.submit() : window.location.href = updateQueryStringParameter(window.location.href, '{{ $paramName }}', this.value)">
        @foreach($options as $val)
            <option value="{{ $val }}" {{ $currentLimit == $val ? 'selected' : '' }}>{{ $val }}</option>
        @endforeach
    </select>
    <label class="small text-muted mb-0 d-none d-sm-inline">entries</label>
</div>

@once
<script>
    // Helper to update query string if select is not inside a form
    function updateQueryStringParameter(uri, key, value) {
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.match(re)) {
            return uri.replace(re, '$1' + key + "=" + value + '$2');
        }
        else {
            return uri + separator + key + "=" + value;
        }
    }
</script>
@endonce
