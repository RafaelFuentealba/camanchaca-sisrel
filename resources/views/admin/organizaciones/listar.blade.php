@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('exitoOrganizacion'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoOrganizacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorOrganizacion'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorOrganizacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de organizaciones</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.crear.org') }}" class="btn btn-primary"><i class="fas fa-plus"></i>
                                    Nueva
                                    organización</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.listar.org') }}" method="GET">
                                <div class="row">
                                    <div class="col-xl-3"></div>
                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label>Comuna</label>
                                            <select class="form-control select2" id="comuna" name="comuna" style="width: 100%">
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
                                    <div class="col-xl-4 col-md-4 col-lg-4 text-right md-4">
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                    class="fas fa-search"></i> Filtrar</button>
                                            <a href="{{ route('admin.listar.org') }}" type="button"
                                                class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                                Limpiar</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre organización</th>
                                            <th>Entorno</th>
                                            <th>Cantidad de socios</th>
                                            <th>Ubicación</th>
                                            {{-- <th>Fecha de vínculo</th> --}}
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($organizaciones as $orga)
                                            <tr>

                                                <td>{{ $orga->orga_nombre }}</td>
                                                <td>{{ $orga->ento_nombre }}</td>
                                                <td>{{ $orga->orga_cantidad_socios }}</td>
                                                <td>{{ $orga->orga_domicilio }}</td>
                                                {{-- <td>{{ date('d-m-Y', strtotime($orga->orga_fecha_vinculo)) }}</td> --}}
                                                <td>
                                                    @if ($orga->orga_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $orga->orga_rut_mod }}</td>

                                                <td>
                                                    <a href="{{ route('admin.editar.org', $orga->orga_codigo) }}"
                                                        class="btn btn-icon btn-warning" data-toggle="tooltip"
                                                        data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                    <form action="{{ route('admin.borrar.org', $orga->orga_codigo) }}"
                                                        method="post" style="display: inline-block">
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
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
@endsection
