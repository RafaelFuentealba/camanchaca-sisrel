@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
                            @if (Session::has('exitoDivision'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoDivision') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorDivision'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorDivision') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-xl-3"></div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4>Listado de divisiones</h4>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#modalCrearDivision"><i class="fas fa-plus"></i> Nueva división</button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-bordered table-md">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($divisiones as $division)
                                            <tr>
                                                <td>{{ $division->divi_codigo }}</td>
                                                <td>{{ $division->divi_nombre }}</td>
                                                <td>
                                                    @if ($division->divi_vigente == 'S')
                                                        <div class="badge badge-success badge-shadow">Activo</div>
                                                    @else
                                                        <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{-- <a href="" onclick="editarDivision($division->divi_codigo)"></a> --}}
                                                    <button type="button" class="btn btn-icon btn-warning"
                                                        data-toggle="modal"
                                                        data-target="#modalEditarDivison-{{ $division->divi_codigo }}"
                                                        title="Editar"><i class="fas fa-edit"></i></button>

                                                    <a href="javascript:void(0)" class="btn btn-icon btn-danger"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar"
                                                        onclick="eliminarDivison({{ $division->divi_codigo }})"><i
                                                            class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            {{ $divisiones->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalCrearDivision" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModal">Nueva división</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.divisiones.guardar') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-industry"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" autocomplete="off" min="0" max="100" step="0.1">
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
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect"><i class="fas fa-save"></i> Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @foreach ($divisiones as $division)
    <div class="modal fade" id="modalEditarDivison-{{ $division->divi_codigo }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarDivison" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarDivison">Editar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.divisiones.editar', $division->divi_codigo) }}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="form-group">
                            <label for="divi_nombre">Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-industry"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="divi_nombre" name="divi_nombre" value="{{ old('divi_nombre') ?? @$division->divi_nombre }}" autocomplete="off" min="0" max="100" step="0.1">
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
                            <label for="vigente">Estado</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-traffic-light"></i>
                                    </div>
                                </div>
                                <select class="form-control form-control-sm" name="divi_vigente" id="divi_vigente">
                                    <option value="S" {{ $division->divi_vigente == 'S' ? 'selected' : '' }}>Activo</option>
                                    <option value="N" {{ $division->divi_vigente == 'N' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                            @if ($errors->has('divi_vigente'))
                            <div class="alert alert-warning alert-dismissible show fade mt-2">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>{{ $errors->first('divi_vigente') }}</strong>
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

    <div class="modal fade" id="modalEliminarDivision" tabindex="-1" role="dialog" aria-labelledby="modalEliminar"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{route('admin.divisiones.eliminar')}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEliminar">Eliminar División</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="fas fa-ban text-danger" style="font-size: 50px; color"></i>
                        <h6 class="mt-2">La división sera eliminada permanentemente del sistema. <br> ¿Desea continuar de todos modos?</h6>
                        <input type="hidden" id="divi_codigo" name="divi_codigo" value="">
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Eliminar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editarDivision(cacl_codigo) {
            $('#modalEditarDivison').modal('show');
        }

        function eliminarDivison(divi_codigo) {
            $('#divi_codigo').val(divi_codigo);
            $('#modalEliminarDivision').modal('show');
        }
    </script>
@endsection
