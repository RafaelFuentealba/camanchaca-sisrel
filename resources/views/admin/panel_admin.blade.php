@extends('layout.index')

@section('acceso')
    <ul class="sidebar-menu">
        <li class="menu-header">Administrador</li>
        {{-- <li class="{{
                Route::is('admin.index.iniciativas') ||
                Route::is('admin.index.actividades') ||
                Route::is('admin.index.donaciones')
                ? 'dropdown active' : 'dropdown'
            }}"> --}}
        <li class="dropdown">
            {{-- <a href="{{route('admin.dbgeneral.index')}}" class="menu-toggle nav-link has-dropdown"><i --}}
            <a href="{{route('admin.dbgeneral.index')}}"><i
                    data-feather="monitor"></i><span>Dashboard</span></a>
            {{-- <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.index.iniciativas') }}">Por iniciativas</a></li>
                <li><a class="nav-link" href="{{ route('admin.index.actividades') }}">Por actividades</a></li>
                <li><a class="nav-link" href="{{ route('admin.index.donaciones') }}">Por donaciones</a></li>
            </ul> --}}
        </li>
        {{-- <li
            class="{{ Route::is('admin.paso1.crear') ||
            Route::is('admin.paso1.editar') ||
            Route::is('admin.paso2.crear') ||
            Route::is('admin.paso2.editar') ||
            Route::is('admin.paso3.crear') ||
            Route::is('admin.paso3.editar') ||
            Route::is('admin.paso4.crear') ||
            Route::is('admin.iniciativas.index') ||
            Route::is('admin.iniciativas.show') ||
            Route::is('admin.evidencia.listar') ||
            Route::is('admin.evidencia.guardar') ||
            Route::is('admin.evidencia.editar') ||
            Route::is('admin.evidencia.actualizar') ||
            Route::is('admin.evidencia.descargar') ||
            Route::is('admin.evidencia.eliminar') ||
            Route::is('admin.evaluacion.index') ||
            Route::is('admin.cobertura.index') ||
            Route::is('admin.resultados.index')
                ? 'dropdown active'
                : 'dropdown' }}"> --}}
        <li class="dropdown">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="slack"></i><span>Iniciativas</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.paso1.crear') }}">Crear iniciativa</a></li>
                <li><a class="nav-link" href="{{ route('admin.iniciativas.index') }}">Iniciativas creadas</a></li>
            </ul>
        </li>
        {{-- <li
            class="{{ Route::is('admin.actividad.crear') ||
            Route::is('admin.actividad.editar') ||
            Route::is('admin.actividad.participantes.editar') ||
            Route::is('admin.actividad.listar') ||
            Route::is('admin.actividad.mostrar') ||
            Route::is('admin.donaciones.crear') ||
            Route::is('admin.donaciones.listar') ||
            Route::is('admin.donaciones.editar') ||
            Route::is('admin.donaciones.info')
                ? 'dropdown active'
                : 'dropdown' }}"> --}}
        <li class="dropdown">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="file-text"></i><span>Bitácoras</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.actividad.crear') }}">Ingresar actividad</a></li>
                <li><a class="nav-link" href="{{ route('admin.actividad.listar') }}">Listar actividades</a></li>
                {{-- <li><a class="nav-link" href="{{ route('admin.donaciones.crear') }}">Ingresar donación</a></li>
                <li><a class="nav-link" href="{{ route('admin.donaciones.listar') }}">Listar donaciones</a></li> --}}
            </ul>
        </li>
        {{-- <li class="{{ Route::is('admin.map') ? 'dropdown active' : 'dropdown' }}">
            <a class="nav-link" href="{{ route('admin.map') }}"><i data-feather="map"></i><span>Mapa</span></a>
        </li> --}}
        </li>
        {{-- <li
            class="{{ Route::is('admin.unidades.listar') ||
            Route::is('admin.registrar.unidad') ||
            Route::is('admin.editar.unidad') ||
            Route::is('admin.pilares.listar') ||
            Route::is('admin.impactos.listar') ||
            Route::is('admin.convenios.listar') ||
            Route::is('admin.convenios.registrar') ||
            Route::is('admin.convenios.editar') ||
            Route::is('admin.entornos.listar') ||
            Route::is('admin.subentornos.listar') ||
            Route::is('admin.listar.org') ||
            Route::is('admin.crear.org') ||
            Route::is('admin.editar.org') ||
            Route::is('admin.crear_dirigente.org') ||
            Route::is('admin.dirigente.editar') ||
            Route::is('admin.dirigente.crear') ||
            Route::is('admin.listar.encuestapr') ||
            Route::is('admin.crear.encuestapr') ||
            Route::is('admin.encuestapr.editar') ||
            Route::is('admin.encuestacl.listar') ||
            Route::is('admin.encuestacl.registrar') ||
            Route::is('admin.encuestacl.editar') ||
            Route::is('admin.evaluacionprensa.listar') ||
            Route::is('admin.operacion.listar') ||
            Route::is('admin.dirigente.listar')
                ? 'dropdown active'
                : 'dropdown' }}"> --}}
        <li class="dropdown">
            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="command"></i><span>Parámetros</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('admin.convenios.listar') }}">Convenios</a></li>
                <li><a class="nav-link" href="{{ route('admin.dirigente.listar') }}">Dirigentes</a></li>
                <li><a class="nav-link" href="{{ route('admin.encuestacl.listar') }}">Encuesta de clima</a></li>
                <li><a class="nav-link" href="{{ route('admin.listar.encuestapr') }}">Encuesta de percepción</a></li>
                <li><a class="nav-link" href="{{ route('admin.operacion.listar') }}">Evaluación de operaciones</a></li>
                <li><a class="nav-link" href="{{ route('admin.evaluacionprensa.listar') }}">Evaluación de prensa</a></li>
                <li><a class="nav-link" href="{{ route('admin.entornos.listar') }}">Entornos</a></li>
                <li><a class="nav-link" href="{{ route('admin.impactos.listar') }}">Pilares de modelo de sostenibilidad</a></li>
                <li><a class="nav-link" href="{{ route('admin.divisiones.listar') }}">Divisiones</a></li>
                <li><a class="nav-link" href="{{ route('admin.especies.listar') }}">Especies</a></li>
                <li><a class="nav-link" href="{{ route('admin.listar.org') }}">Organizaciones</a></li>
                <li><a class="nav-link" href="{{ route('admin.pilares.listar') }}">Pilares</a></li>
                <li><a class="nav-link" href="{{ route('admin.subentornos.listar') }}">Subentornos</a></li>
                <li><a class="nav-link" href="{{ route('admin.unidades.listar') }}">Unidades</a></li>
            </ul>
        </li>
        {{-- <li
            class="{{ Route::is('admin.listar.usuario') ||
            Route::is('admin.editar.usuario') ||
            Route::is('admin.crear.usuario') ||
            Route::is('admin.claveusuario.cambiar') ||
            Route::is('admin.claveusuario.actualizar')
                ? 'dropdown active'
                : 'dropdown' }}"> --}}
        <li class="dropdown">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i
                    data-feather="users"></i><span>Usuarios</span></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('admin.listar.usuario') }}">Usuarios creados</a></li>
            </ul>
        </li>
    </ul>
    </aside>
    </div>
@endsection

@section('contenido')
