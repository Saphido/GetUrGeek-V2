<?php
include 'src/libs/db_easy.php';
include 'src/libs/pdo.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GetUrGeek is the first totally free chatting, meeting and dating website made for the Geek community. Let’s create your account and join us, it’s totally free and no subscription needed to message each others. ">
    <link rel="stylesheet" href="src/css/style.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>GetUrGeek - Dating website</title>
</head>

<body>

    <!-- Navbar -->
    <header class="header">
        <a class="logo-link" href="index.php"><img class="header__logo" alt="Logo of Get Ur Geek" src="src/img/global/Logo.png"></a>
        <nav class="header__nav__url">
            <ul class="header__nav__list__link">
                <li class="header__nav__items"><a class="header__nav__links" href="index.php">HOME</a></li>
                <li class="header__nav__items"><a class="header__nav__links" href="index.php">CONTACT US</a></li>
            </ul>
        </nav>
        <nav class="header__nav_button">
            <ul class="header__nav__list__button">
                <li class="header__nav__items"><button class="header__nav__buttons" onclick="window.location.href='register.php'">REGISTER</button></li>
                <li class="header__nav__items"><button class="header__nav__buttons " onclick="window.location.href='login.php'">LOGIN</button></li>
            </ul>
        </nav>
    </header>