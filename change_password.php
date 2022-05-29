<?php
include 'src/include/header.php';

   if(!isset($_SESSION["user_id_reset_pass"])) { //Check if user session is open, redirect to index.php
        header("location: index.php");
    }

    echo $_SESSION['user_id_reset_pass'];
    if(isset($_POST['update'])) {
        $password = strip_tags($_POST['password']);
        $password_confirm = strip_tags($_POST['password_confirm']);

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if(empty($password)) {
            $errorMsg="Please enter a password"; //check password not empty
        }
        else if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $errorMsg="Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."; // check password strength
        }
        else if($password != $password_confirm) {
            $errorMsg="Passwords does not match"; //check passwords matching
        }
        else {
            try {
                    $new_password = password_hash($password, PASSWORD_DEFAULT); //Hash password for security
                    $update = updateInTable($pdo, 'user', ['password'], [$new_password], ['user_id'], [$_SESSION['user_id_reset_pass']]);
                    $remove = deleteInTable($pdo, 'password_reset_request', ['user_id'], [$_SESSION['user_id_reset_pass']]);
                    $updateMsg= "Password changed sucessfully, redirecting...";
                    header("refresh:3; login.php");
                    session_destroy();
            }
            catch(PDOException $e) {
                echo $e->getMessage();
            }
        }
    }


?>

<section class="formular" style="margin-top: 10rem;">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Reset</span> password</h3>
    <p class="formular__text">You can now create your new password</p>
    <?php
    if (isset($errorMsg)) {
    ?>
        <div class="alert alert-danger">
            <strong><?php echo $errorMsg; ?></strong>
        </div>
    <?php
    }

    if (isset($updateMsg)) {
    ?>
        <div class="alert alert-sucess">
            <strong><?php echo $updateMsg; ?></strong>
        </div>
    <?php
    }
    ?>
    <form class="formular__form" method="POST">
        <input class="formular__form__input" type="password" placeholder="Password" name="password">
        <input class="formular__form__input" type="password" placeholder="Password confirmation" name="password_confirm">

        <input class="formular__form__button" name="update" type="submit" value="RESET PASSWORD">
    </form>
    <p class="formular__text__signup"> Don't have an account yet ? <a class="formular__link__signup" href="register.php">Sign up</a></p>
</section>


<?php
include 'src/include/footer.php';
?>