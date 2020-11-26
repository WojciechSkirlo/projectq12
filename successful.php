<?php
session_start();
if (!isset($_SESSION['successful_registration'])) {
    header('Location: index.php');
    exit();
} else {
    unset($_SESSION['successful_registration']);
}

//Delete variables from form
if (isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
if (isset($_SESSION['fr_password'])) unset($_SESSION['fr_password']);
if (isset($_SESSION['fr_repeat_password'])) unset($_SESSION['fr_repeat_password']);

//Delete error from registration
if (isset($_SESSION['e_login'])) unset($_SESSION['e_login']);
if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>ProjectQ12 | Successful Registration</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    <link rel="stylesheet" href="css/normalize.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>

    Successful Registration!
    <a href="index.php">Sign in</a>

</body>

</html>