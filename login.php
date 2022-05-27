<?php
include 'src/include/header.php';
?>


<section class="login-hero">
    <h1 class="login-hero__title">Login page</h1>
    <p class="login-hero__text">HOME - Login</p>
</section>

<section class="formular">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Welcome</span> back !</h3>
    <p class="formular__text">Kindly fill in your login details to proceed</p>
    <?php
    if (isset($_POST['login'])) {
        if (empty($_POST['pseudo'])) {
            //check for empty pseudo
            echo '<p class="formular__text__signup">Username cannot be empty</p>';
        } else if (empty($_POST['password'])) {
            //check for empty email
            echo '<p class="formular__text__signup">Password cannot be empty</p>';
        } else {
            $req = selectInTable(
                $pdo,
                'user',
                ['pseudo', 'password'],
                ['pseudo'],
                ["'" . $_POST['pseudo'] . "'"]
            );
            $user = $req->fetch();
            if ($user['password'] == $_POST['password']) {
                echo 'connecté';
            } else {
                echo '<p class="formular__text__signup">Username or password incorrect !</p>';
            }
        }
    }

    ?>
    <form class="formular__form" action="index.php" method="POST">
        <input class="formular__form__input" type="text" placeholder="Username" name="pseudo">
        <input class="formular__form__input" type="password" placeholder="Password" name="password">
        <div class="formular__form__linkarea">
            <div class="formular__form__checkboxarea">
                <input type="checkbox" class="formular__form__checkboxarea__checkbox">
                <p class="formular__form__checkboxarea__text">Remember me </p>
            </div>
            <p class="formular__text__forgot"><a href="#">I forgot my password ?</a></p>
        </div>
        <input class="formular__form__button" name="login" type="submit" value="LOGIN">
    </form>
    <p class="formular__text__signup"> Don't have an account yet ? <a class="formular__link__signup" href="register.php">Sign up</a></p>
</section>


<?php
include 'src/include/footer.php';
?>