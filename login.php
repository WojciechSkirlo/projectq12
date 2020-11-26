<?php
session_start();
if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

require_once "connect.php";

$link = @new mysqli($db_server, $db_login, $db_password, $db_name);
if ($link->connect_errno != 0) {
    echo "Error: " . $link->connect_errno;
} else {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $login = htmlentities($login, ENT_QUOTES, "utf-8");
    if ($result = @$link->query(sprintf(
        "SELECT * FROM users WHERE user='%s'",
        mysqli_real_escape_string($link, $login)
    ))) {
        $how_many_users = $result->num_rows;
        if ($how_many_users > 0) {
            $cell = $result->fetch_assoc();
            if (password_verify($password, $cell['password'])) {

                $_SESSION['logged'] = true;
                $_SESSION['id'] = $cell['id'];
                $_SESSION['login'] = $cell['login'];
                $_SESSION['email'] = $cell['email'];

                unset($_SESSION['error']);
                $result->close();
                header('Location: page.php');
            } else {
                $_SESSION['error'] = 'Incorrect login or password!';
                header('Location: index.php');
            }
        } else {
            $_SESSION['error'] = 'Incorrect login or password!';
            header('Location: index.php');
        }
    }
    $link->close();
}
