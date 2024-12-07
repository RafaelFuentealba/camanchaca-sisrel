@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-header">
                                <h4>Estadísticas Nacionales</h4>
                                <div class="card-header-action">
                                    <a href="{{route('admin.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Iniciativas por Región</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartInicitivasRegion"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Participantes por Región</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartParticipantesRegion"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Relacionamientos por Región</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartRealacionamientosRegion"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Iniciativas por Pilares</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartInicitivasPilares"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Donaciones por Región</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartDonacionesRegion"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4>Índice de Vinculación</h4>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="chartIndiceVinculacion"></canvas>
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
    <script src="{{ asset('public/js/admin/estadisticas/nacionales.js') }}"></script>
@endsection
