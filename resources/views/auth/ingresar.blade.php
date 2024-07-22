<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Inicio de sesión - SISREL Camanchaca</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('public/css/estilos.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('public/img/camanchaca.png') }}' />

</head>

<body style="background: url({{ asset('public/img/mar.jpg') }}); background-size:cover; background-repeat:no-repeat;">
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-10">
                    <div class="wrap d-md-flex">
                        <div class="img" style="background-image: url({{ asset('public/img/camanchaca.png') }});">
                        </div>
                        <div class="login-wrap p-4 p-md-5">
                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">INICIAR SESIÓN</h3>
                                </div>
                            </div>
                            <form action="{{route('auth.ingresar')}}" class="signin-form" method="POST">
                                @csrf

                                @if (Session::has('errorRut'))
                                    <div class="alert alert-danger alert-dismissible show fade text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('errorRut') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif

                                @if (Session::has('errorClave'))
                                    <div class="alert alert-danger alert-dismissible show fade text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('errorClave') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif

                                @if (Session::has('sessionFinalizada'))
                                    <div class="alert alert-danger alert-dismissible show fade text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('sessionFinalizada') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif

                                @if (Session::has('usuarioRegistrado'))
                                    <div class="alert alert-success alert-dismissible show fade text-center">
                                        <div class="alert-body">
                                            <strong>{{ Session::get('usuarioRegistrado') }}</strong>
                                            <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group mb-3">
                                    <label class="label" for="run">Run</label>
                                    <input type="text" class="form-control"
                                        placeholder="Ingrese su run sin puntos y con guión"
                                        required pattern="\d{3,8}-[\d|kK]{1}" title="Debe ser un Rut válido"
                                        name="run" id="run" value="{{ old('run') }}" />
                                    @if($errors->has('run'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('run') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="clave">Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Ingrese su contraseña" required
                                        id="clave" name="clave">
                                    @if($errors->has('clave'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('clave') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group mb-3">
                                    <label class="label" for="clave">Rol de acceso</label>
                                    <div class="form-group">
                                        <select class="form-control" id="rol" name="rol">
                                            @foreach ($roles as $rol)
                                                <option value="{{ $rol->rous_codigo }}">{{ $rol->rous_nombre }}</option>
                                            @endforeach
                                        </select>
                                      </div>
                                    @if($errors->has('rol'))
                                        <div class="alert alert-warning alert-dismissible show fade mt-2">
                                            <div class="alert-body">
                                                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                                <strong>{{ $errors->first('rol') }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="form-control btn btn-primary rounded submit px-3">Ingresar</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('public/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/popper.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/js/main.js') }}"></script>

</body>

</html>
