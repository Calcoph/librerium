<?php
    require "../login.php";

    // Nos aseguramos de que los datos del login son correctos antes de continuar.
    login();

    if (!comprobar_token_csrf($_POST["_token"])) {
        echo "Ha habido un error interno (E9013), pruebe más tarde";
        die();
    }

    $hostname = "db";
    $username = "admin";
    $password = file_get_contents('/var/db_pass.txt');
    $db = "database";

    $conn = mysqli_connect($hostname,$username,$password,$db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // modifica el capítulo
    $query = mysqli_prepare($conn, "UPDATE capitulo SET Chapter_ID = ?, Texto = ? WHERE `Book ID` = ? AND Chapter_ID = ?") or die ("Error interno E890");
    mysqli_stmt_bind_param($query, "ssss", $titulo, $texto, $titulo_libro, $titulo_anterior);
    $titulo = htmlspecialchars($_POST["titulo"]);
    $texto = htmlspecialchars($_POST["texto"]);
    $titulo_libro = htmlspecialchars($_POST["titulo_libro"]);
    $titulo_anterior = htmlspecialchars($_POST["titulo_anterior"]);
    mysqli_stmt_execute($query) or die ("Error interno E890");

    header('Location: '."/PHP/mod_libros/modificar_libro.php/?titulo=$titulo_libro");
?>
