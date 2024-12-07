@extends('admin.panel_admin')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('errorEntorno'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorEntorno') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                            @if (Session::has('exitoEntorno'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoEntorno') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('ento_nombre'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('ento_nombre') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('ento_vigencia'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ $errors->first('ento_vigencia') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if ($errors->has('ento_ruta_icono'))
                                <div class="alert alert-warning alert-dismissible show fade mt-2 text-center">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <strong>{{ $errors->first('ento_ruta_icono') }}</strong>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de entornos</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearEntorno"><i class="fas fa-plus"></i> Nuevo Entorno</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-md" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Icono</th>
                                            <th>Estado</th>
                                            <th>Modificado por</th>
                                            <th>Acción</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($entorno as $ento)
                                            <tr>
                                                <td>{{ $ento->ento_nombre }}</td>

                                                <td class="text-center">
                                                    @if ($ento->ento_ruta_icono)
                                                        <img src="{{ asset($ento->ento_ruta_icono) }}" alt="">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ento->ento_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>{{ $ento->ento_rut_mod }}</td>
                                                <!-- en este apartado deben ir los botones de editar y borrar manito -->


                                                <td>
                                                        <button type="button" class="btn btn-icon btn-warning"
                                                            data-toggle="modal"
                                                            data-placement="top"
                                                            data-target="#modalEditarEntorno-{{ $ento->ento_codigo }}"
                                                            title="Editar"><i class="fas fa-edit"></i></button>
                                                        <form
                                                            action="{{ route('admin.entornos.borrar', $ento->ento_codigo) }}"
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
                                {{-- <div class="card-body">{{ $entorno->links() }}</div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- modals de entornos -->
    <div class="modal fade" id="modalCrearEntorno" tabindex="-1" role="dialog" aria-labelledby="formModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nuevo entorno</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.entornos.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-street-view"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="ento_nombre" name="ento_nombre"
                                    placeholder="" autocomplete="off" value="{{old('ento_nombre')}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seleccione un ícono</label>
                            <div class="selectgroup selectgroup-pills">
                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono"
                                        id="ento_ruta_icono" value="public/img/icons/watertower.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/watertower.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/factory.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/factory.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/hospital.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/hospital.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/junta-vecino.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/junta-vecino.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/school.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/school.png') }}" alt="">
                                    </span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/watertower-2.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/watertower-2.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/factory-2.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/factory-2.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/hospital-2.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/hospital-2.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/junta-vecino-2.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/junta-vecino-2.png') }}" alt="">
                                    </span>
                                </label>

                                <label class="selectgroup-item">
                                    <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                        value="public/img/icons/school-2.png">
                                    <span class="selectgroup-button selectgroup-button-icon">
                                        <img src="{{ asset('public/img/icons/school-2.png') }}" alt="">
                                    </span>
                                </label>
                            </div>

                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($entorno as $ento)
        <div class="modal fade" id="modalEditarEntorno-{{ $ento->ento_codigo }}" tabindex="-1" role="dialog"
            aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEditarEntorno">Editar entorno</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.entornos.actualizar', $ento->ento_codigo) }}" method="POST">
                            @method('PUT')
                            @csrf

                            <div class="form-group">
                                <label>Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-street-view"></i>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="ento_nombre" name="ento_nombre"
                                        value="{{old('ento_nombre') ??@$ento->ento_nombre }}" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Seleccione un ícono</label>
                                <div class="selectgroup selectgroup-pills">
                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono"
                                            id="ento_ruta_icono" value="public/img/icons/watertower.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/watertower.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/factory.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/factory.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/hospital.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/hospital.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/junta-vecino.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/junta-vecino.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/school.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/school.png') }}" alt="">
                                        </span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/watertower-2.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/watertower-2.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/factory-2.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/factory-2.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/hospital-2.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/hospital-2.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/junta-vecino-2.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/junta-vecino-2.png') }}" alt="">
                                        </span>
                                    </label>

                                    <label class="selectgroup-item">
                                        <input type="radio" class="selectgroup-input" name="ento_ruta_icono" id="ento_ruta_icono"
                                            value="public/img/icons/school-2.png">
                                        <span class="selectgroup-button selectgroup-button-icon">
                                            <img src="{{ asset('public/img/icons/school-2.png') }}" alt="">
                                        </span>
                                    </label>
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
                                    <select class="form-control " id="ento_vigencia" name="ento_vigencia">
                                        <option value="S" {{ $ento->ento_vigente == 'S' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="N" {{ $ento->ento_vigente == 'N' ? 'selected' : '' }}>Inactivo
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

@endsection
