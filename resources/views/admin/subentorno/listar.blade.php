@extends('admin.panel_admin')

@section('contenido')
    <!-- nueva seccion de sub entornos -->
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorSubEntorno'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorSubEntorno') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoSubEntorno'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoSubEntorno') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('sube_nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('sube_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('sube_vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('sube_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de subentornos</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearSubEntorno"><i class="fas fa-plus"></i> Nuevo Subentorno</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.subentornos.listar') }}" method="GET">
                                <div class="row">
                                    <div class="col-3"></div>
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Entorno</label>
                                            <select class="form-control select2" id="ento_codigo" name="ento_codigo">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($entornos as $entorno)
                                                    <option value="{{ $entorno->ento_codigo }}"
                                                        {{ Request::get('ento_codigo') == $entorno->ento_codigo ? 'selected' : '' }}>
                                                        {{ $entorno->ento_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div style="position: absolute; top: 50%; transform: translateY(-50%);">
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                    class="fas fa-search"></i> Filtrar</button>
                                            <a href="{{ route('admin.subentornos.listar') }}" type="button"
                                                class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                                Limpiar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre del subentorno</th>
                                            <th>Entorno</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subentorno as $sube)
                                            <tr>
                                                <td>{{ $sube->sube_nombre }}</td>
                                                <td>{{ $sube->ento_nombre }}</td>
                                                <td>
                                                    @if ($sube->sube_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $sube->sube_rut_mod }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-warning"
                                                        data-toggle="modal" data-placement="top"
                                                        data-target="#modalEditarSubEntorno-{{ $sube->sube_codigo }}"
                                                        title="Editar"><i class="fas fa-edit"></i></button>
                                                    <form
                                                        action="{{ route('admin.subentornos.borrar', $sube->sube_codigo) }}"
                                                        method="post" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
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
    <div class="modal fade" id="modalCrearSubEntorno" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo subentorno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.subentornos.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-street-view"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="sube_nombre" name="sube_nombre"
                                    placeholder="" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nombre entorno</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>

                            </div>
                            <select name="codigo" id="codigo" class="form-control">
                                @foreach ($entornos as $ento)
                                    <option
                                        value="{{ $ento->ento_codigo }}
                                        {{ old('codigo') == $ento->ento_codigo ? 'selected' : '' }}">
                                        {{ $ento->ento_nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($subentorno as $sube)
        <div class="modal fade" id="modalEditarSubEntorno-{{ $sube->sube_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarSubEntorno">Editar subentorno</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.subentornos.actualizar', $sube->sube_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-street-view"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="sube_nombre" name="sube_nombre"
                                        value="{{ $sube->sube_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="codigo">Nombre Entorno</label>
                                <select name="codigo" id="codigo" class="form-control">
                                    @foreach ($entornos as $ento)
                                        <option value="{{ $ento->ento_codigo }}"
                                            {{ $sube->ento_codigo == $ento->ento_codigo ? 'selected' : '' }}>
                                            {{ $ento->ento_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-traffic-light"></i>
                                        </div>
                                    </div>
                                    <select class="form-control " id="sube_vigencia" name="sube_vigencia">
                                        <option value="S" {{ $sube->sube_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $sube->sube_vigente == 'N' ? 'selected' : '' }}>Inactivo
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/unidades/unidades.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
@endsection
