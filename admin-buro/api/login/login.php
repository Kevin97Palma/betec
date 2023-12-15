<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();

// Guardar datos de 
if (!isset($_POST["txtUsername"])) {
    $usua = $_GET["us"];	
    $pass = $_GET["cla"];
} else {
    $usua = $_POST["txtUsername"];	
    $pass = $_POST["txtPassword"];
}

$sql = $pdo->query("SELECT
    u.id AS usuario_id,
    u.nombre,
    u.contrasena,
    p.id AS perfil_id,
    p.nombre_perfil AS perfil_nombre
    FROM usuarios u
    JOIN perfiles p ON u.perfil_id = p.id
    WHERE u.nombre = '$usua' and u.contrasena = '$pass'");

if (!$sql) {
    die('Error en la consulta SQL: ' . $pdo->errorInfo()[2]);
}

$row = $sql->fetch();

if ($row) {
    $_SESSION["id"] = $row["usuario_id"];
    $_SESSION["usuario"] = $row["nombre"];
    $_SESSION["id_perfil"] = $row["perfil_id"];
    $_SESSION["nombre_perfil"] = $row["perfil_nombre"];

  
        echo 1;
  
} else {
    echo 0;
}
?>
