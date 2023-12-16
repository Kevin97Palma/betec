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
                    registro.correo,
                    registro.estado,
                    // Agrega los botones en la nueva columna
                    `<button type="button" class="btn btn-primary" onclick="abrirModal(${registro.id})">Editar</button>
                     <button type="button" class="btn btn-danger" onclick="eliminarRegistro(${registro.id})">Eliminar</button>`
                ]).draw();
            });
        }
    })
    .catch(error => {
        // Maneja errores
        console.error(error);
    });
});

// Función para abrir un modal
function abrirModal(id) {
    // Implementa la lógica para abrir el modal según el ID
    console.log(`Abrir modal para el ID ${id}`);
}

// Función para eliminar un registro
function eliminarRegistro(id) {
    // Implementa la lógica para eliminar el registro según el ID
    console.log(`Eliminar registro con ID ${id}`);
}
