<?php
    require_once 'conecta.php';
    // Comprobamos si la conexión fue exitosa:
    try{
        $bd = new BaseDeDatos();
        if($bd->conectar()){
            echo "<p>Conectado</p>";
            $conexion = $bd->getConexion();
             // Verificamos si la base de datos ya existe
            $query = "SHOW DATABASES LIKE 'Biblioteca'";
            $result = mysqli_query($conexion, $query);
            if(mysqli_num_rows($result) == 0){
                echo "<p>No existe la BBDD Biblioteca, creándola...</p>";
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
                    $sql3 = "CREATE TABLE prestamos (
                        id_lector INT,
                        id_libro INT,
                        PRIMARY KEY (id_lector, id_libro),
                        FOREIGN KEY (id_lector) REFERENCES Lectores(id),
                        FOREIGN KEY (id_libro) REFERENCES Libros(id)
                    )";
                    // Queries para insertar 2 filas en cada tabla
                    $sql4 = "INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales)
                    VALUES 
                        ('Cien años de soledad', 'Gabriel García Márquez', '1967-05-30', '978-84-204-6260-4', 'La novela narra la historia de la familia Buendía en el pueblo ficticio de Macondo.', 9, 10),
                        ('To Kill a Mockingbird', 'Harper Lee', '1960-07-11', '0-06-112008-1', 'The novel explores the issues of racism and injustice in the American South.', 9, 10)";
                        
                    $sql5 = "INSERT INTO lectores (lector, dni, estado, n_prestado)
                    VALUES 
                        ('Juan Pérez', '12345678L', 'Activo', 1),
                        ('María Gómez', '98765432G', 'Inactivo', 1)";
                    $sql6 = "INSERT INTO prestamos (id_lector, id_libro)
                    VALUES
                        (1, 2),
                        (2, 1)";
                    // Ejecutamos las queries
                    if(mysqli_query($conexion, $sql1)){
                        echo "<p>Tabla libros creada correctamente</p>";
                    }
                    if(mysqli_query($conexion, $sql2)){
                        echo "<p>Tabla lectores creada correctamente</p>";
                    }
                    if(mysqli_query($conexion, $sql3)){
                        echo "<p>Tabla prestamos creada correctamente</p>";
                    }
                    if(mysqli_query($conexion, $sql4)){
                        echo "<p>Valores añadidos correctamente a la tabla libros</p>";
                    }
                    if(mysqli_query($conexion, $sql5)){
                        echo "<p>Valores añadidos correctamente a la tabla lectores</p>";
                    }
                    if(mysqli_query($conexion, $sql6)){
                        echo "<p>Valores añadidos correctamente a la tabla prestamos</p>";
                    }
    
                } else{
                    echo "<p>Error al crear la base de datos</p>";
                    mysqli_error($bd->getConexion()); // Muestra el código de error
                }
            } else{
                echo "<p>La BBDD Biblioteca ya existe.</p>";
            }
            $bd->cerrar();
        }
    } catch (Exception $e){
        echo $e->getMessage();
    }  
        
    ?>