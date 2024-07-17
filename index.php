<?php
include 'funciones.php';

csrf();
if (isset($_POST['submit']) && !hash_equals($_SESSION['csrf'], $_POST['csrf'])) {
  die();
}

$error = '';
$config = include 'config.php';

try {
  $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
  $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
  $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activar manejo de errores
  
  // Construir la consulta SQL según los parámetros recibidos
  if (isset($_POST['apellido_paterno'])) {
    $consultaSQL = "SELECT a.*, c.grado FROM alumnos a 
                    LEFT JOIN curso c ON a.id_curso = c.id_curso 
                    WHERE a.apellido_paterno LIKE :apellido_paterno";
    $stmt = $conexion->prepare($consultaSQL);
    $stmt->bindValue(':apellido_paterno', '%' . $_POST['apellido_paterno'] . '%', PDO::PARAM_STR);
  } else {
    $consultaSQL = "SELECT a.*, c.grado FROM alumnos a 
                    LEFT JOIN curso c ON a.id_curso = c.id_curso";
    $stmt = $conexion->prepare($consultaSQL);
  }
  
  $stmt->execute();
  $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $error) {
  $error = $error->getMessage();
}

$titulo = isset($_POST['apellido_paterno']) ? 'Lista de alumnos (Apellido: ' . $_POST['apellido_paterno'] . ')' : 'Lista de alumnos';
?>

<?php include "templates/header.php"; ?>

<?php if ($error): ?>
<div class="container mt-2">
  <div class="row">
    <div class="col-md-12">
      <div class="alert alert-danger" role="alert">
        <?= $error ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <a href="crearPadre.php" class="btn btn-primary mt-4">Crear alumno</a>
      <hr>
      <form method="post" class="form-inline">
        <div class="form-group mr-3">
          <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder="Buscar por Apellido" class="form-control">
        </div>
        <input name="csrf" type="hidden" value="<?= escapar($_SESSION['csrf']); ?>">
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
            <th>Vacunas al día</th>
            <th>Curso</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($alumnos as $alumno): ?>
            <tr>
              <td><?= escapar($alumno["id_alumno"]); ?></td>
              <td><?= escapar($alumno["nombre"]); ?></td>
              <td><?= escapar($alumno["apellido_paterno"]); ?></td>
              <td><?= escapar($alumno["apellido_materno"]); ?></td>
              <td><?= escapar($alumno["edad"]); ?></td>
              <td><?= escapar($alumno["CI"]); ?></td>
              <td><?= escapar($alumno["peso"]); ?></td>
              <td><?= escapar($alumno["vacunas_al_dia"]); ?></td>
              <td><?= escapar($alumno["grado"]); ?></td>
              <td>
                <a href="<?= 'borrar.php?id=' . escapar($alumno["id_alumno"]); ?>">🗑️ Borrar</a>
                <a href="<?= 'editarAlumno.php?id=' . escapar($alumno["id_alumno"]); ?>">✏️ Editar</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include "templates/footer.php"; ?>
