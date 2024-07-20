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

    $stmt = $pdo->query('SELECT id_alumno, nombre, apellido_paterno, apellido_materno FROM alumnos');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

