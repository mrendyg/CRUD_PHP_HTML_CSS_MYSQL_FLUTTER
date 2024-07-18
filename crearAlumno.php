<?php
include 'funciones.php';

csrf(); // Función para generar y verificar el token CSRF

$config = include 'config.php';
try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones PDO

    // Obtener los últimos IDs creados y sus nombres y apellidos
    $ultimoPadre = $conexion->query("SELECT * FROM padre_estudiante ORDER BY id_padre DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $ultimoMadre = $conexion->query("SELECT * FROM madre_estudiante ORDER BY id_madre DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $ultimoApoderado = $conexion->query("SELECT * FROM apoderado_alumno ORDER BY id_apoderado DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    // Consulta para obtener los cursos
    $consulta = "SELECT id_curso, grado FROM curso";
    $stmt = $conexion->query($consulta);
    $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo 'Error al conectar con la base de datos: ' . $error->getMessage();
    // Manejar el error adecuadamente en tu aplicación
    exit;
}

if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die("Token CSRF no válido");
}

if (isset($_POST['submit'])) {
  $resultado = [
    'error' => false,
    'mensaje' => 'El alumno ' . escapar($_POST['nombre']) . ' ha sido agregado con éxito'
  ];

  try {
    $alumno = [
      "nombre"   => $_POST['nombre'],
      "apellido_paterno" => $_POST['apellido_paterno'],
      "apellido_materno" => $_POST['apellido_materno'],
      "edad"     => $_POST['edad'],
      "ci"       => $_POST['ci'],
      "peso"     => $_POST['peso'],
      "vacunas_al_dia" => $_POST['vacunas_al_dia'],
      "id_padre" => $ultimoPadre['id_padre'],
      "id_madre" => $ultimoMadre['id_madre'],
      "id_apoderado" => $ultimoApoderado['id_apoderado'],
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
          <label for="nombre_completo_padre">Nombre Completo del Padre</label>
          <input type="text" name="nombre_completo_padre" id="nombre_completo_padre" class="form-control" readonly value="<?= escapar($ultimoPadre['nombres']) . ' ' . escapar($ultimoPadre['apellido_paterno']) . ' ' . escapar($ultimoPadre['apellido_materno']) ?>">
        </div>

        <div class="form-group">
          <label for="nombre_completo_madre">Nombre Completo de la Madre</label>
          <input type="text" name="nombre_completo_madre" id="nombre_completo_madre" class="form-control" readonly value="<?= escapar($ultimoMadre['nombres']) . ' ' . escapar($ultimoMadre['apellido_paterno']) . ' ' . escapar($ultimoMadre['apellido_materno']) ?>">
        </div>

        <div class="form-group">
          <label for="nombre_completo_apoderado">Nombre Completo del Apoderado</label>
          <input type="text" name="nombre_completo_apoderado" id="nombre_completo_apoderado" class="form-control" readonly value="<?= escapar($ultimoApoderado['nombres']) . ' ' . escapar($ultimoApoderado['apellido_paterno']) . ' ' . escapar($ultimoApoderado['apellido_materno']) ?>">
        </div>
        <div class="form-group">
          <label for="id_curso">Curso</label>
          <select name="id_curso" id="id_curso" class="form-control">
              <option value="">Selecciona un curso</option>
              <?php foreach ($curso as $c): ?>
                  <option value="<?= escapar($c['id_curso']) ?>" <?= ($c['id_curso']) ? 'selected' : '' ?>>
                      <?= escapar($c['grado']) ?>
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
