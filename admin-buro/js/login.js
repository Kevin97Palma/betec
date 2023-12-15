;
(function() {
    $(".validar").click(function() {
        //estos valores toma del index.php toma el campo del usuario y la clave  
        var txtUsername = $("#txtUsername").val()
        var txtPassword = $("#txtPassword").val()


        if (txtUsername == '') {
            alert('Escriba el usuario')
            return
        }

        if (txtPassword == '') {
            alert('No ha digitado ninguna contraseña')
            return
        }
        $.ajax({
            type: "POST",
            url: "api/login/login.php",
            data: { txtUsername: txtUsername, txtPassword: txtPassword },
            datatype: "JSON"
        }).done(function(swap) {
           // console.log(swap)
            if (swap == 1) {
                location.href = 'index2.html'
            }
            if (swap == 0) {
                alert("Contraseña o usuario incorrectos")
            }
        })
    })
})()