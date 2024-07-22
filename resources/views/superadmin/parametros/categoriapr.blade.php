@extends('superadmin.panel')
@section('contenido-principal')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorCategoriaPr'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorCategoriaPr') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoCategoriaPr'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoCategoriaPr') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de categorías de percepción</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearCategoriaPr"><i class="fas fa-plus"></i> Nueva categoría</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Fecha de creación</th>
                                            <th>Última actualización</th>
                                            <th>Modificado por</th>
                                            <th>Estado</th>                                            
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoriapr as $cape)
                                            <tr>
                                                <td>{{ $cape->cape_nombre }}</td>
                                                <td>{{ date('d-m-Y', strtotime($cape->cape_creado)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($cape->cape_actualizado)) }}</td>
                                                <td>{{ $cape->cape_rut_mod }}</td>
                                                <td>
                                                    @if ($cape->cape_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning" onclick="editarCape({{ $cape->cape_codigo }})" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('superadmin.categoriapr.borrar', $cape->cape_codigo) }}" method="POST" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash"></i></button>
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

    <div class="modal fade" id="modalCrearCategoriaPr" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva categoría de percepción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('superadmin.categoriapr.crear') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder=""
                                    autocomplete="off">
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

    @foreach ($categoriapr as $cape)
        <div class="modal fade" id="modalEditarCategoriaPR-{{ $cape->cape_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarCategoriaPR">Editar categoría de percepción</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('superadmin.categoriapr.actualizar', $cape->cape_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="{{ $cape->cape_nombre }}" autocomplete="off">
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
                                    <select class="form-control select2" id="vigencia" name="vigencia">
                                        <option value="S" {{ $cape->cape_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $cape->cape_vigente == 'N' ? 'selected' : '' }}>Inactivo
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

    <script>
        function editarCape(cape_codigo) {
            $('#modalEditarCategoriaPR-'+cape_codigo).modal('show');
        }
    </script>

    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>

@endsection
