const csrftoken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;

$(document).ready(() => {
    cargarInformacionGraficos();
});

function dynamicColors() {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return "rgba(" + r + "," + g + "," + b + ", 1)";
}

function poolColors(a) {
    var pool = [];
    for (i = 0; i < a; i++) {
        pool.push(dynamicColors());
    }
    return pool;
}

function cargarInformacionGraficos() {
    fetch(`${window.location.origin}/admin/estadisticas/nacional/datos`, {
        method: "GET",
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var nombresUnidades = data.N_unidades;
            var totalUnidades = data.T_unidades;

            var nombresRegiones = data.N_regiones;
            var totalRegiones = data.T_regiones;

            var nombresRegionesP = data.N_regiones_p;
            var totalParticipantes = data.T_participantes;

            var nombresRegionesR = data.N_regiones_R;
            var totalRelacionamientos = data.T_participantes_R;

            var nombresRegionesD = data.N_regiones_D;
            var totalDonaciones = data.T_Donaciones;

            var promedio = data.invi;

            IniciativasPorPilares(nombresUnidades, totalUnidades);
            IniciativasPorRegiones(nombresRegiones,totalRegiones);
            ParticipantesPorRegion(nombresRegionesP,totalParticipantes);
            RelacionamientosPorRegion(nombresRegionesR,totalRelacionamientos);
            DonacionesPorRegion(nombresRegionesD,totalDonaciones);
            INVI(promedio);
        });
}

function IniciativasPorPilares(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartInicitivasPilares").getContext("2d");
    let myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Iniciativas",
                    data: datos,
                    borderWidth: 2,
                    backgroundColor: colorBarra,
                    borderColor: colorBarra,
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            legend: {
                position: "bottom",
            },
        },
    });
}


function IniciativasPorRegiones(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartInicitivasRegion").getContext("2d");
    let myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Iniciativas",
                    data: datos,
                    borderWidth: 2,
                    backgroundColor: colorBarra,
                    borderColor: colorBarra,
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            legend: {
                display: false,
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                            fontColor: "#9aa0ac",
                        },
                    },
                ],
                xAxes: [
                    {
                        barThickness: 25,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            display: false,
                        },
                    },
                ],
            },
        },
    });
}

function DonacionesPorRegion(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartDonacionesRegion").getContext("2d");
    let myChart = new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Monto",
                    data: datos,
                    borderWidth: 2,
                    backgroundColor: colorBarra,
                    borderColor: colorBarra,
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            legend: {
                display: false,
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1000000,
                            fontColor: "#9aa0ac",
                        },
                    },
                ],
                xAxes: [
                    {
                        barThickness: 25,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            display: false,
                        },
                    },
                ],
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data, inversion) {
                        var xLabel = data.datasets[tooltipItem.datasetIndex].label;
                        var yLabel = tooltipItem.yLabel >= 1000 ? '$' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") : '$' + tooltipItem.yLabel;
                        return xLabel + ': ' + yLabel;
                    }
                }
            }
        },
    });
}

function ParticipantesPorRegion(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartParticipantesRegion").getContext("2d");
    let myChart = new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Participantes",
                    data: datos,
                    borderWidth: 2,
                    backgroundColor: colorBarra,
                    borderColor: colorBarra,
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            responsive: true,
            legend: {
                position: "bottom",
            },
        },
    });
}



function INVI(promedio) {
    let ctx = document.getElementById("chartIndiceVinculacion").getContext("2d");
    let myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [promedio, 100-promedio],
                backgroundColor: [
                    '#6777ef'
                ],
                label: ''
            }],
            labels: ['Puntaje promedio', 'Puntaje restante'],
        },
        options: {
            responsive: true,
            legend: {
                position: 'right',
            },
        }
    });
}

function RelacionamientosPorRegion(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartRealacionamientosRegion").getContext("2d");
    let myChart = new Chart(ctx, {
        type: "horizontalBar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Actividades",
                    data: datos,
                    borderWidth: 2,
                    backgroundColor: colorBarra,
                    borderColor: colorBarra,
                    borderWidth: 2.5,
                    pointBackgroundColor: "#ffffff",
                    pointRadius: 4,
                },
            ],
        },
        options: {
            legend: {
                display: false,
            },
            scales: {
                yAxes: [
                    {
                        gridLines: {
                            drawBorder: false,
                            color: "#f2f2f2",
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 5,
                            fontColor: "#9aa0ac",
                        },
                    },
                ],
                xAxes: [
                    {
                        barThickness: 25,
                        ticks: {
                            display: false,
                        },
                        gridLines: {
                            display: false,
                        },
                    },
                ],
            },
        },
    });
}
