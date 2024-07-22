@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorIniciativa'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorIniciativa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoEvaluacion'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEvaluacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorEliminar'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEliminar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoEliminar'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEliminar') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de iniciativas</h4>
                            <!--<div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearInfraestructura"><i class="fas fa-plus"></i> Nueva infraestructura</button>
                            </div>-->
                            <div class="card-header-action">
                                <a href="{{route('digitador.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 col-md-3 col-lg-3"></div>
                                <div class="col-6 col-md-6 col-lg-6 text-center" id="div-alert-iniciativas"></div>
                                <div class="col-3 col-md-3 col-lg-3"></div>
                            </div>
                            <form action="{{ route('digitador.iniciativas.index') }}" method="GET">
                                <div class="row">
                                    {{-- <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Región</label>
                                            <select class="form-control select2" id="region" name="region"
                                                onchange="consultarComunas()">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($regiones as $region)
                                                    <option value="{{ $region->regi_codigo }}"
                                                        {{ Request::get('region') == $region->regi_codigo ? 'selected' : '' }}>
                                                        {{ $region->regi_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Comuna</label>
                                            <select class="form-control select2" id="comuna" name="comuna"
                                                onchange="consultarUnidades()">
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
                                    {{-- <div class="col-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label>Unidad</label>
                                            <select class="form-control select2" id="unidad" name="unidad">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($unidades as $unidad)
                                                    <option value="{{ $unidad->unid_codigo }}"
                                                        {{ Request::get('unidad') == $unidad->unid_codigo ? 'selected' : '' }}>
                                                        {{ $unidad->unid_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div> --}}
                                    <div class="col-12 col-md-12 col-lg-12 text-right mb-4">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect"><i
                                                class="fas fa-search"></i> Filtrar</button>
                                        <a href="{{ route('digitador.iniciativas.index') }}" type="button"
                                            class="btn btn-primary mr-1 waves-effect"><i class="fas fa-broom"></i>
                                            Limpiar</a>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre</th>
                                            <th>Responsable</th>
                                            <th>Mecanismo</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iniciativas as $iniciativa)
                                            <tr>
                                                <td>{{ $iniciativa->inic_codigo }}</td>
                                                <td>{{ $iniciativa->inic_nombre }}</td>
                                                <td>{{ $iniciativa->inic_nombre_responsable }}</td>
                                                <td>{{ $iniciativa->meca_nombre }}</td>
                                                <td>
                                                    @if ($iniciativa->inic_aprobada == null)
                                                        <div class="badge badge-light badge-shadow">Sin definir</div>
                                                    @else
                                                        @if ($iniciativa->inic_aprobada == 'S')
                                                            <div class="badge badge-success badge-shadow">Aprobada</div>
                                                        @else
                                                            <div class="badge badge-danger badge-shadow">Rechazada</div>
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- <div class="dropdown d-inline">
                                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        Opciones
                                                    </button>
                                                    <div class="dropdown-menu dropright">
                                                        <a class="dropdown-item has-icon" href="{{ route('digitador.paso1.editar', $iniciativa->inic_codigo) }}"><i class="fas fa-edit"></i> Editar iniciativa</a>
                                                        <a href="javascript:void(0)" class="dropdown-item has-icon" onclick="eliminarIniciativa({{ $iniciativa->inic_codigo }})"><i class="fas fa-trash"></i>Eliminar iniciativa</a>
                                                    </div>
                                                </div> --}}
                                                    <a href="{{ route('digitador.iniciativas.show', $iniciativa->inic_codigo) }}"
                                                        class="btn btn-icon btn-warning" data-toggle="tooltip"
                                                        data-placement="top" title="Ver detalles"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="javascript:void(0)" class="btn btn-icon btn-warning"
                                                        data-toggle="tooltip" data-placement="top" title="Calcular INVI"
                                                        onclick="calcularIndice({{ $iniciativa->inic_codigo }})"><i
                                                            class="fas fa-tachometer-alt"></i></a>
                                                    {{-- <a href="{{ route('digitador.evidencia.listar', $iniciativa->inic_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Adjuntar evidencia"><i class="fas fa-paperclip"></i></a>
                                                <a href="{{ route('digitador.cobertura.index', $iniciativa->inic_codigo) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Ingresar cobertura"><i class="fas fa-users"></i></a>
                                                <a href="{{ route('digitador.resultados.index', $iniciativa->inic_codigo) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Ingresar resultados"><i class="fas fa-flag"></i></a>
                                                <a href="{{ route('digitador.evaluacion.index', $iniciativa->inic_codigo) }}" class="btn btn-icon btn-success" data-toggle="tooltip" data-placement="top" title="Evaluar iniciativa"><i class="fas fa-file-signature"></i></a> --}}
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

    <div class="modal fade" id="modalINVI" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Índice de vinculación INVI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-md" id="table-1"
                            style="border-top: 1px ghostwhite solid;">
                            <tbody>
                                <tr>
                                    <td><strong>Mecanismo</strong></td>
                                    <td id="mecanismo-nombre"></td>
                                    <td id="mecanismo-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Frecuencia</strong></td>
                                    <td id="frecuencia-nombre"></td>
                                    <td id="frecuencia-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Cobertura</strong></td>
                                    <td></td>
                                    <td id="cobertura-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Resultados</strong></td>
                                    <td></td>
                                    <td id="resultados-puntaje"></td>
                                </tr>
                                <tr>
                                    <td><strong>Evaluación</strong></td>
                                    <td></td>
                                    <td id="evaluacion-puntaje"></td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <h6>Índice de vinculación INVI</h6>
                                    </td>
                                    <td id="valor-indice"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEliminarIniciativa" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('digitador.iniciativas.destroy') }}" method="POST"
                    id="form-eliminar-iniciativa">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar iniciativa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">Todos los datos de la iniciativa serán eliminados. ¿Desea continuar de todos
                            modos?</h6>
                        <input type="hidden" id="inic_codigo" name="inic_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Eliminar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
    <script src="{{ asset('public/js/digitador/iniciativas/listar.js') }}"></script>
@endsection
