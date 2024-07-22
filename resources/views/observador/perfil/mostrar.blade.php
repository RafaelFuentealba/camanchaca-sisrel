@extends('observador.panel_observador')

@section('contenido')

<section class="section">
    <div class="section-body">
        <div class="row">            
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorPerfil'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorPerfil') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoPerfil'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoPerfil') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
            </div>
            <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                <div class="card">
                    <div class="card-header">
                        <h4>Mi perfil</h4>
                        <div class="card-header-action">
                            <a href="{{ route('observador.clave.cambiar', [$usuario->usua_rut, $usuario->rous_codigo]) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Cambiar contraseña"><i class="fas fa-user-lock"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('observador.perfil.update', [$usuario->usua_rut, $usuario->rous_codigo]) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-user"></i></div>
                                            </div>
                                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') ?? @$usuario->usua_nombre }}" autocomplete="off">
                                        </div>
                                        @if ($errors->has('nombre'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('nombre') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="apellido">Apellido</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                            </div>
                                            <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido') ?? @$usuario->usua_apellido }}" autocomplete="off">
                                        </div>
                                        @if ($errors->has('apellido'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('apellido') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="profesion">Profesión</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-user-graduate"></i></div>
                                            </div>
                                            <input type="text"  value="{{ old('profesion') ?? @$usuario->usua_profesion }}" name="profesion" id="profesion" class="form-control" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email">Correo electrónico</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                                            </div>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?? @$usuario->usua_email }}" autocomplete="off">
                                        </div>
                                        @if ($errors->has('email'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email_alt">Correo electrónico alternativo</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-at"></i></div>
                                            </div>
                                            <input type="email" class="form-control" id="email_alt" name="email_alt" value="{{ old('email_alt') ?? @$usuario->usua_email_alternativo }}" autocomplete="off">
                                        </div>
                                        @if ($errors->has('email_alt'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('email_alt') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="cargo">Cargo</label>
                                        <input value="{{ old('cargo') ?? @$usuario->usua_cargo }}" type="text" class="form-control" name="cargo" id="cargo" autocomplete="off">
                                        @if ($errors->has('cargo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('cargo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="form-group col-4">
                                    <label for="">Unidad</label>
                                    <div class="input-group">
                                        <select name="unidad" id="unidad" class="form-control select2">
                                            <option value="" disabled selected>Seleccione...</option>
                                            @foreach ($unidades as $unidad)
                                                <option value="{{ $unidad->unid_codigo }}" {{ old('implementacion')==$usuario->unid_codigo || $unidad->unid_codigo==$usuario->unid_codigo ? 'selected' : '' }}>{{ $unidad->unid_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('unidad'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close"
                                                    data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('unidad') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/page/datatables.js') }}"></script>


@endsection
