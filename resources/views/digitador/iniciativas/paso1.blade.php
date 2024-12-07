@extends('digitador.panel_digitador')

@section('contenido')

<section class="section">
    <div class="section-body">

        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                @if(Session::has('errorPaso1'))
                    <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                        <div class="alert-body">
                            <strong>{{ Session::get('errorPaso1') }}</strong>
                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ isset($iniciativa) ? $iniciativa->inic_nombre : 'Crear iniciativa' }} - Paso 1 de 3</h4>
                    </div>
                    <div class="card-body">
                        @if (isset($iniciativa))
                            <form action="{{ route('digitador.paso1.actualizar', $iniciativa->inic_codigo) }}" method="POST">
                                @method('PUT')
                                @csrf
                        @else
                            <form action="{{ route('digitador.paso1.verificar') }}" method="POST">
                                @csrf
                        @endif

                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="form-group">
                                        <label>Nombre de actividad</label> <label for="" style="color: red;">*</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') ?? @$iniciativa->inic_nombre }}">
                                        @if($errors->has('nombre'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('nombre') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label>Descripción y objetivos</label> <label for="" style="color: red;">*</label>
                                        <div class="input-group">
                                            <textarea class="formbold-form-input" id="descripcion" name="descripcion" rows="5" style="width: 100%;">{{ old('descripcion') ?? @$iniciativa->inic_objetivo_desc }}</textarea>
                                        </div>
                                        @if($errors->has('descripcion'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('descripcion') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Fecha de inicio</label> <label for="" style="color: red;">*</label>
                                        <input type="date" class="form-control" id="fechainicio" name="fechainicio" value="{{ old('fechainicio') ?? @\Carbon\Carbon::parse($iniciativa->inic_fecha_inicio)->format('Y-m-d') }}">
                                        @if($errors->has('fechainicio'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('fechainicio') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Fecha de finalización</label>
                                        <input type="date" class="form-control" id="fechafin" name="fechafin" value="{{ old('fechafin') ?? @\Carbon\Carbon::parse($iniciativa->inic_fecha_fin)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="col-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Frecuencia</label> <label for="" style="color: red;">*</label>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="frecuencia" name="frecuencia">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($frecuencias as $frecuencia)
                                                    <option value="{{ $frecuencia->frec_codigo }}" {{ $iniciativa->frec_codigo==$frecuencia->frec_codigo ? 'selected' : '' }}>{{ $frecuencia->frec_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="frecuencia" name="frecuencia">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($frecuencias as $frecuencia)
                                                    <option value="{{ $frecuencia->frec_codigo }}" {{ old('frecuencia')==$frecuencia->frec_codigo ? 'selected' : '' }}>{{ $frecuencia->frec_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('frecuencia'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('frecuencia') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-3 col-md-3 col-lg-3">
                                    <div class="form-group">
                                        <label>Pilar</label> <label for="" style="color: red;">*</label><i data-toggle="tooltip" data-placement="right" title="Pilar programa Camanchaca Amiga" class="fas fa-info-circle"></i>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="pilar" name="pilar">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($pilares as $pilar)
                                                    <option value="{{ $pilar->pila_codigo }}" {{ $iniciativa->pila_codigo==$pilar->pila_codigo ? 'selected' : '' }}>{{ $pilar->pila_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="pilar" name="pilar">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($pilares as $pilar)
                                                    <option value="{{ $pilar->pila_codigo }}" {{ old('pilar')==$pilar->pila_codigo ? 'selected' : '' }}>{{ $pilar->pila_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('pilar'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('pilar') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Unidad</label> <label for="" style="color: red;">*</label>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" multiple="" id="unidad" name="unidad[]">
                                                @forelse ($unidades as $unidad)
                                                    <option value="{{ $unidad->unid_codigo }}" {{ in_array($unidad->unid_codigo, $iniciativasUnidades) ? 'selected' : '' }}>{{ $unidad->unid_nombre }} ({{ $unidad->comu_nombre }})</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" multiple="" id="unidad" name="unidad[]">
                                                @forelse ($unidades as $unidad)
                                                    <option value="{{ $unidad->unid_codigo }}" {{ (collect(old('unidad'))->contains($unidad->unid_codigo)) ? 'selected' : '' }}>{{ $unidad->unid_nombre }} ({{ $unidad->comu_nombre }})</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('unidad'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('unidad') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Convenio</label> <i data-toggle="tooltip" data-placement="right" title="Convenio con entidad participante" class="fas fa-info-circle"></i>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="convenio" name="convenio">
                                                <option value="">Seleccione...</option>
                                                @forelse ($convenios as $convenio)
                                                    <option value="{{ $convenio->conv_codigo }}" {{ $iniciativa->conv_codigo==$convenio->conv_codigo ? 'selected' : '' }}>{{ $convenio->conv_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="convenio" name="convenio">
                                                <option value="">Seleccione...</option>
                                                @forelse ($convenios as $convenio)
                                                    <option value="{{ $convenio->conv_codigo }}" {{ old('convenio')==$convenio->conv_codigo ? 'selected' : '' }}>{{ $convenio->conv_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('convenio'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('convenio') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Mecanismo</label> <label for="" style="color: red;">*</label>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="mecanismo" name="mecanismo"
                                                style="width: 100%">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($mecanismos as $mecanismo)
                                                    @if (count($mecanismoSeleccionado) > 0)
                                                        <option value="{{ $mecanismo->meca_codigo }}"
                                                            {{ $mecanismoSeleccionado[0]->meca_codigo == $mecanismo->meca_codigo ? 'selected' : '' }}>
                                                            {{ $mecanismo->meca_nombre }}</option>
                                                    @else
                                                        <option value="{{ $mecanismo->meca_codigo }}">
                                                            {{ $mecanismo->meca_nombre }}</option>
                                                    @endif
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="mecanismo" name="mecanismo"
                                                style="width: 100%">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($mecanismos as $mecanismo)
                                                    <option value="{{ $mecanismo->meca_codigo }}"
                                                        {{ old('mecanismo') == $mecanismo->meca_codigo ? 'selected' : '' }}>
                                                        {{ $mecanismo->meca_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if ($errors->has('mecanismo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close"
                                                        data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('mecanismo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Actividad</label> <label for="" style="color: red;">*</label>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="submecanismo" name="submecanismo">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($mecanismos as $mecanismo)
                                                    <option value="{{ $mecanismo->subm_codigo }}" {{ $iniciativa->subm_codigo==$mecanismo->subm_codigo ? 'selected' : '' }}>{{ $mecanismo->subm_nombre }} ({{ $mecanismo->meca_nombre }})</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="submecanismo" name="submecanismo">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($mecanismos as $mecanismo)
                                                    <option value="{{ $mecanismo->subm_codigo }}" {{ old('submecanismo')==$mecanismo->subm_codigo ? 'selected' : '' }}>{{ $mecanismo->subm_nombre }} ({{ $mecanismo->meca_nombre }})</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('submecanismo'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('submecanismo') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Formato de implementación</label> <label for="" style="color: red;">*</label>
                                        @if (isset($iniciativa))
                                            <select class="form-control select2" id="implementacion" name="implementacion">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($formatos as $formato)
                                                    <option value="{{ $formato->foim_codigo }}" {{ $iniciativa->foim_codigo==$formato->foim_codigo ? 'selected' : '' }}>{{ $formato->foim_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @else
                                            <select class="form-control select2" id="implementacion" name="implementacion">
                                                <option value="" selected disabled>Seleccione...</option>
                                                @forelse ($formatos as $formato)
                                                    <option value="{{ $formato->foim_codigo }}" {{ old('implementacion')==$formato->foim_codigo ? 'selected' : '' }}>{{ $formato->foim_nombre }}</option>
                                                @empty
                                                    <option value="-1">No existen registros</option>
                                                @endforelse
                                            </select>
                                        @endif
                                        @if($errors->has('implementacion'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('implementacion') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Nombre encargado responsable</label>
                                        <input type="text" class="form-control" id="nombreresponsable" name="nombreresponsable" value="{{ old('nombreresponsable') ?? @$iniciativa->inic_nombre_responsable }}">
                                        @if($errors->has('nombreresponsable'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('nombreresponsable') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-4 col-md-4 col-lg-4">
                                    <div class="form-group">
                                        <label>Cargo encargado responsable</label>
                                        <input type="text" class="form-control" id="cargoresponsable" name="cargoresponsable" value="{{ old('cargoresponsable') ?? @$iniciativa->inic_cargo_responsable }}">
                                        @if($errors->has('cargoresponsable'))
                                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                                <div class="alert-body">
                                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                    <strong>{{ $errors->first('cargoresponsable') }}</strong>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12 col-lg-12">
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary mr-1 waves-effect">Siguiente <i class="fas fa-chevron-right"></i></button>
                                        @if (isset($iniciativa))
                                            <a href="{{ route('digitador.paso1.editar', $iniciativa->inic_codigo) }}" type="button" class="btn btn-warning waves-effect">Recargar</a>
                                        @else
                                            <a href="{{ route('digitador.paso1.crear') }}" type="button" class="btn btn-warning waves-effect">Recargar</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // TODO:Función para cargar dinamicamente las actividades de acuerdo al mecanismo al que pertenecen
            $('#mecanismo').on('change', function() {
                var mecanismo = $('#mecanismo').val();
                $.ajax({
                    type: 'POST',
                    url: `${window.location.origin}/digitador/iniciativa/obtener/submecanismos`,
                    data: {
                        meca_codigo: mecanismo
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(mecaListar) {
                        respuesta = JSON.parse(mecaListar);
                        $('#submecanismo').find('option').not(':first').remove();
                        $('#submecanismo').prop('selectedIndex', 0);
                        if (!respuesta.status) {

                            $('#submecanismo').append(new Option('No exiten registros', '-1'))
                            return
                        }

                        aSubmecanismo = respuesta.resultado;
                        aSubmecanismo.forEach(submecanismo => {
                            $('#submecanismo').append(new Option(submecanismo
                                .subm_nombre, submecanismo.subm_codigo))
                        });

                    },
                    error: function(error) {
                        console.error(error);
                    }
                })
            })
        })
    </script>

@endsection
