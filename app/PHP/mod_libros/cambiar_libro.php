<?php
    // La carpeta donde se van a guardar los archivos
    $target_dir = "/var/www/html/uploads/";
    // La dirección que se ve desde el html (para insertar las imágenes luego)
    $save_path = "/uploads/";
    $titulo = $_POST["titulo"];
    $titulo_anterior = $_POST["titulo_anterior"];
    $descripcion = $_POST["descripcion"];
    $resumen = $_POST["resumen"];
    $texto = $_POST["texto"];
    if (file_exists($_FILES["portada_personalizada"]["tmp_name"])) {
        // Si ha elegido una portada personalizada, la descargamos
        $target_file = $target_dir . basename($_FILES["portada_personalizada"]["name"]);
        $save_path = $save_path . basename($_FILES["portada_personalizada"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $files = $_FILES["portada_personalizada"]["tmp_name"];
        // código sacado de https://www.w3schools.com/php/php_file_upload.asp
        // modificaciones: Hemos eliminado todos los checks
        if (move_uploaded_file($_FILES["portada_personalizada"]["tmp_name"], $target_file)) {

        } else {
            echo "Ha habido un error al subir la portada.";
            return;
        }
    } else {
        // Si ha elegido una portada predefinida, solo guardamos la elección en la base de datos
        $target_file = $target_dir . $_POST["portada"] . ".png";
        $save_path = $save_path . $_POST["portada"] . ".png";
    }

    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";

    $conn = mysqli_connect($hostname,$username,$password,$db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    if ($_POST["portada"] == "anterior") {
        // Actualizar todo menos la portada
        $query = mysqli_prepare($conn, "UPDATE libro SET `Book ID`=?, Text_corto=?, Text_largo=? Prologue=? WHERE `Book ID`=?") or die (mysqli_error($conn));
        mysqli_stmt_bind_param($query, "sssss", $b_id, $corto, $largo, $prologo, $tit_ant);
        $b_id = $titulo;
        $corto = $descripcion;
        $largo = $resumen;
        $prologo = $texto;
        $tit_ant = $titulo_anterior;
        mysqli_stmt_execute($query) or die (mysqli_error($conn));
    } else {
        // Actualizar todo
        $query = mysqli_prepare($conn, "UPDATE libro SET `Book ID`=?, imglink=?, Text_corto=?, Text_largo=?, Prologue=? WHERE `Book ID`=?") or die (mysqli_error($conn));
        mysqli_stmt_bind_param($query, "ssssss", $b_id, $link, $corto, $largo, $prologo, $tit_ant);
        $b_id = $titulo;
        $link = $save_path;
        $corto = $descripcion;
        $largo = $resumen;
        $prologo = $texto;
        $tit_ant = $titulo_anterior;
        mysqli_stmt_execute($query) or die (mysqli_error($conn));
    }

    header('Location: '."/index.php");
    die();
?>
