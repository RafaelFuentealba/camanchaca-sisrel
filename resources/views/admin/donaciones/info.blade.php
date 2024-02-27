@extends('admin.panel_admin')
@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-6">
                            @if (Session::has('exitoActividad'))
                                <div class="alert alert-success alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('exitoActividad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif

                            @if (Session::has('errorActividad'))
                                <div class="alert alert-danger alert-dismissible show fade mb-4 text-center">
                                    <div class="alert-body">
                                        <strong>{{ Session::get('errorActividad') }}</strong>
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Información de la donación</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.dbgeneral.index') }}" type="button" class="btn btn-info"
                                    title="Ir a inicio"><i class="fas fa-home"></i></a>
                                <a type="button" class="btn btn-success" href="{{ route('admin.donaciones.listar') }}"><i
                                   title="Volver a listado" class="fas fa-backward"></i></a>
                                <a href="{{ route('admin.donaciones.editar', $donacion->dona_codigo) }}"
                                    class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top"
                                    title="Editar"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.donaciones.eliminar', $donacion->dona_codigo) }}"
                                    method="POST" style="display: inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-danger"><i class="fas fa-trash"
                                            data-toggle="tooltip" data-placement="top" title="Eliminar"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-md">
                                        <tbody>
                                            <tr>
                                                <td width="20%"><strong>Nombre organización</strong></td>
                                                <td>{{ $donacion->orga_nombre }}</td>
                                            </tr>

                                            <tr>
                                                <td width="20%"><strong>Comuna</strong></td>
                                                <td>{{ $donacion->comu_nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Motivo de la donación</strong></td>
                                                <td>{{ $donacion->dona_motivo }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nombre persona solicitante</strong></td>
                                                <td>{{ $donacion->dona_nombre_solicitante }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Cargo persona solicitante</strong></td>
                                                <td>{{ $donacion->dona_cargo_solicitante }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Aprobado por</strong></td>
                                                <td>{{ $donacion->dona_persona_aprueba }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Monto donado</strong></td>
                                                <td>{{ '$' . number_format($donacion->dona_monto, 0, ',', '.') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Recepcionado por</strong></td>
                                                <td>{{ $donacion->dona_persona_recepciona }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de entrega</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($donacion->dona_fecha_entrega)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pilar asociado</strong></td>
                                                <td>{{ $donacion->pila_nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estado de la donación</strong></td>
                                                <td>{{ $donacion->dona_estado }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estado formulario de autorización</strong></td>
                                                <td>{{ $donacion->dona_form_autorizacion }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Estado declaración jurada</strong></td>
                                                <td>{{ $donacion->dona_declaracion_jurada }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tipo de aporte</strong></td>
                                                <td>{{ $donacion->dona_tipo_aporte }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Descripción</strong></td>
                                                <td>
                                                    <?php
                                                    $descripcion = nl2br($donacion->dona_descripcion);
                                                    echo $descripcion;
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de ingreso</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($donacion->dona_creado)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha última actualización</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($donacion->dona_actualizado)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Modificado por</strong></td>
                                                <td>{{ $donacion->dona_rut_mod }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
