<?php
header('Content-Type: application/json');
$config = require 'db_config.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['name']}",
        $config['db']['user'],
        $config['db']['pass'],
        $config['db']['options']
    );

    // Obtener el id del parámetro GET
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Verificar si se proporcionó un id válido
    if ($id <= 0) {
        echo json_encode(['error' => 'ID inválido']);
        exit;
    }

    // Preparar y ejecutar la consulta
    $stmt = $pdo->prepare('SELECT id_alumno, nombre, apellido_paterno, apellido_materno,
    edad, CI, peso
     FROM alumnos WHERE id_alumno = :id');
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);


    // Devolver el resultado en formato JSON
    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'Alumno no encontrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
