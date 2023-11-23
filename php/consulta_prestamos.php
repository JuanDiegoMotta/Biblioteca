<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta préstamos</title>
    <link rel="stylesheet" href="../css/consulta_prestamos.css">
</head>

<body>

    <div class="titulo">
        <h1>Consulta préstamos</h1>
    </div>
    <?php
    require_once 'controlBBDD/conecta.php';
    try {
        // Creamos la instancia de la clase BaseDeDatos
        $bd = new BaseDeDatos();

        // Intentamos conectarnos a la bbdd
        if ($bd->conectar()) {
            $conexion = $bd->getConexion();
            mysqli_select_db($conexion, "Biblioteca");
            $queryLectores = "SELECT lector FROM lectores WHERE estado = 'Activo'";
            $result = mysqli_query($conexion, $queryLectores);
    ?>
            <div class="containerPpal">
                <div class="containerLector">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <!-- Creamos un select para escoger a un lector -->
                        <label for="lector" id="escogerLector">Escoge a un lector:</label>
                        <select name="lector" id="lector">
                            <option value="" selected></option>
                            <?php
                            // Comprobamos si hay resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Recorremos cada una de las filas
                                while ($fila = mysqli_fetch_assoc($result)) {
                                    // Guardamos el valor del nombre
                                    $nombre = $fila['lector'];

                                    // Añadimos la opción al select:
                                    echo "<option value='$nombre'>$nombre</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="mostrar">Mostrar</button>
                    </form>
                </div>
                <?php
                if (isset($_POST['mostrar']) && !empty($_POST['lector'])) {
                    $lector = $_POST['lector'];

                    // Consulta para obtener ID lector
                    $consultaLector = "SELECT id FROM lectores WHERE lector = '$lector'";
                    $result = mysqli_query($conexion, $consultaLector);
                    $fila = mysqli_fetch_assoc($result);
                    $id = $fila['id'];

                    // Consulta para obtener una tabla con datos sobre los préstamos de un lector
                    $consultaPrestamosLector = "SELECT Lectores.lector, Prestamos.id_lector, Libros.nombre, Prestamos.id_libro
                FROM Prestamos
                JOIN Libros ON Prestamos.id_libro = Libros.id
                JOIN Lectores ON Prestamos.id_lector = Lectores.id
                WHERE Prestamos.id_lector = '$id'";
                    $result = mysqli_query($conexion, $consultaPrestamosLector);
                ?>
                    <div class="containerTabla">
                        <table>
                            <tr>
                                <th>Nombre</th>
                                <th>ID</th>
                                <th>Nombre_libro</th>
                                <th>ID_libro</th>
                            </tr>
                            <?php
                            // Comprobamos si la consulta da resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Por cada fila de la consulta imprimimos el valor de las columnas en sus respectivos <td></td>
                                while ($fila = mysqli_fetch_assoc($result)) {
                                    $nombre = $fila['lector'];
                                    $idLector = $fila['id_lector'];
                                    $libro = $fila['nombre'];
                                    $idLibro = $fila['id_libro'];
                                    echo "<tr>";
                                    echo "<td>$nombre</td>";
                                    echo "<td>$idLector</td>";
                                    echo "<td>$libro</td>";
                                    echo "<td>$idLibro</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo '<p style="color: white; font-weight: bold;">El lector no tiene ningún libro en préstamo</p>';
                            }
                            ?>
                        </table>
                    </div>
            </div>
        <?php
                }
        ?>
<?php
            $bd->cerrar();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
?>

<div id="boton-home">
    <a href="../index.php">
        <button>HOME</button>
    </a>
</div>

</body>

</html>