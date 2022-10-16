<?php
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";

    $conn = mysqli_connect($hostname,$username,$password,$db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $titulo = $_GET["titulo"];

    // Si está logueado, tiene la cookie "username"
    if (isset($_COOKIE["username"])) {
        $username = $_COOKIE["username"];
    } else {
        $username = "Iniciar Sesión";
    }
    // pone el nombre de usuario en el header
    $header = str_replace('%usuario%', $username, file_get_contents('/var/www/html/HTML/header_small.html')); 
    // inserta el header en la página
    $pagina = str_replace('%header%', $header, file_get_contents('/var/www/html/HTML/leer_libro.html'));

    // Obtiene el capítulo que se ha pedido
    $query = mysqli_query($conn, "SELECT * FROM libro WHERE `Book ID`='$titulo'")
        or die (mysqli_error($conn));

    $row = mysqli_fetch_assoc($query);
    // Guarda el nombre del capítulo para luego
    $chap_id = $row["Chapter_ID"];
    // inserta el texto en la página
    $pagina = str_replace('%texto%', $row["Prologue"], $pagina);
    $pagina = str_replace('%TitCapitulo%', "Prólogo", $pagina);

    $anterior = "";

    // El botón de capítulo siguiente no está en el último capítulo
    $cap_siguiente = "1";
    $query = mysqli_query($conn, "SELECT * FROM capitulo WHERE `Book ID`='$titulo' AND `Chapter Num`=$cap_siguiente")
        or die (mysqli_error($conn));
    $siguiente = "";
    if ($row = mysqli_fetch_assoc($query)) {
        $siguiente = "
    <form metod=\"get\" action=\"/PHP/leer_libro.php\">
        <Button>Capítulo siguiente</Button>
        <input type=\"hidden\" name=\"titulo\" value=\"$titulo\" />
        <input type=\"hidden\" name=\"capitulo\" value=\"$cap_siguiente\" />
    </form>";
    }

    // inserta los botones de "capítulo anterior", tanto arriba como abajo
    $pagina = str_replace('%boton anterior%', $anterior, $pagina);
    // inserta los botones de "capítulo siguiente", tanto arriba como abajo
    $pagina = str_replace('%boton siguiente%', $siguiente, $pagina);

    // Los comentarios
    $query = mysqli_query($conn, "SELECT * FROM `comentario capitulo` WHERE `Book ID`='$titulo' AND Chapter_ID='$chap_id'")
        or die (mysqli_error($conn));
    $comentarios = "";
    while ($row = mysqli_fetch_assoc($query)) {
        $comentarios .= "<div class=\"comentario\">
            <div class=\"infocoment\">
                <h4>{$row['User ID']}</h4>
                <p>{$row['Texto']}</p>
            </div>
        </div>";
    }

    $pagina = str_replace('%comentario%', $comentarios, $pagina);

    echo $pagina;
?>