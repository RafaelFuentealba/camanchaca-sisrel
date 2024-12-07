    <!DOCTYPE html>
    <html lang="es">


    <!-- index.html  21 Nov 2019 03:44:50 GMT -->

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
        <title>SISREL Camanchaca</title>
        <!-- General CSS Files -->
        <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('public/css/social.css') }}" rel="stylesheet">
        <!-- Select2 CSS -->
        <link rel="stylesheet" href="{{ asset('public/bundles/select2/dist/css/select2.min.css') }}">
        <!-- Template CSS -->
        <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('public/css/components.css') }}" rel="stylesheet">
        <!-- Custom style CSS -->
        <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
        <link rel='shortcut icon' type='image/x-icon' href='assets/img/favicon.ico' />
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.1/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
            integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
        <link rel="stylesheet" href="{{ asset('public/css/mapa.css') }}" />
        {{-- <script src="{{asset('public/js/mapa.js')}}"></script> --}}
        <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/img/camanchaca.png') }}' />
        <link rel="stylesheet" href="{{ asset('public/css/leaflet.legend.css') }}" />
        <script src="{{ asset('public/js/leaflet.legend.js') }}"></script>
        <!-- componentes del formulario -->
        <link rel="stylesheet" href="{{ asset('public/css/app.min.css') }}">
        <link rel="stylesheet" href="{{ asset('public/bundles/jquery-selectric/selectric.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/components.css') }}">
        <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
        <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/img/favicon.ico') }}' />
    </head>

    <body class="light light-sidebar theme-white sidebar-mini">
        <!-- <div class="loader"></div> -->
        <div id="app">
            <div class="main-wrapper main-wrapper-1">
                <div class="navbar-bg"></div>
                <nav class="navbar navbar-expand-lg main-navbar sticky">
                    <div class="form-inline mr-auto">
                        <ul class="navbar-nav mr-3">
                            <li>
                                <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn"><i data-feather="align-justify"></i></a>
                            </li>
                            <li>
                                <a href="#" class="nav-link nav-link-lg fullscreen-btn"><i data-feather="maximize"></i></a>
                            </li>
                        </ul>
                    </div>
                    <ul class="navbar-nav navbar-right">
                        <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                                <figure class="avatar mr-2" data-initial="
                                    @if (Session::has('admin'))
                                        {{ Str::upper(Session::get('admin.usua_nombre')[0]).Str::upper(Session::get('admin.usua_apellido')[0]) }}
                                    @elseif (Session::has('digitador'))
                                        {{ Str::upper(Session::get('digitador.usua_nombre')[0]).Str::upper(Session::get('digitador.usua_apellido')[0]) }}
                                    @elseif(Session::has('observador'))
                                        {{ Str::upper(Session::get('observador.usua_nombre')[0]).Str::upper(Session::get('observador.usua_apellido')[0]) }}
                                    @endif
                                "></figure>
                                <span class="d-sm-none d-lg-inline-block"></span></a>
                            <div class="dropdown-menu dropdown-menu-right pullDown">
                                <div class="dropdown-title">
                                    @if (Session::has('admin'))
                                        Hola {{ Session::get('admin.usua_nombre') }}
                                    @elseif (Session::has('digitador'))
                                        Hola {{ Session::get('digitador.usua_nombre') }}
                                    @elseif(Session::has('observador'))
                                        Hola {{ Session::get('observador.usua_nombre') }}
                                    @endif

                                </div>
                                @if (Session::has('admin'))
                                    <a href="{{ route('admin.perfil.show', ['usua_rut' => Session::get('admin.usua_rut'), 'rous_codigo' => Session::get('admin.rous_codigo')]) }}" class="dropdown-item has-icon"><i class="far fa-user"></i> Perfil</a>
                                @elseif (Session::has('digitador'))
                                    <a href="{{ route('digitador.perfil.show', ['usua_rut' => Session::get('digitador.usua_rut'), 'rous_codigo' => Session::get('digitador.rous_codigo')]) }}" class="dropdown-item has-icon"><i class="far fa-user"></i> Perfil</a>
                                @elseif (Session::has('observador'))
                                    <a href="{{ route('observador.perfil.show', ['usua_rut' => Session::get('observador.usua_rut'), 'rous_codigo' => Session::get('observador.rous_codigo')]) }}" class="dropdown-item has-icon"><i class="far fa-user"></i> Perfil</a>
                                @endif

                                <a href="{{ route('auth.cerrar') }}" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="main-sidebar">
                    <aside id="sidebar-wrapper">
                        <div class="sidebar-brand theme-cyan">
                            <a href="javascript:void(0)"> <img alt="image" src="{{ asset('public/img/camanchaca.png') }}" class="header-logo" />
                                <span class="logo-name">SISREL</span>
                            </a>
                        </div>



                        <!-- barra lateral izquierda  -->
                        @yield('acceso')



                        <!-- Main Content -->
                        <div class="main-content">
                            @yield('contenido')
                        </div>



                </div>
            </div>
        </div>
        </div>

        <!-- General JS Scripts -->
        <script src="{{ asset('public/js/app.min.js') }}"></script>
        <!-- JS Libraies -->
        <script src="{{ asset('public/js/chart.min.js') }}"></script>
        <!-- Page Specific JS File -->
        {{-- <script src="{{ asset('public//js/chart-chartjs.js') }}"></script> --}}
        <script src="{{ asset('public/bundles/select2/dist/js/select2.full.min.js') }}"></script>
        <!-- Template JS File -->
        <script src="{{ asset('public/js/scripts.js') }}"></script>
        <!-- Custom JS File -->
        <script src="{{ asset('public/js/custom.js') }}"></script>
        {{-- <script src="{{ asset('public/js/index.js') }}"></script> --}}
        {{-- <!-- General JS Scripts -->
            <script src="{{ asset('public/js/app.min.js') }}"></script>
            <!-- JS Libraies -->
            <script src="{{ asset('public/js/apex.min.js') }}"></script>
            <!-- Page Specific JS File -->
            <script src="{{ asset('public/js/index.js') }}"></script>
            <!-- Template JS File -->
            <script src="{{ asset('public/js/scripts.js') }}"></script>
            <!-- Custom JS File -->
            <script src="{{ asset('public/js/custom.js') }}"></script> --}}

        <script src="{{ asset('public/bundles/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
        <script src="{{ asset('public/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
        <script src="{{ asset('public/js/page/auth-register.js') }}"></script>


    </body>


    <!-- index.html  21 Nov 2019 03:47:04 GMT -->

    </html>
