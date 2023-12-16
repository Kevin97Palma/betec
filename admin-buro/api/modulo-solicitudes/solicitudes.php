<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();

// Inicializa el parámetro de estado a 1 si no se proporciona
$estadoId = isset($_GET['estado']) ? $_GET['estado'] : 1;

// Construye la consulta SQL base
$sqlQuery = "SELECT dp.id, dp.cedula, dp.nombre, dp.celular, dp.correo, e.nombre_estado AS estado
    FROM datos_personales dp
    JOIN estados e ON dp.estado_id = e.id ";

// Agrega la condición WHERE si se proporciona un estado diferente de 3
if ($estadoId != 3) {
    $sqlQuery .= "WHERE e.id = :estado";
}

// Prepara la consulta SQL
$sql = $pdo->prepare($sqlQuery);

// Vincula el valor del parámetro de estado si se proporciona
if ($estadoId != 3) {
    $sql->bindParam(':estado', $estadoId, PDO::PARAM_INT);
}

// Ejecuta la consulta
if ($sql->execute()) {
    // Recuperar todos los registros de la consulta
    $registros = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Devolver la información de la sesión y los registros del menú
    $response = [
        "id" => $_SESSION["id"],
        "registros" => $registros,
    ];

    echo json_encode($response);
} else {
    // Si hay un error en la consulta, devuelve un mensaje de error
    http_response_code(500);
    echo json_encode(["message" => "Error en la consulta SQL"]);
}
?>
