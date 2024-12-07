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

                    @if (Session::has('exitoConvenio'))
                        <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('exitoConvenio') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-3"></div>
            </div>

            <div class="row">
                <div class="col-2 col-md-2 col-lg-2"></div>
                <div class="col-8 col-md-8 col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Editar convenio</h4>
                            <div class="card-header-action">
                                @if ($conv->conv_ruta_archivo != null)
                                    <a class="btn btn-icon btn-primary" href="{{ asset($conv->conv_ruta_archivo) }}" download="{{ $conv->conv_nombre_archivo }}" data-toggle="tooltip" data-placement="top" title="Descargar convenio"><i class="fas fa-download"></i></a>
                                @endif
                                <a class="btn btn-icon btn-primary" href="javascript:void(0)" onclick="subirConvenio()" data-toggle="tooltip" data-placement="top" title="Cambiar archivo de convenio"><i class="fas fa-file-upload"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.convenios.actualizar', $conv->conv_codigo) }}" method="POST" enctype="multipart/form-data">
                                @method('PUT')
                                @csrf

                                <div class="form-group">
                                    <label for="conv_nombre">Nombre del convenio</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        </div>
                                        <input type="text" class="form-control" id="conv_nombre"
                                            name="conv_nombre"
                                            value="{{ old('conv_nombre') ?? @$conv->conv_nombre }}"
                                            autocomplete="off">
                                    </div>
                                    @if ($errors->has('conv_nombre'))
                                        <div
                                            class="alert alert-warning alert-dismissible show fade mt-2 text-center">
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
                                        <textarea rows="6" class="formbold-form-input" placeholder="Ingresar descripción" style="width:100%"
                                            id="conv_descripcion" name="conv_descripcion" autocomplete="off">{{ old('conv_descripcion') ?? @$conv->conv_descripcion }}</textarea>
                                    </div>
                                    @if ($errors->has('conv_descripcion'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center" style="width:100%">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('conv_descripcion') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>                                

                                <div class="row">
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="conv_vigente">Estado del convenio </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                </div>
                                                <select class="form-control form-control-sm" name="conv_vigente" id="conv_vigente">
                                                    <option value="S">Activo</option>
                                                    <option value="N">Inactivo</option>
                                                </select>

                                            </div>
                                            @if ($errors->has('conv_vigente'))
                                                <div
                                                    class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                                    <div class="alert-body">
                                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('conv_vigente') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="modal fade" id="modalSubirConvenio" tabindex="-1" role="dialog" aria-labelledby="modalConvenio" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.convenios.cambiar', $conv->conv_codigo) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConvenio">Cambiar archivo de convenio</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center mt-3">
                        <div class="form-group">
                            <div class="input-group">
                                <input class="form-control" id="conv_archivo" name="conv_archivo" type="file" accept=".pdf" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function subirConvenio() {
            $('#conv_archivo').val('');
            $('#modalSubirConvenio').modal('show');
        }
    </script>

@endsection
