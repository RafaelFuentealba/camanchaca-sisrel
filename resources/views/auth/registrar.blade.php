@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorUsuario'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorUsuario') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                </div>
                <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Registro de nuevo usuario</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.guardar.usuario') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user"></i></div>
                                                </div>
                                                <input type="text" class="form-control" id="nombre" name="nombre"
                                                    value="{{ old('nombre') }}" autocomplete="off">
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
                                                <input type="text" class="form-control" id="apellido" name="apellido"
                                                    value="{{ old('apellido') }}" autocomplete="off">
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
                                            <label for="run">RUN</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-id-card"></i></div>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Ejemplo: 12345678-K"
                                                    id="run" name="run" value="{{ old('run') }}"
                                                    autocomplete="off">
                                            </div>
                                            @if ($errors->has('run'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('run') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
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
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email') }}" autocomplete="off">
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
                                                <input type="email" class="form-control" id="email_alt" name="email_alt"
                                                    value="{{ old('email_alt') }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="Frecuencia">Cargo del usuario</label>
                                            <input value="{{ old('cargo') }}" type="text" class="form-control"
                                                name="cargo" id="cargo" autocomplete="off">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="profesion">Profesión</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><i class="fas fa-user-graduate"></i>
                                                    </div>
                                                </div>
                                                <input type="text" value="{{ old('profesion') }}" name="profesion"
                                                    id="profesion" class="form-control" autocomplete="off" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-3">
                                        <label class="label" for="rol">Rol de acceso</label>
                                        <div class="input-group">
                                            <select class="form-control" id="rol" name="rol">
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($roles as $rol)
                                                    <option value="{{ $rol->rous_codigo }}"
                                                        {{ old('rol') == $rol->rous_codigo ? 'selected' : '' }}>
                                                        {{ $rol->rous_nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('rol'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('rol') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="unidad">Unidad</label>
                                            <div class="input-group">
                                                <select name="unidad" id="unidad" class="form-control select2">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    @foreach ($unidades as $unidad)
                                                        <option value="{{ $unidad->unid_codigo }}"
                                                            {{ old('unidad') == $unidad->unid_codigo ? 'selected' : '' }}>
                                                            {{ $unidad->unid_nombre }}</option>
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
                                </div>

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="clave" class="d-block">Contraseña</label>
                                        <input type="password" class="form-control" id="clave" name="clave"
                                            value="{{ old('clave') }}" autocomplete="off">
                                        @if ($errors->has('clave'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('clave') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="confirmarclave" class="d-block">Confirmar contraseña</label>
                                        <input type="password" class="form-control" id="confirmarclave"
                                            name="confirmarclave" autocomplete="off">
                                        @if ($errors->has('confirmarclave'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('confirmarclave') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary waves-effect"><i
                                            class="fas fa-save"></i> Registrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
