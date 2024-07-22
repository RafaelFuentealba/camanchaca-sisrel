@extends('digitador.panel_digitador')

@section('contenido')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3"></div>
                        <div class="col-xl-6">
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
                        <div class="col-xl-3"></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Información de la actividad</h4>
                            <div class="card-header-action">
                                <a href="{{route('digitador.dbgeneral.index')}}" type="button" class="btn btn-info" title="Ir a inicio"><i class="fas fa-home"></i></a>
                                <a href="{{route('digitador.actividad.listar')}}" type="button" class="btn btn-success" title="Ir a realacionamientos"><i class="fas fa-backward"></i></a>

                                <a href="{{ route('digitador.actividad.editar', $actividad->acti_codigo) }}" class="btn btn-icon btn-warning" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('digitador.actividad.eliminar', $actividad->acti_codigo) }}" method="POST" style="display: inline-block;">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-danger" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fas fa-trash"></i></button>
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
                                                <td>{{ $actividad->orga_nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nombre actividad</strong></td>
                                                <td>{{ $actividad->acti_nombre }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de realización</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($actividad->acti_fecha)->format('d-m-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Acuerdos</strong></td>
                                                <td>
                                                    <?php
                                                        $acuerdos = nl2br($actividad->acti_acuerdos);
                                                        echo $acuerdos;
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de cumplimiento</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($actividad->acti_fecha_cumplimiento)->format('d-m-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Avance</strong></td>
                                                <td>{{ $actividad->acti_avance }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Participantes</strong></td>
                                                <td>
                                                    @if (sizeof($participantes) > 0)
                                                        @foreach ($participantes as $participante)
                                                            @if ($participante->diri_codigo != null)
                                                                <li>{{ $participante->asis_nombre.' '.$participante->asis_apellido }} (Dirigente)</li>
                                                            @else
                                                                <li>{{ $participante->asis_nombre.' '.$participante->asis_apellido }}</li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha de ingreso</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($actividad->acti_creado)->format('d-m-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Fecha última actualización</strong></td>
                                                <td>{{ \Carbon\Carbon::parse($actividad->acti_actualizado)->format('d-m-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Modificado por</strong></td>
                                                <td>{{ $actividad->acti_rut_mod }}</td>
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
