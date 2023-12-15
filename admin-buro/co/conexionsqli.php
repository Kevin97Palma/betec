<?php 
function conectar_mysqli()
{
    $server = '144.91.73.120';
    $user = 'soporte';
    $password = 'soporte';
    $db = 'firmasecuador';
    $conectar = mysqli_connect($server, $user, $password, $db);
    if (!$conectar) {
        die("Error al conectar con la base de datos");
    }
    return $conectar;
}

?>