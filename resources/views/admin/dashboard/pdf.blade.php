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
            html {
            min-height: 100%;
            position: relative;
            }
            body {
            margin: 0;
            margin-bottom: 40px;
            font-family: Arial, Helvetica, sans-serif;
            }
            footer {
            background-color: #0070BA;
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 40px;
            color: white;
            }
            #table {
              font-family: Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }

            #table td, #table th {
              border: 1px solid #ddd;
              padding: 1px;
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

            .container {
            font-size: 0; /* Elimina el espacio entre los elementos en línea */
            }

            .div1, .div2 {
            display: inline-block;
            width: 50%;
            vertical-align: top;
            font-size: 16px; /* Restaura el tamaño de fuente a su valor normal */
            }


            .tableMod {
            border-collapse: collapse;
            text-align: left;
            width: 100%;
            }

            .tdMod {
            padding: 8px;
            }

            .tdMod:first-child {
            text-align: left;
            }

            .tdMod:last-child {
            text-align: left;
            }

            .titulo{
                font-weight: bold;
            }

            .colorMod{
                color: black;
            }


            </style>
    </head>

    <body>

        <div class="content" style="text-align: center;">
            <img width="200px" height="auto" style="display: block;margin-left:auto; margin-right:auto;" src="https://www.camanchaca.cl/wp-content/themes/camanchaca/images/logo-cc-web-celeste.png"
                alt="logo camanchaca">
        </div>


        <div style="display: flex; justify-content: space-between; padding-top: 5%; text-align:center;">
            <div>
                <h2 class="colorMod">Compañía Pesquera Camanchaca S.A</h2>
                <h3 class="colorMod">Reporte de actividades</h3>
            </div>
            <div>
                <h3 class="colorMod" style="margin-top:1px">Fecha de reporte: desde {{ $fechaInicio }} hasta
                    {{ $fechaFinal }}</h3>
            </div>
        </div>

        <div class="container" style="margin-top:50px">
            <div class="div1">
                <table id="table">
                    <tr>
                        <th class="tdMod colorMod titulo">Región:</th>
                        <td class="tdMod colorMod ">
                            @if (Request::get('regi_codigo') != null)
                                <span>{{ Request::get('regi_codigo') }}</span>
                            @else
                                <span>No especificada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">División:</th>
                        <td class="tdMod colorMod ">
                            @if (Request::get('divi_codigo') != null)
                                <span>{{ Request::get('divi_codigo') }}</span>
                            @else
                                <span>No especificada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Organizaciones:</th>
                        <td class="tdMod colorMod ">
                            <span>{{ $cantidadOrganizaciones }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Donaciones:</th>
                        <td class="tdMod colorMod ">
                            <span>{{ $cantidadDonaciones }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Organizaciones en iniciativas:</th>
                        <td class="tdMod colorMod">
                            <span>{{ $iniciativasOrganizaciones }}</span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="div2">
                <table id="table">
                    <tr>
                        <th class="tdMod colorMod titulo">Comuna:</th>
                        <td class="tdMod colorMod ">
                            @if (Request::get('comu_codigo') != null)
                                <span>{{ Request::get('comu_codigo') }}</span>
                            @else
                                <span>No especificada</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Iniciativas:</th>
                        <td class="tdMod colorMod ">
                            <span>{{ $cantidadIniciativas }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Actividades:</th>
                        <td class="tdMod colorMod ">
                            <span>{{ $cantidadActividades }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th class="tdMod colorMod titulo">Organizaciones en actividades:</th>
                        <td class="tdMod colorMod ">
                            <span>{{ $actividadesOrganizaciones }}</span>
                        </td>
                    </tr>
                </table>
            </div>
          </div>



        <div style="padding-top: 5%">
            @if (count($iniciativasDatos) == 0)
                <h2 class="colorMod" style="text-align:center">No hay iniciativas para los datos seleccionados</h2>
            @else
            <h2 class="colorMod" style="text-align:center">Iniciativas</h2>
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
                <h2 class="colorMod" style="text-align:center">No hay unidades para los datos seleccionados</h2>
            @else
                <h2 class="colorMod" style="text-align:center">Unidades</h2>
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
            <h2 class="colorMod" style="text-align:center">Financiamiento</h2>
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

        <footer style="text-align: center;">PDF generado en: <a style="font-weight:bold; color:white;" href="camanchaca.vinculamos.org">camanchaca.vinculamos.org</a> | Compañia Pesquera Camanchaca SA</footer>

    </body>

    </html>
