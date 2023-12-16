$(document).ready(function() {
    // Llama a tu API para obtener los datos
    fetch('./api/modulo-solicitudes/solicitudes.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        // Manipula los datos y llena la tabla
        if (data.registros && data.registros.length > 0) {
            var table = $('#datatable-buttons').DataTable();
            table.clear().draw(); // Limpia la tabla antes de agregar nuevos datos

            data.registros.forEach(registro => {
                // Agrega cada registro como una fila en la tabla
                table.row.add([
                    registro.id,
                    registro.cedula,
                    registro.nombre,
                    registro.celular,
                    registro.correo
                ]).draw();
            });
        }
    })
    .catch(error => {
        // Maneja errores
        console.error(error);
    });
});
