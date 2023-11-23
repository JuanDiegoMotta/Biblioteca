<?php
require_once 'controlBBDD/conecta.php';

// Creo instancia BBDD
$bd = new BaseDeDatos();

// Realizo la conexión
try {
    if ($bd->conectar()) {
        // Guardamos la conexión
        $conexion = $bd->getConexion();

        // Cambiamos al contexto de nuestra BBDD
        mysqli_select_db($conexion, 'Biblioteca') or die("Error al seleccionar BBDD" . mysqli_error($conexion));

        // Si se intenta añadir préstamo
        if (isset($_POST['realizar'])) {
            // Guardamos los datos del préstamo, el lector y el libro, en variables
            $lector = $_POST['lector'];
            $libro = $_POST['libro'];

            // Obtenemos IDs correspondientes
            $consultaIdLibro = "SELECT id FROM libros WHERE nombre = '$libro'";
            $consultaIdLector = "SELECT id FROM lectores WHERE lector = '$lector'";

            // ID lector
            $result = mysqli_query($conexion, $consultaIdLector);
            $fila = mysqli_fetch_assoc($result);
            $id_lector = $fila['id'];

            // ID libro
            $result1 = mysqli_query($conexion, $consultaIdLibro);
            $fila1 = mysqli_fetch_assoc($result1);
            $id_libro = $fila1['id'];

            // Comprobamos si ya existe un préstamo con este lector y este libro
            $consultaExistePrestamo = "SELECT * FROM prestamos WHERE id_lector = $id_lector AND id_libro = $id_libro";
            $result3 = mysqli_query($conexion, $consultaExistePrestamo);
            if (mysqli_num_rows($result3) > 0) {
                echo '<p style="color: white; font-weight: bold;">El lector ya tiene el libro en préstamo</p>';
            } else {
                // En el caso de que no exista este préstamo, lo introducimos.

                // INSERT para añadir a la tabla préstamos la nueva fila
                $insertPrestamos = "INSERT INTO prestamos (id_lector, id_libro)
                VALUES ('$id_lector', '$id_libro')";

                if ($result = mysqli_query($conexion, $insertPrestamos)) {
                    echo '<p style="color: white; font-weight: bold;">Inserción completada</p>';

                    // UPDATE para restar 1 a n_disponibles en la tabla libros
                    $updateLibro = "UPDATE libros
                    SET n_disponibles = n_disponibles - 1
                    WHERE id = $id_libro";

                    if ($result = mysqli_query($conexion, $updateLibro)) {
                        // echo "<p>Update tabla libros completado</p>";
                    } else {
                        // echo "<p>Problema con el update de la tabla libros</p>";
                    }

                    // UPDATE para añadir 1 a n_prestado en la tabla libros
                    $updateLector = "UPDATE lectores
                    SET n_prestado = n_prestado + 1
                    WHERE id = $id_lector";

                    if ($result = mysqli_query($conexion, $updateLector)) {
                        // echo "<p>Update tabla lectores completado</p>";
                    } else {
                        // echo "<p>Problema con el update de la tabla lectores</p>";
                    }
                } else {
                    echo '<p style="color: white; font-weight: bold;">Problema con la inserción</p>';
                }
            }
        }

        // Si se intenta devolver préstamo
        if (isset($_POST['devolver'])) {
            // Guardamos información, libro y nombre lector en variables
            $libro = $_POST['librosLector'];
            $lector = $_POST['nombreLector'];

            // Obtenemos IDs correspondientes
            $consultaIdLibro = "SELECT id FROM libros WHERE nombre = '$libro'";
            $consultaIdLector = "SELECT id FROM lectores WHERE lector = '$lector'";

            // ID lector
            $result = mysqli_query($conexion, $consultaIdLector);
            $fila = mysqli_fetch_assoc($result);
            $id_lector = $fila['id'];

            // ID libro
            $result1 = mysqli_query($conexion, $consultaIdLibro);
            $fila1 = mysqli_fetch_assoc($result1);
            $id_libro = $fila1['id'];

            // Borramos la fila de la tabla prestamos
            $borrarPrestamo = "DELETE FROM prestamos
            WHERE id_libro = $id_libro AND id_lector = $id_lector";

            if (mysqli_query($conexion, $borrarPrestamo)) {
                echo '<p style="color: white; font-weight: bold;">Libro devuelto correctamente</p>';
                // UPDATE para sumar 1 a n_disponibles en la tabla libros
                $updateLibro = "UPDATE libros
                 SET n_disponibles = n_disponibles + 1
                 WHERE id = $id_libro";

                if ($result = mysqli_query($conexion, $updateLibro)) {
                    // echo "<p>Update tabla libros completado</p>";
                } else {
                    // echo "<p>Problema con el update de la tabla libros</p>";
                }

                // UPDATE para restar 1 a n_prestado en la tabla libros
                $updateLector = "UPDATE lectores
                 SET n_prestado = n_prestado - 1
                 WHERE id = $id_lector";

                if ($result = mysqli_query($conexion, $updateLector)) {
                    // echo "<p>Update tabla lectores completado</p>";
                } else {
                    // echo "<p>Problema con el update de la tabla lectores</p>";
                }
            }
        }

?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Gestión préstamos</title>
            <link rel="stylesheet" href="../css/prestamos.css">
        </head>

        <body>
            <div class="titulo">
                <h1>Gestión de préstamos</h1>
            </div>
            <div class="containerPpal">
                <div class="formRealizarPrest">
                    <h1>Realizar préstamo</h1>
                    <br><br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <label for="lector">
                            <h2>Selecciona al lector:</h2>
                        </label>
                        <select name="lector" id="lector" required>
                            <option value="" selected></option>
                            <?php
                            // Consulta de lectores que estén activos (puedan realizar préstamo)
                            $consultaLectores = "SELECT lector FROM lectores WHERE estado = 'Activo'";
                            $result = mysqli_query($conexion, $consultaLectores);

                            // Comprobamos si la consulta tiene resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Si los tiene, recorremos las filas con un while
                                while ($fila = mysqli_fetch_assoc($result)) {
                                    // Insertamos una opción del select por cada lector
                                    $nombre = $fila['lector'];
                                    echo "<option value='$nombre'>$nombre</option>";
                                }
                            }
                            ?>
                        </select>
                        <label for="libro">
                            <h2>Selecciona un libro:</h2>
                        </label>
                        <select name="libro" id="libro" required>
                            <option value="" selected></option>
                            <?php
                            // Consulta de libros que tengan unidades disponibles
                            $consultaLibros = "SELECT nombre FROM libros WHERE n_disponibles > 0";
                            $result = mysqli_query($conexion, $consultaLibros);

                            // Comprobamos si tiene resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Si los tiene, recorremos las filas con un while
                                while ($fila = mysqli_fetch_assoc($result)) {
                                    // Insertamos una opción del select por cada libro
                                    $nombre = $fila['nombre'];
                                    echo "<option value='$nombre'>$nombre</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="realizar">Realizar préstamo</button>
                    </form>
                </div>
                <div class="vertical-line"></div>
                <div class="formDevolverPrest">
                    <h1>Devolver préstamo</h1>
                    <br><br>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <label for="lector2">
                            <h2>Selecciona al lector:</h2>
                        </label>
                        <select name="lector2" id="lector2" required>
                            <option value="" selected></option>
                            <?php
                            // Consulta de lectores que estén activos (puedan realizar préstamo)
                            $consultaLectores = "SELECT lector FROM lectores WHERE estado = 'Activo'";
                            $result = mysqli_query($conexion, $consultaLectores);

                            // Comprobamos si la consulta tiene resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Si los tiene, recorremos las filas con un while
                                while ($fila = mysqli_fetch_assoc($result)) {
                                    // Insertamos una opción del select por cada lector
                                    $nombre = $fila['lector'];
                                    echo "<option value='$nombre'>$nombre</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" name="seleccionar">Seleccionar</button>
                    </form>
                    <div id="boton-home">
                        <a href="../index.html">
                            <button>HOME</button>
                        </a>
                    </div>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="formLectorDev">
                        <label for="librosLector">Selecciona un libro a devolver:</label>
                        <select name="librosLector" id="librosLector" required>
                            <option value="" selected></option>
                            <?php
                            if (isset($_POST['seleccionar'])) {
                                $lector = $_POST['lector2'];

                                // Consulta para obtener ID lector
                                $consultaLector = "SELECT id FROM lectores WHERE lector = '$lector'";
                                $result = mysqli_query($conexion, $consultaLector);
                                $fila = mysqli_fetch_assoc($result);
                                $id = $fila['id'];

                                // Consulta para obtener nombres de libros prestados para dicho lector
                                $consultaLibrosLector = "SELECT libros.nombre AS nombre_libro
                                FROM prestamos
                                JOIN libros ON prestamos.id_libro = libros.id
                                WHERE prestamos.id_lector = '$id'";
                                $result = mysqli_query($conexion, $consultaLibrosLector);

                                // Comprobamos si la consulta tiene resultados
                                if (mysqli_num_rows($result) > 0) {
                                    echo "<p>Está dentro del mysqli_num_rows</p>";
                                    // Si los tiene, recorremos las filas con un while
                                    while ($fila = mysqli_fetch_assoc($result)) {
                                        // Insertamos una opción del select por cada lector
                                        $nombre = $fila['nombre_libro'];
                                        echo "<option value='$nombre'>$nombre</option>";
                                    }
                                }
                            ?>
                        </select>
            <?php
                                // Pásamos el nombre del lector a la variable $_POST mediante input:hidden
                                echo "<input type='hidden' name='nombreLector' value='$lector'>";
                                echo "<p>Lector seleccionado: $lector</p>";
                            }
                            // Cerramos conexión
                            $bd->cerrar();
                        }
                    } catch (Exception $e) {
                        echo "Error al intentar conectar con la BBDD: " . $e->getMessage();
                    }

            ?>
            <button type="submit" name="devolver">Devolver</button>
                    </form>

                </div>

            </div>

        </body>

        </html>