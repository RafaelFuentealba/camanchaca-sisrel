@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoConvenio'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoConvenio') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

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
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de convenios</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.convenios.registrar') }}" class="btn btn-primary"><i
                                        class="fas fa-plus"></i> Nuevo
                                    convenio</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Documento</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($convenios as $conv)
                                            <tr>

                                                <td>{{ $conv->conv_nombre }}</td>
                                                <td>{{ $conv->conv_descripcion }}</td>
                                                <td>
                                                    {{ $conv->conv_nombre_archivo }}
                                                    @if ($conv->conv_ruta_archivo != null)
                                                        <a href="{{ asset($conv->conv_ruta_archivo) }}" download="{{ $conv->conv_nombre_archivo }}"><i class="fas fa-download"></i></a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($conv->conv_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $conv->conv_rut_mod }}</td>
                                                <td>
                                                    <a href="{{ route('admin.convenios.editar', $conv->conv_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" data-placement="top" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.convenios.eliminar', $conv->conv_codigo) }}" method="post" style="display: inline-block">
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
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>

@endsection
