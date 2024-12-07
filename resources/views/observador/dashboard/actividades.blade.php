@extends('observador.panel_observador')
@section('contenido')
    <section class="section">

        <div class="row">
            <div class="col-4"></div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card mb-3 bg-blue">
                    <div class="row g-0">
                        <div class="col-md-4" style="margin-right:25px;">
                            <img src="{{ asset('public/img/dashboard/acti.png') }}" class="img-fluid rounded-start"
                                style="max-width: 150px;">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                            <div class="card-content">
                                <h5 class="card-title">Número de actividades</h5>
                                <h2 class="mb-3 font-30">{{ count($actividades) }}</h2>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="section-body">
            <div class="row">

                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filtros</h4>
                        </div>

                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="form-group">
                                    <label for="">Región</label>
                                    <select name="regi_codigo" id="regi_codigo" class="form-control select2"
                                        onchange="cargarComunas()">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @foreach ($regiones as $region)
                                            <option value="{{ $region->regi_codigo }}"
                                                {{ Request::get('regi_codigo') == $region->regi_codigo ? 'selected' : '' }}>
                                                {{ $region->regi_nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Comuna</label>
                                    <select name="comu_codigo" id="comu_codigo" class="form-control select2"
                                        onchange="cargarOrganizaciones()">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($comunas as $comuna)
                                            <option value="{{ $comuna->comu_codigo }}"
                                                {{ Request::get('comu_codigo') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                {{ $comuna->comu_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Organización</label>
                                    <select name="orga_codigo" id="orga_codigo" class="form-control select2" onchange="">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($organizaciones as $organizacion)
                                            <option value="{{ $organizacion->orga_codigo }}"
                                                {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                                {{ $organizacion->orga_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label for="">Fecha de realización</label>
                                    <input type="date" class="form-control" name="acti_fecha" id="acti_fecha"
                                        value="{{ old('acti_fecha') }}">
                                </div>

                                <div class="form-group">
                                    <button class="btn btn-primary waves-effect"><i class="fas fa-search"></i>
                                        Filtrar</button>
                                    <a href="{{ route('observador.index.actividades') }}" class="btn btn-primary waves-effect"
                                        type="button"><i class="fas fa-broom"></i> Limpiar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Actividades según avance</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="ActiEstados"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Actividades según avance</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="ActiEstadosP"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="{{ asset('public/js/chart.min.js') }}"></script>
    <script src="{{ asset('public/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/home_observador/actividades.js') }}"></script>
@endsection
