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
    $idEstudiantes = $_POST['idEstudiantes'];
    $nombre_tutor = $_POST['nombre_tutor'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $correo_electronico = $_POST['correo_electronico'];
    $relacion_estudiante = $_POST['relacion_estudiante'];

    // Cambiar la consulta SQL para insertar en la nueva tabla tutores_registro
    $sql_insert = "INSERT INTO tutores_registro 
        (IdEstudiantes, Nombre_tutor, Teléfono_contacto, Correo_electronico, Relación_estudiante) 
        VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("issss", $idEstudiantes, $nombre_tutor, $telefono_contacto, $correo_electronico, $relacion_estudiante);

    if ($stmt->execute()) {
        $mensaje = '<div class="alert alert-success">🎉 ¡Registro exitoso!</div>';
    } else {
        $mensaje = '<div class="alert alert-danger">❌ Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Obtener la lista de estudiantes
$sql_estudiantes = "SELECT idEstudiantes, Nombre_completo FROM estudiantes";
$result_estudiantes = $conn->query($sql_estudiantes);

// Obtener la lista de tutores registrados
$sql_tutores = "SELECT t.IdEstudiantes, e.Nombre_completo, t.Nombre_tutor, t.Teléfono_contacto, t.Correo_electronico, t.Relación_estudiante 
                FROM tutores_registro t
                INNER JOIN estudiantes e ON t.IdEstudiantes = e.idEstudiantes";
$result_tutores = $conn->query($sql_tutores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Padres/Tutores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Estilos */
        body {
            background: linear-gradient(to right, #d7efff, #fce2e2);
            font-family: 'Arial', sans-serif;
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
        <h2 class="form-title" id="registro">Registrar un Nuevo Padre/Tutor</h2>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($mensaje)) echo $mensaje; ?>

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
                <label for="nombre_tutor" class="form-label">👤 Nombre del Tutor</label>
                <input type="text" class="form-control" id="nombre_tutor" name="nombre_tutor" placeholder="Nombre del tutor" required>
            </div>
            <div class="mb-3">
                <label for="telefono_contacto" class="form-label">📞 Teléfono de Contacto</label>
                <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" placeholder="Teléfono" required>
            </div>
            <div class="mb-3">
                <label for="correo_electronico" class="form-label">📧 Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="Correo electrónico" required>
            </div>
            <div class="mb-3">
                <label for="relacion_estudiante" class="form-label">👥 Relación con el Estudiante</label>
                <select class="form-select" id="relacion_estudiante" name="relacion_estudiante" required>
                    <option value="Padre">Padre</option>
                    <option value="Madre">Madre</option>
                    <option value="Otro">Otro</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Registrar Tutor</button>
            </div>
        </form>

        <!-- Tabla de tutores registrados -->
        <h3 class="form-title">Lista de Tutores Registrados</h3>
        <table>
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Tutor</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Relación</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_tutores->num_rows > 0) {
                    while ($row = $result_tutores->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['Nombre_completo']}</td>
                            <td>{$row['Nombre_tutor']}</td>
                            <td>{$row['Teléfono_contacto']}</td>
                            <td>{$row['Correo_electronico']}</td>
                            <td>{$row['Relación_estudiante']}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No hay tutores registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
