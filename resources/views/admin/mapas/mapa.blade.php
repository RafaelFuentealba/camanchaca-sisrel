@extends('admin.panel_admin')

@section('contenido')
    <div class="section">
        <div class="section-body">
            <div id="div-alert-undifined" class="col-12 col-md-12 col-lg-12 text-center"></div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h6>Buscar :</h6>
                        </div>
                        <div class="card-body" style="height: 492px;">

                            <div class="form-group">
                                <label for="region">Seleccione región</label>
                                <select name="region" id="region" class="form-control" onchange="cargarComunas()">
                                    <option selected>Seleccione...</option>
                                    @foreach ($regiones as $region)
                                        <option value="{{ $region->regi_codigo }}">{{ $region->regi_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comuna">Seleccione comuna</label>
                                <select name="comunas" id="comunas" class="form-control" onchange="cargarInfoComuna()">
                                    <option value="" selected disabled>Seleccione...</option>
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="tipoorganizacion">Seleccione el entorno</label>
                                <select name="tipo_orga" id="tipo_orga" class="form-control"
                                    onchange="cargarOrganizaciones()">
                                    <option value="" selected disabled>Seleccione...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="organizaciones">Seleccione la organización</label>
                                <select name="organizacion" id="organizacion" class="form-control"
                                    onchange="cargarInfoOrganizacion()">
                                    <option value="" selected disabled>Seleccione...</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="sidebar" class="sidebar collapsed">
                                <div class="sidebar-content">
                                    <div class="sidebar-pane" id="home">
                                        <h1 class="sidebar-header" id="titulo"> </h1>

                                        <p class="lorem" id="informacion"></p>
                                    </div>
                                </div>
                            </div>
                            <div id="map" class="w-auto p-3 sidebar-map" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('public/js/mapa.js') }}"></script>
    <script src="{{ asset('public/js/mapaOrga.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script></script>
@endsection
