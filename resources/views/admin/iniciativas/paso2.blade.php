@extends('admin.panel_admin')

@section('contenido')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-xl-3"></div>
            <div class="col-xl-6">
                @if(Session::has('exitoPaso1'))
                    <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                        <div class="alert-body">
                            <strong>{{ Session::get('exitoPaso1') }}</strong>
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    </div>
                @endif

                @if(Session::has('errorPaso2'))
                    <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                        <div class="alert-body">
                            <strong>{{ Session::get('errorPaso2') }}</strong>
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (empty($iniciativa))
            <div class="row">
                <div class="col-xl-3"></div>
                <div class="col-xl-6">
                    <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                        <div class="alert-body">
                            <strong>Ocurrió un error al recuperar información de la iniciativa registrada.</strong>
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $iniciativa->inic_nombre }} - Paso 2 de 3</h4>
                            <div class="card-header-action">
                                @if (isset($iniciativa))
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Iniciativa</button>
                                        <div class="dropdown-menu dropright">
                                            <a href="{{ route('admin.cobertura.index', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon"><i class="fas fa-users"></i>Ingresar
                                                cobertura</a>
                                            <a href="{{ route('admin.resultados.index', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon"><i class="fas fa-flag"></i>Ingresar
                                                resultados</a>
                                            <a href="{{ route('admin.evaluacion.index', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon"><i class="fas fa-file-signature"></i>Ingresar
                                                evaluación</a>
                                            <a href="{{ route('admin.evidencia.listar', $iniciativa->inic_codigo) }}"
                                                class="dropdown-item has-icon"><i class="fas fa-paperclip"></i> Adjuntar
                                                evidencia</a>
                                        </div>
                                    </div>
                                @endif
                                <a href="{{ route('admin.dbgeneral.index') }}" type="button" class="btn btn-primary"
                                    title="Ir a inicio"><i class="fas fa-home"></i>
                                    Volver a inicio</a>
                                <a href="{{ route('admin.iniciativas.index') }}" type="button" class="btn btn-primary"
                                    title="Ir a iniciativas"><i class="fas fa-backward"></i> Volver a iniciativas</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6>Territorialidad</h6>
                            <div class="row mt-3">
                                <input type="hidden" id="codigo" name="codigo" value="{{ $iniciativa->inic_codigo }}">
                                <div class="col-xl-5 col-md-5 col-lg-5">
                                    <div class="form-group">
                                        <label>Región</label> <label for="" style="color: red;">*</label>
                                        <select class="form-control select2" id="region" style="width: 100%" name="region" onchange="listarComunas()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($regiones as $region)
                                                <option value="{{ $region->regi_codigo }}">{{ $region->regi_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-5 col-md-5 col-lg-5">
                                    <div class="form-group">
                                        <label>Comuna</label> <label for="" style="color: red;">*</label>
                                        <select class="form-control select2" id="comuna" name="comuna" style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2" style="position: relative;">
                                    <button type="button" style="margin-bottom: 5%" class="btn btn-primary waves-effect" onclick="guardarUbicacion()"><i class="fas fa-plus"></i> Agregar</button>
                                </div>
                                <div class="col-xl-3 col-md-3 col-lg-3"></div>
                                <div class="col-xl-6 col-md-6 col-lg-6 text-center" id="div-alert-territorio"></div>
                            </div>
                            <div class="row" id="row-tabla-ubicaciones" style="display: none;">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tr>
                                                        <th>Región</th>
                                                        <th>Comuna</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                    <tbody id="body-tabla-ubicaciones">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="card-body" style="margin-top: -2%;">
                            <h6>Subentornos esperados</h6>
                            <div class="row mt-3">
                                <input type="hidden" id="codigo" name="codigo" value="{{ $iniciativa->inic_codigo }}">
                                <div class="col-xl-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Entorno relevante</label> <label for="" style="color: red;">*</label>
                                        <select class="form-control select2" id="entorno" name="entorno" style="width: 100%" onchange="mostrarSubentornos()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($entornos as $entorno)
                                                <option value="{{ $entorno->ento_codigo }}" {{ old('entorno')==$entorno->ento_codigo ? 'selected' : '' }}>{{ $entorno->ento_nombre }}</option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Subentorno relevante</label> <label for="" style="color: red;">*</label>
                                        <select class="form-control select2" id="subentorno" name="subentorno" style="width: 100%">
                                            <option value="" selected disabled>Seleccione...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label>Cantidad de participantes</label> <label for="" style="color: red;">*</label>
                                        <input type="number" class="form-control" id="cantidad" name="cantidad" autocomplete="off" min="0">
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2" style="position: relative;">
                                    <button type="button"  style="margin-bottom: 5%" class="btn btn-primary waves-effect" onclick="agregarSubentorno()"><i class="fas fa-plus"></i> Agregar</button>
                                </div>
                                <div class="col-xl-3 col-md-3 col-lg-3"></div>
                                <div class="col-xl-6 col-md-6 col-lg-6 text-center" id="div-alert-subentorno"></div>
                            </div>
                            <div class="row" id="row-tabla-subentornos" style="display: none;">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tr>
                                                        <th>Entorno</th>
                                                        <th>Subentorno</th>
                                                        <th>Participantes</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                    <tbody id="body-tabla-subentornos">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="card-body"  style="margin-top: -5%;">
                            @if (isset($iniciativasImpactos))
                                <form action="{{ route('admin.paso2.actualizar', $iniciativa->inic_codigo) }}" method="POST">
                                    @method('PUT')
                            @else
                                <form action="{{ route('admin.paso2.verificar') }}" method="POST">
                            @endif
                                    @csrf
                                <div class="row mt-4">
                                    <div class="col-xl-6 col-md-6 col-lg-6">
                                        <h6>Resultados esperados</h6>
                                        <div class="row mt-3">
                                            <div class="col-xl-3 col-md-3 col-lg-3">
                                                <div class="form-group">
                                                    <label>Cuantificación</label> <label for="" style="color: red;">*</label>
                                                    <input type="number" class="form-control" id="cuantificacion" name="cuantificacion" autocomplete="off" min="0">
                                                </div>
                                            </div>
                                            <div class="col-xl-7 col-md-7 col-lg-7">
                                                <div class="form-group">
                                                    <label>Resultado esperado</label> <label for="" style="color: red;">*</label>
                                                    <input type="text" class="form-control" id="resultado" name="resultado" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-2 col-lg-2" style="position: relative;">
                                                <button  type="button" style="margin-bottom: 5%" class="btn btn-primary waves-effect" onclick="agregarResultado()"><i class="fas fa-plus"></i></button>
                                            </div>
                                            <div class="col-xl-12 col-md-12 col-lg-12 text-center" id="div-alert-resultado"></div>
                                        </div>
                                        <div class="card" id="card-tabla-resultados" style="display: none;">
                                            <div class="card-body p-0">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-md">
                                                        <tr>
                                                            <th>Cuantificación</th>
                                                            <th>Resultado</th>
                                                            <th>Acción</th>
                                                        </tr>
                                                        <tbody id="body-tabla-resultados">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <h6>Impactos relacionados</h6>
                                            <label>Impactos</label> <label for="" style="color: red;">*</label>
                                            @if (isset($iniciativasImpactos))
                                                <select class="form-control select2" multiple="" id="impacto" name="impacto[]" style="width: 100%">
                                                    @forelse ($impactos as $impacto)
                                                        <option value="{{ $impacto->impa_codigo }}" {{ in_array($impacto->impa_codigo, $iniciativasImpactos) ? 'selected' : '' }}>{{ $impacto->impa_nombre }}</option>
                                                    @empty
                                                        <option value="-1">No existen registros</option>
                                                    @endforelse
                                                </select>
                                            @else
                                                <select class="form-control select2" multiple="" id="impacto" name="impacto[]" style="width: 100%">
                                                    @forelse ($impactos as $impacto)
                                                        <option value="{{ $impacto->impa_codigo }}" {{ (collect(old('impacto'))->contains($impacto->impa_codigo)) ? 'selected' : '' }}>{{ $impacto->impa_nombre }}</option>
                                                    @empty
                                                        <option value="-1">No existen registros</option>
                                                    @endforelse
                                                </select>
                                            @endif
                                            @if($errors->has('impacto'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                    <div class="alert-body">
                                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('impacto') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                            <input type="hidden" id="iniciativa" name="iniciativa" value="{{ $iniciativa->inic_codigo }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-lg-12">
                                        <div class="text-right">
                                            {{-- <a href="{{route('admin.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                                        <a href="{{route('admin.iniciativas.index')}}" type="button" class="btn btn-success" title="Ir a iniciativas"><i class="fas fa-backward"></i></a> --}}
                                            <a href="{{ route('admin.paso1.editar', $iniciativa->inic_codigo) }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-chevron-left"></i> Volver al paso anterior</a>
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect">Siguiente <i class="fas fa-chevron-right"></i></button>
                                            <a href="{{ route('admin.paso2.editar', $iniciativa->inic_codigo) }}" type="button" class="btn btn-warning waves-effect">Recargar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12 col-lg-12">
                    <div class="card">

                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/js/admin/iniciativas/paso2.js') }}"></script>

@endsection
