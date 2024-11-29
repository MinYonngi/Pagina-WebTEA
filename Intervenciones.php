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

// Consulta para obtener los estudiantes
$result_estudiantes = $conn->query("SELECT idEstudiantes, Nombre_completo FROM estudiantes");

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $idEstudiantes = $_POST['idEstudiantes'] ?? '';
    $tipo_intervencion = $_POST['tipo_intervencion'] ?? '';
    $frecuencia_intervencion = $_POST['frecuencia_intervencion'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $responsable_intervencion = $_POST['responsable_intervencion'] ?? '';
    $resultados = $_POST['resultados'] ?? '';

    // Guardar los datos en la nueva tabla
    $stmt = $conn->prepare("INSERT INTO intervenciones_nueva 
        (idEstudiante, tipo_intervencion, frecuencia_intervencion, fecha_inicio, responsable_intervencion, resultados) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $idEstudiantes, $tipo_intervencion, $frecuencia_intervencion, $fecha_inicio, $responsable_intervencion, $resultados);

    if ($stmt->execute()) {
        $mensaje = "Datos guardados correctamente.";
    } else {
        $mensaje = "Error al guardar los datos: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Intervenciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #d7efff, #fce2e2);
            font-family: 'Arial', sans-serif;
        }
        nav {
            background-color: #5dade2;
            padding: 15px;
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
            max-width: 800px;
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
        .btn-primary {
            background: #3498db;
            border: none;
        }
        .btn-primary:hover {
            background: #21618c;
        }
        .alert {
            font-size: 1.2rem;
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
        <a href="Intervenciones.php"><i class="fa fa-puzzle-piece"></i> Intervenciones</a>
    </nav>

    <!-- Contenido principal -->
    <div class="container">
        <div class="header">
            <h1>Formulario de Intervenciones <i class="fa fa-puzzle-piece"></i></h1>
        </div>

        <!-- Mostrar mensaje -->
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>

        <!-- Formulario -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="idEstudiantes" class="form-label">🔢 Nombre del Estudiante</label>
                <select class="form-select" id="idEstudiantes" name="idEstudiantes" required>
                    <option value="">Seleccione un estudiante</option>
                    <?php
                    if ($result_estudiantes->num_rows > 0) {
                        while ($row = $result_estudiantes->fetch_assoc()) {
                            echo "<option value='{$row['idEstudiantes']}'>{$row['Nombre_completo']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="tipo_intervencion" class="form-label">Tipo de Intervención</label>
                <select class="form-select" id="tipo_intervencion" name="tipo_intervencion" required>
                    <option value="Terapia del habla">Terapia del habla</option>
                    <option value="Terapia ocupacional">Terapia ocupacional</option>
                    <option value="Terapia física">Terapia física</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="frecuencia_intervencion" class="form-label">Frecuencia de Intervención</label>
                <select class="form-select" id="frecuencia_intervencion" name="frecuencia_intervencion" required>
                    <option value="Diaria">Diaria</option>
                    <option value="Semanal">Semanal</option>
                    <option value="Mensual">Mensual</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
            <div class="mb-3">
                <label for="responsable_intervencion" class="form-label">Responsable</label>
                <input type="text" class="form-control" id="responsable_intervencion" name="responsable_intervencion" required>
            </div>
            <div class="mb-3">
                <label for="resultados" class="form-label">Resultados</label>
                <textarea class="form-control" id="resultados" name="resultados" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Guardar Intervención</button>
        </form>
    </div>
</body>
</html>
