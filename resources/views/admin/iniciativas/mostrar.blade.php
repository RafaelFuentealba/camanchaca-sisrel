@extends('admin.panel_admin')

@section('contenido')

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorIniciativa'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorIniciativa') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('exitoIniciativa'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoIniciativa') }}</strong>
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
                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Información de la iniciativa</h4>
                            <div class="card-header-action">
                                @if ($iniciativa->inic_aprobada != null)
                                    @if ($iniciativa->inic_aprobada == 'S')
                                        <a href="javascript:void(0)" class="btn btn-icon btn-success" data-toggle="tooltip"
                                            data-placement="top" title="Iniciativa aprobada"><i
                                                class="fas fa-check"></i></a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-icon btn-danger" data-toggle="tooltip"
                                            data-placement="top" title="Iniciativa rechazada"><i
                                                class="fas fa-times"></i></a>
                                    @endif
                                @endif
                                <div class="dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2"
                                        data-toggle="dropdown" aria-haspopup="true"
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
                                @if (Session::has('admin'))
                                    <div class="dropdown d-inline">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">Estado</button>
                                        <div class="dropdown-menu dropright">
                                            <a href="javascript:void(0)" class="dropdown-item has-icon" data-toggle="modal"
                                                data-target="#modalAprobarIniciativa"><i class="fas fa-check"></i>Aprobar
                                                iniciativa</a>
                                            <a href="javascript:void(0);" class="dropdown-item has-icon" data-toggle="modal"
                                                data-target="#modalRechazarIniciativa"><i class="fas fa-times"></i>Rechazar
                                                iniciativa</a>
                                        </div>
                                    </div>
                                @endif
                                <a href="{{ route('admin.dbgeneral.index') }}" type="button" class="btn btn-primary"
                                    title="Ir a inicio"><i class="fas fa-home"></i> Volver a inicio</a>
                                <a href="{{ route('admin.iniciativas.index') }}" type="button" class="btn btn-primary"
                                    title="Ir a iniciativas"><i class="fas fa-backward"></i> Volver a iniciativas</a>
                                <a href="javascript:void(0)" class="btn btn-icon btn-primary"
                                    onclick="calcularIndice({{ $iniciativa->inic_codigo }})" data-toggle="tooltip"
                                    data-placement="top" title="Calcular INVI"><i class="fas fa-tachometer-alt"></i></a>
                                {{-- <a href="{{ route('admin.evidencia.listar', $iniciativa->inic_codigo) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-placement="top" title="Adjuntar evidencia"><i class="fas fa-paperclip"></i></a> --}}
                                <a href="javascript:void(0);" class="btn btn-icon btn-primary"
                                    onclick="imprimirIniciativa()" data-toggle="tooltip" data-placement="top"
                                    title="Imprimir"><i class="fas fa-print"></i></a>
                                <a href="{{ route('admin.paso1.editar', $iniciativa->inic_codigo) }}"
                                    class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top"
                                    title="Editar iniciativa"><i class="fas fa-edit"></i></a>
                                <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                    onclick="eliminarIniciativa({{ $iniciativa->inic_codigo }})" data-toggle="tooltip"
                                    data-placement="top" title="Eliminar iniciativa"><i class="fas fa-trash"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <table class="table table-striped table-responsive table-md" id="tabla-info-iniciativa">
                                    <tbody>
                                        <tr>
                                            <td width="20%"><strong>Nombre de la iniciativa</strong></td>
                                            <td>{{ $iniciativa->inic_nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Descripción y objetivos</strong></td>
                                            <td>
                                                <?php
                                                $descripcion = nl2br($iniciativa->inic_objetivo_desc);
                                                echo $descripcion;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de inicio</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($iniciativa->inic_fecha_inicio)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de finalización</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($iniciativa->inic_fecha_fin)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Frecuencia</strong></td>
                                            <td>{{ $frecuencia->frec_nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pilar</strong></td>
                                            <td>{{ $pilar->pila_nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Unidad</strong></td>
                                            <td>
                                                @if (sizeof($unidades) > 0)
                                                    <?php
                                                    $aUnidades = [];
                                                    foreach ($unidades as $unidad) {
                                                        array_push($aUnidades, $unidad->unid_nombre);
                                                    }
                                                    echo implode(', ', $aUnidades);
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Convenio</strong></td>
                                            <td>
                                                @if ($convenio != null)
                                                    {{ $convenio->conv_nombre }}
                                                @else
                                                    No aplica
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mecanismo</strong></td>
                                            <td>{{ $mecanismo->subm_nombre }} ({{ $mecanismo->meca_nombre }})</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Formato de implementación</strong></td>
                                            <td>{{ $formato->foim_nombre }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Nombre encargado responsable</strong></td>
                                            <td>{{ $iniciativa->inic_nombre_responsable }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cargo encargado responsable</strong></td>
                                            <td>{{ $iniciativa->inic_cargo_responsable }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Impactos relacionados</strong></td>
                                            <td>
                                                @if (sizeof($impactos) > 0)
                                                    <?php
                                                    $aImpactos = [];
                                                    foreach ($impactos as $impacto) {
                                                        array_push($aImpactos, $impacto->impa_nombre);
                                                    }
                                                    echo implode(', ', $aImpactos);
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Cobertura</strong></td>
                                            <td>
                                                @if (sizeof($subentornos) > 0)
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-sm small">
                                                            <thead>
                                                                <tr>
                                                                    <th>Entorno</th>
                                                                    <th>Subentorno</th>
                                                                    <th>Part. iniciales</th>
                                                                    <th>Part. finales</th>
                                                                    <th>Género</th>
                                                                    <th>Seg. etario</th>
                                                                    <th>Procedencia</th>
                                                                    <th>Nacionalidad</th>
                                                                    <th>Pueblos originarios</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($subentornos as $subentorno)
                                                                    <tr style="background-color: inherit;">
                                                                        <td>{{ $subentorno->ento_nombre }}</td>
                                                                        <td>{{ $subentorno->sube_nombre }}</td>
                                                                        <td>{{ $subentorno->part_cantidad_inicial }}</td>
                                                                        <td>{{ $subentorno->part_cantidad_final }}</td>
                                                                        <td>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Hombre">Hombre:</label>
                                                                                {{ $subentorno->part_genero_hombre == null ? '0' : $subentorno->part_genero_hombre }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Mujer">Mujer:</label>
                                                                                {{ $subentorno->part_genero_mujer == null ? '0' : $subentorno->part_genero_mujer }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Otro">Otro:</label>
                                                                                {{ $subentorno->part_genero_otro == null ? '0' : $subentorno->part_genero_otro }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Niños">Niños:</label>
                                                                                {{ $subentorno->part_etario_ninhos == null ? '0' : $subentorno->part_etario_ninhos }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Jóvenes">Jóvenes:</label>
                                                                                {{ $subentorno->part_etario_jovenes == null ? '0' : $subentorno->part_etario_jovenes }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Adultos">Adultos:</label>
                                                                                {{ $subentorno->part_etario_adultos == null ? '0' : $subentorno->part_etario_adultos }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Adultos mayores">Adultos
                                                                                    mayores:</label>
                                                                                {{ $subentorno->part_etario_adumayores == null ? '0' : $subentorno->part_etario_adumayores }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Rural">Rural:</label>
                                                                                {{ $subentorno->part_procedencia_rural == null ? '0' : $subentorno->part_procedencia_rural }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Urbano">Urbano:</label>
                                                                                {{ $subentorno->part_procedencia_urbano == null ? '0' : $subentorno->part_procedencia_urbano }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Rural">Chilena:</label>
                                                                                {{ $subentorno->part_nacionalidad_chilena == null ? '0' : $subentorno->part_nacionalidad_chilena }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Urbano">Migrante:</label>
                                                                                {{ $subentorno->part_nacionalidad_migrante == null ? '0' : $subentorno->part_nacionalidad_migrante }}
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Mapuche">Mapuche:</label>
                                                                                {{ $subentorno->part_adscrito_pueblos == null ? '0' : $subentorno->part_adscrito_pueblos }}
                                                                            </div>
                                                                            <div style="white-space:nowrap">
                                                                                <label for="Otro">Otro:</label>
                                                                                {{ $subentorno->part_no_adscrito_pueblos == null ? '0' : $subentorno->part_no_adscrito_pueblos }}
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Resultados</strong></td>
                                            <td>
                                                @if (sizeof($resultados) > 0)
                                                    @foreach ($resultados as $resultado)
                                                        {{ $resultado->resu_cuantificacion_final == null ? '0' : $resultado->resu_cuantificacion_final }}
                                                        de
                                                        {{ $resultado->resu_cuantificacion_inicial }}
                                                        {{ $resultado->resu_nombre }}
                                                        <br>
                                                    @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total de dinero</strong></td>
                                            <td>
                                            <td>
                                                <?php
                                                $recursos = $dinero->codi_valorizacion + $infraestructura->coin_valorizacion + $rrhh->corh_valorizacion;
                                                echo '$' . number_format($recursos, 0, ',', '.');
                                                ?>
                                            </td>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total recursos dinero</strong></td>
                                            <td>${{ number_format($dinero->codi_valorizacion, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total recursos especies</strong></td>
                                            <td>${{ number_format($especies->coes_valorizacion, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total recursos infraestructura</strong></td>
                                            <td>${{ number_format($infraestructura->coin_valorizacion, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Total recursos humanos</strong></td>
                                            <td>${{ number_format($rrhh->corh_valorizacion, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Detalle de recursos</strong></td>
                                            <td>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm small">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Dinero</th>
                                                                <th>Especies</th>
                                                                <th>Infraestructura</th>
                                                                <th>Recursos humanos</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($entidades as $entidad)
                                                                <tr style="background-color: inherit;">
                                                                    <td>{{ $entidad->enti_nombre }}</td>
                                                                    <td>
                                                                        @if (sizeof($recursoDinero) == 0)
                                                                            $0
                                                                        @else
                                                                            @foreach ($recursoDinero as $dinero)
                                                                                @if ($entidad->enti_codigo == $dinero->enti_codigo)
                                                                                    ${{ number_format($dinero->suma_dinero, 0, ',', '.') }}
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (sizeof($recursoEspecies) == 0)
                                                                            $0
                                                                        @else
                                                                            @foreach ($recursoEspecies as $especie)
                                                                                @if ($entidad->enti_codigo == $especie->enti_codigo)
                                                                                    <div style="white-space:nowrap">
                                                                                        <label>{{ $especie->coes_nombre }}:</label>
                                                                                        ${{ number_format($especie->suma_especies, 0, ',', '.') }}
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (sizeof($recursoInfraestructura) == 0)
                                                                            $0
                                                                        @else
                                                                            @foreach ($recursoInfraestructura as $infraestructura)
                                                                                @if ($entidad->enti_codigo == $infraestructura->enti_codigo)
                                                                                    <div style="white-space:nowrap">
                                                                                        <label>{{ $infraestructura->tiin_nombre }}:</label>
                                                                                        ${{ number_format($infraestructura->suma_infraestructura, 0, ',', '.') }}
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if (sizeof($recursoRrhh) == 0)
                                                                            $0
                                                                        @else
                                                                            @foreach ($recursoRrhh as $rrhh)
                                                                                @if ($entidad->enti_codigo == $rrhh->enti_codigo)
                                                                                    <div style="white-space:nowrap">
                                                                                        <label>{{ $rrhh->tirh_nombre }}:</label>
                                                                                        ${{ number_format($rrhh->suma_rrhh, 0, ',', '.') }}
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Índice de vinculación INVI</strong></td>
                                            <td>
                                                <?php
                                                $cobertura = 0;
                                                $resultados = 0;
                                                $evaluacion = 0;

                                                if (sizeof($datosCobertura) > 0) {
                                                    $partInicial = 0;
                                                    $partFinal = 0;
                                                    foreach ($datosCobertura as $registro) {
                                                        $partInicial = $partInicial + intval($registro->part_cantidad_inicial);
                                                        $partFinal = $partFinal + intval($registro->part_cantidad_final);
                                                    }
                                                    $cobertura = round(($partFinal * 100) / $partInicial);
                                                }
                                                if (sizeof($datosResultados) > 0) {
                                                    $resuInicial = 0;
                                                    $resuFinal = 0;
                                                    foreach ($datosResultados as $registro) {
                                                        $resuInicial = $resuInicial + intval($registro->resu_cuantificacion_inicial);
                                                        $resuFinal = $resuFinal + intval($registro->resu_cuantificacion_final);
                                                    }
                                                    $resultados = round(($resuFinal * 100) / $resuInicial);
                                                }
                                                if ($datosEvaluacion != null) {
                                                    $evaluacion = intval($datosEvaluacion->eval_plazos) + intval($datosEvaluacion->eval_horarios) + intval($datosEvaluacion->eval_infraestructura) + intval($datosEvaluacion->eval_equipamiento) + intval($datosEvaluacion->eval_conexion_dl) + intval($datosEvaluacion->eval_desempenho_responsable) + intval($datosEvaluacion->eval_desempenho_participantes) + intval($datosEvaluacion->eval_calidad_presentaciones);
                                                    $evaluacion = round(($evaluacion * 20) / 8);
                                                }

                                                $indice = round(0.2 * $datosMecanismo->meca_puntaje + 0.1 * $datosFrecuencia->frec_puntaje + 0.1 * $cobertura + 0.25 * $resultados + 0.35 * $evaluacion);
                                                echo $indice;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>INVI - Mecanismo</strong></td>
                                            <td>{{ $datosMecanismo->meca_puntaje }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>INVI - Frecuencia</strong></td>
                                            <td>{{ $datosFrecuencia->frec_puntaje }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>INVI - Cobertura</strong></td>
                                            <td>
                                                @if (sizeof($datosCobertura) == 0)
                                                    0
                                                @else
                                                    <?php
                                                    $cobertura = 0;
                                                    $partInicial = 0;
                                                    $partFinal = 0;
                                                    foreach ($datosCobertura as $registro) {
                                                        $partInicial = $partInicial + intval($registro->part_cantidad_inicial);
                                                        $partFinal = $partFinal + intval($registro->part_cantidad_final);
                                                    }
                                                    $cobertura = round(($partFinal * 100) / $partInicial);
                                                    echo $cobertura;
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>INVI - Resultados</strong></td>
                                            <td>
                                                @if (sizeof($datosResultados) == 0)
                                                    0
                                                @else
                                                    <?php
                                                    $resultados = 0;
                                                    $resuInicial = 0;
                                                    $resuFinal = 0;
                                                    foreach ($datosResultados as $registro) {
                                                        $resuInicial = $resuInicial + intval($registro->resu_cuantificacion_inicial);
                                                        $resuFinal = $resuFinal + intval($registro->resu_cuantificacion_final);
                                                    }
                                                    $resultados = round(($resuFinal * 100) / $resuInicial);
                                                    echo $resultados;
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>INVI - Evaluación</strong></td>
                                            <td>
                                                @if ($datosEvaluacion == null)
                                                    0
                                                @else
                                                    <?php
                                                    $evaluacion = 0;
                                                    $evaluacion = intval($datosEvaluacion->eval_plazos) + intval($datosEvaluacion->eval_horarios) + intval($datosEvaluacion->eval_infraestructura) + intval($datosEvaluacion->eval_equipamiento) + intval($datosEvaluacion->eval_conexion_dl) + intval($datosEvaluacion->eval_desempenho_responsable) + intval($datosEvaluacion->eval_desempenho_participantes) + intval($datosEvaluacion->eval_calidad_presentaciones);
                                                    $evaluacion = round(($evaluacion * 20) / 8);
                                                    echo $evaluacion;
                                                    ?>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha de ingreso</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($iniciativa->inic_creado)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Fecha última actualización</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($iniciativa->inic_actualizado)->format('d-m-Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Modificado por</strong></td>
                                            <td>{{ $iniciativa->inic_rut_mod }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if (count($objetivos) > 0)
                                <h2 class="section-title">Objetivos de Desarrollo Sostenible donde contribuye</h2>
                                <div class="row" id="div-ods">
                                    @foreach ($objetivos as $ods)
                                        <div class="col-6 col-md-3 col-lg-2 mb-5">
                                            <div class="country" data-name="{{ $ods->obde_nombre }}"
                                                data-continent="{{ $ods->obde_nombre }}">
                                                <img class="img-fluid" src="{{ asset($ods->obde_ruta_imagen) }}"
                                                    alt="{{ $ods->obde_nombre }}" title="{{ $ods->obde_nombre }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="row" id="div-ods"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalINVI" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
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
                        <table class="table table-bordered table-md" style="border: 1px ghostwhite solid; th,">
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
                <form action="{{ route('admin.iniciativas.destroy') }}" method="POST" id="form-eliminar-iniciativa">
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

    <div class="modal fade" id="modalAprobarIniciativa" tabindex="-1" role="dialog" aria-labelledby="modalAprobar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.iniciativas.aprobar', $iniciativa->inic_codigo) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAprobar">Aprobar iniciativa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-check-circle text-success" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">Se aprobará esta iniciativa. <br> ¿Desea continuar?</h6>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRechazarIniciativa" tabindex="-1" role="dialog" aria-labelledby="modalRechazar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.iniciativas.rechazar', $iniciativa->inic_codigo) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalRechazar">Rechazar iniciativa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">Se rechazará esta iniciativa. <br> ¿Desea continuar?</h6>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
    <script src="{{ asset('public/js/admin/iniciativas/listar.js') }}"></script>


@endsection
