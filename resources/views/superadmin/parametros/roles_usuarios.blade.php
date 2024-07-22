@extends('superadmin.panel')

@section('contenido-principal')

<section class="section">
    <div class="section-body">
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

                        @if(Session::has('errorRol'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorRol') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                        @if(Session::has('exitoRol'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoRol') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if($errors->has('nombre'))
                            <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Roles de usuario del sistema</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Actualizado</th>
                                        <th>Estado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $contadorUsuarios = 0;    
                                    ?>
                                    @foreach ($roles as $rol)
                                        <tr>
                                            <td>{{ $rol->rous_codigo }}</td>
                                            <td>{{ $rol->rous_nombre }}</td>
                                            <td>
                                            <?php
                                                setlocale(LC_TIME, 'spanish');
                                                $fecha = ucwords(strftime('%d-%m-%Y', strtotime($rol->rous_actualizado)));
                                                echo $fecha;
                                            ?>
                                            </td>
                                            <td>
                                                @if ($rol->rous_vigente=='N')
                                                    <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                @else
                                                    <div class="badge badge-success badge-shadow">Activo</div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-icon btn-warning" onclick="editarRol({{ $rol->rous_codigo }})" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
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


@foreach ($roles as $rol)
    <div class="modal fade" id="modalEditarRol-{{ $rol->rous_codigo }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarRolTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarRolTitulo">Editar rol de usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('superadmin.actualizar.rol', $rol->rous_codigo) }}" method="POST">
                        @method('PUT')
                        @csrf
                        
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $rol->rous_nombre }}" autocomplete="off" required maxlength="100">
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
    function editarRol(rous_codigo) {
        $('#modalEditarRol-'+rous_codigo).modal('show');
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
