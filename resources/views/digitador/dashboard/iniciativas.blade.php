@extends('digitador.panel_digitador')
@section('contenido')
    <section class="section">

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-icon l-bg-cyan">
                        <i class="fab fa-slack"></i>
                    </div>
                    <a href="{{ route('digitador.iniciativas.index') }}">
                        <div class="card-wrap">
                            <div class="padding-20">
                                <div class="text-right">
                                    <h3 class="font-light mb-0">
                                        <i class="ti-arrow-up text-success"></i> {{ $iniciativas }}
                                    </h3>
                                    <h6 class="text-muted">Iniciativas</h6>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-icon l-bg-green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $participantes }}
                                </h3>
                                <h6 class="text-muted">Participantes</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-icon l-bg-orange">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> 3
                                </h3>
                                <h6 class="text-muted">Organizaciones</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon l-bg-orange">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0" data-toggle="tooltip" data-placement="right" title="{{ '$'.number_format($inversion, 0, ',', '.') }}">
                                    <i class="ti-arrow-up text-success"></i>
                                    @if ($inversion > 1000000)
                                        {{ number_format($inversion / 1000000, 1).' M' }}
                                    @else
                                        {{ '$'.number_format($inversion, 0, ',', '.') }}
                                    @endif
                                </h3>
                                <h6 class="text-muted">Inversión</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-2">
                    <div class="card-icon l-bg-red">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="padding-20">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $invi }}
                                </h3>
                                <h6 class="text-muted">INVI Promedio</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Filtrar por</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 col-md-3 col-lg-3"></div>
                            <div class="col-6 col-md-6 col-lg-6 text-center" id="div-alert-filtros"></div>
                            <div class="col-3 col-md-3 col-lg-3"></div>
                        </div>
                        <form action="{{ route('admin.index.iniciativas') }}" method="GET">
                            <div class="row">
                                {{-- <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Región</label>
                                        <select class="form-control select2" id="region" name="region" onchange="consultarComunas()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($regiones as $region)
                                                <option value="{{ $region->regi_codigo }}" {{ Request::get('region') == $region->regi_codigo ? 'selected' : '' }}>{{ $region->regi_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Comuna</label>
                                        <select class="form-control select2" id="comuna" name="comuna"
                                            onchange="consultarUnidades()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($comunas as $comuna)
                                                <option value="{{ $comuna->comu_codigo }}"
                                                    {{ Request::get('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                    {{ $comuna->comu_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Unidad</label>
                                        <select class="form-control select2" id="unidad" name="unidad">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($unidades as $unidad)
                                                <option value="{{ $unidad->unid_codigo }}" {{ Request::get('unidad') == $unidad->unid_codigo ? 'selected' : '' }}>{{ $unidad->unid_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-12 col-md-12 col-lg-12 text-right mb-4">
                                    <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                            class="fas fa-search"></i> Filtrar</button>
                                    <a href="{{ route('admin.index.iniciativas') }}" type="button"
                                        class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i> Limpiar</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Iniciativas por unidades</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="chartUnidades"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Participantes por entornos</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartEntornos"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Inversión por pilares</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartPilares"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>ODS relacionados</h4>
                            </div>
                            <div class="card-body">
                                <div class="gallery gallery-md">
                                    @foreach ($objetivos as $obj)
                                        <div class="gallery-item" id="img-{{ $obj->obde_codigo }}"
                                            style="filter: saturate(0) opacity(0.40);"
                                            data-image="{{ asset($obj->obde_ruta_imagen) }}" data-toggle="tooltip"
                                            data-placement="top" title=""></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-12 col-md-3 col-lg-3"></div> --}}
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Índice de vinculación INVI</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartInvi"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 col-lg-3"></div>
                </div>

            </div>

        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/chart.min.js') }}"></script>
    <script src="{{ asset('public/js/page/gallery1.js') }}"></script>
    <script src="{{ asset('public/js/home_admin/iniciativas.js') }}"></script>
@endsection
