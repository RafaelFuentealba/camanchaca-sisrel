@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorDirigente'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorDirigente') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="row">
                        <div class="col-xl-2 col-md-2 col-lg-2"></div>

                        <div class="col-xl-8 col-md-8 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Nuevo dirigente</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.dirigente.guardar') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Nombre</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="diri_nombre"
                                                            name="diri_nombre" value="{{ old('diri_nombre') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('diri_nombre'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('diri_nombre') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Apellido</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-user-tie"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="diri_apellido"
                                                            name="diri_apellido" value="{{ old('diri_apellido') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('diri_apellido'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('diri_apellido') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Teléfono</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-phone"></i>
                                                            </div>
                                                        </div>
                                                        <input type="tel" class="form-control" id="diri_telefono" placeholder="+569XXXXXXXX"
                                                            name="diri_telefono" value="{{ old('diri_telefono') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('diri_telefono'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('diri_telefono') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Correo electrónico</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-at"></i>
                                                            </div>
                                                        </div>
                                                        <input type="email" class="form-control" id="diri_email"
                                                            name="diri_email" value="{{ old('diri_email') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('diri_email'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('diri_email') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label>Cargo del dirigente</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-clipboard"></i>
                                                            </div>
                                                        </div>
                                                        <input type="text" class="form-control" id="diri_cargo"
                                                            name="diri_cargo" value="{{ old('diri_cargo') }}"
                                                            autocomplete="off">
                                                    </div>
                                                    @if ($errors->has('diri_cargo'))
                                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                            <div class="alert-body">
                                                                <button class="close"
                                                                    data-dismiss="alert"><span>&times;</span></button>
                                                                <strong>{{ $errors->first('diri_cargo') }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-xl-4 col-md-4 col-lg-4">
                                                <div class="form-group">
                                                    <label for="Frecuencia">Organización</label>
                                                    <select class="form-control select2" name="orga_codigo" style="width: 100%"
                                                        id="orga_codigo">
                                                        @foreach ($organizaciones as $orga)
                                                            <option value="{{ $orga->orga_codigo }}" {{old('orga_codigo') == $orga->orga_codigo ? 'selected' : ''}}>
                                                                {{ $orga->orga_nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
