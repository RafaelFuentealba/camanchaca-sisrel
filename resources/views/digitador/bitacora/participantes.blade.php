@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('exitoActividad'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoActividad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-3 col-lg-3"></div>
                        <div class="col-xl-6 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ $actividad->orga_nombre }} - {{ $actividad->acti_nombre }}</h4>
                                    <div class="card-header-action">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearParticipante"><i class="fas fa-user"></i>
                                            Agregar participante
                                        </button>
                                        <input type="hidden" id="codigo_actividad" name="codigo_actividad" value="{{ $actividad->acti_codigo }}">
                                    </div>
                                </div>
                                @if (!empty($dirigentes))
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Dirigentes</label> <i data-toggle="tooltip" data-placement="right" title="Seleccione un dirigente para agregar al listado de participantes" class="fas fa-info-circle"></i>
                                            <select class="form-control select2" id="diri_codigo" name="diri_codigo" onchange="agregarDirigente()">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @foreach ($dirigentes as $diri)
                                                    <option value="{{ $diri->diri_codigo }}">{{ $diri->diri_nombre }} {{ $diri->diri_apellido }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-3 col-lg-3"></div>
                        <div class="col-xl-6 col-md-6 col-lg-6 text-center" id="div-alert-participantes"></div>
                        <div class="col-xl-3 col-md-3 col-lg-3"></div>

                        <div class="col-xl-3 col-md-3 col-lg-3"></div>
                        <div class="col-xl-6 col-md-6 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Participantes</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-md">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th>Eliminar</th>
                                            </tr>
                                            <tbody id="body-tabla-participantes">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-xl-12 col-md-12 col-lg-12 text-right">
                                            <a href="{{ route('digitador.actividad.editar', $actividad->acti_codigo) }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-chevron-left"></i> Volver al paso anterior</a>
                                            <a href="{{ route('digitador.actividad.listar') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-check"></i> Finalizar</a>
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

    <div class="modal fade" id="modalCrearParticipante" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Añadir participante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nombre</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="far fa-id-card"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="asis_nombre" name="asis_nombre" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Apellido</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="far fa-id-card"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="asis_apellido" name="asis_apellido" placeholder="" autocomplete="off">
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary waves-effect" onclick="agregarParticipantes()">Agregar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/digitador/bitacora/bitacora.js') }}"></script>
@endsection
