<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();

$idR = $_POST['idR'];

// Verificar si se recibió un archivo buro
if (isset($_FILES['buro']) && $_FILES['buro']['error'] == UPLOAD_ERR_OK) {
    // Obtener el contenido del archivo buro en base64
    $archivoAdjunto = base64_encode(file_get_contents($_FILES['buro']['tmp_name']));
} else {
    // Si no se recibió un archivo buro, establecer el valor a NULL o manejarlo según tus necesidades
    $archivoAdjunto = null;
}

// Construir la consulta SQL base
$sqlQuery = "SELECT 
    d.id,
    d.cedula, 
    d.nombre,
    d.celular,
    d.correo,
    d.created_at, 
    d.codigoTr,
    d.banco, 
    e.nombre_estado,
    a.ruta_cedula_anverso,
    a.ruta_cedula_reverso,
    a.ruta_comprobante_pago
    FROM datos_personales d
    JOIN archivos a ON d.id = a.cedula_id 
    JOIN estados e ON d.estado_id = e.id 
    WHERE d.id = :idR";

// Preparar la consulta SQL
$sql = $pdo->prepare($sqlQuery);

// Asignar el valor a los parámetros de la consulta
$sql->bindParam(':idR', $idR, PDO::PARAM_INT);

// Ejecutar la consulta
if ($sql->execute()) {
    // Recuperar el primer registro de la consulta (asumiendo que solo hay uno)
    $registro = $sql->fetch(PDO::FETCH_ASSOC);

    // Asignar los valores a variables individuales
    $id = $registro['id'];
    $cedula = $registro['cedula'];
    $nombre = $registro['nombre'];
    $celular = $registro['celular'];
    $correo = $registro['correo'];
    $created_at = $registro['created_at'];
    $codigoTr = $registro['codigoTr'];
    $banco = $registro['banco'];
    $nombre_estado = $registro['nombre_estado'];
    $ruta_cedula_anverso = $registro['ruta_cedula_anverso'];
    $ruta_cedula_reverso = $registro['ruta_cedula_reverso'];
    $ruta_comprobante_pago = $registro['ruta_comprobante_pago'];

    $Cli = substr($celular, 1);
$Clie = '593' . $Cli;
    // Actualizar el campo "ruta_comprobante_pago" en la tabla "archivos" con el archivo buro
    $sqlUpdate = "UPDATE archivos 
                  SET ruta_comprobante_pago = :archivoAdjunto, 
                      updated_at = NOW() 
                  WHERE cedula_id = :idR";

    // Preparar la consulta SQL para la actualización
    $updateStatement = $pdo->prepare($sqlUpdate);

    // Asignar los valores a los parámetros de la consulta
    $updateStatement->bindParam(':archivoAdjunto', $archivoAdjunto, PDO::PARAM_STR);
    $updateStatement->bindParam(':idR', $idR, PDO::PARAM_INT);

    // Ejecutar la actualización
    $updateStatement->execute();


    // Construye el JSON para enviar el mensaje con el archivo adjunto
    $json_data = array(
        'ruc' => '1724718158001',
        'image' => '',
        'message' => '*MENSAJE AUTOMÁTICO*
*NO CONTESTAR* 
La firma del cliente '.$nombre.' con número de cédula: '.$cedula.'  se ha generado con éxito. Para realizar la descarga de la firma electrónica, ingrese al siguiente enlace 
        
*Portal de descarga* 
https://tribufirmas.com/descarga-certificado/
        
*Las instrucciones de la descarga están en el siguiente videotutorial* 
https://youtu.be/bi7tBlSC50U',
        'document' => $archivoAdjunto,  // Ruta del PDF adjunto
        'cellphone' => $Clie,
        'from' => 'Envio Buro',
        'typeMessage' => '0',  // Tipo 0 para archivo adjunto
        'idWhatsapp' => '7afBvkqzaDrS2V20bKOjmjU22',
        'title' => 'Envio'
    );

    $json_string = json_encode($json_data);

    $ch = curl_init();

    // Configura la URL de la nueva API
    $url = 'http://194.163.136.238:9090/api-whatsapp/bridge.php';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    $result = curl_exec($ch);

    if ($result) {
        if (strpos($result, 'success') !== false) {
            echo "Mensaje enviado correctamente para ID: " . $Clie . PHP_EOL;
            // Imprime la respuesta de la API
            echo "Respuesta de la API: " . $result . PHP_EOL;
        } elseif (strpos($result, 'error') !== false) {
            echo "Mensaje NO enviado para ID: " . $Clie . PHP_EOL;
            // $telefonosNoEnviados[] = $nombre; // Agrega el teléfono al array
        }
    } else {
        echo "Error en la solicitud para ID: " . $Clie . PHP_EOL;
        // $telefonosNoEnviados[] = $nombre; // Agrega el teléfono al array
    }

    curl_close($ch);

} else {
    // Si hay un error en la consulta, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(["message" => "Error en la consulta SQL"]);
}


