@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if(Session::has('errorUsuario'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorUsuario') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if(Session::has('exitoClave'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoClave') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                </div>
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Editar usuario</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.claveusuario.cambiar', [$usuario->usua_rut, $usuario->rous_codigo]) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Cambiar contraseña"><i class="fas fa-user-lock"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form
                                action="{{ route('admin.actualizar.usuario', [$usuario->usua_rut, $usuario->rous_codigo]) }}" method="POST">
                                @method('PUT')
                                @csrf

                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="nombre" name="nombre"
                                                    value="{{ old('nombre') ?? @$usuario->usua_nombre }}"
                                                    autocomplete="off">
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

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="apellido">Apellido</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user-tie"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="apellido" name="apellido"
                                                    value="{{ old('apellido') ?? @$usuario->usua_apellido }}"
                                                    autocomplete="off">
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

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="profesion">Profesión</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user-graduate"></i></div>
                                                </div>
                                                <input type="text"
                                                    value="{{ old('profesion') ?? @$usuario->usua_profesion }}"
                                                    name="profesion" id="profesion" class="form-control"
                                                    autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="email">Correo electrónico</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-at"></i></div>
                                                </div>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email') ?? @$usuario->usua_email }}" autocomplete="off">
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

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="email_alt">Correo electrónico alternativo</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-at"></i></div>
                                                </div>
                                                <input type="email" class="form-control" id="email_alt" name="email_alt"
                                                    value="{{ old('email_alt') ?? @$usuario->usua_email_alternativo }}"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="form-group">
                                            <label for="cargo">Cargo del usuario</label>
                                            <input value="{{ old('cargo') ?? @$usuario->usua_cargo }}" type="text"
                                                class="form-control" name="cargo" id="cargo" autocomplete="off">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="form-group col-xl-4">
                                        <label for="">Unidad</label>
                                        <div class="input-group">
                                            <select name="unidad" id="unidad" class="form-control select2" style="width: 100%">
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($unidades as $unidad)
                                                    <option value="{{ $unidad->unid_codigo }}" {{ $unidad->unid_codigo==$usuario->unid_codigo ? 'selected' : '' }}>{{ $unidad->unid_nombre }}</option>
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
                                    <div class="form-group col-xl-4">
                                        <label for="">Rol</label>
                                        <div class="input-group">
                                            <select name="rol" id="rol" class="form-control select2" style="width: 100%">
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($roles as $rol)
                                                    <option value="{{ $rol->rous_codigo }}" {{ $rol->rous_codigo==$rol->rous_codigo ? 'selected' : '' }}>{{ $rol->rous_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('roles'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('roles') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-xl-3">
                                        <label for="vigente" class="d-block">Estado</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-traffic-light"></i></div>
                                            </div>
                                            <select class="form-control" name="vigente" id="vigente">
                                                <option value="S"
                                                    {{ $usuario->usua_vigente == 'S' ? 'selected' : '' }}>
                                                    Activo
                                                </option>
                                                <option value="N"
                                                    {{ $usuario->usua_vigente == 'N' ? 'selected' : '' }}>
                                                    Inactivo
                                                </option>
                                            </select>
                                        </div>
                                        @if ($errors->has('vigente'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('vigente') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                                    <a href="{{ route('admin.listar.usuario') }}" type="button" class="btn btn-warning mr-1 waves-effect"><i class="fas fa-angle-left"></i> Volver al listado</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
