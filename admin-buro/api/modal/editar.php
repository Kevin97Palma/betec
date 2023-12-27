<?php
include '../co/conexion.php';
include '../co/config.php';
session_start();

$id = $_GET['id'] ; 
// Construye la consulta SQL base
$sqlQuery = "SELECT 
d.id,
d.cedula , 
d.nombre,
d.correo,
d.celular,
d.created_at, 
d.codigoTr,
d.banco, 
e.nombre_estado,
a.ruta_cedula_anverso,
a.ruta_cedula_reverso,
a.ruta_comprobante_pago
FROM datos_personales d
JOIN archivos a on d.id = a.cedula_id 
JOIN estados e ON d.estado_id = e.id where d.id = $id  ";


// Prepara la consulta SQL
$sql = $pdo->prepare($sqlQuery);


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
