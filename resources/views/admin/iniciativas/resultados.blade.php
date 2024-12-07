@extends('admin.panel_admin')

@section('contenido')

<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorResultados'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorResultados') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoResultados'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoResultados') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $iniciativa->inic_nombre }} - Resultados</h4>
                        <div class="card-header-action">
                            <div class="dropdown d-inline">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Iniciativa</button>
                                <div class="dropdown-menu dropright">
                                    {{-- <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}" class="dropdown-item has-icon"><i class="fas fa-users"></i>Ingresar cobertura</a>
                                    <a href="{{ route('admin.resultados.index', $iniciativa->inic_codigo) }}" class="dropdown-item has-icon"><i class="fas fa-flag"></i>Ingresar resultados</a> --}}
                                    <a href="{{ route('admin.evaluacion.index', $iniciativa->inic_codigo) }}" class="dropdown-item has-icon"><i class="fas fa-file-signature"></i>Ingresar evaluación</a>
                                    <a href="{{ route('admin.evidencia.listar', $iniciativa->inic_codigo) }}" class="dropdown-item has-icon"><i class="fas fa-paperclip"></i> Adjuntar evidencia</a>
                                </div>
                                <a href="{{ route('admin.dbgeneral.index') }}" type="button" class="btn btn-warning mr-1 waves-effect"><i class="fas fa-home"></i> Volver a inicio</a>
                                <a href="{{ route('admin.iniciativas.index') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-angle-left"></i> Volver al listado</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6>Cuantificación de resultados</h6>
                        <form action="{{ route('admin.resultados.update', $iniciativa->inic_codigo) }}" method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tr>
                                                        <th>Resultado</th>
                                                        <th>Cuantificación inicial</th>
                                                        <th>Cuantificación final</th>
                                                    </tr>
                                                    <tbody id="body-tabla-participantes">
                                                        @foreach ($resultados as $resultado)
                                                            <tr>
                                                                <td>{{ $resultado->resu_nombre }}</td>
                                                                <td>{{ $resultado->resu_cuantificacion_inicial }}</td>
                                                                <td>
                                                                    <input type="number" class="form-control" id="cantidad-{{ $resultado->resu_codigo }}" name="{{ $resultado->resu_codigo }}" value="{{ $resultado->resu_cuantificacion_final }}" min="0">
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
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 text-right">
                                    <input type="hidden" id="inic_codigo" name="inic_codigo" value="{{ $iniciativa->inic_codigo }}">
                                    <button type="submit" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-save"></i> Guardar todo</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/js/admin/iniciativas/listar.js') }}"></script>

@endsection
