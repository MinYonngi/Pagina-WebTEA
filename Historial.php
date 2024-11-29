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

// Procesar formulario de historial académico
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['form_type']) && $_POST['form_type'] == "historial") {
    $idEstudiantes = $_POST['idEstudiantes'];
    $grado_academico = $_POST['grado_academico'];
    $promedio = $_POST['promedio'];
    $adaptaciones = $_POST['adaptaciones'];
    $asistencia_escolar = $_POST['asistencia_escolar'];

    $sql_insert = "INSERT INTO historial_académico (idEstudiantes, Grado_academico, Promedio, Adaptaciones, asistencia_escolar) 
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("isisi", $idEstudiantes, $grado_academico, $promedio, $adaptaciones, $asistencia_escolar);

    if ($stmt->execute()) {
        $mensaje_historial = '<div class="alert alert-success">🎉 ¡Historial registrado exitosamente!</div>';
    } else {
        $mensaje_historial = '<div class="alert alert-danger">❌ Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Obtener la lista de estudiantes
$sql_estudiantes = "SELECT idEstudiantes, Nombre_completo FROM estudiantes";
$result_estudiantes = $conn->query($sql_estudiantes);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Historial Académico</title>
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
            <h1>Registro de Historial Académico 📚</h1>
        </div>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($mensaje_historial)) echo $mensaje_historial; ?>

        <!-- Formulario -->
        <h2 class="form-title" id="registro">Registrar un Nuevo Historial Académico</h2>
        <form method="POST" action="">
            <input type="hidden" name="form_type" value="historial">
            
            <!-- Selección de Estudiante -->
            <div class="mb-3">
                <label for="idEstudiantes" class="form-label">👤 Estudiante</label>
                <select class="form-select" id="idEstudiantes" name="idEstudiantes" required>
                    <option value="">Seleccione un estudiante</option>
                    <?php
                    if ($result_estudiantes->num_rows > 0) {
                        while ($row_estudiante = $result_estudiantes->fetch_assoc()) {
                            echo "<option value='{$row_estudiante['idEstudiantes']}'>{$row_estudiante['Nombre_completo']}</option>";
                        }
                    } else {
                        echo "<option value=''>No hay estudiantes registrados</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Grado académico -->
            <div class="mb-3">
                <label for="grado_academico" class="form-label">🎓 Grado Académico</label>
                <input type="text" class="form-control" id="grado_academico" name="grado_academico" placeholder="Ejemplo: 5to grado" required>
            </div>

            <!-- Promedio -->
            <div class="mb-3">
                <label for="promedio" class="form-label">📊 Promedio</label>
                <input type="number" class="form-control" id="promedio" name="promedio" step="0.01" placeholder="Promedio del estudiante" required>
            </div>

            <!-- Adaptaciones -->
            <div class="mb-3">
                <label for="adaptaciones" class="form-label">🔧 Adaptaciones</label>
                <textarea class="form-control" id="adaptaciones" name="adaptaciones" placeholder="Adaptaciones realizadas" rows="3" required></textarea>
            </div>

            <!-- Asistencia escolar -->
            <div class="mb-3">
                <label for="asistencia_escolar" class="form-label">📅 Asistencia Escolar</label>
                <input type="text" class="form-control" id="asistencia_escolar" name="asistencia_escolar" placeholder="Asistencia (Ejemplo: 90%)" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Historial</button>
            </div>
        </form>
    </div>
</body>
</html>
