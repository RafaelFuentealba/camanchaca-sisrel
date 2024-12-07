<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Reporte Camanchaca</title>
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/app.min.css') }}">
</head>

<body class="mt-4">
    <div class="section-body p-4">
        <div class="row d-print-none">
            <div class="col-xl-10"></div>
            <div class="col-xl-2">
                <div class="card">
                    <div class="card-header">
                        <div class="card-header-action">
                            <button id="btnGuardar" class="btn btn-primary d-print-none">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row">
                <div class="col-xl-3 col-sm-3 col-md-3 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <img src="{{asset('public/img/camanchaca.png')}}" style="max-width: 100%; height: auto;" alt="">
                            <h4>Reporte Camanchaca</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-sm-4 col-md-4 col-lg-4">

                    <div class="card">
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item">Región:
                                    {{ Request::get('regi_codigo') != null ? Request::get('regi_codigo') : 'No especificado.' }}
                                </li>
                                <li class="list-group-item">Comuna:
                                    {{ Request::get('comu_codigo') != null ? Request::get('comu_codigo') : 'No especificado.' }}
                                </li>
                                <li class="list-group-item">División:
                                    {{ Request::get('divi_codigo') != null ? Request::get('divi_codigo') : 'No especificado.' }}
                                </li>
                                <li class="list-group-item">Desde: {{ $fechaInicio }} hasta {{ $fechaFinal }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row">

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-2 l-bg-orange">
                            <div class="card-icon l-bg-cyan">
                                <i class="fab fa-slack"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($iniciativasCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Iniciativas
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-orange">
                            <div class="card-icon l-bg-red">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($organizacionesCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            N° organizaciones involucradas en iniciativas
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-2 l-bg-green">
                            <div class="card-icon l-bg-red">
                                <i class="fas fa-clipboard"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($actividadesCantidad) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Actividades en bitácora
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-green">
                            <div class="card-icon l-bg-cyan">
                                <i class="fas fa-hotel"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            {{ count($organizacionesCantidadActividades) }}
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            N° organizaciones involucradas en actividades
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">

                        <div class="card card-statistic-2 l-bg-red">
                            <div class="card-icon l-bg-cyan">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="padding-20">
                                    <div class="text-right">
                                        <h3 class="font-light mb-0"
                                            title="{{ '$' . number_format($costosDonaciones, 0, ',', '.') }}">
                                            {{-- Aca va el valor numerico a represnetar --}}
                                            @if ($costosDonaciones > 1000000)
                                                {{ number_format($costosDonaciones / 1000000, 1) . ' M' }}
                                            @else
                                                {{ '$' . number_format($costosDonaciones, 0, ',', '.') }}
                                            @endif
                                        </h3>
                                        <h6 class="font-light">
                                            {{-- aca va el texto que representa el numero de arriba --}}
                                            Monto de donaciones
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="table-responsive">

                    <table class="table table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>Comuna</th>
                                <th>Organizaciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organizacionesByComunas->groupBy('comu_nombre') as $comuna => $organizaciones)
                                <tr>
                                    <td>{{ $comuna }}</td>
                                    <td>
                                        @foreach ($organizaciones as $key => $organizacion)
                                            {{ $organizacion->orga_nombre }}{{ $key < count($organizaciones) - 1 ? ',' : '' }}
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        document.getElementById('btnGuardar').addEventListener('click', function() {
            // Configurar las opciones de impresión
            var printOptions = {
                headersAndFooters: false, // Desmarcar la opción de encabezados y pies de página
                background: true // Marcar la opción de gráficos en segundo plano
            };

            // Imprimir la página con las opciones configuradas
            window.print(printOptions);
        });

        $(document).ready(function() {
            iniciativas();
            organizaciones();

        });

        function pieChart(div_name, data, view_label = true) {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create(div_name, am4charts.PieChart);
            $(`#${div_name}`).css("height", "10cm")
            var ejes = Object.keys(data[0]);
            var labels = ejes[0];
            var valores = ejes[1]
            // Add data
            chart.data = data;


            // Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = valores;
            pieSeries.dataFields.category = labels;
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.labels.template.fill = am4core.color("#9aa0ac");

            // Configura la leyenda (barra lateral)
            if (view_label == true) {
                chart.legend = new am4charts.Legend();
                chart.legend.position = "top";
            }
            // Configura las etiquetas
            pieSeries.ticks.template.disabled = false; // Desactiva las marcas de división
            pieSeries.labels.template.disabled = false; // Habilita las etiquetas
            pieSeries.labels.template.text =
                "{category}: {value.percent.formatNumber('#.0')}%"; // Personaliza el texto de las etiquetas

            // This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;
        }

        function iniciativas() {
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/observador/dashboard/general/iniciativas',
                data: {
                    region: $('#region').val(),
                    division: $('#division').val()
                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    resPilares = respuesta.resultado[0];
                    iniciativasPilares = respuesta.resultado[1];

                    pilares = [];
                    resPilares.forEach(registro => {
                        pilares.push(registro.pila_nombre);
                    });

                    var pilaresOBJ = pilares.map((nombre, index) => {
                        if (iniciativasPilares[index] > 0) {
                            return {
                                nombre: nombre,
                                cantidad: iniciativasPilares[index]
                            }
                        } else {
                            return null;
                        }
                    }).filter(objeto => objeto !== null);
                    if (Object.keys(pilaresOBJ).length > 0) {
                        pieChart("chartIniciativas", pilaresOBJ)
                    } else {
                        $("#chartIniciativas").html(`<h4>No hay datos disponibles</h4>`)
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        function organizaciones() {
            $.ajax({
                type: 'GET',
                url: window.location.origin + '/observador/dashboard/general/organizaciones',
                data: {
                    region: $('#region').val(),
                    division: $('#division').val()
                },
                success: function(resConsultar) {
                    respuesta = JSON.parse(resConsultar);
                    resEntornos = respuesta.resultado[0];
                    totalOrganizaciones = respuesta.resultado[1];

                    entornos = [];
                    resEntornos.forEach(registro => {
                        entornos.push(registro.ento_nombre);
                    });

                    var organizacioneOBJ = entornos.map((nombre, index) => {
                        if (totalOrganizaciones[index] > 0) {
                            return {
                                nombre: nombre,
                                cantidad: totalOrganizaciones[index]
                            }
                        } else {
                            return null;
                        }
                    }).filter(objeto => objeto !== null);

                    if (Object.keys(organizacioneOBJ).length > 0) {
                        pieChart("chartOrganizaciones", organizacioneOBJ, false)
                    } else {
                        $("#chartOrganizaciones").html(`<h4>No hay datos disponibles</h4>`)
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    </script>
</body>

</html>
