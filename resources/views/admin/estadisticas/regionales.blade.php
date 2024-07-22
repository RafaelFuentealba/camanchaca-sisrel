@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Estadísticas de {{$region[0]->regi_nombre}}</h4>
                            <div class="card-header-action">
                                <a href="{{route('admin.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Iniciativas por Entorno</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartInicitivasEntorno"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Relacionamientos por Entorno</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartRelacionamientoPorEntorno"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Donaciones por Entorno</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartDonacionesPorEntorno"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Participantes por Entorno</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartParticipantesPorEntorno"></canvas>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/chart.min.js') }}"></script>
    <script src="{{ asset('public/js/page/gallery1.js') }}"></script>
    <script src="{{ asset('public/js/admin/estadisticas/regionales.js') }}"></script>
@endsection
