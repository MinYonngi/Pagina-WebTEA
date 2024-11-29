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
    $nombre_escuela = $_POST['nombre_escuela'];
    $direccion = $_POST['direccion'];
    $telefono_contacto = $_POST['telefono_contacto'];
    $tipo_escuela = $_POST['tipo_escuela'];

    // Insertar datos en la tabla escuelas
    $sql_insert = "INSERT INTO escuelas (Nombre_escuela, Dirección, Teléfono_contacto, Tipo_Escuela) 
        VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("ssss", $nombre_escuela, $direccion, $telefono_contacto, $tipo_escuela);

    if ($stmt->execute()) {
        $mensaje = '<div class="alert alert-success animated bounceInDown">🎉 ¡Escuela registrada exitosamente!</div>';
    } else {
        $mensaje = '<div class="alert alert-danger animated shake">❌ Error: ' . $stmt->error . '</div>';
    }
    $stmt->close();
}

// Obtener los datos de la tabla escuelas
$sql = "SELECT * FROM escuelas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Escuelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        nav a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        nav a:hover {
            color: #fce2e2;
            text-decoration: underline;
            transform: scale(1.1);
        }
        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            color: #5dade2;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 3rem;
            font-weight: bold;
        }
        .form-title {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }
        .btn-primary {
            background: #3498db;
            border: none;
            padding: 12px;
            font-size: 1.2rem;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background: #21618c;
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border-radius: 8px;
            overflow: hidden;
        }
        table thead {
            background: #5dade2;
            color: white;
        }
        table td, table th {
            padding: 12px 15px;
            text-align: center;
        }
        table tbody tr:hover {
            background: #f0f8ff;
            transform: scale(1.02);
            transition: all 0.3s;
        }
        .alert {
            font-size: 1.1rem;
            font-weight: bold;
            text-align: center;
            animation-duration: 1s;
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
            <h1>Registro de Escuelas 🏫</h1>
        </div>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if (isset($mensaje)) echo $mensaje; ?>

        <!-- Formulario -->
        <h2 class="form-title" id="registro">Registrar una Nueva Escuela</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="nombre_escuela" class="form-label">🏫 Nombre de la Escuela</label>
                <input type="text" class="form-control" id="nombre_escuela" name="nombre_escuela" placeholder="Escribe el nombre de la escuela" required>
            </div>
            <div class="mb-4">
                <label for="direccion" class="form-label">🏠 Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección de la escuela" required>
            </div>
            <div class="mb-4">
                <label for="telefono_contacto" class="form-label">📞 Teléfono de Contacto</label>
                <input type="text" class="form-control" id="telefono_contacto" name="telefono_contacto" placeholder="Teléfono de contacto" required>
            </div>
            <div class="mb-4">
                <label for="tipo_escuela" class="form-label">🏫 Tipo de Escuela</label>
                <select class="form-select" id="tipo_escuela" name="tipo_escuela" required>
                    <option value="Pública">Pública</option>
                    <option value="Privada">Privada</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Registrar Escuela</button>
            </div>
        </form>

        <!-- Tabla de escuelas -->
        <h2 class="form-title mt-5" id="lista">Lista de Escuelas Registradas</h2>
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID</th><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Tipo</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['idEscuelas']}</td><td>{$row['Nombre_escuela']}</td><td>{$row['Dirección']}</td><td>{$row['Teléfono_contacto']}</td><td>{$row['Tipo_Escuela']}</td></tr>";
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-info">No hay escuelas registradas.</div>';
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
