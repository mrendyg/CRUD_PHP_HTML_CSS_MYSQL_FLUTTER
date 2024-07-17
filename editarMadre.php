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
  $resultado['mensaje'] = 'El madre no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $madre = [
      "id"        => $_GET['id_madre'],
      "nombres"    => $_POST['nombres'],
      "apellido_paterno"  => $_POST['apellido_paterno'],
      "apellido_materno"  => $_POST['apellido_materno'],
      "edad"      => $_POST['edad'],
      "telefono"      => $_POST['telefono'],
      // se debe traer la informacion del madre, madre y apoderado y curso
    ];
    
    $consultaSQL = "UPDATE madre_estudiante SET
        dni = :dni,
        nombress = :nombress,
        apellido_paterno = :apellido_paterno,
        apellido_materno = :apellido_materno,
        edad = :edad,
        telefono = telefono,
        updated_at = NOW()
        WHERE id_madre = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($madre);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM madre_estudiante WHERE id_madre =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $madre = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$madre) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el madre';
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
          El madre ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($madre) && $madre) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editar Madre <?= escapar($madre['nombres']) . ' ' . escapar($madre['apellido_paterno'])  ?></h2>
        <hr>
        <form method="post">
        <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni" value="<?= escapar($madre['dni']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" value="<?= escapar($madre['nombres']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= escapar($madre['apellido_paterno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_materno">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="<?= escapar($madre['apellido_materno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad" value="<?= escapar($madre['edad']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="number" name="telefono" id="telefono" value="<?= escapar($madre['telefono']) ?>" class="form-control">
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