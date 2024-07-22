@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorEvaluacionPrensa'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEvaluacionPrensa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoEvaluacionPrensa'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEvaluacionPrensa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('evpr_valor'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('evpr_valor') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('evpr_vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('evpr_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="col-3"></div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de evaluaciones de prensa</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearEvaluacionPrensa"><i class="fas fa-plus"></i> Nuevo
                                    Registro</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Región</th>
                                            <th>Puntaje</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluacionprensa as $evpr)
                                            <tr>
                                                <td>{{ $evpr->regi_nombre }}</td>
                                                <td>{{ $evpr->evpr_valor }}</td>
                                                <td>
                                                    @if ($evpr->evpr_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $evpr->evpr_rut_mod }}</td>

                                                <td>
                                                    <button type="button" class="btn btn-icon btn-warning"
                                                        data-toggle="modal" data-placement="top"
                                                        data-target="#modalEditarEvaluacionPrensa-{{ $evpr->evpr_codigo }}"
                                                        title="Editar"><i class="fas fa-edit"></i></button>
                                                    <form
                                                        action="{{ route('admin.evaluacionprensa.borrar', $evpr->evpr_codigo) }}"
                                                        method="POST" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger"
                                                            data-toggle="tooltip" title="Eliminar"><i
                                                                class="fas fa-trash"></i></button>
                                                    </form>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- modals de entornos -->
    <div class="modal fade" id="modalCrearEvaluacionPrensa" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva evaluación de prensa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.evaluacionprensa.guardar') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label>Región</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                            </div>
                            <select name="regi_codigo" id="regi_codigo" class="form-control">
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($regiones as $regi)
                                    <option value="{{ $regi->regi_codigo }}"
                                        {{ old('regi_codigo') == $regi->regi_codigo }}>{{ $regi->regi_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Puntaje</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" id="evpr_valor" name="evpr_valor" placeholder="" autocomplete="off" min="0">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($evaluacionprensa as $evpr)
        <div class="modal fade" id="modalEditarEvaluacionPrensa-{{ $evpr->evpr_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarEvaluacionPrensa">Editar evaluación de prensa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.evaluacionprensa.actualizar', $evpr->evpr_codigo) }}"
                            method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Región</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                    </div>

                                    <select name="regi_codigo" id="regi_codigo" class="form-control">
                                        @foreach ($regiones as $regi)
                                            @if ($evpr->regi_codigo == $regi->regi_codigo)
                                                <option value="{{ $regi->regi_codigo }}" selected disabled>{{ $regi->regi_nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Puntaje</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" id="evpr_valor" name="evpr_valor" value="{{ $evpr->evpr_valor }}" autocomplete="off" min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-traffic-light"></i>
                                        </div>
                                    </div>
                                    <select class="form-control " id="evpr_vigencia" name="evpr_vigencia">
                                        <option value="S" {{ $evpr->evpr_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $evpr->evpr_vigente == 'N' ? 'selected' : '' }}>Inactivo
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>

@endsection
