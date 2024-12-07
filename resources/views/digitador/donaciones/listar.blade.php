@extends('digitador.panel_digitador')

@section('contenido')
    <!-- nueva seccion de sub entornos -->
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">

                            @if (Session::has('exitoDonacion'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoDonacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif


                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de donaciones</h4>

                        </div>
                        <div class="card-body">
                            <form action="{{ route('digitador.donaciones.listar') }}" method="GET">
                                <div class="row">
                                    <div class="col-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label>Comuna</label>
                                            <select class="form-control select2" id="comu_codigo" name="comu_codigo"
                                                style="width: 100%">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($comunas as $comuna)
                                                    <option value="{{ $comuna->comu_codigo }}"
                                                        {{ Request::get('comu_codigo') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                        {{ $comuna->comu_nombre }}
                                                    </option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-3"></div>
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Organización</label>
                                            <select class="form-control select2" id="orga_codigo" name="orga_codigo">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($organizaciones as $organizacion)
                                                    <option value="{{ $organizacion->orga_codigo }}"
                                                        {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                                        {{ $organizacion->orga_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div style="position: absolute; top: 50%; transform: translateY(-50%);">
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                    class="fas fa-search"></i> Filtrar</button>
                                            <a href="{{ route('digitador.donaciones.listar') }}" type="button"
                                                class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                                Limpiar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive mt-2">
                                <table class="table table-striped table-md" id="tableExport" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Organización</th>
                                            <th>Motivo de donación</th>
                                            <th>Solicitante</th>
                                            <th>Recepcionista</th>
                                            <th>Monto</th>
                                            <th>Fecha de entrega</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($donaciones as $donacion)
                                            <tr>
                                                <td>{{ $donacion->dona_codigo }}</td>
                                                <td>{{ $donacion->orga_nombre }}</td>
                                                <td>{{ $donacion->dona_motivo }}</td>
                                                <td>{{ $donacion->dona_nombre_solicitante }}</td>
                                                <td>{{ $donacion->dona_persona_recepciona }}</td>
                                                <td>{{ '$'.number_format($donacion->dona_monto, 0, ',', '.') }}</td>
                                                <td>
                                                    <?php
                                                        setlocale(LC_TIME, 'spanish');
                                                        $fecha = ucwords(strftime('%d-%m-%Y', strtotime($donacion->dona_fecha_entrega)));
                                                        echo $fecha;
                                                    ?>
                                                </td>
                                                <td>

                                                        <a href="{{route('digitador.donaciones.info',$donacion->dona_codigo)}}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Ver detalles"><i
                                                                class="fas fa-eye"></i></a>
                                                        <a href="{{route('digitador.donaciones.editar',$donacion->dona_codigo)}}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar"><i
                                                                class="fas fa-edit"></i></a>
                                                        <form action="{{route('digitador.donaciones.eliminar',$donacion->dona_codigo)}}" method="POST" style="display: inline-block">
                                                            @csrf
                                                            <button type="submit" class="btn btn-icon btn-danger"><i
                                                                    class="fas fa-trash" data-toggle="tooltip"
                                                                    data-placement="top" title="Eliminar"></i></button>
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
        .buttons-copy, .buttons-csv {
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
