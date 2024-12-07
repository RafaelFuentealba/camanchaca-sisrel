@extends('admin.panel_admin')
@section('contenido')
    <div class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">

                        <div class="colxl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('errorDonacion'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorDonacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Agregar donación</h4>

                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearOrga"><i class="fas fa-plus"></i> Nueva Organización</button>

                            </div>


                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-1"></div>
                                <div class="col-xl-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Organización</label>
                                        <select class="form-control select2" id="orga_codigo" name="orga_codigo" style="width: 100%"
                                            onchange="cargarDirigentes()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($organizaciones as $organizacion)
                                                <option value="{{ $organizacion->orga_codigo }}"
                                                    {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                                    {{ $organizacion->orga_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                        <input onchange="MostrarDirigentes()" class="form-check-input" type="checkbox"
                                            name="esdirigente" id="esdirigente">
                                        <small class="form-check-label"><strong>El receptor es dirigente</strong></small>

                                        @if ($errors->has('orga_codigo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('orga_codigo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label>Comuna</label>
                                        <select class="form-control select2" id="comu_codigo" name="comu_codigo" style="width: 100%" onchange="cargarComuna()">
                                            <option value="" selected disabled>Seleccione...</option>
                                            @forelse ($comunas as $comuna)
                                                <option value="{{ $comuna->comu_codigo }}"
                                                    {{ Request::get('comu_codigo') == $comuna->comu_codigo ? 'selected' : '' }}
                                                    >
                                                    {{ $comuna->comu_nombre }}
                                                </option>
                                            @empty
                                                <option value="-1">No existen registros</option>
                                            @endforelse
                                        </select>
                                        @if ($errors->has('comu_codigo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('comu_codigo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2" id="div-select-dirigentes"
                                    name="div-select-dirigentes">
                                    <div class="form-group">
                                        <label>Dirigente</label>
                                        <select class="form-control" id="diri_codigo" name="diri_codigo"
                                            onchange="CargarDatosDirigente()">

                                        </select>
                                    </div>
                                </div>


                            </div>
                            <form action="{{ route('admin.donaciones.guardar') }}" method="POST">
                                @method('POST')
                                @csrf

                                <input type="text" class="form-control " id="dirigente" name="dirigente" value="">
                                @if ($errors->has('dirigente'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('dirigente') }}</strong>
                                        </div>
                                    </div>
                                @endif
                                <input type="text" class="form-control " id="organizacion" name="organizacion"
                                    value="" style="display: none;">
                                <input type="text" class="form-control " id="orga_dona" name="orga_dona"
                                    value="" style="display: none;">
                                <input type="text" class="form-control " id="comu_dona" name="comu_dona"
                                    value="" style="display: none;">
                                @if ($errors->has('organizacion'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('organizacion') }}</strong>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-xl-1"></div>
                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="dona_motivo">Motivo de la donación</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="dona_motivo"
                                                    name="dona_motivo" value="{{ old('dona_motivo') }}">
                                            </div>
                                            @if ($errors->has('dona_motivo'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_motivo') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="dona_nombre_solicitante">Nombre del solicitante</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="text" id="dona_nombre_solicitante"
                                                    name="dona_nombre_solicitante"
                                                    value="{{ old('dona_nombre_solicitante') }}">
                                            </div>
                                            @if ($errors->has('dona_nombre_solicitante'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_nombre_solicitante') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="dona_cargo_solicitante">Cargo del solicitante</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="far fa-address-card"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="text" id="dona_cargo_solicitante"
                                                    name="dona_cargo_solicitante"
                                                    value="{{ old('dona_cargo_solicitante') }}">
                                            </div>
                                            @if ($errors->has('dona_cargo_solicitante'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_cargo_solicitante') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-1"></div>
                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="dona_persona_aprueba">Aprobado por</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="text" id="dona_persona_aprueba"
                                                    name="dona_persona_aprueba"
                                                    value="{{ old('dona_persona_aprueba') }}">
                                            </div>
                                            @if ($errors->has('dona_persona_aprueba'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_persona_aprueba') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_monto">Monto donado</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="number" id="dona_monto"
                                                    name="dona_monto" value="{{ old('dona_monto') }}" autocomplete="off"
                                                    min="0">
                                            </div>
                                            @if ($errors->has('dona_monto'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_monto') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-3 col-lg-3">
                                        <div class="form-group">
                                            <label for="dona_persona_recepciona">Recepcionista de donación</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-hand-holding-usd"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="text" id="dona_persona_recepciona"
                                                    name="dona_persona_recepciona"
                                                    value="{{ old('dona_persona_recepciona') }}">
                                            </div>
                                            @if ($errors->has('dona_persona_recepciona'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('dona_persona_recepciona') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="pila_codigo">Pilar asociado</label>
                                            <div class="input-group">
                                                <select name="pila_codigo" id="pila_codigo" class="form-control" style="width: 100%">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    @foreach ($pilares as $pila)
                                                        <option value="{{ $pila->pila_codigo }}"
                                                            {{ old('pila_codigo') == $pila->pila_codigo ? 'selected' : '' }}>
                                                            {{ $pila->pila_nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if ($errors->has('pila_codigo'))
                                                <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                                    <div class="alert-body">
                                                        <button class="close"
                                                            data-dismiss="alert"><span>&times;</span></button>
                                                        <strong>{{ $errors->first('pila_codigo') }}</strong>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-1"></div>
                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_fecha_entrega">Fecha de entrega</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-calendar-check"></i>
                                                    </div>
                                                </div>
                                                <input class="form-control" type="date" id="dona_fecha_entrega"
                                                    name="dona_fecha_entrega" value="{{ old('dona_fecha_entrega') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_estado">Estado de la donación</label>
                                            <div class="input-group">
                                                <select name="dona_estado" id="dona_estado" class="form-control">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="Pendiente compra"
                                                        {{ old('dona_estado') == 'Pendiente compra' ? 'selected' : '' }}>
                                                        Pendiente compra</option>
                                                    <option value="Pendiente aprobación"
                                                        {{ old('dona_estado') == 'Pendiente aprobación' ? 'selected' : '' }}>
                                                        Pendiente aprobación</option>
                                                    <option value="Pendiente entrega"
                                                        {{ old('dona_estado') == 'Pendiente entrega' ? 'selected' : '' }}>
                                                        Pendiente entrega</option>
                                                    <option value="Rechazada"
                                                        {{ old('dona_estado') == 'Rechazada' ? 'selected' : '' }}>Rechazada
                                                    </option>
                                                    <option value="En evaluación"
                                                        {{ old('dona_estado') == 'En evaluación' ? 'selected' : '' }}>En
                                                        evaluación</option>
                                                    <option value="Aprobada"
                                                        {{ old('dona_estado') == 'Aprobada' ? 'selected' : '' }}>Aprobada
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_form_autorizacion">Estado del formulario de
                                                autorización</label>
                                            <div class="input-group">
                                                <select name="dona_form_autorizacion" id="dona_form_autorizacion"
                                                    class="form-control">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="Listo"
                                                        {{ old('dona_form_autorizacion') == 'Listo' ? 'selected' : '' }}>
                                                        Listo</option>
                                                    <option value="Pendiente"
                                                        {{ old('dona_form_autorizacion') == 'Pendiente' ? 'selected' : '' }}>
                                                        Pendiente</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_declaracion_jurada">Estado de declaración jurada</label>
                                            <div class="input-group">
                                                <select name="dona_declaracion_jurada" id="dona_declaracion_jurada"
                                                    class="form-control">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="Listo"
                                                        {{ old('dona_declaracion_jurada') == 'Listo' ? 'selected' : '' }}>
                                                        Listo</option>
                                                    <option value="Pendiente"
                                                        {{ old('dona_declaracion_jurada') == 'Pendiente' ? 'selected' : '' }}>
                                                        Pendiente</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-2 col-lg-2">
                                        <div class="form-group">
                                            <label for="dona_tipo_aporte">Tipo de aporte</label>
                                            <div class="input-group">
                                                <select name="dona_tipo_aporte" id="dona_tipo_aporte"
                                                    class="form-control">
                                                    <option value="" disabled selected>Seleccione...</option>
                                                    <option value="Patrocinios y auspicios"
                                                        {{ old('dona_tipo_aporte') == 'Patrocinios y auspicios' ? 'selected' : '' }}>
                                                        Patrocinios y auspicios</option>
                                                    <option value="Capacidades de la empresa"
                                                        {{ old('dona_tipo_aporte') == 'Capacidades de la empresa' ? 'selected' : '' }}>
                                                        Capacidades de la empresa
                                                    </option>
                                                    <option value="Emergencias y solidaridad"
                                                        {{ old('dona_tipo_aporte') == 'Emergencias y solidaridad' ? 'selected' : '' }}>
                                                        Emergencias y solidaridad
                                                    </option>
                                                    <option value="Vinculación"
                                                        {{ old('dona_tipo_aporte') == 'Vinculación' ? 'selected' : '' }}>
                                                        Vinculación</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xl-1"></div>

                                    <div class="col-xl-4 col-md-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="dona_descripcion">Descripción</label>
                                            <div class="input-group">
                                                <textarea name="dona_descripcion" class="formbold-form-input" id="dona_descripcion" cols="60" rows="6">{{ old('dona_descripcion') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-12 text-right">
                                    <a href="{{ route('admin.dbgeneral.index') }}" type="button"
                                        class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i>
                                        Volver</a>
                                    <a href="{{ route('admin.donaciones.listar') }}" type="button"
                                        class="btn btn-success" title="Ir a lista"><i class="fas fa-backward"></i> Ir a
                                        donaciones</a>
                                    <button type="submit" class="btn btn-primary waves-effect"><i
                                            class="fas fa-save"></i> Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalCrearOrga" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva Organización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.actividad.orga.crear') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="nombre">Nombre de la organización</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    value="{{ old('nombre') }}" autocomplete="off" min="0">
                            </div>
                            @if ($errors->has('nombre'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('nombre') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="form-group ">
                            <label for="comuna">Comuna</label>
                            <div class="input-group">
                                <select class="form-control" name="comuna" id="comuna">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($comunas as $comuna)
                                        <option value="{{ $comuna->comu_codigo }}"
                                            {{ old('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
                                            {{ $comuna->comu_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('comuna'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('comuna') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="tiporg">Tipo de organización</label>
                            <div class="input-group">

                                <select class="form-control form-control-sm" name="tiporg" id="tiporg">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($tipos as $tipo)
                                        <option value="{{ $tipo->ento_codigo }}"
                                            {{ old('tiporg') == $tipo->ento_codigo ? 'selected' : '' }}>
                                            {{ $tipo->ento_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('tiporg'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('tiporg') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i>
                                Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/js/admin/donaciones/donaciones.js') }}"></script>
@endsection
