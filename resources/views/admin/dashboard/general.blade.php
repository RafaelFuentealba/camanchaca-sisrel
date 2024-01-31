@extends('admin.panel_admin')

@section('contenido')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorIniciativa'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorIniciativa') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoEvaluacion'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoEvaluacion') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('errorEliminar'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorEliminar') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoEliminar'))
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
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
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
                        <div class="card card-statistic-1">
                            <div class="card-icon l-bg-orange">
                                <i class="fas fa-users"></i>
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
                        <div class="card card-statistic-1">
                            <div class="card-icon l-bg-cyan">
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
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon l-bg-orange">
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            <i class="ti-arrow-up text-success"></i> {{ $ods }}
                                        </h3>
                                        <h6 class="text-muted">ODS relacionados</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Iniciativas</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartIniciativas"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Organizaciones</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartOrganizaciones"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Inversión</h4>
                            </div>
                            <div class="card-body">
                                <canvas id="chartInversion"></canvas>
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
                                        @if (in_array($obj->obde_codigo, $odsvinculados))
                                            <div class="gallery-item" data-image="{{ asset($obj->obde_ruta_imagen) }}" data-title="{{ $obj->obde_nombre }}"></div>
                                        @else
                                            <div class="gallery-item" style="filter: saturate(0) opacity(0.40);" data-image="{{ asset($obj->obde_ruta_imagen) }}" data-title="{{ $obj->obde_nombre }}"></div>
                                        @endif
                                    @endforeach
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
<script src="{{ asset('public/js/page/gallery1.js') }}"></script>

<script>
    $(document).ready(function() {
        iniciativas();
        organizaciones();
        inversion();
    });

    function iniciativas() {
        $.ajax({
            type: 'GET',
            url: window.location.origin+'/admin/dashboard/general/iniciativas',
            data: { },
            success: function(resConsultar) {
                respuesta = JSON.parse(resConsultar);
                resPilares = respuesta.resultado[0];
                iniciativasPilares = respuesta.resultado[1];

                pilares = [];
                resPilares.forEach(registro => {
                    pilares.push(registro.pila_nombre);
                });

                let ctx = document.getElementById("chartIniciativas").getContext('2d');
                let myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: pilares,
                        datasets: [{
                            label: 'Iniciativas',
                            data: iniciativasPilares,
                            borderWidth: 2,
                            backgroundColor: '#6777ef',
                            borderColor: '#6777ef',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ffffff',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                barThickness : 25,
                                gridLines: {
                                    drawBorder: false,
                                    color: '#f2f2f2',
                                },
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 150,
                                    fontColor: "#9aa0ac",
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    display: false
                                },
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                    }
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function organizaciones() {
        $.ajax({
            type: 'GET',
            url: window.location.origin+'/admin/dashboard/general/organizaciones',
            data: { },
            success: function(resConsultar) {
                respuesta = JSON.parse(resConsultar);
                resEntornos = respuesta.resultado[0];
                totalOrganizaciones = respuesta.resultado[1];

                entornos = [];
                resEntornos.forEach(registro => {
                    entornos.push(registro.ento_nombre);
                });

                let ctx = document.getElementById("chartOrganizaciones").getContext('2d');
                let myChart = new Chart(ctx, {
                    type: 'horizontalBar',
                    data: {
                        labels: entornos,
                        datasets: [{
                            label: 'Organizaciones',
                            data: totalOrganizaciones,
                            borderWidth: 2,
                            backgroundColor: '#6777ef',
                            borderColor: '#6777ef',
                            borderWidth: 2.5,
                            pointBackgroundColor: '#ffffff',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                barThickness : 25,
                                gridLines: {
                                    drawBorder: false,
                                    color: '#f2f2f2',
                                },
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 150,
                                    fontColor: "#9aa0ac",
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    display: false
                                },
                                gridLines: {
                                    display: false
                                }
                            }]
                        },
                    }
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    function inversion() {
        $.ajax({
            type: 'GET',
            url: window.location.origin+'/admin/dashboard/general/inversion',
            data: { },
            success: function(resConsultar) {
                respuesta = JSON.parse(resConsultar);
                resPilares = respuesta.resultado[0];
                resInversion = respuesta.resultado[1];
                totalInversion = resInversion.reduce((partialSum, a) => partialSum + a, 0);

                pilares = [];
                resPilares.forEach(registro => {
                    pilares.push(registro.pila_nombre);
                });

                inversion = []
                resInversion.forEach(registro => {
                    inversion.push(Math.round((registro/totalInversion)*100));
                });

                let ctx = document.getElementById("chartInversion").getContext('2d');
                let myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: inversion,
                            backgroundColor: [
                                '#6777ef',
                                '#ffa426',
                                '#63ed7a',
                                '#fc544b',
                                '#191d21',
                            ],
                            label: 'Inversión %'
                        }],
                        labels: pilares,
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'right',
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data, inversion) {
                                    return data['labels'][tooltipItem['index']] + ': ' + data['datasets'][0]['data'][tooltipItem['index']] + '%';
                                }
                            }
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>

@endsection
