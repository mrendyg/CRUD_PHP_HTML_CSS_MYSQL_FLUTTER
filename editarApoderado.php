<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$config = include 'config.php';

$resultado = [
  'error' => false,
  'mensaje' => ''
];

if (!isset($_GET['id'])) {
  $resultado['error'] = true;
  $resultado['mensaje'] = 'El apoderado no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $apoderado = [
      "id"        => $_GET['id'],
      "dni"    => $_POST['dni'],
      "nombres"    => $_POST['nombres'],
      "apellido_paterno"  => $_POST['apellido_paterno'],
      "apellido_materno"  => $_POST['apellido_materno'],
      "edad"      => $_POST['edad'],
      "telefono"      => $_POST['telefono'],
    ];
    
    $consultaSQL = "UPDATE apoderado_alumno SET
        dni = :dni,
        nombres = :nombres,
        apellido_paterno = :apellido_paterno,
        apellido_materno = :apellido_materno,
        edad = :edad,
        telefono = :telefono
        WHERE id_apoderado = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($apoderado);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM apoderado_alumno WHERE id_apoderado =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $apoderado = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$apoderado) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el apoderado';
  }

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}
?>

<?php require "templates/header.php"; ?>

<?php
if ($resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($_POST['submit']) && !$resultado['error']) {
  ?>
  <div class="container mt-2">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-success" role="alert">
          El apoderado ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($apoderado) && $apoderado) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editar Apoderado <?= escapar($apoderado['nombres']) . ' ' . escapar($apoderado['apellido_paterno'])  ?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
              <label for="id_apoderado">ID</label>
              <input type="text" name="id_apoderado" id="id_apoderado" value="<?= escapar($apoderado['id_apoderado']) ?>" class="form-control" disabled>
            </div>
          <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni" value="<?= escapar($apoderado['dni']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" value="<?= escapar($apoderado['nombres']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= escapar($apoderado['apellido_paterno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_materno">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="<?= escapar($apoderado['apellido_materno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad" value="<?= escapar($apoderado['edad']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="number" name="telefono" id="telefono" value="<?= escapar($apoderado['telefono']) ?>" class="form-control">
          </div>


          
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?php echo escapar($_SESSION['csrf']); ?>">
            <input type="submit" name="submit" class="btn btn-primary" value="Actualizar">
            <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php require "templates/footer.php"; ?>