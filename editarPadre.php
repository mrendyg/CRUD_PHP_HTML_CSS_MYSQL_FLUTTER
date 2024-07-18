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
  $resultado['mensaje'] = 'El padre no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $padre = [
      "id"        => $_GET['id'],
      "dni"    => $_POST['dni'],
      "nombres"    => $_POST['nombres'],
      "apellido_paterno"  => $_POST['apellido_paterno'],
      "apellido_materno"  => $_POST['apellido_materno'],
      "edad"      => $_POST['edad'],
      "telefono"      => $_POST['telefono'],
    ];
    
    $consultaSQL = "UPDATE padre_estudiante SET
        dni = :dni,
        nombres = :nombres,
        apellido_paterno = :apellido_paterno,
        apellido_materno = :apellido_materno,
        edad = :edad,
        telefono = :telefono
        WHERE id_padre = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($padre);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM padre_estudiante WHERE id_padre =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $padre = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$padre) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el padre';
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
          El padre ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($padre) && $padre) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editar padre <?= escapar($padre['nombres']) . ' ' . escapar($padre['apellido_paterno'])  ?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
              <label for="id_padre">ID</label>
              <input type="text" name="id_padre" id="id_padre" value="<?= escapar($padre['id_padre']) ?>" class="form-control" disabled>
            </div>
          <div class="form-group">
            <label for="dni">DNI</label>
            <input type="text" name="dni" id="dni" value="<?= escapar($padre['dni']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="nombres">Nombres</label>
            <input type="text" name="nombres" id="nombres" value="<?= escapar($padre['nombres']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= escapar($padre['apellido_paterno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_materno">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="<?= escapar($padre['apellido_materno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad" value="<?= escapar($padre['edad']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="number" name="telefono" id="telefono" value="<?= escapar($padre['telefono']) ?>" class="form-control">
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