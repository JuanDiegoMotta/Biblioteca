<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Gestión de Libros</title>
   <link rel="stylesheet" href="../css/libros.css">
</head>
<body>
<?php
require_once 'conecta.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Comprobamos si la conexión fue exitosa:
   try {
      $bd = new BaseDeDatos();


      if ($bd->conectar()) {
         echo '<p style="color: white; font-weight: bold;">Conectado</p>';
         $conexion = $bd->getConexion();
         mysqli_select_db($conexion, "Biblioteca");
                  
         $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
         $autor = mysqli_real_escape_string($conexion, $_POST['autor']);
         $publicacion = mysqli_real_escape_string($conexion, $_POST['publicacion']);
         $isbn = mysqli_real_escape_string($conexion, $_POST['isbn']);
         $sinopsis = mysqli_real_escape_string($conexion, $_POST['sinopsis']);
         $n_totales = mysqli_real_escape_string($conexion, $_POST['n_totales']);
   
         // Convertimos la fecha en formato MySQL
         $fechaFormateada = date('Y-m-d', strtotime($publicacion));

         // Sentencia que comprueba si existe ya el libro
         $sqlNombre = "SELECT * FROM libros WHERE nombre = '$nombre'";
         $resultadoNombre = mysqli_query($conexion, $sqlNombre);
           
         if (mysqli_num_rows($resultadoNombre) > 0) {
            echo '<p style="color: white; font-weight: bold;">El libro que estás intentando introducir ya existe.</p>';
         } else {
            // Sentencia SQL para la inserción en la tabla libros
            $sql = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_totales, n_disponibles) 
            VALUES ('$nombre', '$autor', '$fechaFormateada', '$isbn', '$sinopsis', '$n_totales', '$n_totales')";
            
            if (mysqli_query($conexion, $sql)) {
               echo '<p style="color: white; font-weight: bold;">Libro introducido</p>';
           }
         }

      } else {
         echo '<p style="color: white; font-weight: bold;">Error al conectar con la base de datos</p>';
         mysqli_error($bd->getConexion()); // Muestra el código de error
      }

      $bd->cerrar();
      
   }   catch (Exception $e){
      echo $e->getMessage();
  } 
} 

    ?>

<div class="titulo">
   <h1>Añade un libro a nuestra biblioteca!</h1>
</div>

<div id="formulario-container">
   <form name="introducirLibro" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
      <label for="nombre">Nombre del libro:</label>
      <br>
      <input type="text" name="nombre" required/><br />

      <label for="autor">Autor:</label>
      <br>
      <input type="text" name="autor" required/><br />

      <label for="publicacion">Publicacion:</label>
      <br>
      <input type="date" name="publicacion" required/><br />

      <label for="isbn">ISBN:</label>
      <br>
      <input type="text" name="isbn" required/><br />

      <label for="sinopsis">Sinopsis:</label>
      <br>
      <textarea id="sinopsis" name="sinopsis" rows="4" cols="50" required></textarea><br />

      <label for="n_totales">Libros totales:</label>
      <br>
      <input type="text" name="n_totales" required/><br />

      <input type="submit" value="INTRODUCIR" />
   </form>

</div>

<div id="boton-home">
   <a href="../index.html">
      <button>HOME</button>
   </a>
</div>


</body>
</html>