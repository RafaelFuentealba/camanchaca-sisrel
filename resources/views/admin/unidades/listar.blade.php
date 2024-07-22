@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('exitoUnidad'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoUnidad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorUnidad'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorUnidad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de unidades</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.registrar.unidad') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i> Nueva
                                    unidad</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.unidades.listar') }}" method="GET">
                                <div class="row">
                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>Comuna</label>
                                            <select class="form-control select2" id="comuna" name="comuna"
                                                style="width: 100%" onchange="cargarTipoUnidades()">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($comunas as $comuna)
                                                    <option value="{{ $comuna->comu_codigo }}"
                                                        {{ Request::get('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
                                                        {{ $comuna->comu_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="tipo_unidad">Tipo de unidad</label>
                                            <select name="tipo_unidad" id="tipo_unidad" class="form-control select2"
                                                style="width: 100%">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($tipoUnidades as $tipo_unidad)
                                                    <option value="{{ $tipo_unidad->tuni_codigo }}"
                                                        {{ Request::get('tipo_unidad') == $tipo_unidad->tuni_codigo ? 'selected' : '' }}>
                                                        {{ $tipo_unidad->tuni_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="division">División</label>
                                            <select name="division" id="division" class="form-control select2"
                                                style="width: 100%">
                                                <option value="" selected>Seleccione...</option>
                                                @forelse ($divisiones as $division)
                                                    <option value="{{ $division->divi_codigo }}"
                                                        {{ Request::get('division') == $division->divi_codigo ? 'selected' : '' }}>
                                                        {{ $division->divi_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3 col-lg-3 text-right md-3">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                class="fas fa-search"></i> Filtrar</button>
                                        <a href="{{ route('admin.unidades.listar') }}" type="button"
                                            class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                            Limpiar</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre unidad</th>
                                            <th>Tipo de unidad</th>
                                            <th>División</th>
                                            <th>Comuna</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="unidades-body">
                                        @foreach ($unidades as $unidad)
                                            <tr>
                                                <td>{{ $unidad->unid_nombre }}</td>
                                                <td>{{ $unidad->tuni_nombre }}</td>
                                                <td>
                                                    @if ($unidad->divi_nombre != null)
                                                        {{ $unidad->divi_nombre }}
                                                    @else
                                                        No registrado
                                                    @endif
                                                </td>
                                                <td>{{ $unidad->comu_nombre }}</td>
                                                <td>
                                                    @if ($unidad->unid_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $unidad->unid_rut_mod }}</td>
                                                <td>
                                                    <a type="buttton"
                                                        href="{{ route('admin.editar.unidad', $unidad->unid_codigo) }}"
                                                        class="btn btn-icon btn-warning" data-toggle="tooltip"
                                                        data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <form
                                                        action="{{ route('admin.unidades.borrar', $unidad->unid_codigo) }}"
                                                        method="post" style="display: inline-block">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/unidades/unidades.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
@endsection
