<?php
include 'Connection/conexion.php';

$id=$_GET["id"];
$time=$_GET["tiempo"];

$nuevo=$pdo->query("insert into xmlcliente values(0,'$id','$time')");

if($nuevo){
echo 2;
} else {
echo 1;
}


?>
