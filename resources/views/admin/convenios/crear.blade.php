@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-3"></div>
                <div class="col-6">
                    @if (Session::has('errorConvenio'))
                        <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('errorConvenio') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-3"></div>
            </div>

            <div class="row">
                <div class="col-2 col-md-2 col-lg-2"></div>
                <div class="col-8x col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Nuevo convenio</h4>
                        </div>
                        <div class="card-body">
                            <!-- poner ruta de accion del formulario -->
                            <form action="{{ route('admin.convenios.guardar') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="conv_nombre">Nombre del convenio</label>
                                    <input type="text" class="form-control" id="conv_nombre" name="conv_nombre" value="{{ old('conv_nombre') }}" autocomplete="off">
                                    @if ($errors->has('conv_nombre'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('conv_nombre') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="conv_descripcion">Descripción del convenio</label>
                                    <div class="input-group">
                                        <textarea rows="6" class="formbold-form-input" id="conv_descripcion" name="conv_descripcion" autocomplete="off" style="width:100%">{{ old('conv_descripcion') }}</textarea>
                                        @if ($errors->has('conv_descripcion'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2 text-center" style="width:100%">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('conv_descripcion') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="conv_archivo">Archivo del convenio</label>
                                    <div class="input-group">
                                        <input id="conv_archivo" class="form-control" name="conv_archivo" type="file" accept=".pdf" />
                                        @if ($errors->has('conv_archivo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('conv_archivo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12 text-right">
                                        <button type="submit" class="btn btn-icon btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                                    </div>
                                </div>                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
