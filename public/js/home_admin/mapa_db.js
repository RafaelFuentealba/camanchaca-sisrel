const csrftoken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;
var map = L.map("map");
map.setView([-35.675147, -71.542969], 5);

L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 18,
    attribution: "Map data &copy; OpenStreetMap contributors",
}).addTo(map);

var sidebar = L.control.sidebar("sidebar", {
    closeButton: true,
    position: "right",
});
map.addControl(sidebar);


$(document).ready(() => {
    $("#div-comunas").hide();
    $("#div-iniciativas").hide();
    $("#div-realacionamiento").hide();
    $("#div-estadisticas").hide();

});

function ocultarRegi(codigo){
    var regionSeleccionada = $(codigo).attr('id')
    $("#div-comunas").show();
    $("#div-iniciativas").show();
    $("#div-realacionamiento").show();
    $("#div-estadisticas").show();
    $("#div-image").hide();
    if(regionSeleccionada == "LAGOS"){
        $("#TPCA").hide();
        $("#BBIO").hide();
        map.setView([-41.84071943384987, -72.99956719597715], 9);
    }else if(regionSeleccionada == "TPCA"){
        $("#LAGOS").hide();
        $("#BBIO").hide();
        map.setView([-20.05800242483127, -69.6016620019451], 9);
    }else if(regionSeleccionada == "BBIO"){
        $("#LAGOS").hide();
        $("#TPCA").hide();
        map.setView([-37.33128467390922, -72.50630268253872], 9);
    }

    fetch(`${window.location.origin}/admin/mapa/obtener/regiones`, {
        method: "POST",
        body: JSON.stringify({
            region: regionSeleccionada,
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
            var stasts = "";
            for (let i in data.comunas) {
                if (data.comunas[i].comu_codigo == "01101"){
                    opciones += `<li onclick="cargarInfoComuna('01101')">${data.comunas[i].comu_nombre}</li>`;
                }else{
                    opciones += `<li onclick="cargarInfoComuna(${data.comunas[i].comu_codigo})">${data.comunas[i].comu_nombre}</li>`;
                }
            }
            stasts += `<li onclick="IraDatosRegionales('${regionSeleccionada}')">Regionales</li>`;
            $("#comunas").html(opciones);
            $("#ul-estadisticas").html(stasts);
            $("#c_iniciativas").html(data.iniciativas)
            $('#div-alert-undifined').hide();
            var region = encodeURIComponent(regionSeleccionada)
            $("#h4-iniciativas").html(`<h4 style="color: white" id="h4-iniciativas" onclick="IraIniciativas('${region}')">Iniciativas</h4>`)
        });

}

function IraDatosRegionales(region){
    window.location.href = `${window.location.origin}/admin/estadisticas/regionales?regi_codigo=${region}`;
}

function IraIniciativas(region){
    window.location.href = `${window.location.origin}/admin/iniciativas/listar?region=${region}`;
}

function cargarInfoComuna(comu_codigo) {
    var comuna = comu_codigo;
    var region = $("#region").val();
    console.log(comuna,region)
    fetch(`${window.location.origin}/admin/mapa/obtener/comuna`, {
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

            for (let i in data.comuna) {
                var coords = JSON.parse(data.comuna[i].comu_geoubicacion);
                map.setView([coords.lat, coords.lng], 14);
                console.log(data)
                var limites = JSON.parse(data.comuna[i].comu_geolimites);

                var marker = L.marker([coords.lat, coords.lng])
                    .addTo(map)
                    .on("click", function () {
                        var info = `<b>N° de iniciativas:</b>
                         ${Object.keys(data.iniciativas).length}
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


function mostrarRegi(){
    $("#TPCA").show();
    $("#BBIO").show();
    $("#LAGOS").show()
    $("#div-comunas").hide();
    $("#div-iniciativas").hide();
    $("#div-realacionamiento").hide();
    $("#div-estadisticas").hide();
    $("#div-image").show();

    map.setView([-35.675147, -71.542969], 5);
}
