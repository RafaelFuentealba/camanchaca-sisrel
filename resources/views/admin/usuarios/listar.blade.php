@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorUsuario'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorUsuario') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoUsuario'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoUsuario') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4>Listado de usuarios</h4>
                <div class="card-header-action">
                    <a href="{{ route('admin.crear.usuario') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo
                        Usuario</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-md" id="table-1">
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Nombre</th>
                                <th>Cargo</th>
                                <th>Profesión</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->usua_rut }}</td>
                                    <td>{{ $usuario->usua_nombre }} {{ $usuario->usua_apellido }}</td>
                                    <td>{{ $usuario->usua_cargo }}</td>
                                    <td>{{ $usuario->usua_profesion }}</td>
                                    <td>{{ $usuario->usua_email }}</td>
                                    <td>
                                        @if ($usuario->usua_vigente == 'S')
                                            <div class="badge badge-success badge-shadow">Activo</div>
                                        @else
                                            <div class="badge badge-danger badge-shadow">Inactivo</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($usuario->rous_codigo == 1)
                                            <div class="badge badge-warning badge-shadow">Administrador</div>
                                        @elseif ($usuario->rous_codigo == 2)
                                            <div class="badge badge-info badge-shadow">Embajador</div>
                                        @elseif ($usuario->rous_codigo == 3)
                                            <div class="badge badge-primary badge-shadow">Directivo/a</div>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" href="{{ route('admin.editar.usuario', [$usuario->usua_rut, $usuario->rous_codigo]) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-icon btn-danger" onclick="eliminarUsuario('{{ $usuario->usua_rut }}', {{ $usuario->rous_codigo }})" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalEliminarUsuario" tabindex="-1" role="dialog" aria-labelledby="modalEliminar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.eliminar.usuario') }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">El usuario dejará de tener acceso al sistema de forma permanente. <br> ¿Desea continuar de todos modos?</h6>
                        <input type="hidden" id="usua_rut" name="usua_rut" value="">
                        <input type="hidden" id="rous_codigo" name="rous_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function eliminarUsuario(usua_rut, rous_codigo) {
            $('#usua_rut').val(usua_rut);
            $('#rous_codigo').val(rous_codigo);
            $('#modalEliminarUsuario').modal('show');
        }
    </script>

    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
    <script src="{{ asset('public/js/admin/iniciativas/listar.js') }}"></script>

@endsection
