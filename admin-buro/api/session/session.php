<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["id"])) {
    http_response_code(401);
    echo json_encode(["message" => "Usuario no autenticado"]);
    exit;
} else {
    $perfil = $_SESSION["id_perfil"];

    // Consulta SQL para obtener los registros del menú
    $sql = $pdo->query("SELECT nombre_menu, url, icono FROM menu where perfil_id = '$perfil';");

    if (!$sql) {
        die('Error en la consulta SQL: ' . $pdo->errorInfo()[2]);
    }

    // Recuperar todos los registros de la consulta
    $menuData = $sql->fetchAll(PDO::FETCH_ASSOC);

    // Devolver la información de la sesión y los registros del menú
    $response = [
        "id" => $_SESSION["id"],
        "usuario" => $_SESSION["usuario"],
        "id_perfil" => $_SESSION["id_perfil"],
        "nombre_perfil" => $_SESSION["nombre_perfil"],
        "menu" => $menuData,
    ];

    echo json_encode($response);
}
?>
