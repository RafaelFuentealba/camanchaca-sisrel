@extends('superadmin.panel')
@section('contenido-principal')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorTipoEv'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorTipoEv') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoTipoEv'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoTipoEv') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('tiev_nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('tiev_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('tiev_vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('tiev_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if ($errors->has('tiev_descripcion'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('tiev_descripcion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado tipos de Evaluación</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCreartipoEvaluacion"><i class="fas fa-plus"></i> Nuevo tipo</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <tbody>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Vigencia</th>
                                            <th>Creado el dia</th>
                                            <th>Actualizado el dia</th>
                                            <th>Modificado por</th>
                                            <th>Editar</th>
                                            <th>Borrar</th>
                                        </tr>
                                        @foreach ($tipoEvaluacion as $tiev)
                                            <tr>
                                                <td>{{ $tiev->tiev_nombre }}</td>
                                                <td>{{ $tiev->tiev_descripcion }}</td>
                                                <td>
                                                    @if ($tiev->tiev_vigente == 'S')
                                                        <p class="badge badge-success"><strong>Activo</strong></p>
                                                    @else
                                                        <p class="badge badge-danger"><strong>Inactivo</strong></p>
                                                    @endif
                                                </td>
                                                <td>{{ $tiev->tiev_creado }}</td>
                                                <td>{{ $tiev->tiev_actualizado }}</td>


                                                <td>{{ $tiev->tiev_rut_mod }}</td>

                                                <td>
                                                    <button type="button" class="btn btn-icon btn-info" data-toggle="modal"
                                                        data-target="#modalEditartipoEvaluacion-{{ $tiev->tiev_codigo }}"
                                                        title="Actualizar"><i class="fas fa-edit"></i></button>
                                                </td>
                                                <td>
                                                    <form
                                                        action="{{ route('superadmin.evaluacion.borrar', $tiev->tiev_codigo) }}"
                                                        method="POST">
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

    <div class="modal fade" id="modalCreartipoEvaluacion" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo tipo de Evaluación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('superadmin.evaluacion.crear') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tiev_nombre" name="tiev_nombre"
                                    placeholder="" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <div class="input-group">
                                <textarea class="form-control" id="tiev_descripcion" name="tiev_descripcion" placeholder="" autocomplete="off"
                                    cols="30" rows="10"></textarea>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($tipoEvaluacion as $tiev)
        <div class="modal fade" id="modalEditartipoEvaluacion-{{ $tiev->tiev_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditartipoEvaluacion" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditartipoEvaluacion">Editar categoría de clima</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('superadmin.evaluacion.actualizar', $tiev->tiev_codigo) }}"
                            method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-clipboard-list"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="tiev_nombre" name="tiev_nombre"
                                        value="{{ $tiev->tiev_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <div class="input-group">
                                    <textarea class="form-control" id="tiev_descripcion" name="tiev_descripcion" placeholder="" autocomplete="off"
                                        cols="30" rows="10">{{$tiev->tiev_descripcion}}</textarea>
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
                                    <select class="form-control select2" id="tiev_vigencia" name="tiev_vigencia">
                                        <option value="S" {{ $tiev->tiev_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $tiev->tiev_vigente == 'N' ? 'selected' : '' }}>Inactivo
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
