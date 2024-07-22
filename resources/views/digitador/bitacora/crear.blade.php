@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorActividad'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorActividad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-3 col-md-3 col-lg-3"></div>
                <div class="col-xl-6 col-md-6 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ isset($actividad) ? 'Formulario edición de actividad' : 'Formulario ingreso de actividad' }}
                            </h4>
                        </div>
                        <div class="card-body">
                            @if (isset($actividad))
                                <form action="{{ route('digitador.actividad.actualizar', $actividad->acti_codigo) }}"
                                    method="POST">
                                    @method('PUT')
                                @else
                                    <form action="{{ route('digitador.actividad.guardar') }}" method="POST">
                            @endif
                            @csrf

                            <div class="form-group">
                                <label>Comuna</label> <label for="" style="color: red;">*</label>
                                @if (isset($actividad))
                                    <select class="form-control select2" style="width: 100%" id="comuna" name="comuna">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($comunas as $comu)
                                            <option value="{{ $comu->comu_codigo }}"
                                                {{ $actividad->comu_codigo == $comu->comu_codigo ? 'selected' : '' }}>
                                                {{ $comu->comu_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @else
                                    <select class="form-control select2" style="width: 100%" id="comuna" name="comuna">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($comunas as $comun)
                                            <option value="{{ $comun->comu_codigo }}"
                                                {{ old('comuna') == $comun->comu_codigo ? 'selected' : '' }}>
                                                {{ $comun->comu_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @endif
                                @if ($errors->has('comuna'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('comuna') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Organización</label> <label for="" style="color: red;">*</label>
                                @if (isset($actividad))
                                    <select class="form-control select2" style="width: 100%" id="organizacion"
                                        name="organizacion">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($organizaciones as $orga)
                                            <option value="{{ $orga->orga_codigo }}"
                                                {{ $actividad->orga_codigo == $orga->orga_codigo ? 'selected' : '' }}>
                                                {{ $orga->orga_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @else
                                    <select class="form-control select2" style="width: 100%" id="organizacion"
                                        name="organizacion">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($organizaciones as $orga)
                                            <option value="{{ $orga->orga_codigo }}"
                                                {{ old('organizacion') == $orga->orga_codigo ? 'selected' : '' }}>
                                                {{ $orga->orga_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @endif
                                @if ($errors->has('organizacion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('organizacion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Unidad</label> <label for="" style="color: red;">*</label>
                                @if (isset($actividad))
                                    <select class="form-control select2" style="width: 100%" id="unidad" name="unidad">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($unidades as $unid)
                                            <option value="{{ $unid->unid_codigo }}"
                                                {{ $actividad->unid_codigo == $unid->unid_codigo ? 'selected' : '' }}>
                                                {{ $unid->unid_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @else
                                    <select class="form-control select2" style="width: 100%" id="unidad" name="unidad">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($unidades as $unid)
                                            <option value="{{ $unid->unid_codigo }}"
                                                {{ old('unidad') == $unid->unid_codigo ? 'selected' : '' }}>
                                                {{ $unid->unid_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                @endif
                                @if ($errors->has('unidad'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('unidad') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Tipo de actividad</label> <label for=""
                                    style="color: red; display:inline-block;">*</label>
                                @if (isset($actividad))
                                    <select name="nombre" id="nombre" class="form-control select2" style="width: 100%">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="Apoyo de traslados"
                                            {{ $actividad->acti_nombre == 'Apoyo de traslados' ? 'selected' : '' }}>Apoyo de
                                            traslados</option>
                                        <option value="Boyacompostar"
                                            {{ $actividad->acti_nombre == 'Boyacompostar' ? 'selected' : '' }}>Boyacompostar
                                        </option>
                                        <option value="Capacitación"
                                            {{ $actividad->acti_nombre == 'Capacitación' ? 'selected' : '' }}>Capacitación
                                        </option>
                                        <option value="Comunicación ante Incidentes"
                                            {{ $actividad->acti_nombre == 'Comunicación ante Incidentes' ? 'selected' : '' }}>
                                            Comunicación ante Incidentes</option>
                                        <option value="Comunicación sobre Apertura/Cierre"
                                            {{ $actividad->acti_nombre == 'Comunicación sobre Apertura/Cierre' ? 'selected' : '' }}>
                                            Comunicación sobre Apertura/Cierre</option>
                                        <option value="Concientización sobre gestión de residuos"
                                            {{ $actividad->acti_nombre == 'Concientización sobre gestión de residuos' ? 'selected' : '' }}>
                                            Concientización sobre gestión de residuos</option>
                                        <option value="Entrega de donación"
                                            {{ $actividad->acti_nombre == 'Entrega de donación' ? 'selected' : '' }}>Entrega de
                                            donación</option>
                                        <option value="Escuela de Vela"
                                            {{ $actividad->acti_nombre == 'Escuela de Vela' ? 'selected' : '' }}>Escuela de
                                            Vela</option>
                                        <option value="Escuelas Sostenibles"
                                            {{ $actividad->acti_nombre == 'Escuelas Sostenibles' ? 'selected' : '' }}>Escuelas
                                            Sostenibles</option>
                                        <option value="Jornadas Formativas"
                                            {{ $actividad->acti_nombre == 'Jornadas Formativas' ? 'selected' : '' }}>Jornadas
                                            Formativas</option>
                                        <option value="Limpieza de Playas"
                                            {{ $actividad->acti_nombre == 'Limpieza de Playas' ? 'selected' : '' }}>Limpieza de
                                            Playas</option>
                                        <option value="Mesa de Trabajo"
                                            {{ $actividad->acti_nombre == 'Mesa de Trabajo' ? 'selected' : '' }}>Mesa de
                                            Trabajo</option>
                                        <option value="Misión Chile"
                                            {{ $actividad->acti_nombre == 'Misión Chile' ? 'selected' : '' }}>Misión Chile
                                        </option>
                                        <option value="Mitigación Impactos"
                                            {{ $actividad->acti_nombre == 'Mitigación Impactos' ? 'selected' : '' }}>Mitigación
                                            Impactos</option>
                                        <option value="Operativo de Salud"
                                            {{ $actividad->acti_nombre == 'Operativo de Salud' ? 'selected' : '' }}>Operativo
                                            de Salud</option>
                                        <option value="Planta de Procesos Hortalizas"
                                            {{ $actividad->acti_nombre == 'Planta de Procesos Hortalizas' ? 'selected' : '' }}>
                                            Planta de Procesos Hortalizas</option>
                                        <option value="Potenciando el crecimiento"
                                            {{ $actividad->acti_nombre == 'Potenciando el crecimiento' ? 'selected' : '' }}>
                                            Potenciando el crecimiento</option>
                                        <option value="Puertas Abiertas"
                                            {{ $actividad->acti_nombre == 'Puertas Abiertas' ? 'selected' : '' }}>Puertas
                                            Abiertas</option>
                                        <option value="Reunión (Inversión Social)"
                                            {{ $actividad->acti_nombre == 'Reunión (Inverisón Social)' ? 'selected' : '' }}>
                                            Reunión (Inverisón Social)</option>
                                        <option value="Reunión Alcaldes"
                                            {{ $actividad->acti_nombre == 'Reunión Alcaldes' ? 'selected' : '' }}>Reunión
                                            Alcaldes</option>
                                        <option value="Reunión Comunidad"
                                            {{ $actividad->acti_nombre == 'Reunión Comunidad' ? 'selected' : '' }}>Reunión
                                            Comunidad</option>
                                        <option value="Reunión Departamentos Municipales"
                                            {{ $actividad->acti_nombre == 'Reunión Departamentos Municipales' ? 'selected' : '' }}>
                                            Reunión Departamentos Municipales</option>
                                        <option value="Reunión Medios Comunicación"
                                            {{ $actividad->acti_nombre == 'Reunión Medios Comunicación' ? 'selected' : '' }}>
                                            Reunión Medios Comunicación</option>
                                        <option value="Reunión Auditoria 1"
                                            {{ $actividad->acti_nombre == 'Reunión Auditoria 1' ? 'selected' : '' }}>Reunión
                                            Auditoria 1</option>
                                        <option value="Slippers Camanchaca"
                                            {{ $actividad->acti_nombre == 'Slippers Camanchaca' ? 'selected' : '' }}>Slippers
                                            Camanchaca</option>
                                        <option value="Taller Comunitario"
                                            {{ $actividad->acti_nombre == 'Taller Comunitario' ? 'selected' : '' }}>Taller
                                            Comunitario</option>
                                        <option value="Otros" {{ $actividad->acti_nombre == 'Otros' ? 'selected' : '' }}>
                                            Otros</option>
                                    </select>
                                @else
                                    <select name="nombre" id="nombre" class="form-control select2"
                                        style="width: 100%">
                                        <option value="" selected disabled>Seleccione</option>
                                        <option value="Apoyo de traslados"
                                            {{ old('nombre') == 'Apoyo de traslados' ? 'selected' : '' }}>Apoyo de traslados
                                        </option>
                                        <option value="Boyacompostar"
                                            {{ old('nombre') == 'Boyacompostar' ? 'selected' : '' }}>Boyacompostar</option>
                                        <option value="Capacitación" {{ old('nombre') == 'Capacitación' ? 'selected' : '' }}>
                                            Capacitación</option>
                                        <option value="Comunicación ante Incidentes"
                                            {{ old('nombre') == 'Comunicación ante Incidentes' ? 'selected' : '' }}>
                                            Comunicación ante Incidentes</option>
                                        <option value="Comunicación sobre Apertura/Cierre"
                                            {{ old('nombre') == 'Comunicación sobre Apertura/Cierre' ? 'selected' : '' }}>
                                            Comunicación sobre Apertura/Cierre</option>
                                        <option value="Concientización sobre gestión de residuos"
                                            {{ old('nombre') == 'Concientización sobre gestión de residuos' ? 'selected' : '' }}>
                                            Concientización sobre gestión de residuos</option>
                                        <option value="Entrega de donación"
                                            {{ old('nombre') == 'Entrega de donación' ? 'selected' : '' }}>Entrega de donación
                                        </option>
                                        <option value="Escuela de Vela"
                                            {{ old('nombre') == 'Escuela de Vela' ? 'selected' : '' }}>Escuela de Vela</option>
                                        <option value="Escuelas Sostenibles"
                                            {{ old('nombre') == 'Escuelas Sostenibles' ? 'selected' : '' }}>Escuelas
                                            Sostenibles</option>
                                        <option value="Jornadas Formativas"
                                            {{ old('nombre') == 'Jornadas Formativas' ? 'selected' : '' }}>Jornadas Formativas
                                        </option>
                                        <option value="Limpieza de Playas"
                                            {{ old('nombre') == 'Limpieza de Playas' ? 'selected' : '' }}>Limpieza de Playas
                                        </option>
                                        <option value="Mesa de Trabajo"
                                            {{ old('nombre') == 'Mesa de Trabajo' ? 'selected' : '' }}>Mesa de Trabajo</option>
                                        <option value="Misión Chile" {{ old('nombre') == 'Misión Chile' ? 'selected' : '' }}>
                                            Misión Chile</option>
                                        <option value="Mitigación Impactos"
                                            {{ old('nombre') == 'Mitigación Impactos' ? 'selected' : '' }}>Mitigación Impactos
                                        </option>
                                        <option value="Operativo de Salud"
                                            {{ old('nombre') == 'Operativo de Salud' ? 'selected' : '' }}>Operativo de Salud
                                        </option>
                                        <option value="Planta de Procesos Hortalizas"
                                            {{ old('nombre') == 'Planta de Procesos Hortalizas' ? 'selected' : '' }}>Planta de
                                            Procesos Hortalizas</option>
                                        <option value="Potenciando el crecimiento"
                                            {{ old('nombre') == 'Potenciando el crecimiento' ? 'selected' : '' }}>Potenciando
                                            el crecimiento</option>
                                        <option value="Puertas Abiertas"
                                            {{ old('nombre') == 'Puertas Abiertas' ? 'selected' : '' }}>Puertas Abiertas
                                        </option>
                                        <option value="Reunión (Inverisón Social)"
                                            {{ old('nombre') == 'Reunión (Inverisón Social)' ? 'selected' : '' }}>Reunión
                                            (Inverisón Social)</option>
                                        <option value="Reunión Alcaldes"
                                            {{ old('nombre') == 'Reunión Alcaldes' ? 'selected' : '' }}>Reunión Alcaldes
                                        </option>
                                        <option value="Reunión Comunidad"
                                            {{ old('nombre') == 'Reunión Comunidad' ? 'selected' : '' }}>Reunión Comunidad
                                        </option>
                                        <option value="Reunión Departamentos Municipales"
                                            {{ old('nombre') == 'Reunión Departamentos Municipales' ? 'selected' : '' }}>
                                            Reunión Departamentos Municipales</option>
                                        <option value="Reunión Medios Comunicación"
                                            {{ old('nombre') == 'Reunión Medios Comunicación' ? 'selected' : '' }}>Reunión
                                            Medios Comunicación</option>
                                        <option value="Reunión Auditoria 1"
                                            {{ old('nombre') == 'Reunión Auditoria 1' ? 'selected' : '' }}>Reunión Auditoria 1
                                        </option>
                                        <option value="Slippers Camanchaca"
                                            {{ old('nombre') == 'Slippers Camanchaca' ? 'selected' : '' }}>Slippers Camanchaca
                                        </option>
                                        <option value="Taller Comunitario"
                                            {{ old('nombre') == 'Taller Comunitario' ? 'selected' : '' }}>Taller Comunitario
                                        </option>
                                        <option value="Otros" {{ old('nombre') == 'Otros' ? 'selected' : '' }}>Otros</option>
                                    </select>
                                @endif
                                @if ($errors->has('nombre'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('nombre') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Fecha de realización</label> <label for="" style="color: red;">*</label>
                                <input type="date" id="realizacion" name="realizacion" class="form-control datemask"
                                    value="{{ old('realizacion') ?? @\Carbon\Carbon::parse($actividad->acti_fecha)->format('Y-m-d') }}">
                                @if ($errors->has('realizacion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('realizacion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Acuerdos</label> <label for="" style="color: red;">*</label>
                                <div class="input-group">
                                    <textarea class="formbold-form-input" id="acuerdos" name="acuerdos" rows="5" style="width: 100%;">{{ old('acuerdos') ?? @$actividad->acti_acuerdos }}</textarea>
                                </div>
                                @if ($errors->has('acuerdos'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('acuerdos') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Fecha de cumplimiento</label> <label for="" style="color: red;">*</label>
                                <input type="date" id="cumplimiento" name="cumplimiento"
                                    class="form-control datemask"
                                    value="{{ old('cumplimiento') ?? @\Carbon\Carbon::parse($actividad->acti_fecha_cumplimiento)->format('Y-m-d') }}">
                                @if ($errors->has('cumplimiento'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('cumplimiento') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Avance</label> <label for="" style="color: red;">*</label>
                                @if (isset($actividad))
                                    <select class="form-control select2" id="avance" name="avance">
                                        <option value="" selected disabled>Seleccione...</option>
                                        <option value="Ejecutada"
                                            {{ $actividad->acti_avance == 'Ejecutada' ? 'selected' : '' }}>Ejecutada
                                        </option>
                                        <option value="En avance conforme a plazo"
                                            {{ $actividad->acti_avance == 'En avance conforme a plazo' ? 'selected' : '' }}>
                                            En avance conforme a plazo</option>
                                        <option value="En avance con retraso"
                                            {{ $actividad->acti_avance == 'En avance con retraso' ? 'selected' : '' }}>En
                                            avance con retraso</option>
                                        <option value="Suspendida"
                                            {{ $actividad->acti_avance == 'Suspendida' ? 'selected' : '' }}>Suspendida
                                        </option>
                                        <option value="Descartada"
                                            {{ $actividad->acti_avance == 'Descartada' ? 'selected' : '' }}>Descartada
                                        </option>
                                    </select>
                                @else
                                    <select class="form-control select2" id="avance" name="avance">
                                        <option value="" selected disabled>Seleccione...</option>
                                        <option value="Ejecutada" {{ old('avance') == 'Ejecutada' ? 'selected' : '' }}>
                                            Ejecutada</option>
                                        <option selected value="En avance conforme a plazo"
                                            {{ old('avance') == 'En avance conforme a plazo' ? 'selected' : '' }}>En avance
                                            conforme a plazo</option>
                                        <option value="En avance con retraso"
                                            {{ old('avance') == 'En avance con retraso' ? 'selected' : '' }}>En avance con
                                            retraso</option>
                                        <option value="Suspendida" {{ old('avance') == 'Suspendida' ? 'selected' : '' }}>
                                            Suspendida</option>
                                        <option value="Descartada" {{ old('avance') == 'Descartada' ? 'selected' : '' }}>
                                            Descartada</option>
                                    </select>
                                @endif
                                @if ($errors->has('avance'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('avance') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12 text-right">
                                    <a href="{{ route('digitador.dbgeneral.index') }}" type="button"
                                        class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i>
                                        Volver</a>
                                    <a href="{{ route('digitador.actividad.listar') }}" type="button"
                                        class="btn btn-success" title="Ir a lista"><i class="fas fa-backward"></i> Ir a
                                        relacionamiento</a>
                                    <button type="submit" class="btn btn-icon btn-primary"><i class="fas fa-save"></i>
                                        Siguiente</button>
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
    <script src="{{ asset('public/js/digitador/bitacora/bitacora.js') }}"></script>

@endsection
