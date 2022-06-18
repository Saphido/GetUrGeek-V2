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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="src/css/style.min.css">
    <link href="src/js/nouislider/nouislider.min.css" rel="stylesheet">
    <script src="src/js/nouislider/nouislider.min.js"></script>
    <script src="src/js/wNumb.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.5.0/croppie.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.5.0/croppie.js"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5208337092315292"
     crossorigin="anonymous"></script>
    <title>GetUrGeek - Dating website</title>

<!--     <script>
        var inactivityTime = function() {
            var time;
            window.onload = resetTimer;

            document.onmousemove = resetTimer;
            document.onkeydown = resetTimer;

            function logout() {
                location.href = 'logout.php'
            }

            function resetTimer() {
                clearTimeout(time);
                time = setTimeout(logout, 1800000)
            }
        };

        <?php
        if (isset($_SESSION['user_login'])) {
            echo 'window.onload = function() {
                    inactivityTime();
                }';
        }
        ?>
    </script> -->

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
                <?php
                if (!isset($_SESSION["user_login"])) {
                ?>
                    <li class="header__nav__items"><button class="header__nav__buttons" onclick="window.location.href='register.php'">REGISTER</button></li>
                    <li class="header__nav__items"><button class="header__nav__buttons " onclick="window.location.href='login.php'">LOGIN</button></li>
                <?php
                } else {
                ?>
                    <li class="header__nav__items"><a class="header__nav__links" href="index.php">MATCH</a></li>
                    <li class="header__nav__items"><a class="header__nav__links" href="search_profile.php">SEARCH</a></li>
                    <li class="header__nav__items">
                        <div class="dropdown">
                            <p class="dropbtn">MY PROFILE <i class="drop-icon fa-solid fa-caret-down"></i></p>
                            <div class="dropdown-content">
                                <a href="profile.php">SEE PROFILE</a>
                                <a href="edit_profile.php">EDIT PROFILE</a>
                                <a href="messages.php">MY MESSAGES</a>
                                <a href="logout.php">LOGOUT</a>
                            </div>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </header>