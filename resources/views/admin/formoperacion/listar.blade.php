@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorOperacion'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorOperacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoOperacion'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoOperacion') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('evop_nombre'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('evop_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('unid_codigo'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('unid_codigo') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('evop_vigencia'))
                                <div class="alert alert-warning alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('evop_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de evaluaciones de operación</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearevalucionOperaciones"><i class="fas fa-plus"></i> Nuevo Registro</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Comuna</th>
                                            <th>Unidad</th>
                                            <th>Puntaje</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evalucionOperaciones as $evop)
                                            <tr>
                                                <td>{{ $evop->comu_nombre }}</td>
                                                <td>{{ $evop->unid_nombre }}</td>
                                                <td>{{ $evop->evop_valor }}</td>
                                                <td>
                                                    @if ($evop->evop_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $evop->evop_rut_mod }}</td>

                                                <td>
                                                    <button type="button" class="btn btn-icon btn-warning"
                                                        data-toggle="modal" data-placement="top"
                                                        data-target="#modalEditarevalucionOperaciones-{{ $evop->evop_codigo }}"
                                                        title="Editar"><i class="fas fa-edit"></i></button>
                                                    <form action="{{ route('admin.operacion.borrar', $evop->evop_codigo) }}"
                                                        method="post" style="display: inline-block">
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

    <div class="modal fade" id="modalCrearevalucionOperaciones" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva evaluación de operación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.operacion.crear') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Región</label>
                            <div class="input-group">
                                <select name="regi_codigo" id="regi_codigo" class="form-control">
                                    <option value="" disabled selected>Seleccione...</option>
                                    @foreach ($regiones as $region)
                                        <option value="{{ $region->regi_codigo }}"
                                            {{ old('regi_codigo') == $region->reg_codigo ? 'selected' : '' }}>
                                            {{ $region->regi_nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Comuna</label>
                            <div class="input-group">
                                <select name="comu_codigo" id="comu_codigo" class="form-control">
                                    <option value="" disabled selected>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Unidad</label>
                            <div class="input-group">
                                <select name="unid_codigo" id="unid_codigo" class="form-control">
                                    <option value="" disabled selected>Seleccione...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Puntaje</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-chart-bar"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" id="evop_valor" name="evop_valor" placeholder="" autocomplete="off" value="{{ old('evop_valor') }}" min="0">
                            </div>
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

    @foreach ($evalucionOperaciones as $evop)
        <div class="modal fade" id="modalEditarevalucionOperaciones-{{ $evop->evop_codigo }}" tabindex="-1"
            role="dialog" aria-labelledby="modalEditarevalucionOperaciones" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarevalucionOperaciones">Editar evaluación de operación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.operacion.actualizar', $evop->evop_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Región</label>
                                <div class="input-group">
                                    <select name="regi2_codigo" id="regi2_codigo" class="form-control">
                                        <option value="{{ $evop->regi_codigo }}" selected disabled>{{ $evop->regi_nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Comuna</label>
                                <div class="input-group">
                                    <select name="comu2_codigo" id="comu2_codigo" class="form-control">
                                        <option value="{{ $evop->comu_codigo }}" selected disabled>{{ $evop->comu_nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Unidad</label>
                                <div class="input-group">
                                    <select name="unid2_codigo" id="unid2_codigo" class="form-control">
                                        <option value="{{ $evop->unid_codigo }}" selected disabled>{{ $evop->unid_nombre }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Puntaje</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                    </div>
                                    <input type="number" class="form-control" id="evop_valor" name="evop_valor" placeholder="" autocomplete="off" value="{{ $evop->evop_valor }}" min="0">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-traffic-light"></i>
                                        </div>
                                    </div>
                                    <select class="form-control select" id="evop_vigencia" name="evop_vigencia">
                                        <option value="S" {{ $evop->evop_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $evop->evop_vigente == 'N' ? 'selected' : '' }}>Inactivo
                                        </option>
                                    </select>
                                </div>
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
    
    <script>
        $(document).ready(function() {
            $('#regi_codigo').val('').prop('selected', true);
            $('#comu_codigo').val('').prop('selected', true);
            $('#unid_codigo').val('').prop('selected', true);
        });

        $('#modalCrearevalucionOperaciones').on('show.bs.modal', function () {
            $('#regi_codigo').val('').prop('selected', true);
            $('#comu_codigo').val('').prop('selected', true);
            $('#unid_codigo').val('').prop('selected', true);
        });

        $('#modalCrearevalucionOperaciones').on('hidden.bs.modal', function () {
            $('#regi_codigo').val('').prop('selected', true);
            $('#comu_codigo').val('').prop('selected', true);
            $('#unid_codigo').val('').prop('selected', true);
        });

        const csrftoken = document.head.querySelector('[name~=csrf-token][content]').content;
        var selectRegion = document.getElementById("regi_codigo");
        selectRegion.addEventListener('change', function(e) {
            $('#comu_codigo').val('').prop('selected', true);
            $('#unid_codigo').val('').prop('selected', true);
            $('#unid_codigo').find('option').not(':first').remove();
            fetch("{{ route('admin.operaciones.comunas') }}", {
                method: 'POST',
                body: JSON.stringify({
                    region: e.target.value
                }),
                headers: {
                    'Content-Type': 'aplication/json',
                    'X-CSRF-TOKEN': csrftoken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                $('#comu_codigo').find('option').not(':first').remove();
                for (let i in data.comunas) {
                    $('#comu_codigo').append(new Option(data.comunas[i].comu_nombre, data.comunas[i].comu_codigo));
                }
            })
        });

        var selectComuna = document.getElementById("comu_codigo");
        selectComuna.addEventListener('change', function(e) {
            $('#unid_codigo').val('').prop('selected', true);
            fetch("{{ route('admin.operaciones.unidades') }}", {
                method: 'POST',
                body: JSON.stringify({
                    comuna: e.target.value
                }),
                headers: {
                    'Content-Type': 'aplication/json',
                    'X-CSRF-TOKEN': csrftoken
                }
            }).then(response => {
                return response.json();
            }).then(data => {
                $('#unid_codigo').find('option').not(':first').remove();
                for (let i in data.unidades) {
                    $('#unid_codigo').append(new Option(data.unidades[i].unid_nombre, data.unidades[i].unid_codigo));
                }
            })
        });
    </script>

@endsection
