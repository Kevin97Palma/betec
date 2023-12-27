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

    // Imprimir valores para depuración
    echo "idR recibido: $idR\n";
    echo "id del registro obtenido: $id\n";

    // Actualizar el campo "ruta_comprobante_pago" en la tabla "archivos" con el archivo buro
    $sqlUpdate = "UPDATE archivos 
                  SET buro = :archivoAdjunto, 
                      updated_at = NOW() 
                  WHERE cedula_id = :idR";

    // Preparar la consulta SQL para la actualización
    $updateStatement = $pdo->prepare($sqlUpdate);

    // Asignar los valores a los parámetros de la consulta
    $updateStatement->bindParam(':archivoAdjunto', $archivoAdjunto, PDO::PARAM_STR);
    $updateStatement->bindParam(':idR', $idR, PDO::PARAM_INT);

    // Imprimir valores para depuración
    echo "archivoAdjunto: $archivoAdjunto\n";

    // Ejecutar la actualización
    if ($updateStatement->execute()) {
        // Devolver la información
        $response = [
            "id" => $_SESSION["id"],
            "idRegistro" => $id,
            // Agregar otras variables necesarias para la respuesta
        ];

        echo json_encode($response);
    } else {
        // Si hay un error en la actualización, devuelve un mensaje de error
        http_response_code(500);
        echo json_encode(["message" => "Error en la actualización SQL"]);
    }
} else {
    // Si hay un error en la consulta, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(["message" => "Error en la consulta SQL"]);
}
?>
