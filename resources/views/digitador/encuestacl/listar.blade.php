@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoEncuestacl'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEncuestacl') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorEncuestacl'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEncuestacl') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de encuestas de clima</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearencuestacl"><i class="fas fa-plus"></i> Nuevo
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
                                            <th>Modificado por</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($encuestacl as $cl)
                                            <tr>
                                                <td>{{ $cl->comu_nombre }}</td>
                                                <td>{{ $cl->cacl_nombre }}</td>
                                                <td>{{ $cl->encl_anho }}</td>
                                                <td>{{ $cl->encl_puntaje }}</td>
                                                <td>
                                                    @if ($cl->encl_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $cl->encl_rut_mod }}</td>


                                                <td>
                                                    <button type="button" class="btn btn-icon btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#modalEditarencuestacl-{{ $cl->encl_codigo }}"
                                                        title="Editar"><i class="fas fa-edit"></i></button>
                                                    <form
                                                        action="{{ route('digitador.encuestacl.eliminar', $cl->encl_codigo) }}"
                                                        method="post" style="display: inline-block">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
                                                    </form>
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



    <div class="modal fade" id="modalCrearencuestacl" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva encuesta de clima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('digitador.encuestacl.guardar') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="comuna">Comuna</label>
                            <div class="input-group">

                                <select class="form-control form-control-sm" name="comuna" id="comuna">
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
                                <div class="alert alert-warning alert-dismissible show fade mt-2">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('comuna') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>


                        <div class="form-group">
                            <label for="catepr">Categoría de clima</label>
                            <div class="input-group">

                                <select class="form-control form-control-sm" name="catecl" id="catecl">
                                    <option value="" selected disabled>Seleccione...</option>
                                    @foreach ($categoriacl as $cacl)
                                        <option value="{{ $cacl->cacl_codigo }}"
                                            {{ old('catecl') == $cacl->cacl_codigo ? 'selected' : '' }}>
                                            {{ $cacl->cacl_nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('catecl'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('catecl') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>


                        <div class="form-group">
                            <div class="form-group">
                                <label for="anho">Año</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" id="anho" name="anho"
                                        value="{{ old('anho') }}" autocomplete="off">
                                </div>
                            </div>
                            @if ($errors->has('anho'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2">
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
                        </div>
                        @if ($errors->has('puntaje'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('puntaje') }}</strong>
                                </div>
                            </div>
                        @endif
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i>
                                Registrar</button>
                        </div>
                </div>
                </form>
            </div>





        </div>
    </div>


    @foreach ($encuestacl as $cl)
        <div class="modal fade" id="modalEditarencuestacl-{{ $cl->encl_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarencuestacl" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarencuestacl">Editar encuesta de clima</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('digitador.encuestacl.actualizar', $cl->encl_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf



                            <div class="form-group">
                                <label for="comuna">Comuna</label>
                                <div class="input-group">
                                    <select class="form-control form-control-sm" name="comuna" id="comuna">
                                        @foreach ($comunas as $comuna)
                                            @if ($comuna->comu_codigo == $cl->comu_codigo)
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
                                <label for="catepr">Categoría de clima</label>
                                <div class="input-group">

                                    <select class="form-control form-control-sm" name="catecl" id="catecl">
                                        @foreach ($categoriacl as $cacl)
                                            @if ($cl->cacl_codigo == $cacl->cacl_codigo)
                                                <option value="{{ $cacl->cacl_codigo }}" selected disabled>{{ $cacl->cacl_nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('catecl'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('catecl') }}</strong>
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
                                    <input type="number" class="form-control" id="anho" name="anho"
                                        value="{{ old('anho') ?? @$cl->encl_anho }}" autocomplete="off" disabled>
                                </div>
                                @if ($errors->has('anho'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
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
                                    <input type="number" class="form-control" id="puntaje" name="puntaje" value="{{ old('puntaje') ?? $cl->encl_puntaje }}" autocomplete="off" min="0">
                                </div>
                                @if ($errors->has('puntaje'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('puntaje') }}</strong>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="form-group">
                                <label>Estado de la encuesta </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-traffic-light"></i>
                                        </div>
                                    </div>
                                    <select class="form-control form-control-sm" name="encl_vigente" id="encl_vigente">
                                        <option value="S" {{ $cl->encl_vigente == 'S' ? 'selected' : '' }}>
                                            Activo
                                        </option>
                                        <option value="N" {{ $cl->encl_vigente == 'N' ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>

                                </div>
                                @if ($errors->has('encl_vigente'))
                                    <div class="alert alert-warning alert-dismissible show fade mt-2">
                                        <div class="alert-body">
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                            <strong>{{ $errors->first('encl_vigente') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-undo-alt"></i> Actualizar</button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    @endforeach




    <link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/page/datatables.js') }}"></script>
@endsection
