<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = false;
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

  if (isset($_POST['apellido_paterno'])) {
    $consultaSQL = "SELECT * FROM alumnos WHERE apellido_paterno LIKE '%" . $_POST['apellido_paterno'] . "%'";
  } else {
    $consultaSQL = "SELECT * FROM alumnos";
  }

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $alumno = $sentencia->fetchAll();

} catch(PDOException $error) {
  $error= $error->getMessage();
}

$titulo = isset($_POST['apellido:paterno']) ? 'Lista de alumno (' . $_POST['apellido_paterno'] . ')' : 'Lista de alumno';
?>

<?php include "templates/header.php"; ?>

<?php
if ($error) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $error ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <a href="crear.php"  class="btn btn-primary mt-4">Crear alumno</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder="Buscar por apellido" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
        <button type="submit" name="submit" class="btn btn-primary">Ver resultados</button>
      </form>
    </div>
  </div>
</div>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-3"><?= $titulo ?></h2>
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Edad</th>
            <th>CI</th>
            <th>Peso</th>
            <th>Vacunas al dia</th>
            <th>Padre</th>
            <th>Madre</th>
            <th>Apoderado</th>
            <th>Curso</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($alumno && $sentencia->rowCount() > 0) {
            foreach ($alumno as $fila) {
              ?>
              <tr>
                <td><?php echo escapar($fila["id_alumno"]); ?></td>
                <td><?php echo escapar($fila["nombre"]); ?></td>
                <td><?php echo escapar($fila["apellido_paterno"]); ?></td>
                <td><?php echo escapar($fila["apellido_materno"]); ?></td>
                <td><?php echo escapar($fila["edad"]); ?></td>
                <td><?php echo escapar($fila["CI"]); ?></td>
                <td><?php echo escapar($fila["peso"]); ?></td>
                <td><?php echo escapar($fila["vacunas_al_dia"]); ?></td>
                <td><?php echo escapar($fila["id_padre"]); ?></td>
                <td><?php echo escapar($fila["id_madre"]); ?></td>
                <td><?php echo escapar($fila["id_apoderado"]); ?></td>
                <td><?php echo escapar($fila["id_curso"]); ?></td>
                <td>
                  <a href="<?= 'borrar.php?id=' . escapar($fila["id_alumno"]) ?>">🗑️Borrar</a>
                  <a href="<?= 'editar.php?id=' . escapar($fila["id_alumno"]) ?>">✏️Editar</a>
                </td>
              </tr>
              <?php
            }
          }
          ?>
        <tbody>
      </table>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>