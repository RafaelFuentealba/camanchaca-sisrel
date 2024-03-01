    {{-- <h1>Iniciativas : {{ $cantidadIniciativas }}</h1>
    <h1>Organizaciones : {{ $cantidadOrganizaciones }}</h1>
    <h1>Actividades : {{ $cantidadActividades }}</h1>
    <h1>Donaciones : {{ $cantidadDonaciones }}</h1>
    <h1>Organizaciones en actividades : {{ $actividadesOrganizaciones }}</h1>
    <h1>Organizaciones en iniciativas : {{ $iniciativasOrganizaciones }}</h1> --}}

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Reporte camanchaca</title>

        <style>
            #table {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }

            #table td, #table th {
              border: 1px solid #ddd;
              padding: 8px;
            }

            #table tr:nth-child(even){background-color: #f2f2f2;}

            #table tr:hover {background-color: #ddd;}

            #table th {
              padding-top: 8px;
              padding-bottom: 8px;
              text-align: left;
              background-color: #0070BA;
              color: white;
            }
            </style>
    </head>

    <body>

        <div class="content">
            <img src="https://www.camanchaca.cl/wp-content/themes/camanchaca/images/logo-cc-web-celeste.png"
                alt="logo camanchaca">
        </div>


        <h1 style="color: #0070BA; text-align:center;">Reporte</h1>
        <div class="contenedor">
            <table id="table">
                @if (Request::get('regi_codigo') != null)
                    <tr>
                        <th>Región</th>
                        <td><span>{{ Request::get('regi_codigo') }}</span></td>
                    </tr>
                @else
                <tr>
                    <th>Región</th>
                    <td><span>No especificada</span></td>
                </tr>
                @endif
                @if (Request::get('comu_codigo') != null)
                <tr>
                    <th>Comuna</th>
                    <td><span>{{ Request::get('comu_codigo') }}</span></td>
                </tr>
                @else
                <tr>
                    <th>Comuna</th>
                    <td><span>No especificada</span></td>
                </tr>
                @endif

                @if (Request::get('divi_codigo') != null)
                <tr>
                    <th>División</th>
                    <td><span>{{ Request::get('divi_codigo') }}</span></td>
                </tr>
                @else
                <tr>
                    <th>División</th>
                    <td><span>No especificada</span></td>
                </tr>
                @endif

                <tr>
                    <th>Iniciativas:</th>
                    <td><span>{{ $cantidadIniciativas }}</span></td>
                </tr>
                <tr>
                    <th>Organizaciones:</th>
                    <td><span>{{ $cantidadOrganizaciones }}</span></td>
                </tr>
                <tr>
                    <th>Actividades:</th>
                    <td><span>{{ $cantidadActividades }}</span></td>
                </tr>
                <tr>
                    <th>Donaciones:</th>
                    <td><span>{{ $cantidadDonaciones }}</span></td>
                </tr>
                <tr>
                    <th>Organizaciones en actividades:</th>
                    <td><span>{{ $actividadesOrganizaciones }}</span></td>
                </tr>
                <tr>
                    <th>Organizaciones en iniciativas:</th>
                    <td><span>{{ $iniciativasOrganizaciones }}</span></td>
                </tr>


            </table>

        </div>

        <div style="padding-top: 10%">
            @if (count($iniciativasDatos) == 0)
                <h2 style="color: #0070BA; text-align:center">No hay iniciativas para los datos seleccionados</h2>
            @else
            <table id="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Iniciativa</th>
                        <th>Responsable</th>
                        <th>Fecha de inicio</th>
                        <th>Fecha de finalización</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($iniciativasDatos as $iniciativas)
                        <tr>
                            <td>{{ $iniciativas->inic_codigo }}</td>
                            <td>{{ $iniciativas->inic_nombre }}</td>
                            <td>{{ $iniciativas->inic_nombre_responsable }}</td>
                            <td>{{ \Carbon\Carbon::parse($iniciativas->inic_fecha_inicio)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($iniciativas->inic_fecha_fin)->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @endif

        </div>

        <div>
            @if (count($iniciativasUnidades) == 0)
                <h2 style="color: #0070BA; text-align:center">No hay unidades para los datos seleccionados</h2>
            @else
                <table id="table">
                    <thead>
                        <tr>
                            <th>ID Iniciativa</th>
                            <th>Unidades</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($iniciativasUnidades as $iniciativas )
                        <tr>
                            <td>{{$iniciativas->inic_codigo}}</td>
                            <td>{{$iniciativas->unidades_nombre}}</td>
                        </tr>
                        @endforeach


                    </tbody>
                    </tr>
                </table>

            @endif

        </div>

        <div >
            <table id="table">
                <thead>
                    <tr>
                        <th>Aportes</th>
                        <th>Especies</th>
                        <th>Infraestructura</th>
                        <th>Recursos humanos</th>
                        <th>Donaciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ '$' . number_format($costosDinero, 0, ',', '.') }}</td>
                        <td>{{ '$' . number_format($costosEspecies, 0, ',', '.') }}</td>
                        <td>{{ '$' . number_format($costosInfra, 0, ',', '.') }}</td>
                        <td>{{ '$' . number_format($costosRrhh, 0, ',', '.') }}</td>
                        <td>{{ '$' . number_format($costosDonaciones, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
                </tr>
            </table>
        </div>
        <p style="font-size: 10; text-align:center;"><strong>Datos obtenidos </strong><span> desde {{ $fechaInicio }} hasta
            {{ $fechaFinal }}</span></p>
        <p style="text-align: center;">PDF generado en: <a href="camanchaca.vinculamos.org">camanchaca.vinculamos.org</a> | Compania Pesquera Camanchaca SA</p>

    </body>

    </html>
