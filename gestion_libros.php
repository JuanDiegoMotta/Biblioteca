<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gestión de Libros</title>

   <style>
      #sinopsis {
         resize: none
      }
   </style>
</head>
<body>
<?php
require_once 'conecta.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   $nombre = mysqli_real_escape_string($_POST['nombre']);
   $autor = mysqli_real_escape_string($_POST['autor']);
   $publicacion = mysqli_real_escape_string($_POST['publicacion']);
   $isbn = mysqli_real_escape_string($_POST['isbn']);
   $sinopsis = mysqli_real_escape_string($_POST['sinopsis']);
   $n_totales = mysqli_real_escape_string($_POST['n_totales']);

   // Convertimos la fecha en formato MySQL
   $fechaFormateada = date('Y-m-d', strtotime($publicacion));


   // Comprobamos si la conexión fue exitosa:
   try {
      $bd = new BaseDeDatos();
      if ($bd->conectar()) {
         echo "<p>Conectado</p>";
         $conexion = $bd->getConexion();
         // Sentencia SQL para la inserción en la tabla libros
         $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_totales) 
            VALUES ('$nombre', '$autor', '$fechaFormateada', '$isbn', '$sinopsis', '$n_totales')";

         if (mysqli_query($conexion, $sql)) {
            echo "<p>Libro introducido</p>";
         }
      } else {
         echo "<p>Error al conectar con la base de datos</p>";
         mysqli_error($bd->getConexion()); // Muestra el código de error
      }

      $bd->cerrar();
      
   }   catch (Exception $e){
      echo $e->getMessage();
  } 
} 

    ?>

<form name="introducirLibro" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

      <label for="nombre">Nombre del libro:</label>
      <br>
      <input type="text" name="nombre"/><br />

      <label for="autor">Autor:</label>
      <br>
      <input type="text" name="autor"/><br />

      <label for="publicacion">Publicacion:</label>
      <br>
      <input type="date" name="publicacion"/><br />

      <label for="isbn">ISBN:</label>
      <br>
      <input type="text" name="isbn"/><br />


      <label for="sinopsis">Sinopsis:</label>
      <br>
      <textarea id="sinopsis" name="sinopsis" rows="4" cols="50"/></textarea><br />

      <label for="n_totales">Libros totales:</label>
      <br>
      <input type="text" name="n_totales"/><br />

      <input type="submit" value="Introducir" />
    </form>

    <a href="index.html">
            <button>HOME</button>
         </a>
</body>
</html>