<?php
header('Access-Control-Allow-Origin: *'); 
include 'Connection/conexion.php';

$id=$_GET["id"];
$select = "SELECT USUAPELLIDO as 'licencia' FROM seg_maeusuario where USUNOMBRE = '$id' order by USUNOMBRE desc limit 1 ;";
$stmt = $pdo->query($select);
//echo $select;
$manage = $stmt->fetchAll(PDO::FETCH_ASSOC);

$resp = '';
if (isset($manage[0])){
    $resp=$manage[0]["licencia"];
} else {
    $resp = 'NO';
}

echo json_encode(array("licencia"=>$resp),JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
?>
