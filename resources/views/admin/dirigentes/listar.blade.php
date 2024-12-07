@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoDirigente'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoDirigente') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de dirigentes de organizaciones</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.dirigente.crear') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i>
                                    Nuevo
                                    dirigente</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.dirigente.listar') }}" method="GET">
                                <div class="row">
                                    <div class="col-xl-3"></div>
                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Organización</label>
                                            <select class="form-control select2" style="width: 100%" id="orga_codigo" name="orga_codigo">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($organizaciones as $organizacion)
                                                    <option value="{{ $organizacion->orga_codigo }}"
                                                        {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                                        {{ $organizacion->orga_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-lg-4 text-right mb-4">
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                    class="fas fa-search"></i> Filtrar</button>
                                            <a href="{{ route('admin.dirigente.listar') }}" type="button"
                                                class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                                Limpiar</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>

                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Teléfono</th>
                                            <th>Correo</th>
                                            <th>Cargo</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($dirigentes as $diri)
                                            <tr>

                                                <td>{{ $diri->diri_nombre }}</td>
                                                <td>{{ $diri->diri_apellido }}</td>
                                                <td>{{ $diri->diri_telefono }}</td>
                                                <td>{{ $diri->diri_email }}</td>
                                                <td>{{ $diri->diri_cargo }}</td>

                                                <td>
                                                    @if ($diri->diri_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $diri->diri_rut_mod }}</td>

                                                <td>
                                                    <a href="{{ route('admin.dirigente.editar', $diri->diri_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="eliminarDirigente({{ $diri->diri_codigo }})"><i class="fas fa-trash"></i></a>
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

    <div class="modal fade" id="modalEliminarDirigente" tabindex="-1" role="dialog" aria-labelledby="modalEliminar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.dirigente.eliminar') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar dirigente</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">El dirigente podría tener participación en actividades y donaciones. Estos datos serán eliminados del sistema. <br> ¿Desea continuar de todos modos?</h6>
                        <input type="hidden" id="diri_codigo" name="diri_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Eliminar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>

    <script>
        function eliminarDirigente(diri_codigo) {
            $('#diri_codigo').val(diri_codigo);
            $('#modalEliminarDirigente').modal('show');
        }
    </script>
@endsection
