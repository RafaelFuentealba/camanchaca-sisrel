@extends('digitador.panel_digitador')
@section('contenido')
<div class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">

                    <div class="col-xl-3"></div>
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
                            <input onchange="MostrarDirigentes()" class="form-check-input" type="checkbox" name="esdirigente" id="esdirigente">
                            <small class="form-check-label"><strong>El receptor es dirigente</strong></small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-1"></div>
                            <div class="col-xl-4 col-md-4 col-lg-4">
                                <div class="form-group">
                                    <label>Organización</label>
                                    <select class="form-control select2" id="orga_codigo" name="orga_codigo" onchange="cargarDirigentes()">
                                        <option value="" selected disabled>Seleccione...</option>
                                        @forelse ($organizaciones as $organizacion)
                                        <option value="{{ $organizacion->orga_codigo }}" {{ Request::get('orga_codigo') == $organizacion->orga_codigo ? 'selected' : '' }}>
                                            {{ $organizacion->orga_nombre }}
                                        </option>
                                        @empty
                                        <option value="-1">No existen registros</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-4 col-lg-4" id="div-select-dirigentes" name="div-select-dirigentes">
                                <div class="form-group">
                                    <label>Dirigente</label>
                                    <select class="form-control" id="diri_codigo" name="diri_codigo" onchange="CargarDatosDirigente()">

                                    </select>
                                </div>
                            </div>


                        </div>
                        <form action="{{route('digitador.donaciones.guardar')}}" method="POST">
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
                            <input type="text" class="form-control " id="organizacion" name="organizacion" value="">
                            @if ($errors->has('organizacion'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('organizacion') }}</strong>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-1"></div>
                                <div class="col-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label for="dona_motivo">Motivo de la donación</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="dona_motivo" name="dona_motivo" value="{{ old('dona_motivo') }}">
                                        </div>
                                        @if ($errors->has('dona_motivo'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="text" id="dona_nombre_solicitante" name="dona_nombre_solicitante" value="{{ old('dona_nombre_solicitante') }}">
                                        </div>
                                        @if ($errors->has('dona_nombre_solicitante'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="text" id="dona_cargo_solicitante" name="dona_cargo_solicitante" value="{{ old('dona_cargo_solicitante') }}">
                                        </div>
                                        @if ($errors->has('dona_cargo_solicitante'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="text" id="dona_persona_aprueba" name="dona_persona_aprueba" value="{{ old('dona_persona_aprueba') }}">
                                        </div>
                                        @if ($errors->has('dona_persona_aprueba'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="number" id="dona_monto" name="dona_monto" value="{{ old('dona_monto') }}" autocomplete="off" min="0">
                                        </div>
                                        @if ($errors->has('dona_monto'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="text" id="dona_persona_recepciona" name="dona_persona_recepciona" value="{{ old('dona_persona_recepciona') }}">
                                        </div>
                                        @if ($errors->has('dona_persona_recepciona'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <select name="pila_codigo" id="pila_codigo" class="form-control">
                                                <option value="" disabled selected>Seleccione...</option>
                                                @foreach ($pilares as $pila)
                                                    <option value="{{$pila->pila_codigo}}" {{old('pila_codigo') == $pila->pila_codigo ? 'selected':''}}>{{$pila->pila_nombre}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('pila_codigo'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2 ">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
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
                                            <input class="form-control" type="date" id="dona_fecha_entrega" name="dona_fecha_entrega" value="{{ old('dona_fecha_entrega') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="dona_estado">Estado de la donación</label>
                                        <div class="input-group">
                                            <select name="dona_estado" id="dona_estado" class="form-control">
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Pendiente compra" {{old('dona_estado') == "Pendiente compra" ? 'selected':''}}>Pendiente compra</option>
                                                <option value="Pendiente aprobación" {{old('dona_estado') == "Pendiente aprobación" ? 'selected':''}}>Pendiente aprobación</option>
                                                <option value="Pendiente entrega" {{old('dona_estado') == "Pendiente entrega" ? 'selected':''}}>Pendiente entrega</option>
                                                <option value="Rechazada" {{old('dona_estado') == "Rechazada" ? 'selected':''}}>Rechazada</option>
                                                <option value="En evaluación" {{old('dona_estado') == "En evaluación" ? 'selected':''}}>En evaluación</option>
                                                <option value="Aprobada" {{old('dona_estado') == "Aprobada" ? 'selected':''}}>Aprobada</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="dona_form_autorizacion">Estado del formulario de
                                            autorización</label>
                                        <div class="input-group">
                                            <select name="dona_form_autorizacion" id="dona_form_autorizacion" class="form-control">
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Listo" {{old('dona_form_autorizacion') == "Listo" ? 'selected':''}}>Listo</option>
                                                <option value="Pendiente" {{old('dona_form_autorizacion') == "Pendiente" ? 'selected':''}}>Pendiente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="dona_declaracion_jurada">Estado de declaración jurada</label>
                                        <div class="input-group">
                                            <select name="dona_declaracion_jurada" id="dona_declaracion_jurada" class="form-control">
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Listo" {{old('dona_declaracion_jurada') == "Listo" ? 'selected':''}}>Listo</option>
                                                <option value="Pendiente" {{old('dona_declaracion_jurada') == "Pendiente" ? 'selected':''}}>Pendiente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-2 col-md-2 col-lg-2">
                                    <div class="form-group">
                                        <label for="dona_tipo_aporte">Tipo de aporte</label>
                                        <div class="input-group">
                                            <select name="dona_tipo_aporte" id="dona_tipo_aporte" class="form-control">
                                                <option value="" disabled selected>Seleccione...</option>
                                                <option value="Patrocinios y auspicios" {{old('dona_tipo_aporte') == "Patrocinios y auspicios" ? 'selected':''}}>Patrocinios y auspicios</option>
                                                <option value="Capacidades de la empresa" {{old('dona_tipo_aporte') == "Capacidades de la empresa" ? 'selected':''}}>Capacidades de la empresa
                                                </option>
                                                <option value="Emergencias y solidaridad" {{old('dona_tipo_aporte') == "Emergencias y solidaridad" ? 'selected':''}}>Emergencias y solidaridad
                                                </option>
                                                <option value="Vinculación" {{old('dona_tipo_aporte') == "Vinculación" ? 'selected':''}}>Vinculación</option>
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
                            <div class="text-right">
                                <a href="{{route('digitador.dbgeneral.index')}}" type="button" class="btn btn-warning" title="Ir a inicio"><i class="fas fa-home"></i> Volver</a>
                                <a href="{{route('digitador.donaciones.listar')}}" type="button" class="btn btn-success" title="Ir a lista"><i class="fas fa-backward"></i> Ir a donaciones</a>
                                <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/js/digitador/donaciones/donaciones.js') }}"></script>
@endsection
