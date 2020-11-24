<?php
// session_start();

if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
    header('Location: index.php');
    exit();
}

// require_once "connect.php";

$link = @new mysqli($host, $db_user, $db_password, $db_name);
if ($link->connect_errno != 0) {
    echo "Error: " . $link->connect_errno;
} else {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $login = htmlentities($login, ENT_QUOTES, "utf-8");

    if ($rezultat = @$link->query(sprintf(
        "SELECT * FROM uzytkownicy WHERE user='%s'",
        mysqli_real_escape_string($link, $login)
    ))) {
        $ilu_userow = $rezultat->num_rows;
        if ($ilu_userow > 0) {
            $wiersz = $rezultat->fetch_assoc();
            if (password_verify($password, $wiersz['pass'])) {

                $_SESSION['zalogowany'] = true;
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['drewno'] = $wiersz['drewno'];
                $_SESSION['kamien'] = $wiersz['kamien'];
                $_SESSION['zboze'] = $wiersz['zboze'];
                $_SESSION['email'] = $wiersz['email'];
                $_SESSION['dnipremium'] = $wiersz['dnipremium'];

                unset($_SESSION['blad']);
                $rezultat->close();
                header('Location: game.php');
            } else {
                $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                header('Location: index.php');
            }
        } else {
            $_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
            header('Location: index.php');
        }
    }

    $link->close();
}
?>

<?php
session_start();

require_once "connect.php";

$link = @new mysqli("$db_server", "$db_user", "$db_password", $db_name);
if ($link->connect_errno != 0) {
    echo "Error: " . $link->connect_errno;
} else {

    $login = $_POST['login'];
    $password = $POST['password'];
    $login = htmlentities($login, ENT_QUOTES, "utf-8");
    if ($result)
        $link->close();
}

?>