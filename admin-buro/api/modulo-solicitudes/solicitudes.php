<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();


    // Consulta SQL para obtener los registros del menú
    $sql = $pdo->query("SELECT dp.id, dp.cedula, dp.nombre, dp.celular, dp.correo, e.nombre_estado AS estado
    FROM datos_personales dp
    JOIN estados e ON dp.estado_id = e.id;");

    if (!$sql) {
        die('Error en la consulta SQL: ' . $pdo->errorInfo()[2]);
    }

    // Recuperar todos los registros de la consulta
    $registros = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Devolver la información de la sesión y los registros del menú
    $response = [
        "id" => $_SESSION["id"],
        "registros" => $registros,
    ];

    echo json_encode($response);

?>
