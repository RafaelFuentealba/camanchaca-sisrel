@extends('layout.index')

@section('acceso')
    <ul class="sidebar-menu">
        <li class="menu-header">Observador</li>
        {{-- <li class="{{
                Route::is('observador.index.iniciativas') ||
                Route::is('observador.index.actividades') ||
                Route::is('observador.index.donaciones')
                ? 'dropdown active' : 'dropdown'
            }}"> --}}
        <li class="dropdown">
            <a href="javascript:void(0)" class="menu-toggle nav-link has-dropdown"><i data-feather="monitor"></i><span>Dashboard</span></a>
            <ul class="dropdown-menu">
                <li><a class="nav-link" href="{{ route('observador.dbgeneral.index') }}">General</a></li>
                <li><a class="nav-link" href="{{ route('observador.index.iniciativas') }}">Por iniciativas</a></li>
                <li><a class="nav-link" href="{{ route('observador.index.actividades') }}">Por actividades</a></li>
                <li><a class="nav-link" href="{{ route('observador.index.donaciones') }}">Por donaciones</a></li>
            </ul>
        </li>
        <li class="{{ Route::is('observador.map') ? 'dropdown active' : 'dropdown' }}">
            <a class="nav-link" href="{{route('observador.map')}}"><i data-feather="map"></i><span>Mapa</span></a></li>
        </li>
    </ul>
    </aside>
    </div>
@endsection


@section('contenido')


@endsection
