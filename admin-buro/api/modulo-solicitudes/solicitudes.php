<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();


    // Consulta SQL para obtener los registros del menú
    $sql = $pdo->query("SELECT id,cedula,nombre,celular,correo  FROM datos_personales;");

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
