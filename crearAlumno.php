<?php

include 'funciones.php';

csrf(); // Función para generar y verificar el token CSRF

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die("Token CSRF no válido");
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El alumno ' . escapar($_POST['nombre']) . ' ha sido agregado con éxito'
  ];

  $config = include 'config.php';

  try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones PDO

    $alumno = [
      "nombre"   => $_POST['nombre'],
      "apellido_paterno" => $_POST['apellido_paterno'],
      "apellido_materno" => $_POST['apellido_materno'],
      "edad"     => $_POST['edad'],
      "ci"       => $_POST['ci'],
      "peso"     => $_POST['peso'],
      "vacunas_al_dia" => $_POST['vacunas_al_dia'],
      "id_padre" => $_POST['id_padre'],
      "id_madre" => $_POST['id_madre'],
      "id_apoderado" => $_POST['id_apoderado'],
      "id_curso" => $_POST['id_curso'],
    ];

    $consultaSQL = "INSERT INTO alumnos (nombre, apellido_paterno, apellido_materno, edad, CI, peso, vacunas_al_dia, id_padre, id_madre, id_apoderado, id_curso)";
    $consultaSQL .= " VALUES (:" . implode(", :", array_keys($alumno)) . ")";

    $sentencia = $conexion->prepare($consultaSQL);
    $sentencia->execute($alumno);

  } catch(PDOException $error) {
    $resultado['error'] = true;
    $resultado['mensaje'] = 'Error al agregar alumno: ' . $error->getMessage();
  }
}
?>

<?php
// Conexión a la base de datos y configuración
$config = include 'config.php';
try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones PDO

    // Consulta para obtener los curso
    $consulta = "SELECT id_curso, grado FROM curso";
    $stmt = $conexion->query($consulta);
    $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo 'Error al conectar con la base de datos: ' . $error->getMessage();
    // Manejar el error adecuadamente en tu aplicación
    exit;
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
      <h2 class="mt-4">Crea un alumno</h2>
      <hr>
      <form method="post">
        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" id="nombre" class="form-control">
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
          <label for="ci">CI</label>
          <input type="text" name="ci" id="ci" class="form-control">
        </div>
        <div class="form-group">
          <label for="peso">Peso</label>
          <input type="text" name="peso" id="peso" class="form-control">
        </div>
        <div class="form-group">
          <label for="vacunas_al_dia">Vacunas al día</label>
          <select name="vacunas_al_dia" id="vacunas_al_dia" class="form-control">
            <option value="si">Sí</option>
            <option value="no">No</option>
          </select>
        </div>
        <div class="form-group">
          <label for="id_padre">ID del Padre</label>
          <input type="text" name="id_padre" id="id_padre" class="form-control">
        </div>
        <div class="form-group">
          <label for="id_madre">ID de la Madre</label>
          <input type="text" name="id_madre" id="id_madre" class="form-control">
        </div>
        <div class="form-group">
          <label for="id_apoderado">ID del Apoderado</label>
          <input type="text" name="id_apoderado" id="id_apoderado" class="form-control">
        </div>
        <div class="form-group">
              <label for="id_curso">Curso</label>
              <select name="id_curso" id="id_curso" class="form-control">
                  <option value="">Selecciona un curso</option>
                  <?php foreach ($curso as $curso): ?>
                      <option value="<?= escapar($curso['id_curso']) ?>" <?= ($curso['id_curso']) ? 'selected' : '' ?>>
                          <?= escapar($curso['grado']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>
        <input name="csrf" type="hidden" value="<?= escapar($_SESSION['csrf']) ?>">
        <div class="form-group">
          <input type="submit" name="submit" class="btn btn-primary" value="Enviar">
          <a class="btn btn-primary" href="index.php">Regresar al inicio</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include 'templates/footer.php'; ?>
