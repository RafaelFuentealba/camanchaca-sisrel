@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
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
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <div class="row">
                                <div class="card card-statistic-2 l-bg-orange">
                                    <div class="card-icon l-bg-cyan">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="padding-20 text-center">
                                            <h2 style="color: white">{{ $region[0]->regi_nombre }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="card card-statistic-2 l-bg-cyan">
                                    <a href="{{ route('digitador.actividad.listar') }}" style="text-decoration: none">
                                        <div class="card-icon l-bg-green">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="padding-20">
                                                <div class="text-right">
                                                    <h3 class="font-light mb-0" style="color: white">
                                                        <i class="ti-arrow-up text-success"></i> {{ count($actividades) }}
                                                    </h3>
                                                    <h4 style="color: white">Relacionamientos</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>

                            <div class="row">

                                <div class="card card-statistic-2 l-bg-cyan">
                                    <a href="{{ route('digitador.iniciativas.index') }}" style="text-decoration: none">
                                        <div class="card-icon l-bg-red">
                                            <i class="fab fa-slack"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="padding-20">
                                                <div class="text-right">
                                                    <h3 class="font-light mb-0" style="color: white">
                                                        <i class="ti-arrow-up text-success"></i> {{ $iniciativas }}
                                                    </h3>
                                                    <h4 style="color: white">Iniciativas</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                            </div>

                            <div class="row">
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

                                                <p class="lorem" id="informacion" style="font-size: 20px; margin-top:30%">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="map" class="w-auto p-3 sidebar-map" style="height: 15cm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" id="div-regiones">
                <label for="region">Seleccione región</label>
                <select name="region" id="region" class="form-control">
                    <option selected>Seleccione...</option>
                    <option selected value="{{ $region[0]->regi_codigo }}">{{ $region[0]->regi_nombre }}
                    </option>
                </select>
            </div>


        </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/chart.min.js') }}"></script>
    <script src="{{ asset('public/js/mapa.js') }}"></script>
    <script src="{{ asset('public/js/home_digitador/mapa_db.js') }}"></script>
    <script src="{{ asset('public/js/page/gallery1.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
