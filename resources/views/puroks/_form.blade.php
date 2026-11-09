<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="form-label">Purok Name <span class="text-danger">*</span></label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $purok->name ?? '') }}" placeholder="e.g. Purok 1, Purok Maligaya" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="color" class="form-label">Mapping Color <span class="text-danger">*</span></label>
        <div class="d-flex gap-2">
            <input type="color" name="color" id="purok_color" class="form-control form-control-color"
                value="{{ old('color', $purok->color ?? '#0d6efd') }}" title="Choose mapping color">
            <input type="text" id="color_hex" class="form-control form-control-sm font-monospace text-uppercase"
                value="{{ old('color', $purok->color ?? '#0d6efd') }}" style="max-width: 100px;">
        </div>
        @error('color')
            <div class="text-danger extra-small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description (Optional)</label>
        <textarea name="description" id="description" rows="2"
            class="form-control @error('description') is-invalid @enderror"
            placeholder="Provide a brief description of the area...">{{ old('description', $purok->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label d-flex justify-content-between align-items-center">
            <span>Boundary Mapping (Polygon)</span>
            <button type="button" class="btn btn-link link-danger p-0 extra-small" onclick="resetMap()">Reset
                Map</button>
        </label>
        <div id="map" style="height: 400px; border-radius: 8px; border: 1px solid #dee2e6;"></div>
        <input type="hidden" name="coordinates" id="coordinates_input"
            value="{{ old('coordinates', isset($purok) && $purok->coordinates ? json_encode($purok->coordinates) : '') }}">
        <div class="form-text extra-small mt-2">
            <i class="bi bi-info-circle me-1"></i> <strong>Click</strong> to add points, <strong>Drag</strong> to move,
            and <strong>Right-click/Double-click</strong> to remove points.
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .custom-dot {
            width: 12px;
            height: 12px;
            background: #0d6efd;
            border: 2px solid #fff;
            border-radius: 50%;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.3);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map', {
            attributionControl: false
        }).setView([10.3157, 123.8854], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        var points = [];
        var markers = [];
        var polygon = null;
        var coordinatesInput = document.getElementById('coordinates_input');
        var colorPicker = document.getElementById('purok_color');
        var colorHex = document.getElementById('color_hex');

        // SYNC COLOR INPUTS
        colorPicker.addEventListener('input', function () {
            colorHex.value = this.value.toUpperCase();
            updateMapColors();
        });

        colorHex.addEventListener('input', function () {
            if (/^#[0-9A-F]{6}$/i.test(this.value)) {
                colorPicker.value = this.value;
                updateMapColors();
            }
        });

        function updateMapColors() {
            const color = colorPicker.value;
            if (polygon) {
                polygon.setStyle({ color: color });
            }
            // Update marker shadows/dots if wanted, but standard blue is fine
            document.querySelectorAll('.custom-dot').forEach(el => {
                el.style.background = color;
            });
        }

        // INITIAL LOAD
        try {
            var existingCoords = coordinatesInput.value;
            if (existingCoords) {
                points = JSON.parse(existingCoords);
                if (points.length > 0) {
                    // Adjust view to first point
                    map.setView(points[0], 16);

                    points.forEach((pt, i) => {
                        var marker = createMarker(pt[0], pt[1], i);
                        markers.push(marker);
                    });
                    redrawPolygon();
                    updateMapColors(); // Apply saved color
                }
            }
        } catch (e) {
            console.error("Coordinate parsing error:", e);
        }

        // DRAW POLYGON
        function redrawPolygon() {
            if (polygon) {
                map.removeLayer(polygon);
            }

            if (points.length > 0) {
                polygon = L.polygon(points, {
                    color: colorPicker.value,
                    weight: 3,
                    fillOpacity: 0.2
                }).addTo(map);
            }

            // Sync with hidden input
            coordinatesInput.value = points.length > 0 ? JSON.stringify(points) : '';
        }

        // CREATE DRAGGABLE DOT MARKER
        function createMarker(lat, lng, index) {
            var icon = L.divIcon({
                className: '',
                html: '<div class="custom-dot"></div>',
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });

            var marker = L.marker([lat, lng], {
                icon: icon,
                draggable: true
            }).addTo(map);

            marker.pointIndex = index;

            // UPDATE POSITION WHEN DRAGGED
            marker.on('drag', function (e) {
                var pos = marker.getLatLng();
                points[marker.pointIndex] = [pos.lat, pos.lng];
                redrawPolygon();
            });

            // REMOVE ON RIGHT CLICK
            marker.on('contextmenu', function () {
                removeMarker(marker);
            });

            // REMOVE ON DOUBLE CLICK
            marker.on('dblclick', function () {
                removeMarker(marker);
            });

            return marker;
        }

        function removeMarker(marker) {
            var index = marker.pointIndex;
            map.removeLayer(marker);

            points.splice(index, 1);
            markers.splice(index, 1);

            // Re-index
            markers.forEach((m, i) => {
                m.pointIndex = i;
            });

            redrawPolygon();
        }

        // CLICK → ADD POINT
        map.on('click', function (e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            points.push([lat, lng]);
            var marker = createMarker(lat, lng, points.length - 1);
            markers.push(marker);

            redrawPolygon();
        });

        // RESET
        function resetMap() {
            Swal.fire({
                title: 'Confirm Reset',
                text: 'Are you sure you want to clear the entire boundary mapping?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, clear it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    points = [];
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];

                    if (polygon) {
                        map.removeLayer(polygon);
                        polygon = null;
                    }
                    coordinatesInput.value = '';

                    Swal.fire({
                        icon: 'success',
                        title: 'Cleared!',
                        text: 'Boundary mapping has been reset.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
@endpush