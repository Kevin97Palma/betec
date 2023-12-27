$(document).ready(function () {
    // Llama a tu API inicialmente sin parámetros
    cargarDatos();

    // Agrega un evento de clic a los elementos del menú
    $('#e1, #e2, #e3').on('click', function (e) {
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
                    <span class="badge-${obtenerColorAlerta(registro.estado)} badge mr-2">${registro.estado}</span>
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
    $('#myModalLabel').text('CLIENTE ' + data.registros[0].nombre); // Cambia el título del modal

    // Construye el contenido del cuerpo del modal
    var modalContent = `
    <div class="card">
    
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#home" role="tab" aria-selected="true">
                    <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                    <span class="d-none d-sm-block">Informacion</span>    
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#profile" role="tab" aria-selected="false">
                    <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                    <span class="d-none d-sm-block">Cargar Buro</span>    
                </a>
            </li>
          
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane p-3 active" id="home" role="tabpanel">
                <p class="mb-0">
                <input type="hidden" value="${data.registros[0].id}" name="idR" id="idR">
                <b> Registro: </b> ${data.registros[0].id}<br>
                <b>Cédula: </b> ${data.registros[0].cedula} <br>
                <b>Celular: </b> ${data.registros[0].celular} <br>
                <b>Correo: </b> ${data.registros[0].correo} <br>
                <b>Estado:</b>  <span class="badge-primary badge mr-2">${data.registros[0].nombre_estado}</span> <br>
                <b>Fecha de Creación: </b> ${data.registros[0].created_at} <br>
                <b>Código TR: </b> ${data.registros[0].codigoTr} <br>
                <b>Banco: </b> ${data.registros[0].banco} <br>
                <b>Imagen Cédula Anverso:</b>  <a type="button" href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_cedula_anverso}" class="badge-info badge mr-2" target="_blank"><i class="fa fa-inbox"></i> Descargar</a> <br>
                <b>Imagen Cédula Reverso:</b>  <a type="button" href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_cedula_reverso}" class="badge-info badge mr-2" target="_blank"><i class="fa fa-inbox"></i> Descargar</a> <br>
                <b>Comprobante de Pago:</b>  <a type="button" href="./../form/api/doc/${data.registros[0].cedula}/${data.registros[0].ruta_comprobante_pago}" class="badge-info badge mr-2" target="_blank"><i class="fa fa-inbox"></i> Descargar</a>
                </p>
            </div>
            <div class="tab-pane p-3" id="profile" role="tabpanel">
             
            <div class="form-group mb-0">
                                                <label>Cargar Aqui el PDf del Buro</label>
                                                <input type="file" name="buro" id="buro">
                                                </div>
           

          
           
                                            </div>
        </div>

   
</div>
        `;

    // Llena el contenido del cuerpo del modal
    $('.modal-body').html(modalContent);
}

// Función para eliminar un registro
function eliminarRegistro(id) {
    // Implementa la lógica para eliminar el registro según el ID
    console.log(`Eliminar registro con ID ${id}`);
}