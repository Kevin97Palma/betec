$(document).ready(function() {
    // Llama a tu API inicialmente sin parámetros
    cargarDatos();

    // Agrega un evento de clic a los elementos del menú
    $('#e1, #e2, #e3').on('click', function(e) {
        // Previene el comportamiento predeterminado del enlace
        e.preventDefault();
        
        // Obtiene el ID del elemento clicado
        var estadoId = $(this).attr('id').substring(1);

        // Llama a la función para cargar los datos con el parámetro de estado
        cargarDatos(estadoId);
    });
});

// Función para cargar los datos desde la API con un parámetro opcional de estado
function cargarDatos(estadoId) {
    // Construye la URL de la API con o sin el parámetro de estado
    var apiUrl = estadoId ? `./api/modulo-solicitudes/solicitudes.php?estado=${estadoId}` : './api/modulo-solicitudes/solicitudes.php';

    // Función para determinar el color de la alerta según el estado
    function obtenerColorAlerta(estado) {
        return estado === 'En Proceso' ? 'success' : (estado === 'Enviado' ? 'danger' : 'info');
    }

    // Muestra la solicitud que se enviará a la API
    console.log(`Solicitud a la API: ${apiUrl}`);

    // Llama a tu API para obtener los datos
    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    })
    .then(response => {
        // Muestra la respuesta de la API
        console.log('Respuesta de la API:', response);
        return response.json();
    })
    .then(data => {
        // Muestra los datos devueltos por la API
        console.log('Datos recibidos:', data);

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
                    `
                        <p class="alert alert-${obtenerColorAlerta(registro.estado)}" role="alert">${registro.estado}</p>
                    `,
                    // Agrega los botones en la nueva columna
                    `<button type="button" class="btn btn-primary" onclick="abrirModal(${registro.id})">Editar</button>
                     <button type="button" class="btn btn-danger" onclick="eliminarRegistro(${registro.id})">Eliminar</button>`
                ]).draw();
            });
        }
    })
    .catch(error => {
        // Muestra errores en la consola
        console.error('Error al obtener datos:', error);
    });
}

// Función para abrir un modal y cargar datos desde la API
function abrirModal(id) {
    // Construye la URL de la API con el ID proporcionado
    var apiUrl = `./api/modal/editar.php?id=${id}`;

    // Muestra la solicitud que se enviará a la API
    console.log(`Solicitud a la API: ${apiUrl}`);

    // Realiza la solicitud a la API
    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    })
    .then(response => {
        // Muestra la respuesta de la API
        console.log('Respuesta de la API:', response);
        return response.json();
    })
    .then(data => {
        // Muestra los datos devueltos por la API
        console.log('Datos recibidos:', data);

        // Llena el modal con los datos recibidos
        llenarModal(data);
        
        // Abre el modal
        $('#myModal').modal('show');
    })
    .catch(error => {
        // Muestra errores en la consola
        console.error('Error al obtener datos:', error);
    });
}

// Función para llenar el contenido del modal con datos
function llenarModal(data) {
    // Modifica el contenido del modal según los datos recibidos
    $('#myModalLabel').text(data.registros[0].nombre); // Cambia el título del modal

    // Construye el contenido del cuerpo del modal
    var modalContent = `
        <p>ID: ${data.registros[0].id}</p>
        <p>Cédula: ${data.registros[0].cedula}</p>
        <p>Correo: ${data.registros[0].correo}</p>
        <p>Estado: ${data.registros[0].nombre_estado}</p>
        <p>Fecha de Creación: ${data.registros[0].created_at}</p>
        <p>Código TR: ${data.registros[0].codigoTr}</p>
        <p>Banco: ${data.registros[0].banco}</p>
        <!-- Agrega más campos según tus necesidades -->
        <p>Imagen Cédula Anverso: <a href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_cedula_anverso}" target="_blank">Ver Comprobante</a></p>
        <p>Imagen Cédula Reverso: <a href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_cedula_reverso}" target="_blank">Ver Comprobante</a></p>
        <p>Comprobante de Pago: <a href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_comprobante_pago}" target="_blank">Ver Comprobante</a></p>
    `;

    // Llena el contenido del cuerpo del modal
    $('.modal-body').html(modalContent);
}

// Función para eliminar un registro
function eliminarRegistro(id) {
    // Implementa la lógica para eliminar el registro según el ID
    console.log(`Eliminar registro con ID ${id}`);
}