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
            body {
                font-family: Arial, Helvetica, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 0;
            }

            .content {
                text-align: center;
                padding: 20px;
                padding-bottom: 10%;
            }

            img {
                width: 293px;
                height: 81px;
            }

            //TODO:TABLA DE COSTOS
            table.greenTable {
                font-family: "Courier New", Courier, monospace;
                border: 6px solid #24943A;
                background-color: #D4EED1;
                width: 100%;
                text-align: right;
            }

            table.greenTable td,
            table.greenTable th {
                border: 1px solid #24943A;
                padding: 3px 2px;
            }

            table.greenTable tbody td {
                font-size: 13px;
            }

            table.greenTable td:nth-child(even) {
                background: #B7D9F5;
            }

            table.greenTable thead {
                background: #24943A;
                border-bottom: 0px solid #444444;
            }

            table.greenTable thead th {
                font-size: 19px;
                font-weight: bold;
                color: #F0F0F0;
                text-align: center;
                border-left: 2px solid #24943A;
            }

            table.greenTable thead th:first-child {
                border-left: none;
            }

            table.greenTable tfoot td {
                font-size: 13px;
            }

            table.greenTable tfoot .links {
                text-align: right;
            }

            table.greenTable tfoot .links a {
                display: inline-block;
                background: #FFFFFF;
                color: #24943A;
                padding: 2px 8px;
                border-radius: 5px;
            }

            //TODO: TABLA DE INICIATIVAS
            table.blueTable {
                border: 1px solid #1C6EA4;
                background-color: #EEEEEE;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
            }

            table.blueTable td,
            table.blueTable th {
                border: 3px solid #AAAAAA;
                padding: 3px 2px;
            }

            table.blueTable tbody td {
                font-size: 13px;
            }

            table.blueTable tr:nth-child(even) {
                background: #D0E4F5;
            }

            table.blueTable thead {
                background: #1C6EA4;
                border-bottom: 2px solid #444444;
            }

            table.blueTable thead th {
                font-size: 15px;
                font-weight: bold;
                color: #FFFFFF;
                border-left: 2px solid #D0E4F5;
            }

            table.blueTable thead th:first-child {
                border-left: none;
            }

            table.blueTable tfoot td {
                font-size: 14px;
            }

            table.blueTable tfoot .links {
                text-align: right;
            }

            table.blueTable tfoot .links a {
                display: inline-block;
                background: #1C6EA4;
                color: #FFFFFF;
                padding: 2px 8px;
                border-radius: 5px;
            }

            //TODO:tabla unidades
            table.redTable {
                border: 2px solid #A40808;
                background-color: #EEE7DB;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
            }

            table.redTable td,
            table.redTable th {
                border: 1px solid #AAAAAA;
                padding: 3px 2px;
            }

            table.redTable tbody td {
                font-size: 13px;
            }

            table.redTable tr:nth-child(even) {
                background: #F5C8BF;
            }

            table.redTable thead {
                background: #A40808;
            }

            table.redTable thead th {
                font-size: 17px;
                font-weight: bold;
                color: #FFFFFF;
                text-align: center;
                border-left: 2px solid #A40808;
            }

            table.redTable thead th:first-child {
                border-left: none;
            }

            table.redTable tfoot td {
                font-size: 13px;
            }

            table.redTable tfoot .links {
                text-align: right;
            }

            table.redTable tfoot .links a {
                display: inline-block;
                background: #FFFFFF;
                color: #A40808;
                padding: 2px 8px;
                border-radius: 5px;
            }
        </style>
    </head>

    <body>

        <div class="content">
            <img src="https://www.camanchaca.cl/wp-content/themes/camanchaca/images/logo-cc-web-celeste.png"
                alt="logo camanchaca">
        </div>

        <div class="contenedor">
            <div class="valor">
                @if (Request::get('regi_codigo') != null)
                    <p style="font-size: 10"><strong>Región: </strong><span>{{ Request::get('regi_codigo') }}</span></p>
                @else
                    <p style="font-size: 10"><strong>Región: </strong><span>No especificada</span></p>
                @endif

                @if (Request::get('comu_codigo') != null)
                    <p style="font-size: 10"><strong>Comuna: </strong><span>{{ Request::get('comu_codigo') }}</span></p>
                @else
                    <p style="font-size: 10"><strong>Comuna: </strong><span>No especificada</span></p>
                @endif

                @if (Request::get('divi_codigo') != null)
                    <p style="font-size: 10"><strong>División </strong><span>{{ Request::get('divi_codigo') }}</span>
                    </p>
                @else
                    <p style="font-size: 10"><strong>División </strong><span>No especificada</span></p>
                @endif

                <p style="font-size: 10"><strong>Datos obtenidos: </strong><span> desde {{ $fechaInicio }} hasta
                        {{ $fechaFinal }}</span></p>
                <p style="font-size: 10"><strong>Iniciativas: </strong><span>{{ $cantidadIniciativas }}</span></p>
                <p style="font-size: 10"><strong>Organizaciones: </strong><span>{{ $cantidadOrganizaciones }}</span></p>
                <p style="font-size: 10"><strong>Actividades: </strong><span>{{ $cantidadActividades }}</span></p>
                <p style="font-size: 10"><strong>Donaciones: </strong><span>{{ $cantidadDonaciones }}</span></p>
                <p style="font-size: 10"><strong>Organizaciones en actividades:
                    </strong><span>{{ $actividadesOrganizaciones }}</span></p>
                <p style="font-size: 10"><strong>Organizaciones en iniciativas:
                    </strong><span>{{ $iniciativasOrganizaciones }}</span></p>
            </div>

        </div>

        <div style="padding-top: 10%">
            <table class="blueTable">
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
        </div>

        <div style="padding-top: 10%;padding-left: 30%">
            <table class="redTable">
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
        </div>

        <div style="padding-top: 10%">
            <table class="greenTable">
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

    </body>

    </html>
