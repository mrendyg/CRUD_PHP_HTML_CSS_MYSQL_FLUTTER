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

      "id_curso"      => $_POST['id_curso'],
    ];
    
    $consultaSQL = "UPDATE alumnos SET
        nombre = :nombre,
        apellido_paterno = :apellido_paterno,
        apellido_materno = :apellido_materno,
        edad = :edad,
        CI = :CI,
        peso = :peso,
        vacunas_al_dia = :vacunas_al_dia,

        id_curso = :id_curso
        WHERE id_alumno = :id";
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

// Consulta para obtener los datos del padre asociado al alumno
try {
  $consultaSQLPadre = "SELECT p.id_padre, p.nombres, p.apellido_paterno, p.apellido_materno
                       FROM padre_estudiante p
                       INNER JOIN alumnos a ON p.id_padre = a.id_padre
                       WHERE a.id_alumno = :id_alumno";

  $stmtPadre = $conexion->prepare($consultaSQLPadre);
  $stmtPadre->bindParam(':id_alumno', $id);
  $stmtPadre->execute();

  $padre = $stmtPadre->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}

try {
  $consultaSQLMadre = "SELECT m.id_madre, m.nombres, m.apellido_paterno, m.apellido_materno
                       FROM madre_estudiante m
                       INNER JOIN alumnos a ON m.id_madre = a.id_madre
                       WHERE a.id_alumno = :id_alumno";

  $stmtMadre = $conexion->prepare($consultaSQLMadre);
  $stmtMadre->bindParam(':id_alumno', $id);
  $stmtMadre->execute();

  $madre = $stmtMadre->fetch(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
  $resultado['error'] = true;
  $resultado['mensaje'] = $error->getMessage();
}

try {
  $consultaSQLApoderado = "SELECT ap.id_apoderado, ap.nombres, ap.apellido_paterno, ap.apellido_materno
                       FROM apoderado_alumno ap
                       INNER JOIN alumnos a ON ap.id_apoderado = a.id_apoderado
                       WHERE a.id_alumno = :id_alumno";

  $stmtApoderado = $conexion->prepare($consultaSQLApoderado);
  $stmtApoderado->bindParam(':id_alumno', $id);
  $stmtApoderado->execute();

  $apoderado = $stmtApoderado->fetch(PDO::FETCH_ASSOC);

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
// Conexión a la base de datos y configuración
$config = include 'config.php';
try {
    $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Habilitar excepciones PDO

    // Consulta para obtener los cursos
    $consulta = "SELECT id_curso, grado FROM curso";
    $stmt = $conexion->query($consulta);
    $curso = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
    echo 'Error al conectar con la base de datos: ' . $error->getMessage();
    // Manejar el error adecuadamente en tu aplicación
    exit;
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
            <label for="id_alumno">ID</label>
            <input type="text" name="id_alumno" id="id_alumno" value="<?= escapar($alumno['id_alumno']) ?>" class="form-control" disabled>
          </div>
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
            <label for="vacunas_al_dia">Vacunas al día</label>
            <select name="vacunas_al_dia" id="vacunas_al_dia" class="form-control">
              <option value="si" <?= ($alumno['vacunas_al_dia'] == 'si') ? 'selected' : '' ?>>Sí</option>
              <option value="no" <?= ($alumno['vacunas_al_dia'] == 'no') ? 'selected' : '' ?>>No</option>
            </select>
          </div>          
          <div class="form-group">
            <label for="nombre_padre">Nombre del Padre</label>
            <input type="text" name="nombre_padre" id="nombre_padre" value="<?= isset($padre) ? escapar($padre['nombres'] . ' ' . $padre['apellido_paterno'] . ' '. $padre['apellido_materno']) : '' ?>" class="form-control" readonly>
            <a href="<?= 'editarPadre.php?id=' . escapar($fila["id_alumno"]) ?>">✏️Editar</a>

          </div>
          <div class="form-group">
            <label for="nombre_madre">Nombre de la Madre</label>
            <input type="text" name="nombre_madre" id="nombre_madre" value="<?= isset($madre) ? escapar($madre['nombres'] . ' ' . $madre['apellido_paterno'] . ' '. $madre['apellido_materno']) : '' ?>" class="form-control" readonly>
            <a href="<?= 'editarMadre.php?id=' . escapar($fila["id_alumno"]) ?>">✏️Editar</a>
          </div>
          <div class="form-group">
            <label for="nombre_apoderado">Nombre Apoderado</label>
            <input type="text" name="nombre_apoderado" id="nombre_apoderado" value="<?= isset($apoderado) ? escapar($apoderado['nombres'] . ' ' . $apoderado['apellido_paterno'] . ' '. $apoderado['apellido_materno']) : '' ?>" class="form-control" readonly>
            <a href="<?= 'editarApoderado.php?id=' . escapar($fila["id_alumno"]) ?>">✏️Editar</a>

          </div>
          <div class="form-group">
            <label for="id_curso">Curso</label>
            <select name="id_curso" id="id_curso" class="form-control">
              <option value="">Selecciona un curso</option>
              <?php foreach ($curso as $curso): ?>
                <option value="<?= escapar($curso['id_curso']) ?>" <?= ($alumno['id_curso'] == $curso['id_curso']) ? 'selected' : '' ?>>
                  <?= escapar($curso['grado']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <input name="csrf" type="hidden" value="<?= escapar($_SESSION['csrf']); ?>">
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
