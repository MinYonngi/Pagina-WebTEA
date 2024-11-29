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

// Función para generar diagnóstico basado en los datos seleccionados
function generarDiagnostico($s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8) {
    // Asignar puntajes numéricos a las respuestas
    $puntaje = 0;

    // Mapa de respuestas y puntajes
    $puntajes = [
        "Sin contacto visual" => 0,
        "Contacto visual ocasional" => 1,
        "Contacto visual frecuente" => 2,
        "Ningún interés" => 0,
        "Interés limitado" => 1,
        "Interés amplio" => 2,
        "No señala" => 0,
        "Señala ocasionalmente" => 1,
        "Señala frecuentemente" => 2,
        "No habla" => 0,
        "Lenguaje limitado" => 1,
        "Lenguaje fluido" => 2,
        "No adecuado" => 0,
        "Limitado" => 1,
        "Adecuado" => 2,
        "No presenta" => 0,
        "Presenta ocasionalmente" => 1,
        "Presenta frecuentemente" => 2,
        "Ninguna" => 0,
        "Levemente inusuales" => 1,
        "Muy inusuales" => 2,
        "Ninguna" => 0,
        "Leves" => 1,
        "Severas" => 2
    ];

    // Sumar los puntajes de las respuestas
    $puntaje += $puntajes[$s1] ?? 0;
    $puntaje += $puntajes[$s2] ?? 0;
    $puntaje += $puntajes[$s3] ?? 0;
    $puntaje += $puntajes[$s4] ?? 0;
    $puntaje += $puntajes[$s5] ?? 0;
    $puntaje += $puntajes[$s6] ?? 0;
    $puntaje += $puntajes[$s7] ?? 0;
    $puntaje += $puntajes[$s8] ?? 0;

    // Generar diagnóstico según el puntaje total
    if ($puntaje >= 12) {
        return ["diagnostico" => "Posible TEA severo", "puntaje" => $puntaje];
    } elseif ($puntaje >= 8) {
        return ["diagnostico" => "Posible TEA moderado", "puntaje" => $puntaje];
    } else {
        return ["diagnostico" => "Posible TEA leve", "puntaje" => $puntaje];
    }
}

// Procesar el formulario
$diagnostico = null;
$mensaje = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger respuestas del formulario
    $s1 = $_POST['s1'] ?? '';
    $s2 = $_POST['s2'] ?? '';
    $s3 = $_POST['s3'] ?? '';
    $s4 = $_POST['s4'] ?? '';
    $s5 = $_POST['s5'] ?? '';
    $s6 = $_POST['s6'] ?? '';
    $s7 = $_POST['s7'] ?? '';
    $s8 = $_POST['s8'] ?? '';

    // Generar diagnóstico basado en los datos ingresados
    $diagnostico_resultado = generarDiagnostico($s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8);
    $diagnostico = $diagnostico_resultado["diagnostico"];

    // Guardar los datos en la base de datos
    $stmt = $conn->prepare("INSERT INTO diagnosticos_tea_deta (Contacto_Visual, Interes_en_otros, Habilidad_para_Señalar, Lenguaje, Pragmatica_Lenguaje, Estereotipias_Conductas, Preocupaciones_Inusuales, Sensibilidades_Inusuales, Diagnostico) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $s1, $s2, $s3, $s4, $s5, $s6, $s7, $s8, $diagnostico);

    if ($stmt->execute()) {
        $mensaje = "Datos guardados correctamente.";
    } else {
        $mensaje = "Error al guardar los datos: " . $stmt->error;
    }
    $stmt->close();
}

// Consultar la cantidad de diagnósticos por tipo
$diagnosticos_severos = 0;
$diagnosticos_mod = 0;
$diagnosticos_leves = 0;

$query = "SELECT Diagnostico, COUNT(*) AS cantidad FROM diagnosticos_tea_deta GROUP BY Diagnostico";
$result = $conn->query($query);

