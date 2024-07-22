@extends('superadmin.panel')
@section('contenido-principal')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorFormatoIm'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorFormatoIm') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoFormatoIm'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoFormatoIm') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('foim_nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('foim_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if ($errors->has('foim_puntaje'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('foim_puntaje') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('foim_vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('foim_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de formatos de implementación de iniciativas</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Puntaje</th>
                                            <th>Fecha de creación</th>
                                            <th>Última actualización</th>
                                            <th>Modificado por</th>
                                            <th>Estado</th>                                            
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($formatoim as $foim)
                                            <tr>
                                                <td>{{ $foim->foim_nombre }}</td>
                                                <td>{{ $foim->foim_puntaje }}</td>
                                                <td>{{ date('d-m-Y', strtotime($foim->foim_creado)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($foim->foim_actualizado)) }}</td>
                                                <td>{{ $foim->foim_rut_mod }}</td>
                                                <td>
                                                    @if ($foim->foim_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning" onclick="editarFormato({{ $foim->foim_codigo }})" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('superadmin.formatoim.borrar', $foim->foim_codigo) }}" method="POST" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" title="Eliminar"><i class="fas fa-trash"></i></button>
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

    @foreach ($formatoim as $foim)
        <div class="modal fade" id="modalEditarformatoim-{{ $foim->foim_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarformatoim" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarformatoim">Editar formato de implementación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('superadmin.formatoim.actualizar', $foim->foim_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="far fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="foim_nombre" name="foim_nombre"
                                        value="{{ $foim->foim_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Puntaje</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-sort-numeric-up"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" id="foim_puntaje" name="foim_puntaje"
                                        value="{{ $foim->foim_puntaje }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-traffic-light"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select2" id="foim_vigencia" name="foim_vigencia">
                                        <option value="S" {{ $foim->foim_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $foim->foim_vigente == 'N' ? 'selected' : '' }}>Inactivo
                                        </option>
                                    </select>
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
        function editarFormato(foim_codigo) {
            $('#modalEditarformatoim-'+foim_codigo).modal('show');
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
