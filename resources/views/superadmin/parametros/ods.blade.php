@extends('superadmin.panel')
@section('contenido-principal')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorObjetivo'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorObjetivo') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoObjetivo'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoObjetivo') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('obde_nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('obde_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if ($errors->has('obde_imagen'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('obde_imagen') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de Objetivos de Desarrollo Sostenible</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Imagen</th>
                                            <th>Fecha de creación</th>
                                            <th>Última actualización</th>
                                            <th>Modificado por</th>
                                            <th>Estado</th>                                            
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($objetivos as $ods)
                                            <tr>
                                                <td>{{ $ods->obde_codigo }}</td>
                                                <td>{{ $ods->obde_nombre }}</td>
                                                <td>
                                                    <a class="btn" download="{{ $ods->obde_codigo }}.png" href="{{ asset($ods->obde_ruta_imagen) }}">
                                                        <img alt="image" src="{{ asset($ods->obde_ruta_imagen) }}?nocache=<?php echo time(); ?>" width="40">
                                                    </a>
                                                </td>
                                                <td>{{ date('d-m-Y', strtotime($ods->obde_creado)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($ods->obde_actualizado)) }}</td>
                                                <td>{{ $ods->obde_rut_mod }}</td>                                                
                                                <td>
                                                    @if ($ods->obde_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning" onclick="editarObjetivo({{ $ods->obde_codigo }})" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
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

    @foreach ($objetivos as $obj)
        <div class="modal fade" id="modalEditarObjetivo-{{ $obj->obde_codigo }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarfrecuencias" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarfrecuencias">Editar ODS</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('superadmin.ods.actualizar', $obj->obde_codigo) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="far fa-id-card"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="obde_nombre" name="obde_nombre" value="{{ $obj->obde_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Imagen</label>
                                <div class="input-group">
                                    <input class="form-control" id="obde_imagen" name="obde_imagen" type="file" accept=".png" />
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

    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>

    <script>
        function editarObjetivo(obde_codigo) {
            $('#obde_nombre').val('');
            $('#obde_imagen').val('');
            $('#modalEditarObjetivo-'+obde_codigo).modal('show');
        }
    </script>

@endsection