while ($row = $result->fetch_assoc()) {
    if ($row['Diagnostico'] == 'Posible TEA severo') {
        $diagnosticos_severos = $row['cantidad'];
    } elseif ($row['Diagnostico'] == 'Posible TEA moderado') {
        $diagnosticos_mod = $row['cantidad'];
    } elseif ($row['Diagnostico'] == 'Posible TEA leve') {
        $diagnosticos_leves = $row['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Diagnóstico</title>
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
            max-width: 1200px;
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
        .form-container {
            flex: 1;
            padding-right: 30px;
        }
        .chart-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .col-6 {
            max-width: 48%;
            flex: 0 0 48%;
        }
    </style>

    <!-- Cargar la librería de Google Charts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Diagnóstico', 'Número de Casos'],
          ['Posible TEA Severos', <?php echo $diagnosticos_severos; ?>],
          ['Posible TEA Moderados', <?php echo $diagnosticos_mod; ?>],
          ['Posible TEA Leves', <?php echo $diagnosticos_leves; ?>]
        ]);

        var options = {
          title: 'Distribución de Diagnósticos de TEA',
          pieSliceText: 'percentage',
          slices: {
            0: {offset: 0.1},
            1: {offset: 0.1},
            2: {offset: 0.1}
          }
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
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

    <div class="container">
        <div class="header">
            <h1>Formulario de Diagnóstico de TEA</h1>
            <p>Completa el formulario para obtener el diagnóstico.</p>
        </div>

        <!-- Mostrar mensaje de éxito o error -->
        <?php if ($mensaje) { ?>
            <div class="alert alert-info"><?php echo $mensaje; ?></div>
        <?php } ?>

        <!-- Resultado del diagnóstico -->
        <?php if ($diagnostico) { ?>
            <div class="alert alert-success">
                <h4 class="alert-heading">Resultado del Diagnóstico:</h4>
                <p>Diagnóstico: <?php echo $diagnostico; ?></p>
            </div>
        <?php } ?>

        <div class="row">
            <!-- Formulario -->
            <div class="col-6 form-container">
                <form method="POST">
                    <div class="mb-3">
                        <label for="s1" class="form-label">¿Cómo es su contacto visual?</label>
                        <select class="form-select" id="s1" name="s1">
                            <option value="Sin contacto visual">Sin contacto visual</option>
                            <option value="Contacto visual ocasional">Contacto visual ocasional</option>
                            <option value="Contacto visual frecuente">Contacto visual frecuente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s2" class="form-label">¿Cómo es su interés en otros?</label>
                        <select class="form-select" id="s2" name="s2">
                            <option value="Ningún interés">Ningún interés</option>
                            <option value="Interés limitado">Interés limitado</option>
                            <option value="Interés amplio">Interés amplio</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s3" class="form-label">¿Señala para pedir algo?</label>
                        <select class="form-select" id="s3" name="s3">
                            <option value="No señala">No señala</option>
                            <option value="Señala ocasionalmente">Señala ocasionalmente</option>
                            <option value="Señala frecuentemente">Señala frecuentemente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s4" class="form-label">¿Cómo es su lenguaje?</label>
                        <select class="form-select" id="s4" name="s4">
                            <option value="No habla">No habla</option>
                            <option value="Lenguaje limitado">Lenguaje limitado</option>
                            <option value="Lenguaje fluido">Lenguaje fluido</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s5" class="form-label">¿Cómo es el uso pragmático del lenguaje?</label>
                        <select class="form-select" id="s5" name="s5">
                            <option value="No adecuado">No adecuado</option>
                            <option value="Limitado">Limitado</option>
                            <option value="Adecuado">Adecuado</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s6" class="form-label">¿Presenta conductas estereotipadas?</label>
                        <select class="form-select" id="s6" name="s6">
                            <option value="No presenta">No presenta</option>
                            <option value="Presenta ocasionalmente">Presenta ocasionalmente</option>
                            <option value="Presenta frecuentemente">Presenta frecuentemente</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s7" class="form-label">¿Presenta preocupaciones inusuales?</label>
                        <select class="form-select" id="s7" name="s7">
                            <option value="Ninguna">Ninguna</option>
                            <option value="Levemente inusuales">Levemente inusuales</option>
                            <option value="Muy inusuales">Muy inusuales</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="s8" class="form-label">¿Tiene sensibilidades inusuales?</label>
                        <select class="form-select" id="s8" name="s8">
                            <option value="Ninguna">Ninguna</option>
                            <option value="Leves">Leves</option>
                            <option value="Severas">Severas</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>

            <!-- Gráfica -->
            <div class="col-6 chart-container">
                <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
            </div>
        </div>
    </div>

</body>
</html>
