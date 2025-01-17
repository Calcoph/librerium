<?php
require "tokens.php";

function login() {
    if (sesion_valida()) {
        $hostname = "db";
        $username = "admin";
        $password = file_get_contents('/var/db_pass.txt');
        $db = "database";

        $conn = mysqli_connect($hostname,$username,$password,$db);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
        $query = mysqli_prepare($conn, "SELECT user_id FROM session_tokens WHERE token=?") or die ("Error interno E890");
        mysqli_stmt_bind_param($query, "s", $tok);
        $tok = $_COOKIE["session"];
        mysqli_stmt_execute($query) or die ("Error interno E890");

        mysqli_stmt_bind_result($query, $user);
        mysqli_stmt_fetch($query);

        if ($user != NULL) {
            return $user;
        } else {
            header('Location: '."/PHP/inicio_sesion.php");
            die();
        }
    } else {
        header('Location: '."/PHP/inicio_sesion.php");
        die();
    }
}

function get_usuario() {
    if (sesion_valida()) {
        $hostname = "db";
        $username = "admin";
        $password = file_get_contents('/var/db_pass.txt');
        $db = "database";

        $conn = mysqli_connect($hostname,$username,$password,$db);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }
        $query = mysqli_prepare($conn, "SELECT user_id FROM session_tokens WHERE token=?") or die ("Error interno E890");
        mysqli_stmt_bind_param($query, "s", $tok);
        $tok = $_COOKIE["session"];
        mysqli_stmt_execute($query) or die ("Error interno E890");

        mysqli_stmt_bind_result($query, $user);
        mysqli_stmt_fetch($query);

        return $user; // Puede ser null
    } else {
        return NULL;
    }
}
?>