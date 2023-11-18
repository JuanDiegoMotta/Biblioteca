<?php
    require_once 'conecta.php';
    // Comprobamos si la conexión fue exitosa:
    try{
        $bd = new BaseDeDatos();
        if($bd->conectar()){
            echo "<p>Conectado</p>";
            $conexion = $bd->getConexion();
            // Creamos la BBDD
            $sql = "CREATE DATABASE Biblioteca";
            if(mysqli_query($conexion, $sql)){
                // Si se ha creado la BBDD exitosamente, procedo a crear las tablas
                echo "<p>Base de datos creada con éxito</p>";
                mysqli_select_db($conexion, "Biblioteca"); // Indica con qué BBDD se va a trabajar
                // Queries para crear las tablas:
                $sql1 = "CREATE TABLE libros (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nombre VARCHAR(255),
                    autor VARCHAR(255),
                    publicacion DATE,
                    isbn VARCHAR(13),
                    sinopsis TEXT,
                    n_disponibles INT,
                    n_totales INT
                )";
                $sql2 = "CREATE TABLE lectores (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    lector VARCHAR(255),
                    dni VARCHAR(9),
                    estado VARCHAR(255),
                    n_prestado INT
                )";
                $sql3 = "CREATE TABLE prestamo (
                    id_lector INT,
                    id_libro INT,
                    PRIMARY KEY (id_lector, id_libro),
                    FOREIGN KEY (id_lector) REFERENCES Lectores(id),
                    FOREIGN KEY (id_libro) REFERENCES Libros(id)
                )";
                // Queries para insertar 2 filas en cada tabla
                $sql4 = "INSERT INTO Libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales)
                VALUES 
                    ('Cien años de soledad', 'Gabriel García Márquez', '1967-05-30', '978-84-204-6260-4', 'La novela narra la historia de la familia Buendía en el pueblo ficticio de Macondo.', 9, 10),
                    ('To Kill a Mockingbird', 'Harper Lee', '1960-07-11', '0-06-112008-1', 'The novel explores the issues of racism and injustice in the American South.', 9, 10)";
                    
                /****TO-DO (Acabar queries y ejecutarlas, probarlo todo con XAMPP)****/
            } else{
                echo "<p>Error al crear la base de datos</p>";
                mysqli_error($bd->getConexion()); // Muestra el código de error
            }
        }
    } catch (Exception $e){
        echo $e->getMessage();
    }  
        
    ?>