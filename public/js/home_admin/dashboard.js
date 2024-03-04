$(document).ready(function () {
    iniciativas();
    organizaciones();
});

function pieChart(div_name, data, view_label = true) {
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create(div_name, am4charts.PieChart);
    $(`#${div_name}`).css("height", "10cm");
    var ejes = Object.keys(data[0]);
    var labels = ejes[0];
    var valores = ejes[1];
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
        type: "GET",
        url: window.location.origin + "/admin/dashboard/general/iniciativas",
        data: {
            region: $("#region").val(),
            division: $("#division").val(),
            anho: $("#anho").val(),
        },
        success: function (resConsultar) {
            respuesta = JSON.parse(resConsultar);
            resPilares = respuesta.resultado[0];
            iniciativasPilares = respuesta.resultado[1];

            pilares = [];
            resPilares.forEach((registro) => {
                pilares.push(registro.pila_nombre);
            });

            var pilaresOBJ = pilares
                .map((nombre, index) => {
                    if (iniciativasPilares[index] > 0) {
                        return {
                            nombre: nombre,
                            cantidad: iniciativasPilares[index],
                        };
                    } else {
                        return null;
                    }
                })
                .filter((objeto) => objeto !== null);
            if (Object.keys(pilaresOBJ).length > 0) {
                pieChart("chartIniciativas", pilaresOBJ);
            } else {
                $("#chartIniciativas").html(
                    `<h4>No hay datos disponibles</h4>`
                );
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function organizaciones() {
    $.ajax({
        type: "GET",
        url: window.location.origin + "/admin/dashboard/general/organizaciones",
        data: {
            region: $("#region").val(),
            division: $("#division").val(),
            anho: $("#anho").val(),
        },
        success: function (resConsultar) {
            respuesta = JSON.parse(resConsultar);
            resEntornos = respuesta.resultado[0];
            totalOrganizaciones = respuesta.resultado[1];

            entornos = [];
            resEntornos.forEach((registro) => {
                entornos.push(registro.ento_nombre);
            });

            var organizacioneOBJ = entornos
                .map((nombre, index) => {
                    if (totalOrganizaciones[index] > 0) {
                        return {
                            nombre: nombre,
                            cantidad: totalOrganizaciones[index],
                        };
                    } else {
                        return null;
                    }
                })
                .filter((objeto) => objeto !== null);

            if (Object.keys(organizacioneOBJ).length > 0) {
                pieChart("chartOrganizaciones", organizacioneOBJ, false);
            } else {
                $("#chartOrganizaciones").html(
                    `<h4>No hay datos disponibles</h4>`
                );
            }
        },
        error: function (error) {
            console.log(error);
        },
    });
}

function cargarComunas() {
    var region = $("#regi_codigo").val();
    fetch(`${window.location.origin}/admin/dashboard/obtener/regiones`, {
        method: "POST",
        body: JSON.stringify({
            region: region,
        }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": document.head.querySelector(
                "[name~=csrf-token][content]"
            ).content,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var opciones = "<option value=''>Seleccione...</option>";
            for (let i in data.comunas) {
                opciones += `<option value='${data.comunas[i].comu_codigo}'>${data.comunas[i].comu_nombre}</option>`;
            }
            $("#comu_codigo").html(opciones);
        });
}
