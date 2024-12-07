const csrftoken = document.head.querySelector(
    "[name~=csrf-token][content]"
).content;

$(document).ready(() => {
    cargarInfoGraficos();
    cargarComunas();
    cargarOrganizaciones();
    // $("#donaChartDiv").hide();
});

function getURLParams(url) {
    let params = {};
    new URLSearchParams(url.replace(/^.*?\?/, "?")).forEach(function (
        value,
        key
    ) {
        params[key] = value;
    });
    return params;
}


function cargarOrganizaciones() {
    var comunas = document.getElementById("comu_codigo").value;
    fetch(`${window.location.origin}/digitador/dashboard/obtener/organizaciones`, {
        method: "POST",
        body: JSON.stringify({ comuna: comunas }),
        headers: {
            "Content-Type": "aplication/json",
            "X-CSRF-TOKEN": csrftoken,
        },
    })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            var organizaciones = data.organizaciones;

            $("#orga_codigo").find("option").not(":first").remove();
            $("#orga_codigo").prop("selectedIndex", 0);

            for (let i in organizaciones) {
                if (organizaciones[i].regi_codigo == comunas && comunas != "") {
                    $("#orga_codigo").append(
                        new Option(
                            organizaciones[i].orga_nombre,
                            organizaciones[i].orga_codigo
                        )
                    );
                } else {
                    $("#orga_codigo").append(
                        new Option(
                            organizaciones[i].orga_nombre,
                            organizaciones[i].orga_codigo
                        )
                    );
                }
            }

            let orgaCodigo = getURLParams(window.location.href)["orga_codigo"];

            if (orgaCodigo == undefined || orgaCodigo == null)
                $("#orga_codigo").val("").change();
            else $("#orga_codigo").val(orgaCodigo).change();
            if ($("#orga_codigo").val() == null)
                $("#orga_codigo").val("").change();
        });
}
