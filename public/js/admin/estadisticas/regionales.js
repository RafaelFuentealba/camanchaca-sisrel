const csrftoken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;

function getURLParams(url) {
    let params = {};
    new URLSearchParams(url.replace(/^.*?\?/, '?')).forEach(function(value, key) {
      params[key] = value
    });
    return params;
}


$(document).ready(() => {
    // $('#regiHide').hide();
    // console.log($('#regiHide').text()); acceso a valor
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
    var region = getURLParams(window.location.href)['regi_codigo']

    $.ajax({
        type:'GET',
        url:`${window.location.origin}/admin/estadisticas/regionales/datos`,
        data:{
            region : region
        },
        success:function(data){
            data = JSON.parse(data);
            var nombresEntornosD = data.N_entornos_D;
            var totalEntornosD = data.T_entornos_D;

            var nombresEntonosA = data.N_entornos_A
            var totalEntonosA = data.T_entornos_A

            var nombresEntonosP = data.N_entornos_P
            var totalEntonosP = data.T_entornos_P

            var nombresEntonosI = data.N_entornos_I
            var totalEntonosI = data.T_entornos_I

            donacionesPorEntornos(nombresEntornosD,totalEntornosD);
            ParticipantesPorEntornos(nombresEntonosP,totalEntonosP);
            IniciativasPorEntornos(nombresEntonosI,totalEntonosI);
            RelacionamientoPorEntornos(nombresEntonosA,totalEntonosA);


        }
    });
}


function donacionesPorEntornos(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartDonacionesPorEntorno").getContext("2d");
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

function RelacionamientoPorEntornos(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartRelacionamientoPorEntorno").getContext("2d");
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
                            fontColor: "#9aa0ac",
                        },
                    },
                ],
                xAxes: [
                    {
                        barThickness: 25,
                        ticks: {
                            display: false,
                            beginAtZero: true,
                            display: true,
                            stepSize: 5,
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

function ParticipantesPorEntornos(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartParticipantesPorEntorno").getContext("2d");
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

function IniciativasPorEntornos(labels, datos) {
    let colorBarra = poolColors(datos.length);
    let ctx = document.getElementById("chartInicitivasEntorno").getContext("2d");
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
