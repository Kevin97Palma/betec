<?php
// api.php

header("Content-Type: application/json");

// Configuración de la base de datos
$servername = "144.91.73.120";
$username = "soporte";
$password = "soporte";
$dbname = "betecburo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$cedula = $_POST["cedula"] ?? "";
$nombre = $_POST["nombre"] ?? "";
$celular = $_POST["celular"] ?? "";
$correo = $_POST["correo"] ?? "";
$disC = $_POST["disC"] ?? "";

$transaccion = $_POST["transaccion"] ?? "";
$banco = $_POST["banco"] ?? "";

// Manejar los archivos subidos
$cedula1File = $_FILES["cedula1"] ?? null;
$cedula2File = $_FILES["cedula2"] ?? null;
$pagoFile = $_FILES["pago"] ?? null;

// Directorio donde se guardarán los archivos subidos
$userFolder = "doc/" . $cedula . "/"; // Carpeta por cada usuario
$uploadDirectory = $userFolder;

// Verificar si la carpeta del usuario existe, si no, crearla
if (!file_exists($userFolder)) {
    mkdir($userFolder, 0777, true);
}

// Verificar y mover los archivos subidos
if ($cedula1File) {
    move_uploaded_file($cedula1File["tmp_name"], $uploadDirectory . basename($cedula1File["name"]));
}

if ($cedula2File) {
    move_uploaded_file($cedula2File["tmp_name"], $uploadDirectory . basename($cedula2File["name"]));
}

if ($pagoFile) {
    move_uploaded_file($pagoFile["tmp_name"], $uploadDirectory . basename($pagoFile["name"]));
}

// Obtener valores de los checks
$check1 = isset($_POST["customCheck1"]) ? $_POST["customCheck1"] : "No Acepto";
$check2 = isset($_POST["customCheck2"]) ? $_POST["customCheck2"] : "No Acepto";
$check3 = isset($_POST["customCheck3"]) ? $_POST["customCheck3"] : "No Acepto";

// Imprimir la secuencia SQL antes de ejecutar el procedimiento almacenado
$sql = "CALL InsertarDatos('$cedula', '$nombre', '$celular', '$correo', '$cedula1File[name]', '$cedula2File[name]', '$pagoFile[name]', '$check1', '$check2', '$check3', '$transaccion', '$banco', '$disC')";
echo "SQL: " . $sql . PHP_EOL;

// Insertar datos en la base de datos usando un procedimiento almacenado
$stmt = $conn->prepare($sql);
$stmt->execute();
$stmt->close();

// Construir una respuesta
$response = [
    "cedula" => $cedula,
    "nombre" => $nombre,
    "celular" => $celular,
    "correo" => $correo,
    "cedula1FileName" => $cedula1File ? basename($cedula1File["name"]) : null,
    "cedula2FileName" => $cedula2File ? basename($cedula2File["name"]) : null,
    "pagoFileName" => $pagoFile ? basename($pagoFile["name"]) : null,
    "transaccion" => $transaccion,
    "banco" => $banco,
    "check1" => $check1,
    "check2" => $check2,
    "check3" => $check3,
];

// Devolver la respuesta en formato JSON
echo json_encode($response);

// Cerrar la conexión a la base de datos
$conn->close();
?>
