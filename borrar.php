<?php
include 'funciones.php';

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "DELETE FROM alumnos WHERE id_alumno =" . $id;
  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $id = $_GET['id'];
  $consultaSQL = "DELETE FROM padre_estudiante WHERE id_padre =" . $id;
  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $id = $_GET['id'];
  $consultaSQL = "DELETE FROM madre_estudiante WHERE id_madre =" . $id;
  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $id = $_GET['id'];
  $consultaSQL = "DELETE FROM apoderado_alumno WHERE id_apoderado =" . $id;
  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  header('Location: /manitosdecolores/index.php');

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<div class="container mt-2">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger" role="alert">
        <?= $resultado['mensaje'] ?>
      </div>
    </div>
  </div>
</div>

<?php require "templates/footer.php"; ?>