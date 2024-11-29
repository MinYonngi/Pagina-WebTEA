<?php  
// Configuración de conexión
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "autismo";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $escuela = $_POST['escuela'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $grado_academico = $_POST['grado_academico'];
    $nivel_educativo = (int) $_POST['nivel_educativo'];
    $sexo = $_POST['sexo'];
    $idEscuelas = (int) $_POST['idEscuelas'];

    $sql_insert = "INSERT INTO estudiantes 
        (Nombre_completo, Dirección, Escuela_de_procedencia, Fecha_nacimiento, Grado_académico, Nivel_educativo, Sexo, idEscuelas) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("sssssssi", $nombre, $direccion, $escuela, $fecha_nacimiento, $grado_academico, $nivel_educativo, $sexo, $idEscuelas);

    if ($stmt->execute()) {
        $mensaje = '<div class="alert alert-success">🎉 ¡Registro exitoso!</div>';
    } else {
        $mensaje = '<div class="alert alert-danger">❌ Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

$sql = "SELECT * FROM estudiantes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Estudiantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #d7efff, #fce2e2);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #5dade2;
            padding: 15px 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        nav a:hover {
            color: #fce2e2;
            text-decoration: underline;
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            color: #5dade2;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .form-title {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: #3498db;
            border: none;
        }
        .btn-primary:hover {
            background: #21618c;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background: #5dade2;
            color: white;
        }
        table td, table th {
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Menú de navegación -->
    <nav>
        <a href="index.php"><i class="fa fa-home"></i> Inicio</a>
        <a href="Diagnostico.php"><i class="fa fa-stethoscope"></i> Diagnóstico</a>
        <a href="Escuelas.php"><i class="fa fa-school"></i> Escuelas</a>
        <a href="PadresTutores.php"><i class="fa fa-users"></i> Padres/Tutores</a>
        <a href="Historial.php"><i class="fa fa-history"></i> Historial</a>
        <a href="Intervenciones.php"><i class="fa fa-cogs"></i> Intervenciones</a>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <div class="header">
            <h1>Registro de Estudiantes 📚</h1>
        </div>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($mensaje)) echo $mensaje; ?>

        <!-- Formulario -->
        <h2 class="form-title" id="registro">Registrar un Nuevo Estudiante</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nombre" class="form-label">👤 Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe el nombre completo" required>
            </div>
            <div class="mb-3">
                <label for="direccion" class="form-label">🏠 Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección del estudiante" required>
            </div>
            <div class="mb-3">
                <label for="escuela" class="form-label">🏫 Escuela de Procedencia</label>
                <input type="text" class="form-control" id="escuela" name="escuela" placeholder="Escuela de Procedencia" required>
            </div>
            <div class="mb-3">
                <label for="fecha_nacimiento" class="form-label">📅 Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
            </div>
            <div class="mb-3">
                <label for="grado_academico" class="form-label">🎓 Grado Académico</label>
                <input type="text" class="form-control" id="grado_academico" name="grado_academico" placeholder="Ejemplo: 5to grado" required>
            </div>
            <div class="mb-3">
                <label for="nivel_educativo" class="form-label">📘 Nivel Educativo</label>
                <select class="form-select" id="nivel_educativo" name="nivel_educativo" required>
                    <option value="">Seleccione el nivel educativo</option>
                    <!-- Opciones desde la base de datos -->
                    <?php
                    $sql_niveles = "SELECT id, nivel FROM niveles_educativos";
                    $result_niveles = $conn->query($sql_niveles);
                    if ($result_niveles->num_rows > 0) {
                        while ($row_nivel = $result_niveles->fetch_assoc()) {
                            echo "<option value='{$row_nivel['id']}'>{$row_nivel['nivel']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hay niveles disponibles</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="sexo" class="form-label">👥 Sexo</label>
                <select class="form-select" id="sexo" name="sexo" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="idEscuelas" class="form-label">🔢 ID Escuela</label>
                <input type="number" class="form-control" id="idEscuelas" name="idEscuelas" placeholder="Escribe el ID de la escuela" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Estudiante</button>
            </div>
        </form>

        <!-- Tabla de estudiantes -->
        <h2 class="form-title mt-5" id="lista">Lista de Estudiantes Registrados</h2>
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Nombre</th><th>Dirección</th><th>Escuela</th><th>Fecha de Nacimiento</th><th>Grado</th><th>Nivel</th><th>Sexo</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['idEstudiantes']}</td><td>{$row['Nombre_completo']}</td><td>{$row['Dirección']}</td><td>{$row['Escuela_de_procedencia']}</td><td>{$row['Fecha_nacimiento']}</td><td>{$row['Grado_académico']}</td><td>{$row['Nivel_educativo']}</td><td>{$row['Sexo']}</td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-info">No hay estudiantes registrados.</div>';
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
