<?php
session_start();

// Destruir la sesión
session_destroy();

// Responder con un objeto JSON
header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit();
?>