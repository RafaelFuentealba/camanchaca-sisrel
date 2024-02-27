@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            @if (Session::has('errorEvidencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEvidencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorValidacion'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorValidacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoEvidencia'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEvidencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de evidencias donación N° {{ $donacion->dona_codigo }}</h4>

                            <div class="card-header-action">
                                {{-- <div class="dropdown d-inline">

                            </div> --}}
                                <a href="{{ route('admin.donaciones.listar') }}" type="button" class="btn btn-success"
                                    title="Ir a lista"><i class="fas fa-backward"></i> Ir a
                                    donaciones</a>
                                <a href="javascript:void(0)" class="btn btn-primary" onclick="agregar()"><i
                                        class="fas fa-plus"></i> Nueva evidencia</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Archivo original</th>
                                            <th>Modificado por</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($evidencias as $evidencia)
                                            <tr>
                                                <td>{{ $evidencia->doen_nombre }}</td>
                                                <td>{{ $evidencia->doen_descripcion }}</td>
                                                <td>{{ $evidencia->doen_nombre_origen }}</td>
                                                <td>{{ $evidencia->doen_rut_mod }}</td>
                                                <td>
                                                    <form
                                                        action="{{ route('admin.donaciones.evidencia.descargar', $evidencia->doen_codigo) }}"
                                                        method="POST" style="display: inline-block">
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-primary"
                                                            data-toggle="tooltip" data-placement="top" title="Descargar"><i
                                                                class="fas fa-download"></i></button>
                                                    </form>

                                                    {{-- <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        onclick="editar({{ $evidencia->inev_codigo }}, '{{ $evidencia->inev_nombre }}', '{{ $evidencia->inev_descripcion }}')"
                                                        data-toggle="tooltip" data-placement="top" data-placement="top"
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a> --}}

                                                    <form
                                                        action="{{ route('admin.donaciones.evidencia.eliminar', $evidencia->doen_codigo) }}"
                                                        method="POST" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger"
                                                            data-toggle="tooltip" data-placement="top" title="Eliminar"><i
                                                                class="fas fa-trash"></i></button>
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

    <div class="modal fade" id="modalAgregarEvidencia" tabindex="-1" role="dialog" aria-labelledby="agregarEvidencia"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarEvidencia">Nueva evidencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.donaciones.evidencia.guardar', $donacion->dona_codigo) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="doen_nombre" name="doen_nombre"
                                    placeholder="" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Descripción</label>
                            <div class="input-group">
                                <textarea class="formbold-form-input" id="doen_descripcion" name="doen_descripcion" rows="3" style="width: 100%;"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Archivo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                            </div>
                            {{-- <input type="file" id="doen_archivo" name="doen_archivo"
                                accept=".png,.jpg,.jpeg,.pdf,.xls,.xlsx,.ppt,.pptx,.doc,.docx,.csv,.mp3,.mp4,.avi"><br> --}}
                            <input type="file" id="doen_archivo" name="doen_archivo"><br>
                            <small>Tamaño máximo de archivo: 10 MB</small>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/admin/bitacora/evidencias.js') }}"></script>
@endsection
