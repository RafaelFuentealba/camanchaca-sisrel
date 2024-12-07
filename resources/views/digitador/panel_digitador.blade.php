@extends('layout.index')


@section('acceso')
    {{-- <ul class="sidebar-menu">
        <li class="menu-header">Digitador</li>

        <li
            class="{{ Route::is('digitador.index.iniciativas') || Route::is('digitador.index.actividades')
                ? // Route::is('digitador.index.donaciones')
                'dropdown active'
                : 'dropdown' }}">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="monitor"></i><span>Dashboard</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('digitador.index.iniciativas') }}">Por iniciativas</a></li>
                <li><a class="nav-link" href="{{ route('digitador.index.actividades') }}">Por relacionamiento</a></li>
                {{-- <li><a class="nav-link" href="{{ route('digitador.index.donaciones') }}">Por donaciones</a></li> --}}
            {{-- </ul> --}}
        {{-- </li> s --}}

        {{-- <li
            class="{{ Route::is('digitador.actividad.crear') ||
            Route::is('digitador.actividad.editar') ||
            Route::is('digitador.actividad.participantes.editar') ||
            Route::is('digitador.actividad.listar') ||
            Route::is('digitador.actividad.mostrar') ||
            Route::is('digitador.donaciones.crear') ||
            Route::is('digitador.donaciones.listar') ||
            Route::is('digitador.donaciones.editar') ||
            Route::is('digitador.donaciones.info')
                ? 'dropdown active'
                : 'dropdown' }}"> --}}
            {{-- <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="file-text"></i><span>Bitácora de relacionamiento</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('digitador.actividad.crear') }}">Ingresar actividad</a></li>
                <li><a class="nav-link" href="{{ route('digitador.actividad.listar') }}">Listar actividades</a></li>
                <!-- <li><a class="nav-link" href="{{ route('digitador.donaciones.crear') }}">Ingresar donación</a></li>
                        <li><a class="nav-link" href="{{ route('digitador.donaciones.listar') }}">Listar donaciones</a></li> -->
            </ul>
        </li> --}}
        {{-- <li
            class="{{ Route::is('digitador.paso1.crear') ||
            Route::is('digitador.paso1.editar') ||
            Route::is('digitador.paso2.crear') ||
            Route::is('digitador.paso2.editar') ||
            Route::is('digitador.paso3.crear') ||
            Route::is('digitador.paso3.editar') ||
            Route::is('digitador.paso4.crear') ||
            Route::is('digitador.iniciativas.index') ||
            Route::is('digitador.iniciativas.show') ||
            Route::is('digitador.evidencia.listar') ||
            Route::is('digitador.evidencia.guardar') ||
            Route::is('digitador.evidencia.editar') ||
            Route::is('digitador.evidencia.actualizar') ||
            Route::is('digitador.evidencia.descargar') ||
            Route::is('digitador.evidencia.eliminar') ||
            Route::is('digitador.evaluacion.index') ||
            Route::is('digitador.cobertura.index') ||
            Route::is('digitador.resultados.index')
                ? 'dropdown active'
                : 'dropdown' }}">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="slack"></i><span>Iniciativas</span></a>
            <ul class="dropdown-menu"> --}}
                {{-- <li><a class="nav-link" href="{{ route('digitador.paso1.crear') }}">Crear iniciativa</a></li> --}}
                {{-- <li><a class="nav-link" href="{{ route('digitador.iniciativas.index') }}">Iniciativas creadas</a></li>
            </ul>
        </li> --}}
        <!-- <li class="{{ Route::is('digitador.listar.encuestapr') ||
        Route::is('digitador.crear.encuestapr') ||
        Route::is('digitador.encuestapr.editar') ||
        Route::is('digitador.encuestacl.listar') ||
        Route::is('digitador.encuestacl.registrar') ||
        Route::is('digitador.encuestacl.editar') ||
        Route::is('digitador.evaluacionprensa.listar') ||
        Route::is('digitador.operacion.listar')
            ? 'dropdown active'
            : 'dropdown' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="command"></i><span>Parámetros</span></a>
                <ul class="dropdown-menu">
                    <li><a class="nav-link" href="{{ route('digitador.encuestacl.listar') }}">Encuesta de clima</a></li>
                    <li><a class="nav-link" href="{{ route('digitador.listar.encuestapr') }}">Encuesta de percepción</a></li>
                    <li><a class="nav-link" href="{{ route('digitador.operacion.listar') }}">Evaluación de operaciones</a></li>
                    <li><a class="nav-link" href="{{ route('digitador.evaluacionprensa.listar') }}">Evaluación de prensa</a></li>
                </ul>
            </li> -->
    </ul>
    </div>
@endsection
