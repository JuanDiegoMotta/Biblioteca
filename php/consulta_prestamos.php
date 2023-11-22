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
            <!-- Creamos un select para escoger a un lector -->
            <div class="containerLector">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <label for="lector">Escoge a un lector:</label>
                    <select name="lector" id="lector">
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
            if (isset($_POST['mostrar'])) {
                $lector = $_POST['lector'];

                // Consulta para obtener ID lector
                $consultaLector = "SELECT id FROM lectores WHERE lector = '$lector'";
                $result = mysqli_query($conexion, $consultaLector);
                $fila = mysqli_fetch_assoc($result);
                $id = $fila['id'];

                // Consulta para obtener una tabla con datos sobre los préstamos de un lector
                $consultaPrestamosLector = "SELECT Prestamos.id_lector, Prestamos.id_libro, Libros.nombre, Lectores.lector
                FROM Prestamos
                JOIN Libros ON Prestamos.id_libro = Libros.id
                JOIN Lectores ON Prestamos.id_lector = Lectores.id
                WHERE Prestamos.id_lector = '$id';";
                $result = mysqli_query($conexion, $consultaPrestamosLector);
                
            }



            ?>
            <?php

            ?>
    <?php
            $bd->cerrar();
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