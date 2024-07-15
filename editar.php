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
  $resultado['mensaje'] = 'El alumno no existe';
}

if (isset($_POST['submit'])) {
  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);

    $alumno = [
      "id"        => $_GET['id'],
      "nombre"    => $_POST['nombre'],
      "apellido_paterno"  => $_POST['apellido_paterno'],
      "apellido_materno"  => $_POST['apellido_materno'],
      "edad"      => $_POST['edad'],
      "CI"      => $_POST['CI'],
      "peso"      => $_POST['peso'],
      "vacunas_al_dia"      => $_POST['vacunas_al_dia'],
      // se debe traer la informacion del padre, madre y apoderado y curso
    ];
    
    $consultaSQL = "UPDATE alumnos SET
        nombre = :nombre,
        apellido_paterno = :apellido_paterno,
        email = :email,
        edad = :edad,
        updated_at = NOW()
        WHERE id = :id";
    $consulta = $conexion->prepare($consultaSQL);
    $consulta->execute($alumno);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = $error->getMessage();
  }
}

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    
  $id = $_GET['id'];
  $consultaSQL = "SELECT * FROM alumnos WHERE id_alumno =" . $id;

  $sentencia = $conexion->prepare($consultaSQL);
  $sentencia->execute();

  $alumno = $sentencia->fetch(PDO::FETCH_ASSOC);

  if (!$alumno) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'No se ha encontrado el alumno';
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
          El alumno ha sido actualizado correctamente
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<?php
if (isset($alumno) && $alumno) {
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4">Editando el alumno <?= escapar($alumno['nombre']) . ' ' . escapar($alumno['apellido_paterno'])  ?></h2>
        <hr>
        <form method="post">
          <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="<?= escapar($alumno['nombre']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_paterno">Apellido Paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="<?= escapar($alumno['apellido_paterno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="apellido_materno">Apellido Materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="<?= escapar($alumno['apellido_materno']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="edad">Edad</label>
            <input type="number" name="edad" id="edad" value="<?= escapar($alumno['edad']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="CI">CI</label>
            <input type="number" name="CI" id="CI" value="<?= escapar($alumno['CI']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="peso">Peso</label>
            <input type="number" name="peso" id="peso" value="<?= escapar($alumno['peso']) ?>" class="form-control">
          </div>
          <div class="form-group">
            <label for="vacunas_al_dia">Vacunas al dia</label>
            <input type="text" name="vacunas_al_dia" id="vacunas_al_dia" value="<?= escapar($alumno['vacunas_al_dia']) ?>" class="form-control">
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