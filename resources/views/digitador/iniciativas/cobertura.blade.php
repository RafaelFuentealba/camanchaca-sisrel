@extends('digitador.panel_digitador')

@section('contenido')

<section class="section">
    <div class="section-body">
        <div class="row">            
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorCobertura'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorCobertura') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if(Session::has('exitoCobertura'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoCobertura') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>                
                <div class="card">
                    <div class="card-header">
                        <h4>{{ $iniciativa->inic_nombre }} - Cobertura</h4>
                    </div>                    
                    <div class="card-body">
                        <h6>Registro de participantes finales</h6>
                        <form action="{{ route('digitador.cobertura.update', $iniciativa->inic_codigo) }}" method="POST">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <div class="card">
                                        <div class="card-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-md">
                                                    <tr>
                                                        <th>Entorno</th>
                                                        <th>Subentorno</th>
                                                        <th>Participantes iniciales</th>
                                                        <th>Participantes finales</th>
                                                    </tr>
                                                    <tbody>
                                                        @foreach ($participantes as $participante)
                                                            <tr>
                                                                <td>{{ $participante->ento_nombre }}</td>
                                                                <td>{{ $participante->sube_nombre }}</td>
                                                                <td>{{ $participante->part_cantidad_inicial }}</td>
                                                                <td>
                                                                    <input type="number" class="form-control" id="cantidad-{{ $participante->sube_codigo }}" name="{{ $participante->sube_codigo }}" value="{{ $participante->part_cantidad_final }}" min="0">
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
                                    <a href="{{ route('digitador.iniciativas.index') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-angle-left"></i> Volver al listado</a>
                                    <button type="submit" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-save"></i> Guardar participantes</button>
                                </div>
                            </div>
                        </form>
                        
                        <hr>

                        <h6>Desagregación de participantes</h6>
                        <div class="row mt-3">
                            <div class="col-3 col-md-3 col-lg-3"></div>
                            <div class="col-3 col-md-3 col-lg-3">
                                <div class="form-group">
                                    <label>Subentorno</label>
                                    <select class="form-control select2" id="participante" name="participante" onchange="cargarCantidad()">
                                        <option value="" selected disabled>Seleccione...</option> 
                                        @forelse ($participantes as $participante)
                                            <option value="{{ $participante->inic_codigo }}-{{ $participante->sube_codigo }}">{{ $participante->sube_nombre }}</option>
                                        @empty
                                            <option value="-1">No existen registros</option>
                                        @endforelse                                            
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label>Participantes finales</label>
                                    <input type="text" class="form-control" id="cantidadfinal" name="cantidadfinal" disabled>
                                    <input type="hidden" id="codigo" name="codigo" value="{{ $iniciativa->inic_codigo }}">
                                </div>
                            </div>
                            <div class="col-4 col-md-4 col-lg-4">
                                <button style="position: absolute; top: 50%; transform: translateY(-50%);" type="button" class="btn btn-primary waves-effect" onclick="actualizarParticipante()" id="button-agregar-participantes"><i class="fas fa-plus"></i> Agregar</button>
                            </div>
                            <div class="col-3 col-md-3 col-lg-3"></div>
                            <div class="col-6 col-md-6 col-lg-6 text-center" id="div-alert-participante"></div>
                            <div class="col-3 col-md-3 col-lg-3"></div>
                            <div class="col-1 col-md-1 col-lg-1"></div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkgenero">
                                        <label class="custom-control-label" for="checkgenero">¿Género?</label>
                                    </div>
                                </div>
                                <div class="form-group mt-0">
                                    <input type="number" class="form-control" id="generohombre" name="generohombre" placeholder="Hombre" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="generomujer" name="generomujer" placeholder="Mujer" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="generootro" name="generootro" placeholder="Otro" autocomplete="off" min="0" disabled>
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checketario">
                                        <label class="custom-control-label" for="checketario">¿Segmento etario?</label>
                                    </div>
                                </div>
                                <div class="form-group mt-0">
                                    <input type="number" class="form-control" id="etarioninhos" name="etarioninhos" placeholder="Niños" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="etariojovenes" name="etariojovenes" placeholder="Jóvenes" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="etarioadultos" name="etarioadultos" placeholder="Adultos" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="etariomayores" name="etariomayores" placeholder="Adultos Mayores" autocomplete="off" min="0" disabled>
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkprocedencia">
                                        <label class="custom-control-label" for="checkprocedencia">¿Procedencia?</label>
                                    </div>
                                </div>
                                <div class="form-group mt-0">
                                    <input type="number" class="form-control" id="procedenciarural" name="procedenciarural" placeholder="Rural" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="procedenciaurbano" name="procedenciaurbano" placeholder="Urbano" autocomplete="off" min="0" disabled>
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checknacion">
                                        <label class="custom-control-label" for="checknacion">¿Nacionalidad?</label>
                                    </div>
                                </div>
                                <div class="form-group mt-0">
                                    <input type="number" class="form-control" id="nacionchilena" name="nacionchilena" placeholder="Chilena" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="nacionmigrante" name="nacionmigrante" placeholder="Migrante" autocomplete="off" min="0" disabled>
                                </div>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkpueblo">
                                        <label class="custom-control-label" for="checkpueblo">¿Pueblos originarios?</label>
                                    </div>
                                </div>
                                <div class="form-group mt-0">
                                    <input type="number" class="form-control" id="pueblomapuche" name="pueblomapuche" placeholder="Mapuche" autocomplete="off" min="0" disabled>
                                    <input type="number" class="form-control" id="pueblootra" name="pueblootra" placeholder="Otra" autocomplete="off" min="0" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3" id="row-tabla-participantes" style="display: none;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered small table-sm">
                                                
                                                    <tr >
                                                        <th colspan="1"></th>
                                                        <th colspan="2" style="border-right: thin solid black;"></th>
                                                        <th colspan="3" class="text-center" style="border-top: thin solid black; border-right: thin solid black;">Género</th>
                                                        <th colspan="4" class="text-center" style="border-top: thin solid black; border-right: thin solid black;">Segmento etario</th>
                                                        <th colspan="2" class="text-center" style="border-top: thin solid black; border-right: thin solid black;" >Procedencia</th>
                                                        <th colspan="2" class="text-center" style="border-top: thin solid black; border-right: thin solid black;">Nacionalidad</th>
                                                        <th colspan="2" class="text-center" style="border-top: thin solid black; border-right: thin solid black;">Pueblos originarios</th>
                                                        <th colspan="1"></th>
                                                    </tr>
                                                    <tr style="outline: thin solid black;">
                                                        <th style="border-style: groove;">Subentorno</th>
                                                        <th style="border-style: groove;">Participantes iniciales</th>
                                                        <th style="border-style: groove;">Participantes finales</th>
                                                        <th style="border-style: groove;">Hombre</th>
                                                        <th style="border-style: groove;">Mujer</th>
                                                        <th style="border-style: groove;">Otro</th>
                                                        <th style="border-style: groove;">Niños</th>
                                                        <th style="border-style: groove;">Jóvenes</th>
                                                        <th style="border-style: groove;">Adultos</th>
                                                        <th style="border-style: groove;">Adultos mayores</th>
                                                        <th style="border-style: groove;">Rural</th>
                                                        <th style="border-style: groove;">Urbano</th>
                                                        <th style="border-style: groove;">Chilena</th>
                                                        <th style="border-style: groove;">Migrante</th>
                                                        <th style="border-style: groove;">Mapuche</th>
                                                        <th style="border-style: groove;">Otro</th>
                                                        <th style="border-style: groove;">Acción</th>
                                                    </tr>
                                                <tbody id="body-tabla-participantes">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-12 col-lg-12 text-right">
                                        <a href="{{ route('digitador.iniciativas.index') }}" type="button" class="btn btn-primary mr-1 waves-effect"><i class="fas fa-angle-left"></i> Volver al listado</a>
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
<script src="{{ asset('public/js/digitador/iniciativas/cobertura.js') }}"></script>

@endsection
