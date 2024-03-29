<!-- Proyecto realizado por Jorge Burgos Barrera y Juan Diego Motta -->
<?php
   // Require del php que crea la BD si no existe
   require_once 'php/controlBBDD/crea_tablas.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Biblioteca</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

   <div class="titulo" >
      <h1>Bienvenid@s a la Biblioteca de Juan Diego y Jorge!</h1>
   </div>
   
   <div class="container-bttns">

      <div class="first-bttns">
         <a href="php/gestion_lectores.php">
            <button>Gestión lectores</button>
            
         <a href="php/gestion_prestamos.php">
            <button>Gestión préstamos</button>
         </a>
         
         <a href="php/gestion_libros.php">
            <button>Gestión libros</button>
         </a>
      </div>

      <div class="second-bttns">
         <a href="php/consulta_prestamos.php">
            <button>Consulta préstamos</button>
         </a>
         
         <a href="php/consulta_libros.php">
            <button>Consulta libros</button>
         </a>
      </div>

   </div>
</body>
</html>