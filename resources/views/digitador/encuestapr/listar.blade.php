@extends('digitador.panel_digitador')
@section('contenido')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if (Session::has('exitoEncuestapr'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoEncuestapr') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if (Session::has('errorEncuestapr'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorEncuestapr') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Listado de encuestas de percepción</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearencuestapr"><i class="fas fa-plus"></i> Nuevo
                                Registro</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-md" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Comuna</th>
                                        <th>Categoría</th>
                                        <th>Año</th>
                                        <th>Puntaje</th>
                                        <th>Estado</th>
                                        <th>Acción</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encuestapr as $encuesta)
                                    <tr>
                                        <td>{{ $encuesta->comu_nombre }}</td>
                                        <td>{{ $encuesta->cape_nombre }}</td>
                                        <td>{{ $encuesta->enpe_anho }}</td>
                                        <td>{{ $encuesta->enpe_puntaje }}</td>
                                        <td>
                                            @if ($encuesta->enpe_vigente == 'S')
                                                <div class="badge badge-success badge-shadow">Activo</div>
                                            @else
                                                <div class="badge badge-danger badge-shadow">Inactivo</div>
                                            @endif
                                        </td>

                                        <td>
                                                <button type="button" class="btn btn-icon btn-warning" data-toggle="modal" data-target="#modalEditarencuestapr-{{ $encuesta->enpe_codigo }}" title="Editar"><i class="fas fa-edit"></i></button>
                                                <form action="{{ route('digitador.encuestapr.borrar', $encuesta->enpe_codigo) }}" method="post" style="display: inline-block">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
                                                </form>
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




<div class="modal fade" id="modalCrearencuestapr" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Nueva encuesta de percepción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('digitador.encuestapr.guardar') }}" method="POST">
                    @csrf

                    <div class="form-group ">
                        <label for="comuna">Comuna</label>
                        <div class="input-group">

                            <select class="form-control form-control-sm" name="comuna" id="comuna">
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($comunas as $comuna)
                                <option value="{{ $comuna->comu_codigo }}" {{ old('comuna') == $comuna->comu_codigo ? 'selected' : '' }}>
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
                        <label for="catepr">Categoría de percepción</label>
                        <div class="input-group">

                            <select class="form-control form-control-sm" name="catepr" id="catepr">
                                <option value="" selected disabled>Seleccione...</option>
                                @foreach ($caper as $cape)
                                <option value="{{ $cape->cape_codigo }}" {{ old('catepr') == $cape->cape_codigo ? 'selected' : '' }}>
                                    {{ $cape->cape_nombre }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('catepr'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('catepr') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        <label for="anho">Año</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="anho" name="anho" value="{{ old('anho') }}" autocomplete="off" min="0">
                        </div>

                        @if ($errors->has('anho'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('anho') }}</strong>
                            </div>
                        </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label for="puntaje">Puntaje</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="puntaje" name="puntaje" value="{{ old('puntaje') }}" autocomplete="off" min="0">
                        </div>
                        @if ($errors->has('puntaje'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('puntaje') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                    </div>
                </form>
            </div>





        </div>
    </div>
</div>


@foreach ($encuestapr as $encuesta)
<div class="modal fade" id="modalEditarencuestapr-{{ $encuesta->enpe_codigo }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarencuestapr" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditarencuestapr">Editar encuesta de percepción</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('digitador.encuestapr.actualizar', $encuesta->enpe_codigo) }}" method="POST">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="comuna">Comuna</label>
                        <div class="input-group">

                            <select class="form-control form-control-sm" name="comuna" id="comuna">
                                @foreach ($comunas as $comuna)
                                    @if ($encuesta->comu_codigo == $comuna->comu_codigo)
                                        <option value="{{ $comuna->comu_codigo }}" selected disabled>{{ $comuna->comu_nombre }}</option>
                                    @endif
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
                        <label for="vigente">Categoría de percepción</label>
                        <div class="input-group">

                            <select class="form-control form-control-sm" name="catepr" id="catepr">
                                @foreach ($caper as $cape)
                                    @if ($encuesta->cape_codigo == $cape->cape_codigo)
                                        <option value="{{ $cape->cape_codigo }}" selected disabled>{{ $cape->cape_nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('catepr'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('catepr') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>


                    <div class="form-group">
                        <label for="anho">Año</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="anho" name="anho" value="{{ old('anho') ?? @$encuesta->enpe_anho }}" autocomplete="off" disabled>
                        </div>

                        @if ($errors->has('anho'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('anho') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="puntaje">Puntaje</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="puntaje" name="puntaje" value="{{ old('puntaje') ?? @$encuesta->enpe_puntaje }}" autocomplete="off" min="0">
                        </div>
                        @if ($errors->has('puntaje'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('puntaje') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>



                    <div class="form-group">
                        <label for="vigente">Estado de la encuesta</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-traffic-light"></i>
                                </div>
                            </div>
                            <select class="form-control form-control-sm" name="vigente" id="vigente">
                                <option value="S" {{ $encuesta->enpe_vigente == 'S' ? 'selected' : '' }}>Activo</option>
                                <option value="N" {{ $encuesta->enpe_vigente == 'N' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        @if ($errors->has('vigente'))
                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                            <div class="alert-body">
                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                <strong>{{ $errors->first('vigente') }}</strong>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach







<link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/page/datatables.js') }}"></script>
@endsection
