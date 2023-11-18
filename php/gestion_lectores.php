<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gestión lectores</title>
</head>
<body>
<?php
require_once 'conecta.php';

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

                // Sentencia SQL para la inserción en la tabla lectores
                $sql = "INSERT INTO lectores (lector, dni, estado) 
                        VALUES ('$nombre', '$dni', '$estado')";

                if (mysqli_query($conexion, $sql)) {
                    echo "<p>Lector introducido correctamente</p>";
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

   <form name="BorrarLector" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
      <label for="dniBorrar">DNI del lector a borrar:</label>
         <br>
      <input type="text" name="dniBorrar" pattern="[0-9]{8}[a-zA-Z]" maxlength="9" placeholder="8 dígitos+letra" required />
         <br>
         <br>
      <input type="submit" name="borrar" value="Borrar" />
    </form>


   <a href="../index.html">
         <button>HOME</button>
      </a>


</body>
</html>



