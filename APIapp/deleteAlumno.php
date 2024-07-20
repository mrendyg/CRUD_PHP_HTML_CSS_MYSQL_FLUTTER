<?php
// delete_student.php
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

    $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id_alumno = ?");
    $stmt->execute([$data['id_alumno']]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
