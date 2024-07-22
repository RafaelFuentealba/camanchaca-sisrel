@extends('superadmin.panel')

@section('contenido-principal')

<section class="section">
    <div class="section-body">
        <div class="row">            
            <div class="col-12">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        @if(Session::has('errorInfraestructura'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('errorInfraestructura') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                        @if(Session::has('exitoInfraestructura'))
                            <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ Session::get('exitoInfraestructura') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif

                        @if($errors->has('nombre'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ $errors->first('nombre') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                        @if($errors->has('valorizacion'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ $errors->first('valorizacion') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                        @if($errors->has('vigencia'))
                            <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                <div class="alert-body">
                                    <strong>{{ $errors->first('vigencia') }}</strong>
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-3"></div>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <h4>Listado de tipos de infraestructura</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCrearInfraestructura"><i class="fas fa-plus"></i> Nuevo tipo de infraestructura</button>
                        </div>
                    </div>                    
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-1">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Valorización</th>
                                        <th>Fecha de creación</th>
                                        <th>Última actualización</th>
                                        <th>Modificado por</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tipos_infraestructura as $infra)
                                        <tr>
                                            <td>{{ $infra->tiin_nombre }}</td>
                                            <td>{{ $infra->tiin_valor }}</td>
                                            <td>{{ date('d-m-Y', strtotime($infra->tiin_creado)) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($infra->tiin_actualizado)) }}</td>
                                            <td>{{ $infra->tiin_rut_mod }}</td>
                                            <td>
                                                @if ($infra->tiin_vigente=='S')
                                                    <div class="badge badge-success badge-shadow">Activo</div>
                                                @else
                                                    <div class="badge badge-danger badge-shadow">Inactivo</div>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" class="btn btn-icon btn-warning" onclick="editarTipoInfra({{ $infra->tiin_codigo }})" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                                <form action="{{ route('superadmin.infra.destroy', $infra->tiin_codigo) }}" method="POST" style="display: inline-block;">
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


@foreach ($tipos_infraestructura as $infra)
    <div class="modal fade" id="modalEditarInfra-{{ $infra->tiin_codigo }}" tabindex="-1" role="dialog" aria-labelledby="modalEditarInfraTitulo" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarInfraTitulo">Editar tipo de infraestructura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('superadmin.infra.update', $infra->tiin_codigo) }}" method="POST">
                        @method('PUT')
                        @csrf
                        
                        <div class="form-group">
                            <label>Nombre</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-building"></i>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="{{ $infra->tiin_nombre }}" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Valorización</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </div>
                                <input type="number" class="form-control" id="valorizacion" name="valorizacion" value="{{ $infra->tiin_valor }}" autocomplete="off">
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
                                <select class="form-control select2" id="vigencia" name="vigencia">
                                    <option value="S" {{ $infra->tiin_vigente=='S' ? 'selected' : '' }}>Activo</option>
                                    <option value="N" {{ $infra->tiin_vigente=='N' ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary waves-effect">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div class="modal fade" id="modalCrearInfraestructura" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Nuevo tipo de infraestructura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('superadmin.infra.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Nombre</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-building"></i>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Valorización</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="valorizacion" name="valorizacion" autocomplete="off">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary waves-effect">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editarTipoInfra(tiin_codigo) {
        $('#modalEditarInfra-'+tiin_codigo).modal('show');
    }
</script>

<link rel="stylesheet" href="{{ asset('public/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="{{ asset('public/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('public/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('public/bundles/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('public/js/page/datatables.js') }}"></script>

@endsection
