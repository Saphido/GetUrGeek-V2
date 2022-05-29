<?php
include 'src/include/header.php';

/*     if(isset($_SESSION["user_login"])) { //Check if user session is open, redirect to index.php
        header("location: index.php");
    } */

if (isset($_POST['reset'])) {
    //Get the email that is being searched for.
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    //The simple SQL query that we will be running.
    $req = selectInTable($pdo, 'user', ['user_id', 'email'], ['email'], ["'" . $_POST['email'] . "'"], '');

    //Fetch our result as an associative array.
    $userInfo = $req->fetch();

    //If $userInfo is empty, it means that the submitted email
    //address has not been found in our users table.
    if (empty($userInfo)) {
        $errorMsg = "That email address was not found in our system!";
    } else {

        //The user's email address and id.
        $userEmail = $userInfo['email'];
        $userId = $userInfo['user_id'];
        $date_requested = date("Y-m-d H:i:s");

        //Create a secure token for this forgot password request.
        $token = openssl_random_pseudo_bytes(16);
        $token = bin2hex($token);

        //The SQL statement.
        $insertSql = "INSERT INTO password_reset_request
                (user_id, date_requested, token)
                VALUES
                (:user_id, :date_requested, :token)";
        $req = insertInTable($pdo, 'password_reset_request', ['user_id', 'date_requested', 'token'], [$userId, $date_requested, $token]);

        //Get the ID of the row we just inserted.
        $passwordRequestId = $pdo->lastInsertId();


        //Create a link to the URL that will verify the
        //forgot password request and allow the user to change their
        //password.
        $verifyScript = '127.0.0.1/forgot_pass.php';

        //The link that we will send the user via email.
        $linkToSend = $verifyScript . '?uid=' . $userId . '&id=' . $passwordRequestId . '&t=' . $token;

        //Print out the email for the sake of this tutorial.
                //Print out the email for the sake of this tutorial.



                $to = $_POST["email"];
                $subject = "Reset password link - GetUrGeek";
                 
                $message = "<b>Here is your link to reset your password</b>";
                $message .= $linkToSend;
                 
                $header = "From:abc@somedomain.com \r\n";
                $header .= "Cc:afgh@somedomain.com \r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html\r\n";
                 
                $retval = mail($to,$subject,$message,$header);
                 
                if( $retval == true ) {
                    $successMsg = "A mail with the link to reset your password has been send, please check your mailbox :)";
                }else {
                    $errorMsg = "An error as occured while sending the mail, please try again or contact the support";
                } 



        echo $linkToSend;
        echo "\n";
        echo $_POST["email"];
        $successMsg = "A mail with the link to reset your password has been send, please check your mailbox :)";
    }
}
?>

<section class="formular" style="margin-top: 10rem;">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Forgot</span> your password ?</h3>
    <p class="formular__text">No problem, let's change it !</p>
    <?php
    if (isset($errorMsg)) {
    ?>
        <div class="alert alert-danger">
            <strong><?php echo $errorMsg; ?></strong>
        </div>
    <?php
    }

    if (isset($successMsg)) {
    ?>
        <div class="alert alert-sucess">
            <strong><?php echo $successMsg; ?></strong>
        </div>
    <?php
    }
    ?>
    <form class="formular__form" method="POST">
        <input class="formular__form__input" type="text" placeholder="Email" name="email">

        <input class="formular__form__button" name="reset" type="submit" value="RESET PASSWORD">
    </form>
    <p class="formular__text__signup"> Don't have an account yet ? <a class="formular__link__signup" href="register.php">Sign up</a></p>
</section>


<?php
include 'src/include/footer.php';
?>