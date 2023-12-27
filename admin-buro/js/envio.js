$(".envio").click(function() {
      console.log("holi boli");
    var idR = $("#idR").val();
  
    var buro = $("#buro")[0].files[0];

    var formData = new FormData();
    formData.append("idR", idR);
    
    if (firmaA) {
        formData.append("buro", buro);
    }

    $.ajax({
        url: "api/enviar.php",
        type: "post",
        data: formData,
        contentType: false,
        processData: false
    }).then(function(data) {
        console.log(data);
        if (data == "2") {
            swal({
                title: "Datos guardados con éxito",
                type: "success"
            });
            $("#modalIngresar1").modal("hide");
            $(".table-responsive").load("getProveedores.php");
        } else if (data == "3") {
            swal({
                title: "Datos actualizados con éxito",
                type: "success"
            });
            $("#modalIngresar1").modal("hide");
            $(".table-responsive").load("getProveedores.php");
        } else if (data == "1") {
            alert(data);
            swal({
                title: "Error. Consulte con el proveedor",
                type: "error"
            });
        }
    });
   })