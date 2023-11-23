<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gestión lectores</title>
   <link rel="stylesheet" href="../css/lectores.css">
</head>
<body>
<?php
require_once 'controlBBDD/conecta.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $bd = new BaseDeDatos();

        if ($bd->conectar()) {
            echo "<p>Conectado</p>";
            $conexion = $bd->getConexion();
            mysqli_select_db($conexion, "Biblioteca");

            if (isset($_POST['introducir'])) {
                // Formulario de inserción
                $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
                $dni = mysqli_real_escape_string($conexion, $_POST['dni']);
                $estado = "Activo";

                // Verificar si el lector ya existe
                $consultaLector = "SELECT * FROM lectores WHERE dni = '$dni'";
                $resultadoLector = mysqli_query($conexion, $consultaLector);

                if (mysqli_num_rows($resultadoLector) > 0) {
                    echo "<p style='color: white; font-weight: bold;'>El lector con DNI $dni ya existe en la base de datos.</p>";

                } else {
                    // Si no existe, insertar en la tabla lectores
                    $sql = "INSERT INTO lectores (lector, dni, estado, n_prestado) 
                            VALUES ('$nombre', '$dni', '$estado', 0)";

                    if (mysqli_query($conexion, $sql)) {
                        echo "<p>Lector introducido correctamente</p>";
                    }
                }
            } elseif (isset($_POST['borrar'])) {
                // Formulario de borrado
                $borrarDni = mysqli_real_escape_string($conexion, $_POST['dniBorrar']);

                // Sentencia SQL para la eliminación en la tabla lectores
                $sql = "DELETE FROM lectores WHERE dni = '$borrarDni'";

                if (mysqli_query($conexion, $sql)) {
                    echo "<p>Lector eliminado correctamente</p>";
                } 
            }
        } else {
            echo "<p>Error al conectar con la base de datos</p>";
            mysqli_error($bd->getConexion()); // Muestra el código de error
        }

        $bd->cerrar();

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<div id="formulario-container">
    
    <div id="container1">
        <h2>Añadir lector</h2>
        <form name="IntroducirLector" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="nombre">Nombre del lector:</label>
            <br>
            <input type="text" name="nombre" required/><br />

            <label for="dni">DNI:</label>
                <br>
            <input type="text" id="dni" name="dni" pattern="[0-9]{8}[a-zA-Z]" maxlength="9" placeholder="8 dígitos+letra" required />
                <br>
                <br>
            <input type="submit" name="introducir" value="Introducir" />
        </form>
    </div>

    <div class="vertical-line"></div>

    <div id="container2">
        <h2>Borrar lector</h2>
        <form name="BorrarLector" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <label for="dniBorrar">DNI del lector a borrar:</label>
                <br>
            <input type="text" name="dniBorrar" pattern="[0-9]{8}[a-zA-Z]" maxlength="9" placeholder="8 dígitos+letra" required />
                <br>
                <br>
            <input type="submit" name="borrar" value="Borrar" />
        </form>
    </div>
</div>

<div id="boton-home">
   <a href="../index.html">
      <button>HOME</button>
   </a>
</div>

</body>
</html>



