$(".envio").click(function () {
    // Obtener el valor del campo idR
    var idR = $("#idR").val();
    // Obtener el archivo seleccionado en el campo buro
    var buro = $("#buro")[0].files[0];

    // Crear un objeto FormData para enviar datos binarios (como archivos)
    var formData = new FormData();
    // Agregar el valor de idR al FormData
    formData.append("idR", idR);

    // Si hay un archivo seleccionado, agregarlo al FormData con la clave "buro"
    if (buro) {
        formData.append("buro", buro);
    }

    // Imprimir el archivo en base64 y otros datos por consola
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Realizar una solicitud fetch a la URL "api/enviar.php" con el método POST y el cuerpo FormData
    fetch("./api/modal/enviar.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.text())  // Convertir la respuesta a texto
        .then(data => {
            console.log(data);

            // Manejar la respuesta según los valores devueltos por el servidor
            if (data === "2") {
                swal({
                    title: "Datos guardados con éxito",
                    type: "success",
                });
                // Ocultar el modal y recargar la tabla de proveedores
                $("#modalIngresar1").modal("hide");
                $(".table-responsive").load("getProveedores.php");
            } else if (data === "3") {
                swal({
                    title: "Datos actualizados con éxito",
                    type: "success",
                });
                // Ocultar el modal y recargar la tabla de proveedores
                $("#modalIngresar1").modal("hide");
                $(".table-responsive").load("getProveedores.php");
            } else if (data === "1") {
                alert(data);
                swal({
                    title: "Error. Consulte con el proveedor",
                    type: "error",
                });
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
        });
});
