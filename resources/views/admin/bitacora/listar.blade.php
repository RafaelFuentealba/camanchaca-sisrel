@extends('admin.panel_admin')
@section('contenido')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if (Session::has('exitoActividad'))
                        <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('exitoActividad') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                        @endif

                        @if (Session::has('errorActividad'))
                        <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                            <div class="alert-body">
                                <strong>{{ Session::get('errorActividad') }}</strong>
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Listado de actividades</h4>
                        <div class="card-header-action">
                            <a href="{{route('admin.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                            <a type="button" class="btn btn-primary" href="{{route('admin.actividad.crear')}}"><i class="fas fa-plus"></i> Nueva actividad</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.actividad.listar') }}" method="GET">
                            <div class="row">
                                <!-- <div class="col-3"></div> -->
                                <div class="col-xl-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Comunas</label>
                                        <select class="form-control select2" style="width: 100%" id="comu_codigo" name="comu_codigo">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($comunas as $comuna)
                                            <option value="{{ $comuna->comu_codigo }}" {{ Request::get('comu_codigo') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                {{ $comuna->comu_nombre }}
                                            </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Organización</label>
                                        <select class="form-control select2" style="width: 100%" id="orga_codigo" name="orga_codigo">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($organizaciones as $organizacion)
                                            <option value="{{ $organizacion->orga_codigo }}" {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                                {{ $organizacion->orga_nombre }}
                                            </option>
                                            @empty
                                            <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label>Fecha de inicio</label>
                                        <input type="date" class="form-control datepicker" id="fecha_inicio" name="fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label>Fecha de termino</label>
                                        <input type="date" class="form-control datepicker" id="fecha_termino" name="fecha_termino">
                                    </div>
                                </div>
                                <div class="col-xl-12 col-md-12 col-lg-12 text-right mb-12">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-search"></i> Filtrar</button>
                                        <a href="{{ route('admin.actividad.listar') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>Limpiar</a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped table-md" id="tableExport" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Organización</th>
                                        <th>Nombre</th>
                                        <th>Fecha realización</th>
                                        <th>Fecha cumplimiento</th>
                                        <th>Creado por</th>
                                        <th>Avance</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($actividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->acti_codigo }}</td>
                                        <td>{{ $actividad->orga_nombre }}</td>
                                        <td>{{ $actividad->acti_nombre }}</td>
                                        <td>
                                            <?php
                                            setlocale(LC_TIME, 'spanish');
                                            $fecha = ucwords(strftime('%d-%m-%Y', strtotime($actividad->acti_fecha)));
                                            echo $fecha;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            setlocale(LC_TIME, 'spanish');
                                            $fecha = ucwords(strftime('%d-%m-%Y', strtotime($actividad->acti_fecha_cumplimiento)));
                                            echo $fecha;
                                            ?>
                                        </td>
                                        <td>{{ $actividad->acti_rut_creado }}</td>
                                        <td>{{ $actividad->acti_avance }}</td>
                                        <td>
                                            <a href="{{ route('admin.actividad.mostrar', $actividad->acti_codigo) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Ver detalles"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.actividades.evidencias.listar', $actividad->acti_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Adjuntar evidencia"><i class="fas fa-paperclip"></i></a>
                                            <a href="{{ route('admin.actividad.editar', $actividad->acti_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.actividad.eliminar', $actividad->acti_codigo) }}" method="POST" style="display: inline-block;">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
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

<link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<style>
    .dt-buttons {
        width: 30%;
    }

    .buttons-copy,
    .buttons-csv {
        display: none;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/jszip.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
<script src="{{ asset('public/js/page/datatables.js') }}"></script>

@endsection
