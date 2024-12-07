@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="co-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorUnidad'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorUnidad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-2 col-md-2 col-lg-2"></div>
                        <div class="col-xl-8 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Editar organización</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.actualizar.org', $org->orga_codigo) }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Nombre</label> <label for=""
                                                        style="color: red;">*</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-hotel"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="nombre"
                                                            name="nombre" value="{{ old('nombre') ?? @$org->orga_nombre }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('nombre'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('nombre') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Tipo</label> <label for="" style="color: red;">*</label>
                                                    <div class="input-group">

                                                        <select class="form-control form-control-sm select2" name="tiporg" style="width: 100%"
                                                            id="tiporg">
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            @foreach ($tiporg as $tipo)
                                                                <option value="{{ $tipo->ento_codigo }}"
                                                                    {{ $org->ento_codigo == $tipo->ento_codigo ? 'selected' : '' }}>
                                                                    {{ $tipo->ento_nombre }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('tiporg'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('tiporg') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Comuna</label> <label for=""
                                                        style="color: red;">*</label>
                                                    <div class="input-group">

                                                        <select class="select2 form-control form-control-sm" name="comuna" style="width: 100%"
                                                            id="comuna" onchange="cargarCoordenadas()">
                                                            <option value="" selected disabled>Seleccione...</option>
                                                            @foreach ($comunas as $comuna)
                                                                <option value="{{ $comuna->comu_codigo }}"
                                                                    {{ $org->comu_codigo == $comuna->comu_codigo ? 'selected' : '' }}>
                                                                    {{ $comuna->comu_nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('comuna'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('comuna') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div id="miDiv">
                                            <div class="row">
                                                <div class="col-xl-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label>Geolocalización</label>
                                                        <div class="form-group">
                                                            <label>Latitud</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-map-marker-alt"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="lat"
                                                                    name="lat" value="{{ old('lat') ?? @$org->lat }}"
                                                                    autocomplete="off">
                                                            </div>
                                                            @if ($errors->has('lat'))
                                                                <div
                                                                    class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                    <div class="alert-body">
                                                                        <button class="close"
                                                                            data-dismiss="alert"><span>&times;</span></button>
                                                                        <strong>{{ $errors->first('lat') }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Longitud</label>
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <i class="fas fa-map-marker-alt"></i>
                                                                    </div>
                                                                </div>
                                                                <input type="text" class="form-control" id="lng"
                                                                    name="lng" value="{{ old('lng') ?? @$org->lng }}"
                                                                    autocomplete="off">
                                                            </div>
                                                            @if ($errors->has('lng'))
                                                                <div
                                                                    class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                    <div class="alert-body">
                                                                        <button class="close"
                                                                            data-dismiss="alert"><span>&times;</span></button>
                                                                        <strong>{{ $errors->first('lng') }}</strong>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-6 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="descripcion">Descripción</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                            </div>
                                                            <textarea cols="100" rows="8" name="descripcion" id="descripcion"
                                                                placeholder="Ingrese una descripción para la organización" class="formbold-form-input">{{ old('descripcion') ?? @$org->orga_descripcion }}</textarea>
                                                        </div>
                                                        @if ($errors->has('descripcion'))
                                                            <div class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                                <div class="alert-body">
                                                                    <button class="close"
                                                                        data-dismiss="alert"><span>&times;</span></button>
                                                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            {{-- <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Fecha de vinculación</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                        </div>
                                                        <input type="date" class="form-control" id="fecha"
                                                            name="fecha"
                                                            value="{{ old('fecha') ?? @\Carbon\Carbon::parse($org->orga_fecha_vinculo)->format('Y-m-d') }}"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Cantidad de socios registrados</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-users"></i>
                                                            </div>
                                                        </div>
                                                        <input type="number" class="form-control" id="socios"
                                                            name="socios"
                                                            value="{{ old('socios') ?? @$org->orga_cantidad_socios }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('socios'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('socios') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Domicilio (dirección de la sede)</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-home"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="domicilio"
                                                            name="domicilio"
                                                            value="{{ old('domicilio') ?? @$org->orga_domicilio }}"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="vigencia">Estado</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-traffic-light"></i>
                                                            </div>
                                                        </div>
                                                        <select class="form-control form-control-sm" name="vigencia"
                                                            id="vigencia">
                                                            <option value="S"
                                                                {{ $org->orga_vigente == 'S' ? 'selected' : '' }}>Activo
                                                            </option>
                                                            <option value="N"
                                                                {{ $org->orga_vigente == 'N' ? 'selected' : '' }}>Inactivo
                                                            </option>
                                                        </select>
                                                    </div>
                                                    @if ($errors->has('vigencia'))
                                                        <div
                                                            class="alert alert-warning alert-dismissible show fade mt-2 text">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('vigencia') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary waves-effect"> <i
                                                    class="fas fa-undo-alt"></i> Actualizar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-2 col-md-2 col-lg-2"></div>

                        <div class="col-xl-8 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Seleccione la ubicación de la organización</h4>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="height: 350px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <script src="{{ asset('public/js/organizaciones.js') }}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@latest/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('public/css/geocoder.css') }}" />
    <script src="https://unpkg.com/leaflet@latest/dist/leaflet-src.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>
        $(document).ready(function() {
            $("#miDiv").hide();
        });

        const csrftoken = document.head.querySelector(
            "[name~=csrf-token][content]"

        ).content;
        var map = L.map('map').setView([-33.42486299783035, -70.68914200046231], 5);

        var geocoder = L.Control.Geocoder.nominatim({
            geocoderOptions: {
                format: 'json',
                address: '10 Downing Street, London',
                bounds: [
                    [-0.1276, 51.505],
                    [0.1276, 51.506]
                ],

            },
        });

        var searchInput = document.getElementById('domicilio');
        var latInput = document.getElementById('lat');
        var lngInput = document.getElementById('lng');

        var marker;

        var control = L.Control.geocoder({
                defaultMarkGeocode: false,
                collapsed: false,
                placeholder: 'Buscar ubicación...',
                geocodingQueryParams: {
                    countrycodes: 'cl', // Limitar la búsqueda a España
                    limit: 1, // Limitar a un solo resultado
                    polygon_geojson: 1, // Obtener datos de límites geográficos
                    'accept-language': 'es' // Establecer el idioma de respuesta a español
                }
            })

            .on('markgeocode', function(e) {
                var center = e.geocode.center;
                var lat = center.lat;
                var lng = center.lng;
                var calle = e.geocode.properties.address.road || '';
                var ciudad = e.geocode.properties.address.town || '';
                var provincia = e.geocode.properties.address.county || '';
                var region = e.geocode.properties.address.state || '';

                latInput.value = lat;
                lngInput.value = lng;
                if (calle != '') {
                    searchInput.value = `${calle},${ciudad},${provincia},${region}`;
                } else {
                    searchInput.value = `${ciudad},${provincia},${region}`;
                }

                if (marker) {
                    marker.setLatLng(center)
                } else {
                    marker = L.marker(center)
                        .addTo(map)
                }

                map.setView(center, 15);
            })
            .addTo(map);

        map.on('click', function(e) {
            latInput.value = e.latlng.lat;
            lngInput.value = e.latlng.lng;

            var center = e.latlng;

            if (marker) {
                marker.setLatLng(center);
            } else {
                marker = L.marker(center).addTo(map);
            }

            var geocodeResult = geocoder.getResult();
            var street = geocodeResult.properties.address.street || '';
            var houseNumber = geocodeResult.properties.address.house_number || '';
            var number = geocodeResult.properties.address.number || '';

            var popupContent = '<b>' + (street ? street + ' ' : '') + houseNumber + '</b><br>' + geocodeResult
                .properties.address.road + ', ' + geocodeResult.properties.address.city + ', ' + geocodeResult
                .properties.address.state + ', ' + geocodeResult.properties.address.country + '<br>' + geocodeResult
                .properties.postal_code;

            marker.bindPopup(popupContent).openPopup();
        });


        L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);




        function cargarCoordenadas() {
            var comuna = document.getElementById("comuna").value;

            fetch(window.location.origin + "/admin/organizaciones/comuna", {
                method: "POST",
                body: JSON.stringify({
                    comuna: comuna,
                }),
                headers: {
                    "Content-Type": "aplication/json",
                    "X-CSRF-TOKEN": csrftoken,
                },
            }).then(response => {
                return response.json();
            }).then(data => {
                for (let i in data.comuna) {
                    var coords = JSON.parse(data.comuna[i].comu_geoubicacion);
                    map.setView([coords.lat, coords.lng], 14);
                }
            })
        }
    </script>
@endsection
