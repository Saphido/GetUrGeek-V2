<?php
include 'src/include/header.php';

    if(isset($_SESSION["user_login"])) { //Check if user session is open, redirect to index.php
        echo("<script>location.href = 'index.php';</script>");
    }

    if(isset($_POST['login'])) {
        $username = strip_tags($_POST['username_email']);
        $email = strip_tags($_POST['username_email']);
        $password = strip_tags($_POST['password']);

        if(empty($username)) {
            $errorMsg[]="Please enter username or email"; // Checking if username/email is empty
        }
        else if(empty($email)) {
            $errorMsg[]="Please enter username or email"; // Checking if username/email is empty
        }
        else if(empty($password)) {
            $errorMsg[]="Please enter password"; // Checking if password is empty
        }
        else {
            try {
                $req = selectInTable($pdo, 'user', ['username', 'email', 'password', 'user_id'], ['username', 'email'], ["'" .$_POST['username_email'] . "'", "'" .$_POST['username_email'] . "'"], 'OR');
                $row=$req->fetch();

                if($req->rowCount() > 0) {
                    if($username == $row["username"] OR $username == $row["email"]) {
                        if(password_verify($password, $row["password"])) {
                            $_SESSION["user_login"] = $row["user_id"];
                            echo $row["user_id"];
                            echo $_SESSION["user_login"];
                            $request = updateInTable($pdo, 'user', ['isConnected'], ['1'], ['user_id'], [$_SESSION['user_login']]);

                            $loginMsg = "Successfully Login, redirecting...";
                            echo("<script>location.href = 'index.php';</script>");
                        }
                        else {
                            $errorMsg[]="Wrong password";
                        }
                    }
                    else {
                        $errorMsg[]="Wrong username or email";
                    }
                }
                else {
                    $errorMsg[]="Wrong username or email";
                }
            }
            catch(PDOException $e) {
                $e->getMessage();
            }
        }
    }

    ?>

<section class="login-hero">
    <h1 class="login-hero__title">Login page</h1>
    <p class="login-hero__text">HOME - Login</p>
</section>

<section class="formular">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Welcome</span> back !</h3>
    <p class="formular__text">Kindly fill in your login details to proceed</p>
    <?php
        if(isset($errorMsg)) {
            foreach($errorMsg as $error) {
            ?>
            <div class="alert alert-danger">
                <strong><?php echo $error; ?></strong>
            </div>
            <?php
            }
        }

        if(isset($loginMsg)) {
            ?>
            <div class="alert alert-sucess">
                <strong><?php echo $loginMsg; ?></strong>
            </div>
            <?php
        }
        ?>
    <form class="formular__form" method="POST">
        <input class="formular__form__input" type="text" placeholder="Username" name="username_email">
        <input class="formular__form__input" type="password" placeholder="Password" name="password">
        <div class="formular__form__linkarea">
            <div class="formular__form__checkboxarea">
                <input type="checkbox" class="formular__form__checkboxarea__checkbox">
                <p class="formular__form__checkboxarea__text">Remember me </p>
            </div>
            <p class="formular__text__forgot"><a href="forget_password.php">I forgot my password ?</a></p>
        </div>
        <input class="formular__form__button" name="login" type="submit" value="LOGIN">
    </form>
    <p class="formular__text__signup"> Don't have an account yet ? <a class="formular__link__signup" href="register.php">Sign up</a></p>
</section>


<?php
include 'src/include/footer.php';
?>