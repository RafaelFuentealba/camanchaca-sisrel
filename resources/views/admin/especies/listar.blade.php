@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorPilar'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorPilar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoPilar'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoPilar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('pila_nombre'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('pila_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('pila_vigencia'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('pila_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Especies</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearPilar"><i class="fas fa-plus"></i> Nueva especie</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Valor</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($especies as $especie)
                                            <tr>
                                                <td>{{ $especie->tirh_nombre }}</td>
                                                <td>{{ $especie->tirh_valor }}</td>
                                                <td>
                                                        <button type="button" class="btn btn-icon btn-warning"
                                                            data-toggle="modal" data-placement="top"
                                                            data-target="#modalEditarPilar-{{ $especie->tirh_codigo }}"
                                                            title="Editar"><i class="fas fa-edit"></i></button>
                                                        <form
                                                            action="{{ route('admin.especies.borrar', $especie->tirh_codigo) }}"
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

    <div class="modal fade" id="modalCrearPilar" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva especie</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.especies.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre de la especie</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-archway"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tirh_nombre" name="tirh_nombre"
                                    value="{{ old('tirh_nombre') }}" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Valor</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        $
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tirh_valor" name="tirh_valor"
                                    value="{{ old('tirh_valor') }}" autocomplete="off">
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i>
                                Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($especies as $especie)
        <div class="modal fade" id="modalEditarPilar-{{ $especie->tirh_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarPilar">Editar pilar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.especies.actualizar', $especie->tirh_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                            <label>Nombre de la especie</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-archway"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tirh_nombre" name="tirh_nombre"
                                    value="{{ $especie->tirh_nombre }}" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Valor</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        $
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="tirh_valor" name="tirh_valor"
                                    value="{{ $especie->tirh_valor }}" autocomplete="off">
                            </div>
                        </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect"><i
                                        class="fas fa-undo-alt"></i> Actualizar</button>
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
