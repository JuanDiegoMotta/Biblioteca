<?php
require_once 'conecta.php';

try {
    $bd = new BaseDeDatos();

    if ($bd->conectar()) {
        $conexion = $bd->getConexion();

        $query = "SHOW DATABASES LIKE 'Biblioteca'";
        $result = mysqli_query($conexion, $query);

        if (mysqli_num_rows($result) == 0) {
            // Crear la base de datos
            $sqlCreateDB = "CREATE DATABASE Biblioteca";
            if (mysqli_query($conexion, $sqlCreateDB)) {
                echo "<p style='color: white; font-weight: bold;'>Base de datos 'Biblioteca' creada con éxito.</p>";

                // Cambiar al contexto de la nueva base de datos
                mysqli_select_db($conexion, "Biblioteca");

                // Crear las tablas
                $sqlCreateTables = "
                    CREATE TABLE libros (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        nombre VARCHAR(255),
                        autor VARCHAR(255),
                        publicacion DATE,
                        isbn VARCHAR(13),
                        sinopsis TEXT,
                        n_disponibles INT,
                        n_totales INT
                    );

                    CREATE TABLE lectores (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        lector VARCHAR(255),
                        dni VARCHAR(9),
                        estado VARCHAR(255),
                        n_prestado INT
                    );

                    CREATE TABLE prestamos (
                        id_lector INT,
                        id_libro INT,
                        PRIMARY KEY (id_lector, id_libro),
                        FOREIGN KEY (id_lector) REFERENCES Lectores(id),
                        FOREIGN KEY (id_libro) REFERENCES Libros(id)
                    );
                ";

                if (mysqli_multi_query($conexion, $sqlCreateTables)) {
                    do {
                        // Vaciamos los resultados de cada consulta
                        mysqli_next_result($conexion);
                    } while (mysqli_more_results($conexion));

                    // echo "<p>Tablas creadas correctamente.</p>";

                    // Insertar datos
                    $sqlInsertData = "
                        INSERT INTO libros (nombre, autor, publicacion, isbn, sinopsis, n_disponibles, n_totales)
                        VALUES 
                            ('Cien años de soledad', 'Gabriel García Márquez', '1967-05-30', '978-84-204-6260-4', 'La novela narra la historia de la familia Buendía en el pueblo ficticio de Macondo.', 9, 10),
                            ('To Kill a Mockingbird', 'Harper Lee', '1960-07-11', '0-06-112008-1', 'The novel explores the issues of racism and injustice in the American South.', 9, 10);

                        INSERT INTO lectores (lector, dni, estado, n_prestado)
                        VALUES 
                            ('Juan Pérez', '12345678L', 'Activo', 1),
                            ('María Gómez', '98765432G', 'Activo', 1);

                        INSERT INTO prestamos (id_lector, id_libro)
                        VALUES
                            (1, 2),
                            (2, 1);
                    ";

                    if (mysqli_multi_query($conexion, $sqlInsertData)) {
                        do {
                            // Vaciamos los resultados de cada consulta
                            mysqli_next_result($conexion);
                        } while (mysqli_more_results($conexion));

                        // echo "<p>Datos insertados correctamente.</p>";
                    } else {
                        throw new Exception("Error al insertar datos: " . mysqli_error($conexion));
                    }
                } else {
                    throw new Exception("Error al crear las tablas: " . mysqli_error($conexion));
                }
            } else {
                throw new Exception("Error al crear la base de datos: " . mysqli_error($conexion));
            }
        } else {
            // echo "<p style='color: white; font-weight: bold;'>La BBDD Biblioteca ya existe.</p>";
        }

        $bd->cerrar();
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
