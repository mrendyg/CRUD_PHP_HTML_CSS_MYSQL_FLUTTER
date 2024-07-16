<?php

include 'funciones.php';

csrf(); // Función para generar y verificar el token CSRF

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die("Token CSRF no válido");
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El apoderado ' . escapar($_POST['nombres']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones PDO

    $apoderado = [
      "dni"   => $_POST['dni'],
      "nombres"   => $_POST['nombres'],
      "apellido_paterno" => $_POST['apellido_paterno'],
      "apellido_materno" => $_POST['apellido_materno'],
      "edad"     => $_POST['edad'],
      "telefono"     => $_POST['telefono'],
    ];

    $consultaSQL = "INSERT INTO apoderado_alumno (
    dni, nombres, apellido_paterno, apellido_materno, edad, telefono
    )";
    $consultaSQL .= " VALUES (:" . implode(", :", array_keys($apoderado)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($apoderado);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'Error al agregar apoderado: ' . $error->getMessage();
  }
}
?>

<?php include 'templates/header.php'; ?>

<?php if (isset($resultado)): ?>
  <div class="container mt-3">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
          <?= $resultado['mensaje'] ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <h2 class="mt-4">Apoderado del alumno</h2>
      <hr>
      <form method="post">
      <div class="form-group">
          <label for="dni">DNI</label>
          <input type="text" name="dni" id="dni" class="form-control">
        </div>
        <div class="form-group">
          <label for="nombres">Nombres</label>
          <input type="text" name="nombres" id="nombres" class="form-control">
        </div>
        <div class="form-group">
          <label for="apellido_paterno">Apellido Paterno</label>
          <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control">
        </div>
        <div class="form-group">
          <label for="apellido_materno">Apellido Materno</label>
          <input type="text" name="apellido_materno" id="apellido_materno" class="form-control">
        </div>
        <div class="form-group">
          <label for="edad">Edad</label>
          <input type="text" name="edad" id="edad" class="form-control">
        </div>
        <div class="form-group">
          <label for="telefono">Telefono</label>
          <input type="int" name="telefono" id="telefono" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?= escapar($_SESSION['csrf']) ?>">
        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-primary" value="Crear">
          <a class="btn btn-primary" href="crearAlumno.php">Avanzar</a>
          <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
