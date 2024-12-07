const csrftoken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;
var map = L.map("map");
map.setView([-35.675147, -71.542969], 5);
var sidebar = L.control.sidebar("sidebar", {
    closeButton: true,
    position: "right",
});
map.addControl(sidebar);


$(document).ready(() => {
    $("#div-regiones").hide();
    var selectRegion = document.getElementById("region");
    cargarRegion(selectRegion);
    cargarComunas(selectRegion);

});

L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: "Map data &copy; OpenStreetMap contributors",
}).addTo(map);

function cargarRegion(selectRegion){
    var codigo = selectRegion.value;
    console.log(codigo);
    if (codigo == "TPCA") {
        map.setView([-20.05800242483127, -69.6016620019451], 8);
    }
    if (codigo == "BBIO") {
        map.setView([-37.33128467390922, -72.50630268253872], 8);
    }
    if (codigo == "LAGOS") {
        map.setView([-41.84071943384987, -72.99956719597715], 8);
    }
}

function cargarComunas(selectRegion) {
    var region = selectRegion.value;
    sidebar.hide();
    fetch(`${window.location.origin}/digitador/mapa/obtener/regiones`, {
        method: "POST",
        body: JSON.stringify({
            region: region,
        }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var opciones = "";
            for (let i in data.comunas) {
                // opciones += `<li value='${data.comunas[i].comu_codigo}'>${data.comunas[i].comu_nombre}</li>`;
                console.log(data.comunas[i]);
                opciones += `<li onclick="cargarInfoComuna(${data.comunas[i].comu_codigo})">${data.comunas[i].comu_nombre}</li>`;
            }
            $("#comunas").html(opciones);
            $('#div-alert-undifined').hide();
        });
}

function cargarInfoComuna(comu_codigo) {
    // var comuna = $("#comunas").val();
    var comuna = comu_codigo;
    var region = $("#region").val();
    fetch(`${window.location.origin}/digitador/mapa/obtener/comuna`, {
        method: "POST",
        body: JSON.stringify({
            comunas: comuna,
            region: region,
        }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            sidebar.hide();
            // var percepcion = 0;
            // var clima = 0;
            // var prensa = 0;
            // var operaciones = 0;
            // var comu_avg = 0;
            // var color_comuna = "";
            // var escala = [100, 90, 80, 70, 60, 50, 40, 30, 20, 10, 0];

            // var opciones = "<option value=''>Seleccione...</option>";
            // for (let i in data.entornos) {
            //     opciones += `<option value='${data.entornos[i].ento_codigo}'>${data.entornos[i].ento_nombre}</option>`;
            // }
            // $("#tipo_orga").html(opciones);

            var myIcon = L.icon({
                iconUrl: `${window.location.origin}/public/img/camanchaca.png`,
                iconSize: [25, 25],
                iconAnchor: [12, 24],
            });

            for (let i in data.unidades) {
                if (data.unidades[i].unid_geoubicacion != null) {
                    var coords = JSON.parse(data.unidades[i].unid_geoubicacion);

                    if(coords.lat == null || coords.lng == null){
                        console.log("Coordenadas de unidades no disponibles")
                    }else{

                        var marker = L.marker([coords.lat, coords.lng], {
                            icon: myIcon,
                        })
                            .addTo(map)
                            .on("click", () => {
                                var info = `<b>Responsable de la unidad:</b><br>${data.unidades[i].unid_responsable}<br>
                                <b>Descripción:</b><br>${data.unidades[i].unid_descripcion}<br>
                                <b>Cargo de la unidad:</b><br> ${data.unidades[i].unid_nombre_cargo}<br>
                                <div class="text-right">
                                    <a type=button class='btn btn-icon btn-warning' href='${window.location.origin}/observador/unidades/listar'>Ver Unidades</a>
                                </div>`;
                                document.getElementById("titulo").innerHTML =
                                    data.unidades[i].unid_nombre;
                                document.getElementById("informacion").innerHTML =
                                    info;
                                sidebar.toggle();
                            });
                    }

                }
            }




            // if(color_comuna == "" ){
            //     mensaje = `<div class="alert alert-warning alert-dismissible show fade"><div class="alert-body"><button class="close" data-dismiss="alert"><span>&times;</span></button><strong>No se puede determinar el índice de criticidad porque no se ha informado acerca de las encuestas de clima y percepción, ni de las evaluaciones de operación y prensa.</strong></div></div>`;
            //     $('#div-alert-undifined').html(mensaje);
            //     $('#div-alert-undifined').show();
            // }else{
            //     $('#div-alert-undifined').hide();
            // }

            for (let i in data.comuna) {
                var coords = JSON.parse(data.comuna[i].comu_geoubicacion);
                map.setView([coords.lat, coords.lng], 14);
                console.log(data)
                var limites = JSON.parse(data.comuna[i].comu_geolimites);

                var marker = L.marker([coords.lat, coords.lng])
                    .addTo(map)
                    .on("click", function () {
                        var info = `<b>N° de iniciativas:</b> ${
                            Object.keys(data.iniciativas).length
                        }
                    <br><b>N° de organizaciones:</b> ${
                        Object.keys(data.organizaciones).length
                    }<br><b>N° de relacionamientos:</b> ${Object.keys(data.actividades).length}
                    <br><b>N° de donaciones:</b> ${Object.keys(data.donaciones).length}
                    <br><b>Índice de vinculación:</b> ${data.invi}
                    `;

                        $("#titulo").html(data.comuna[i].comu_nombre);
                        $("#informacion").html(info);
                        sidebar.toggle();
                    });

                var figura = [];
                for (var j = 0; j < limites.clat.length; j++) {
                    figura.push([limites.clat[j], limites.clng[j]]);
                }

                var polygon = L.polygon(figura, {
                    color: "blue",
                }).addTo(map);
            }
        });
}

function cargarOrganizaciones() {
    var entorno = $("#tipo_orga").val();
    var comuna = $("#comunas").val();
    sidebar.hide();

    fetch(`${window.location.origin}/digitador/mapa/obtener/orga`, {
        method: "POST",
        body: JSON.stringify({
            entorno: entorno,
            comuna: comuna,
        }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var opciones = "<option value=''>Seleccione...</option>";
            for (let i in data.organizacion) {
                opciones += `<option value = '${data.organizacion[i].orga_codigo}'>${data.organizacion[i].orga_nombre}</option>`;
            }
            $("#organizacion").html(opciones);
        });
}

function cargarInfoOrganizacion() {
    var entorno = $("#tipo_orga").val();
    var organizacion = $("#organizacion").val();
    sidebar.hide();

    fetch(`${window.location.origin}/digitador/mapa/obtener/orga-data`, {
        method: "POST",
        body: JSON.stringify({
            org: organizacion,
            entorno: entorno,
        }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var coords = "";
            var ico = "";
            var orga_nombre = "";
            var donaciones = "";
            var actividades = "";

            for (let i in data.organizacion) {
                coords = JSON.parse(data.organizacion[i].orga_geoubicacion);
                orga_nombre = data.organizacion[i].orga_nombre;
                orga_descripcion = data.organizacion[i].orga_descripcion;
                orga_direcion = data.organizacion[i].orga_domicilio == null ? "No específicada" : data.organizacion[i].orga_domicilio;
                orga_socios = data.organizacion[i].orga_cantidad_socios == null ? "No específica" : data.organizacion[i].orga_cantidad_socios;
                orga_fecha = data.organizacion[i].orga_fecha_vinculo == null ? "No específica":new Date(data.organizacion[i].orga_fecha_vinculo);
                final_fecha = orga_fecha == "No específica" ? "No específica" :  orga_fecha.getDate()+"/"+(orga_fecha.getMonth() + 1) + "/" + orga_fecha.getFullYear();
            }

            for(let i in data.donaciones){
                donaciones += `${parseInt(i)+1}.- ${data.donaciones[i].dona_motivo}<br>`
            }

            for(let i in data.actividades){
                actividades += `${parseInt(i)+1}.- ${data.actividades[i].acti_nombre}<br>`
            }

            for (let i in data.entorno) {
                ico = data.entorno[i].ento_ruta_icono;
                ento_nombre = data.entorno[i].ento_nombre;
            }

            if(coords.lat == null || coords.lng == null){
                map.setView([coords.lat, coords.lng], 20);
                var mrIcon = L.icon({
                    iconUrl: `${window.location.origin}/${ico}`,
                    iconSize: [22, 35],
                    iconAnchor: [12, 24],
                });

                var marker = L.marker([coords.lat, coords.lng], {
                    icon: mrIcon,
                })
                    .addTo(map)
                    .on("click", function () {
                        var info =`<b>Descripción: </b>${orga_descripcion}<br><b>Dirección: </b>${orga_direcion}<br>
                        <b>N° de socios: </b>${orga_socios}<br><b>Fecha de vinculación: </b>${final_fecha}<br>
                        <b>Últimas donaciones: </b><br>${donaciones}<br><b>Últimas actividades:</b><br>${actividades}`;

                        $("#titulo").html(orga_nombre);
                        $("#informacion").html(info);
                        sidebar.toggle();
                    });
            }else{
                console.log("no hay coordenas")
            }
        });
}
