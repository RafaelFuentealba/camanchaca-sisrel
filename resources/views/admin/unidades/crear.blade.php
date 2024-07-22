@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorUnidad'))
                                <div class="alert alert-danger alert-dismissible show fade">
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
                                    <h4>Registro de unidades</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.guardar.unidad') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="form-group col-xl-6">
                                                <label for="unid_nombre">Nombre de la unidad</label> <label for=""
                                                    style="color: red;">*</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-hotel"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" id="unid_nombre"
                                                        name="unid_nombre" value="{{ old('unid_nombre') }}"
                                                        autocomplete="off">
                                                </div>

                                                @if ($errors->has('unid_nombre'))
                                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                        <div class="alert-body">
                                                            <button class="close"
                                                                data-dismiss="alert"><span>&times;</span></button>
                                                            <strong>{{ $errors->first('unid_nombre') }}</strong>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                            <div class="form-group col-xl-6">
                                                <label class="label" for="">Tipo de unidad</label> <label
                                                    for="" style="color: red;">*</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" id="tuni_codigo" name="tuni_codigo" style="width: 100%">
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($tipounidades as $tuni)
                                                            <option value="{{ $tuni->tuni_codigo }}"
                                                                {{ old('tuni_codigo') == $tuni->tuni_codigo ? 'selected' : '' }}>
                                                                {{ $tuni->tuni_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('tuni_codigo'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('tuni_codigo') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group col-xl-6">
                                                <label class="label" for="comu_codigo">Comuna asociada</label> <label
                                                    for="" style="color: red;">*</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" id="comu_codigo" name="comu_codigo" style="width: 100%"
                                                        onchange="cargarCoordenadas()">
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($comunas as $comu)
                                                            <option value="{{ $comu->comu_codigo }}"
                                                                {{ old('comu_codigo') == $comu->comu_codigo ? 'selected' : '' }}>
                                                                {{ $comu->comu_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('comu_codigo'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('comu_codigo') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>

                                            <div class="form-group col-xl-6">
                                                <label class="label" for="divi_codigo">División</label> <label
                                                    for="" style="color: red;">*</label>
                                                <div class="form-group">
                                                    <select class="form-control select2" id="divi_codigo" name="divi_codigo" style="width: 100%"
                                                        onchange="cargarCoordenadas()">
                                                        <option value="" selected disabled>Seleccione...</option>
                                                        @foreach ($divisiones as $divi)
                                                            <option value="{{ $divi->divi_codigo }}"
                                                                {{ old('divi_codigo') == $divi->divi_codigo ? 'selected' : '' }}>
                                                                {{ $divi->divi_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('comu_codigo'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('comu_codigo') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>


                                            {{-- <div class="form-group col-6">
                                                <label for="apellido">Cargo de la unidad</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-clipboard"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" id="unid_nombre_cargo"
                                                        name="unid_nombre_cargo" value="{{ old('unid_nombre_cargo') }}"
                                                        autocomplete="off">
                                                </div>
                                                @if ($errors->has('unid_nombre_cargo'))
                                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                        <div class="alert-body">
                                                            <button class="close"
                                                                data-dismiss="alert"><span>&times;</span></button>
                                                            <strong>{{ $errors->first('unid_nombre_cargo') }}</strong>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div> --}}
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
                                                                    name="lat" value="{{ old('lat') }}"
                                                                    autocomplete="off">
                                                            </div>
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
                                                                    name="lng" value="{{ old('lng') }}"
                                                                    autocomplete="off">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                {{-- <div class="col-6">
                                                    <div class="form-group">
                                                        <label class="label" for="run">Descripción de la unidad</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend"></div>
                                                            <textarea rows="7" cols="60" class="formbold-form-input" placeholder="Ingresar descripción..."
                                                                id="unid_descripcion" name="unid_descripcion" autocomplete="off">{{ old('unid_descripcion') }}</textarea>
                                                        </div>
                                                        @if ($errors->has('unid_descripcion'))
                                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                                <div class="alert-body">
                                                                    <button class="close"
                                                                        data-dismiss="alert"><span>&times;</span></button>
                                                                    <strong>{{ $errors->first('unid_descripcion') }}</strong>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </div>
                                                </div> --}}
                                            </div>
                                        </div>
                                        <div class="row">

                                            {{-- <div class="form-group col-6">
                                                <label for="text">Persona a cargo</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fas fa-user-tie"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" id="unid_responsable"
                                                        name="unid_responsable" value="{{ old('unid_responsable') }}"
                                                        autocomplete="off">
                                                </div>
                                                @if ($errors->has('unid_responsable'))
                                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                        <div class="alert-body">
                                                            <button class="close"
                                                                data-dismiss="alert"><span>&times;</span></button>
                                                            <strong>{{ $errors->first('unid_responsable') }}</strong>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div> --}}

                                        </div>
                                        <div class="text-right">
                                            <a href="{{route('admin.unidades.listar')}}" class="btn btn-warning waves-effect"><i class="fas fa-backward"></i> Volver al listado</a>
                                            <button type="submit" class="btn btn-primary waves-effect">
                                                <i class="fas fa-save"></i> Registrar
                                            </button>
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
                                    <h4>Seleccione la ubicación de la unidad</h4>
                                </div>
                                <div class="card-body">
                                    <div id="map" style="height: 180px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <script src="{{ asset('public/js/unidades.js') }}"></script> --}}
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

        // var searchInput = document.getElementById('domicilio');
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
                // var calle = e.geocode.properties.address.road || '';
                // var ciudad = e.geocode.properties.address.town || '';
                // var provincia = e.geocode.properties.address.county || '';
                // var region = e.geocode.properties.address.state || '';

                latInput.value = lat;
                lngInput.value = lng;
                // if (calle != '') {
                //     searchInput.value = `${calle},${ciudad},${provincia},${region}`;
                // } else {
                //     searchInput.value = `${ciudad},${provincia},${region}`;
                // }

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
    </script>
@endsection
