@extends('digitador.panel_digitador')

@section('contenido')

<section class="section">
    <div class="section-body">
        <div class="row">            
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorEvaluacion'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorEvaluacion') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>                
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $iniciativa->inic_nombre }} - Evaluación</h4>
                    </div>                    
                    <div class="card-body">
                        <h6>Calidad de la ejecución</h6>
                        <p>
                            A continuación le pedimos que evalúe de 1 a 5 la calidad en la ejecución de la actividad, según los compromisos asumidos por la compañía.
                            Si considera que algunos ítemes no estaban comprometidos, marque <strong>No Aplica</strong>.
                        </p>
                        <div class="row">
                            <div class="col-12">
                            @if (!empty($evaluacion))
                                <form action="{{ route('digitador.evaluacion.update', $evaluacion->eval_codigo) }}" method="POST">
                                    @method('PUT')
                                    @csrf
                            @else
                                <form action="{{ route('digitador.evaluacion.store') }}" method="POST">
                                    @csrf
                            @endif
                                    <input type="hidden" name="iniciativa" value="{{ $iniciativa->inic_codigo }}">
                                    <input type="hidden" name="plazos" value="">
                                    <input type="hidden" name="horarios" value="">
                                    <input type="hidden" name="infraestructura" value="">
                                    <input type="hidden" name="equipamiento" value="">
                                    <input type="hidden" name="conexion" value="">
                                    <input type="hidden" name="responsable" value="">
                                    <input type="hidden" name="participantes" value="">
                                    <input type="hidden" name="presentaciones" value="">
                                    <div class="row">
                                        <div class="col-2"></div>
                                    <div class="col-8">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-md" id="table-1">
                                                <tbody>
                                                    <tr>
                                                        <td>Plazo comprometido</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                <input type="radio" id="plazo1" name="plazos" class="custom-control-input" value="1" {{ $evaluacion->eval_plazos=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo1" name="plazos" class="custom-control-input" value="1" {{ old('plazos')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="plazo2" name="plazos" class="custom-control-input" value="2" {{ $evaluacion->eval_plazos=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo2" name="plazos" class="custom-control-input" value="2" {{ old('plazos')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="plazo3" name="plazos" class="custom-control-input" value="3" {{ $evaluacion->eval_plazos=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo3" name="plazos" class="custom-control-input" value="3" {{ old('plazos')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="plazo4" name="plazos" class="custom-control-input" value="4" {{ $evaluacion->eval_plazos=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo4" name="plazos" class="custom-control-input" value="4" {{ old('plazos')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="plazo5" name="plazos" class="custom-control-input" value="5" {{ $evaluacion->eval_plazos=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo5" name="plazos" class="custom-control-input" value="5" {{ old('plazos')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="plazo0" name="plazos" class="custom-control-input" value="0" {{ $evaluacion->eval_plazos=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="plazo0" name="plazos" class="custom-control-input" value="0" {{ old('plazos')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="plazo0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Horarios comprometidos</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario1" name="horarios" class="custom-control-input" value="1" {{ $evaluacion->eval_horarios=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario1" name="horarios" class="custom-control-input" value="1" {{ old('horarios')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario2" name="horarios" class="custom-control-input" value="2" {{ $evaluacion->eval_horarios=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario2" name="horarios" class="custom-control-input" value="2" {{ old('horarios')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario3" name="horarios" class="custom-control-input" value="3" {{ $evaluacion->eval_horarios=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario3" name="horarios" class="custom-control-input" value="3" {{ old('horarios')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario4" name="horarios" class="custom-control-input" value="4" {{ $evaluacion->eval_horarios=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario4" name="horarios" class="custom-control-input" value="4" {{ old('horarios')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario5" name="horarios" class="custom-control-input" value="5" {{ $evaluacion->eval_horarios=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario5" name="horarios" class="custom-control-input" value="5" {{ old('horarios')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="horario0" name="horarios" class="custom-control-input" value="0" {{ $evaluacion->eval_horarios=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="horario0" name="horarios" class="custom-control-input" value="0" {{ old('horarios')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="horario0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Infraestructura</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra1" name="infraestructura" class="custom-control-input" value="1" {{ $evaluacion->eval_infraestructura=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra1" name="infraestructura" class="custom-control-input" value="1" {{ old('infraestructura')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra2" name="infraestructura" class="custom-control-input" value="2" {{ $evaluacion->eval_infraestructura=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra2" name="infraestructura" class="custom-control-input" value="2" {{ old('infraestructura')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra3" name="infraestructura" class="custom-control-input" value="3" {{ $evaluacion->eval_infraestructura=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra3" name="infraestructura" class="custom-control-input" value="3" {{ old('infraestructura')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra4" name="infraestructura" class="custom-control-input" value="4" {{ $evaluacion->eval_infraestructura=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra4" name="infraestructura" class="custom-control-input" value="4" {{ old('infraestructura')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra5" name="infraestructura" class="custom-control-input" value="5" {{ $evaluacion->eval_infraestructura=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra5" name="infraestructura" class="custom-control-input" value="5" {{ old('infraestructura')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="infra0" name="infraestructura" class="custom-control-input" value="0" {{ $evaluacion->eval_infraestructura=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="infra0" name="infraestructura" class="custom-control-input" value="0" {{ old('infraestructura')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="infra0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Equipamiento</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip1" name="equipamiento" class="custom-control-input" value="1" {{ $evaluacion->eval_equipamiento=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip1" name="equipamiento" class="custom-control-input" value="1" {{ old('equipamiento')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip2" name="equipamiento" class="custom-control-input" value="2" {{ $evaluacion->eval_equipamiento=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip2" name="equipamiento" class="custom-control-input" value="2" {{ old('equipamiento')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip3" name="equipamiento" class="custom-control-input" value="3" {{ $evaluacion->eval_equipamiento=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip3" name="equipamiento" class="custom-control-input" value="3" {{ old('equipamiento')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip4" name="equipamiento" class="custom-control-input" value="4" {{ $evaluacion->eval_equipamiento=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip4" name="equipamiento" class="custom-control-input" value="4" {{ old('equipamiento')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip5" name="equipamiento" class="custom-control-input" value="5" {{ $evaluacion->eval_equipamiento=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip5" name="equipamiento" class="custom-control-input" value="5" {{ old('equipamiento')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="equip0" name="equipamiento" class="custom-control-input" value="0" {{ $evaluacion->eval_equipamiento=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="equip0" name="equipamiento" class="custom-control-input" value="0" {{ old('equipamiento')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="equip0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Conexión digital y/o logística</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion1" name="conexion" class="custom-control-input" value="1" {{ $evaluacion->eval_conexion_dl=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion1" name="conexion" class="custom-control-input" value="1" {{ old('conexion')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion2" name="conexion" class="custom-control-input" value="2" {{ $evaluacion->eval_conexion_dl=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion2" name="conexion" class="custom-control-input" value="2" {{ old('conexion')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion3" name="conexion" class="custom-control-input" value="3" {{ $evaluacion->eval_conexion_dl=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion3" name="conexion" class="custom-control-input" value="3" {{ old('conexion')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion4" name="conexion" class="custom-control-input" value="4" {{ $evaluacion->eval_conexion_dl=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion4" name="conexion" class="custom-control-input" value="4" {{ old('conexion')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion5" name="conexion" class="custom-control-input" value="5" {{ $evaluacion->eval_conexion_dl=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion5" name="conexion" class="custom-control-input" value="5" {{ old('conexion')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="conexion0" name="conexion" class="custom-control-input" value="0" {{ $evaluacion->eval_conexion_dl=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="conexion0" name="conexion" class="custom-control-input" value="0" {{ old('conexion')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="conexion0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Desempeño del responsable</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable1" name="responsable" class="custom-control-input" value="1" {{ $evaluacion->eval_desempenho_responsable=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable1" name="responsable" class="custom-control-input" value="1" {{ old('responsable')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable2" name="responsable" class="custom-control-input" value="2" {{ $evaluacion->eval_desempenho_responsable=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable2" name="responsable" class="custom-control-input" value="2" {{ old('responsable')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable3" name="responsable" class="custom-control-input" value="3" {{ $evaluacion->eval_desempenho_responsable=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable3" name="responsable" class="custom-control-input" value="3" {{ old('responsable')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable4" name="responsable" class="custom-control-input" value="4" {{ $evaluacion->eval_desempenho_responsable=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable4" name="responsable" class="custom-control-input" value="4" {{ old('responsable')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable5" name="responsable" class="custom-control-input" value="5" {{ $evaluacion->eval_desempenho_responsable=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable5" name="responsable" class="custom-control-input" value="5" {{ old('responsable')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="responsable0" name="responsable" class="custom-control-input" value="0" {{ $evaluacion->eval_desempenho_responsable=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="responsable0" name="responsable" class="custom-control-input" value="0" {{ old('responsable')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="responsable0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Desempeño de los participantes</td>
                                                        <td>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante1" name="participantes" class="custom-control-input" value="1" {{ $evaluacion->eval_desempenho_participantes=="1" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante1" name="participantes" class="custom-control-input" value="1" {{ old('participantes')=="1" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante1">1</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante2" name="participantes" class="custom-control-input" value="2" {{ $evaluacion->eval_desempenho_participantes=="2" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante2" name="participantes" class="custom-control-input" value="2" {{ old('participantes')=="2" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante2">2</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante3" name="participantes" class="custom-control-input" value="3" {{ $evaluacion->eval_desempenho_participantes=="3" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante3" name="participantes" class="custom-control-input" value="3" {{ old('participantes')=="3" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante3">3</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante4" name="participantes" class="custom-control-input" value="4" {{ $evaluacion->eval_desempenho_participantes=="4" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante4" name="participantes" class="custom-control-input" value="4" {{ old('participantes')=="4" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante4">4</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante5" name="participantes" class="custom-control-input" value="5" {{ $evaluacion->eval_desempenho_participantes=="5" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante5" name="participantes" class="custom-control-input" value="5" {{ old('participantes')=="5" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante5">5</label>
                                                            </div>
                                                            <div class="custom-control custom-radio custom-control-inline">
                                                                @if (!empty($evaluacion))
                                                                    <input type="radio" id="participante0" name="participantes" class="custom-control-input" value="0" {{ $evaluacion->eval_desempenho_participantes=="0" ? 'checked' : '' }}>
                                                                @else
                                                                    <input type="radio" id="participante0" name="participantes" class="custom-control-input" value="0" {{ old('participantes')=="0" ? 'checked' : '' }}>
                                                                @endif
                                                                <label class="custom-control-label" for="participante0">No Aplica</label>
                                                            </div>
                                                        </td>
                                                        <tr>
                                                            <td>Calidad de las presentaciones</td>
                                                            <td>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion1" name="presentaciones" class="custom-control-input" value="1" {{ $evaluacion->eval_calidad_presentaciones=="1" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion1" name="presentaciones" class="custom-control-input" value="1" {{ old('presentaciones')=="1" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion1">1</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion2" name="presentaciones" class="custom-control-input" value="2" {{ $evaluacion->eval_calidad_presentaciones=="2" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion2" name="presentaciones" class="custom-control-input" value="2" {{ old('presentaciones')=="2" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion2">2</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion3" name="presentaciones" class="custom-control-input" value="3" {{ $evaluacion->eval_calidad_presentaciones=="3" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion3" name="presentaciones" class="custom-control-input" value="3" {{ old('presentaciones')=="3" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion3">3</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion4" name="presentaciones" class="custom-control-input" value="4" {{ $evaluacion->eval_calidad_presentaciones=="4" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion4" name="presentaciones" class="custom-control-input" value="4" {{ old('presentaciones')=="4" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion4">4</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion5" name="presentaciones" class="custom-control-input" value="5" {{ $evaluacion->eval_calidad_presentaciones=="5" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion5" name="presentaciones" class="custom-control-input" value="5" {{ old('presentaciones')=="5" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion5">5</label>
                                                                </div>
                                                                <div class="custom-control custom-radio custom-control-inline">
                                                                    @if (!empty($evaluacion))
                                                                        <input type="radio" id="presentacion0" name="presentaciones" class="custom-control-input" value="0" {{ $evaluacion->eval_calidad_presentaciones=="0" ? 'checked' : '' }}>
                                                                    @else
                                                                        <input type="radio" id="presentacion0" name="presentaciones" class="custom-control-input" value="0" {{ old('presentaciones')=="0" ? 'checked' : '' }}>
                                                                    @endif
                                                                    <label class="custom-control-label" for="presentacion0">No Aplica</label>
                                                                </div>
                                                            </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-2"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-md-12 col-lg-12 text-right">
                                            <a href="{{ route('digitador.iniciativas.index') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-angle-left"></i> Volver al listado</a>
                                            <button type="submit" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-save"></i> Guardar evaluación</button>                                            
                                        </form>
                                            @if (!empty($evaluacion))
                                                <form action="{{ route('digitador.evaluacion.destroy', $evaluacion->eval_codigo) }}" method="POST" id="form-eliminar-evaluacion" style="display: inline-block;">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger waves-effect">Eliminar evaluación</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/js/digitador/iniciativas/listar.js') }}"></script>

@endsection
