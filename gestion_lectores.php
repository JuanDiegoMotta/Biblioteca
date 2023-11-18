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
      <input type="submit" value="Introducir" />
   </form>

   
   <a href="index.html">
         <button>HOME</button>
      </a>


</body>
</html>



