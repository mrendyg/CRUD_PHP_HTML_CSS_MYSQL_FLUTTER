<?php
// add_student.php
header('Content-Type: application/json');
$config = require 'db_config.php';

try {
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['name']}",
        $config['db']['user'],
        $config['db']['pass'],
        $config['db']['options']
    );

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, edad, CI, peso, vacunas_al_dia, id_padre, id_madre, id_apoderado, id_curso)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $data['nombre'],
        $data['apellido_paterno'],
        $data['apellido_materno'],
        $data['edad'],
        $data['CI'],
        $data['peso'],
        $data['vacunas_al_dia'],
        $data['id_padre'],
        $data['id_madre'],
        $data['id_apoderado'],
        $data['id_curso']
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
