<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Consulta libros</title>
   <link rel="stylesheet" href="../css/consulta_libros.css">
</head>
<body>

<div class="titulo" >
      <h1>Libros que tenemos disponibles!</h1>
   </div>

<?php
require_once 'controlBBDD/conecta.php';

try {
   // Creamos la instancia de la clase BaseDeDatos
    $bd = new BaseDeDatos();

   // Intentamos conectarnos a la bbdd
    if ($bd->conectar()) {
        echo "<p>Conectado</p>";
        $conexion = $bd->getConexion();
        mysqli_select_db($conexion, "Biblioteca");

      //   Consulta para seleccionar los libros con mas de 0 unidades disponibles
        $sql = "SELECT * FROM libros WHERE n_disponibles > 0";
        $resultado = mysqli_query($conexion, $sql);

        if ($resultado) {
            $rows = [];
            // Se recuperan el resultado de la consulta
            while ($row = mysqli_fetch_assoc($resultado)) {
                $rows[] = $row;
            }

            if (count($rows) > 0) {
                // Se muestra la tabla si hay resultados
                echo '<table border="1">';
                echo '<tr>';
                foreach ($rows[0] as $key => $value) {
                  // Encabezado de la tabla
                    echo "<th>$key</th>";
                }
                echo '</tr>';

                foreach ($rows as $row) {
                    echo '<tr>';
                    foreach ($row as $value) {
                     // Datos de la tabla
                        echo "<td>$value</td>";
                    }
                    echo '</tr>';
                }
                echo '</table>';
            } else {
                echo "No se encontraron libros disponibles.";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conexion);
        }

        $bd->cerrar();
    } else {
        echo "<p>Error al conectar con la base de datos</p>";
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
?>

<div id="boton-home">
   <a href="../index.html">
      <button>HOME</button>
   </a>
</div>

</body>
</html>
