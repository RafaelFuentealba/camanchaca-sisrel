@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorIniciativa'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorIniciativa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoEvaluacion'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEvaluacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorEliminar'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEliminar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoEliminar'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEliminar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            @foreach ($regiones as $region)
                                <div class="row" onclick="ocultarRegi(this)" id="{{ $region->regi_codigo }}">
                                    <div class="card card-statistic-2 l-bg-orange">
                                        <div class="card-icon l-bg-cyan">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="padding-20 text-center">
                                                <h2 style="color: white">{{ $region->regi_nombre }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="row" id="div-iniciativas">

                                <div class="card card-statistic-2 l-bg-cyan">
                                    {{-- <a href="{{ route('admin.iniciativas.index') }}" style="text-decoration: none"> --}}

                                        <div class="card-icon l-bg-red">
                                            <i class="fab fa-slack"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="padding-20">
                                                <div class="text-right">
                                                    <h3 class="font-light mb-0" style="color: white">
                                                        <i class="ti-arrow-up text-success"></i>
                                                        <h2 style="color: white" id="c_iniciativas">{{ $iniciativas }}</h2>
                                                    </h3>
                                                    <h4 style="color: white" id="h4-iniciativas">Iniciativas</h4>
                                                </div>
                                            </div>
                                        </div>
                                    {{-- </a> --}}
                                </div>

                            </div>

                            <div class="row" id="div-realacionamiento">

                                <div class="card card-statistic-2 l-bg-cyan">

                                    <div class="card-icon l-bg-green">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="padding-20">
                                            <div class="text-right">
                                                <h3 class="font-light mb-0" style="color: white">
                                                    {{-- <i class="ti-arrow-up text-success"></i> {{ count($actividades) }} --}}
                                                </h3>
                                                <h4 style="color: white">Bitácoras</h4>
                                            </div>
                                            <ul style="color: white; font-size: 20px;margin-left: 20%">
                                                <a href="{{ route('admin.actividad.listar') }}"
                                                    style="text-decoration: none;color: white">
                                                    <li>
                                                        Relacionamiento
                                                    </li>
                                                </a>
                                                <a href="{{ route('admin.donaciones.listar') }}"
                                                    style="text-decoration: none;color: white">

                                                    <li>Donaciones</li>
                                                </a>
                                            </ul>

                                        </div>
                                    </div>

                                </div>

                            </div>
                            <div class="row" id="div-estadisticas">
                                <div class="card card-statistic-2 l-bg-cyan">
                                    <div class="card-icon l-bg-red">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="padding-20">
                                            <div class="text-right">
                                                <h3 class="font-light mb-0" style="color: white">
                                                    {{-- <i class="ti-arrow-up text-success"></i> {{ $iniciativas }} --}}
                                                </h3>
                                                <h4 style="color: white">Estadísticas</h4>
                                            </div>
                                            <ul style="color: white; font-size: 20px;margin-left: 20%">
                                                <a href="{{ route('admin.estadisticas.nacionales') }}"
                                                    style="text-decoration: none;color: white">
                                                    <li>Nacional</li>
                                                </a>
                                            </ul>
                                            <ul style="color: white; font-size: 20px;margin-left: 20%" id="ul-estadisticas">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="div-comunas">
                                <div class="card card-statistic-2 l-bg-orange">
                                    <div class="card-icon l-bg-cyan">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="padding-20 text-center">
                                            <h2 style="color: white">Comunas</h2>
                                        </div>
                                        <br>
                                        <ul id="comunas" style="font-size: 20px; margin-left: 37%"></ul>
                                    </div>
                                    <div class="row" style="margin-bottom: 3% ">
                                        <div class="col-12 col-md-12 col-lg-12">
                                            <div class="text-right">
                                                <button class="btn btn-primary mr-4 waves-effect" onclick="mostrarRegi()"><i
                                                        class="fas fa-"></i>Seleccionar región</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="div-image">
                                <div class="card card-statistic-2">
                                    <img src="{{ asset('public/img/camanchaca.png') }}" style="width: 100%">
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

                                                <p class="lorem" id="informacion"
                                                    style="font-size: 20px; margin-top:30%">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="map" class="w-auto p-3 sidebar-map" style="height: 15cm;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/chart.min.js') }}"></script>
    <script src="{{ asset('public/js/mapa.js') }}"></script>
    <script src="{{ asset('public/js/home_admin/mapa_db.js') }}"></script>
    <script src="{{ asset('public/js/page/gallery1.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
