@extends('observador.panel_observador')

@section('contenido')
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>


    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
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
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-icon l-bg-cyan">
                                    <i class="fab fa-slack"></i>
                                </div>
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
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-icon l-bg-green">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="padding-20">
                                        <div class="text-right">
                                            <h3 class="font-light mb-0">
                                                <i class="ti-arrow-up text-success"></i> {{ $organizaciones }}
                                            </h3>
                                            <h6 class="text-muted">Organizaciones</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-2">
                                <div class="card-icon l-bg-orange">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="padding-20">
                                        <div class="text-right">
                                            <h3 class="font-light mb-0" data-toggle="tooltip" data-placement="right"
                                                title="{{ '$' . number_format($inversion, 0, ',', '.') }}">
                                                <i class="ti-arrow-up text-success"></i>
                                                @if ($inversion > 1000000)
                                                    {{ number_format($inversion / 1000000, 1) . ' M' }}
                                                @else
                                                    {{ '$' . number_format($inversion, 0, ',', '.') }}
                                                @endif
                                            </h3>
                                            <h6 class="text-muted">Inversión</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <div class="col-3"></div>
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Filtrar por</h4>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('observador.dbgeneral.index') }}" method="GET">
                                        <div class="row">
                                            <div class="col-xl-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="region">Región</label>
                                                    <select name="region" id="region" class="form-control select2"
                                                        style="width: 100%">
                                                        <option value="">Seleccione...</option>
                                                        @forelse ($regiones as $region)
                                                            <option value="{{ $region->regi_codigo }}"
                                                                {{ Request::get('region') == $region->regi_codigo ? 'selected' : '' }}>
                                                                {{ $region->regi_nombre }}</option>
                                                        @empty
                                                            <option value="-1">No existen registros</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="division">División</label>
                                                    <select name="division" id="division" class="form-control select2"
                                                        style="width: 100%">
                                                        <option value="">Seleccione...</option>
                                                        @forelse ($divisiones as $division)
                                                            <option value="{{ $division->divi_codigo }}"
                                                                {{ Request::get('division') == $division->divi_codigo ? 'selected' : '' }}>
                                                                {{ $division->divi_nombre }}</option>
                                                        @empty
                                                            <option value="-1">No existen registros</option>
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-12 col-lg-12 text-right">
                                                <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                        class="fas fa-search"></i> Filtrar</button>
                                                <a href="{{ route('observador.dbgeneral.index') }}" type="button"
                                                    class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                                    Limpiar</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>



                    {{--
                    <div class="row">
                        <div class="col-xl-4 col-lg-6">
                            <div class="card">
                                <div class="card-header">Región</div>
                                <div class="card-body">
                                    <form action="{{ route('observador.dbgeneral.index') }}" method="GET">
                                        <div class="form-group">
                                            <select class="form-control select2" id="region" name="region"
                                                style="width: 100%">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($regiones as $region)
                                                    <option value="{{ $region->regi_codigo }}"
                                                        {{ Request::get('region') == $region->regi_codigo ? 'selected' : '' }}>
                                                        {{ $region->regi_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-xl-6 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Iniciativas</h4>
                                </div>
                                <div class="card-body">
                                    <div id="chartIniciativas"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Organizaciones</h4>
                                </div>
                                <div class="card-body">
                                    <div id="chartOrganizaciones"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Indicar dato para reporte</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('observador.index.reporte') }}" target="_blank"
                                        method="GET">
                                        @csrf
                                        <div class="row">

                                            <div class="col-xl-3 col-md-3 col-lg-6">
                                                <div class="form-group"><label for="regi_codigo">Región</label>
                                                    <select name="regi_codigo" id="regi_codigo" class="select2 form-control" style="width: 100%">
                                                        <option value="">Seleccione...</option>
                                                        @forelse ($regiones as $region)
                                                            <option value="{{$region->regi_codigo}}">{{$region->regi_nombre}}</option>
                                                        @empty
                                                            <option value="">Sin registros disponibles</option>
                                                        @endforelse
                                                    </select>
                                                    </div>
                                            </div>

                                            <div class="col-xl-3 col-md-3 col-lg-6">
                                                <div class="form-group"><label for="comu_codigo">Comuna</label>
                                                    <select name="comu_codigo" id="comu_codigo" class="select2 form-control" style="width: 100%">
                                                        <option value="">Seleccione...</option>
                                                        @forelse ($comunas as $comuna)
                                                            <option value="{{$comuna->comu_codigo}}">{{$comuna->comu_nombre}}</option>
                                                        @empty
                                                            <option value="">Sin registros disponibles</option>
                                                        @endforelse
                                                    </select>
                                                    </div>
                                            </div>

                                            <div class="col-xl-2 col-md-2 col-lg-6">
                                                <div class="form-group"><label for="tipo_unidad">Tipo de unidad</label>
                                                    <select name="tipo_unidad" id="tipo_unidad" class="select2 form-control" style="width: 100%">
                                                        <option value="">Seleccione...</option>
                                                        @forelse ($tipoUnidades as $tipoUnidad)
                                                            <option value="{{$tipoUnidad->tuni_codigo}}">{{$tipoUnidad->tuni_nombre}}</option>
                                                        @empty
                                                            <option value="">Sin registros disponibles</option>
                                                        @endforelse
                                                    </select>
                                                    </div>
                                            </div>

                                            <div class="col-xl-2 col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label for="fecha_inicio">Desde</label>
                                                    <input required class="form-control" type="date"
                                                        name="fecha_inicio" id="fecha_inicio">
                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-md-2 col-lg-2">
                                                <div class="form-group">
                                                    <label for="fecha_final">Hasta</label>
                                                    <input required class="form-control" type="date"
                                                        name="fecha_final" id="fecha_final">
                                                </div>
                                            </div>


                                        </div>

                                        <div class="col-12 col-md-12 col-lg-12 text-right">
                                            <button type="submit" class="btn btn-success mr-1 waves-effect"><i
                                                    class="fas fa-clipboard"></i> Generar reporte</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/page/gallery1.js') }}"></script>

    <script>
        $(document).ready(function() {
            iniciativas();
            organizaciones();

        });

        function pieChart(div_name, data, view_label = true) {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create(div_name, am4charts.PieChart);
            $(`#${div_name}`).css("height", "10cm")
            var ejes = Object.keys(data[0]);
            var labels = ejes[0];
            var valores = ejes[1]
            // Add data
            chart.data = data;


            // Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = valores;
            pieSeries.dataFields.category = labels;
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.labels.template.fill = am4core.color("#9aa0ac");

            // Configura la leyenda (barra lateral)
            if (view_label == true) {
                chart.legend = new am4charts.Legend();
                chart.legend.position = "top";
            }
            // Configura las etiquetas
            pieSeries.ticks.template.disabled = false; // Desactiva las marcas de división
            pieSeries.labels.template.disabled = false; // Habilita las etiquetas
            pieSeries.labels.template.text =
                "{category}: {value.percent.formatNumber('#.0')}%"; // Personaliza el texto de las etiquetas

            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;
        }

        function iniciativas() {
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/observador/dashboard/general/iniciativas',
                data: {
                    region: $('#region').val(),
                    division: $('#division').val()
                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    resPilares = respuesta.resultado[0];
                    iniciativasPilares = respuesta.resultado[1];

                    pilares = [];
                    resPilares.forEach(registro => {
                        pilares.push(registro.pila_nombre);
                    });

                    var pilaresOBJ = pilares.map((nombre, index) => {
                        if (iniciativasPilares[index] > 0) {
                            return {
                                nombre: nombre,
                                cantidad: iniciativasPilares[index]
                            }
                        } else {
                            return null;
                        }
                    }).filter(objeto => objeto !== null);
                    if (Object.keys(pilaresOBJ).length > 0) {
                        pieChart("chartIniciativas", pilaresOBJ)
                    } else {
                        $("#chartIniciativas").html(`<h4>No hay datos disponibles</h4>`)
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function organizaciones() {
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/observador/dashboard/general/organizaciones',
                data: {
                    region: $('#region').val(),
                    division: $('#division').val()
                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    resEntornos = respuesta.resultado[0];
                    totalOrganizaciones = respuesta.resultado[1];

                    entornos = [];
                    resEntornos.forEach(registro => {
                        entornos.push(registro.ento_nombre);
                    });

                    var organizacioneOBJ = entornos.map((nombre, index) => {
                        if (totalOrganizaciones[index] > 0) {
                            return {
                                nombre: nombre,
                                cantidad: totalOrganizaciones[index]
                            }
                        } else {
                            return null;
                        }
                    }).filter(objeto => objeto !== null);

                    if (Object.keys(organizacioneOBJ).length > 0) {
                        pieChart("chartOrganizaciones", organizacioneOBJ, false)
                    } else {
                        $("#chartOrganizaciones").html(`<h4>No hay datos disponibles</h4>`)
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection
