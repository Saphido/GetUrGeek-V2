<?php
include 'src/include/header.php';

if (isset($_SESSION["user_login"])) { //Check if user session is open, redirect to index.php
    echo("<script>location.href = 'index.php';</script>");
}

if (isset($_POST['register'])) {
    $username = strip_tags($_POST['username']);
    $email = strip_tags($_POST['email']);
    $birth = strip_tags($_POST['birth']);
    $gender = strip_tags($_POST['gender']);
    $country = strip_tags($_POST['country']);
    $state = strip_tags($_POST['state']);
    $city = strip_tags($_POST['city']);
    $password = strip_tags($_POST['password']);
    $password_confirm = strip_tags($_POST['password_confirm']);
    $herefor = strip_tags($_POST['herefor']);
    $lookingfor = strip_tags($_POST['lookingfor']);

    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number    = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (empty($username)) {
        $errorMsg[] = "Please enter an username"; //check username not empty
    } else if (empty($email)) {
        $errorMsg[] = "Please enter an email"; //check email not empty
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMsg[] = "Please enter a valid email adress"; //check email valid
    } else if (empty($birth)) {
        $errorMsg[] = "Please select a birth date"; //check birth date not empty
    } else if (empty($password)) {
        $errorMsg[] = "Please enter a password"; //check password not empty
    } else if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $errorMsg[] = "Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character."; // check password strength
    } else if ($password != $password_confirm) {
        $errorMsg[] = "Passwords does not match"; //check passwords matching
    } else {
        try {
            $req = selectInTable($pdo, 'user', ['username', 'email'], ['username', 'email'], ["'" . $_POST['username'] . "'", "'" . $_POST['email'] . "'"], 'OR');
            $row = $req->fetch();

            if ($row["username"] == $username) {
                $errorMsg[] = "Sorry, that username already exists"; // Check if username already exist
            } else if ($row["email"] == $email) {
                $errorMsg[] = "Sorry, that email is already used"; // Check if email already exist
            } else if (!isset($errorMsg)) {
                $new_password = password_hash($password, PASSWORD_DEFAULT); //Hash password for security

                $insert_stmt = insertInTable($pdo, 'user', ['username', 'email', 'birthday', 'idgender', 'id_country', 'id_state', 'id_city', 'password', 'id_here_for', 'id_looking_for'], [$username, $email, $birth, $gender, $country, $state, $city, $new_password, $herefor, $lookingfor]);

                $registerMsg = "Register Sucessfully, redirecting...";
                echo("<script>location.href = 'login.php';</script>");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>

<section class="login-hero">
    <h1 class="login-hero__title">Login page</h1>
    <p class="login-hero__text">HOME - Register</p>
</section>

<section class="formular">
    <h3 class="formular__title">Sign up</h3>
    <p class="formular__text">It's totally <span style="color: #7EFF7B;">free</span> and it will <span style="color: #7EFF7B;">always</span> be</p>

    <?php
    if (isset($errorMsg)) {
        foreach ($errorMsg as $error) {
    ?>
            <div class="alert alert-danger">
                <strong><?php echo $error; ?></strong>
            </div>
        <?php
        }
    }
    if (isset($registerMsg)) {
        ?>
        <div class="alert alert-success">
            <strong><?php echo $registerMsg; ?> </strong>
        </div>
    <?php
    }
    ?>
    <form class="formular__form" method="POST" action="register.php" Append="?submit=true">
        <p class="formular__form__text">Username *</p>
        <input class="formular__form__input" type="text" name="username" placeholder="Username">
        <p class="formular__form__text">Email *</p>
        <input class="formular__form__input" type="text" name="email" placeholder="Email">
        <p class="formular__form__text">Birthday * </p>
        <input class="formular__form__input" type="date" name="birth" placeholder="dd-mm-yyyy" placeholder="dd-mm-yyyy">
        <p class="formular__form__text">Gender *</p>
        <select class="formular__form__input" id="select" name="gender" required>
            <option value="" selected disabled hidden>Gender...</option>
            <!-- GENERATING GENDER OPTION VIA DATABASE  (With ID's of each ones) -->
            <?php
            $req = selectInTable($pdo, 'gender', ['id', 'gender_en'], [], [], 'AND');
            while ($gender = $req->fetch()) {
                echo '<option class="formular__form__input__options" value="' . $gender['id'] . '">' . $gender['gender_en'] . '</option>';
            }

            ?>
        </select>
        <p class="formular__form__text">Here for *</p>
        <select class="formular__form__input" id="select" name="herefor" required>
            <option value="" selected disabled hidden>I'm here for...</option>
            <!-- GENERATING HEREFOR OPTION VIA DATABASE  (With ID's of each ones) -->
            <?php
            $req = selectInTable($pdo, 'herefor', ['id', 'herefor_en'], [], [], 'AND');
            while ($herefor = $req->fetch()) {
                echo '<option class="formular__form__input__options" value="' . $herefor['id'] . '">' . $herefor['herefor_en'] . '</option>';
            }

            ?>
        </select>
        <p class="formular__form__text">Interested by *</p>
        <select class="formular__form__input" id="select" name="lookingfor" required>
            <option value="" selected disabled hidden>I'm interested by...</option>
            <!-- GENERATING LOOKINGFOR OPTION VIA DATABASE  (With ID's of each ones) -->
            <?php
            $req = selectInTable($pdo, 'lookingfor', ['id', 'lookingfor_en'], [], [], 'AND');
            while ($lookingfor = $req->fetch()) {
                echo '<option class="formular__form__input__options" value="' . $lookingfor['id'] . '">' . $lookingfor['lookingfor_en'] . '</option>';
            }

            ?>
        </select>
        <p class="formular__form__text">Country *</p>
        <select name="country" class="countries formular__form__input" id="countryId">
            <option class="formular__form__input__options" value="">Select Country</option>
        </select>
        <p class="formular__form__text">State *</p>
        <select name="state" class="states formular__form__input" id="stateId">
            <option class="formular__form__input__options" value="">Select State</option>
        </select>
        <p class="formular__form__text">City *</p>
        <select name="city" class="cities formular__form__input" id="cityId">
            <option class="formular__form__input__options" value="">Select City</option>
        </select>
        <p class="formular__form__text">Password *</p>
        <p class="formular__form__text"><span style="color: #fc7b24;"> (8 characters, 1 uppercase, number and special character)</span></p>
        <input class="formular__form__input" type="password" name="password" placeholder="Password">
        <p class="formular__form__text">Confirm password *</p>
        <input class="formular__form__input" type="password" name="password_confirm" placeholder="Confirm password">
        <input class="formular__form__button" type="submit" name="register" value="REGISTER">
    </form>
    <p class="formular__text__signup"> Already a member ? <a class="formular__link__signup" href="login.php">Login</a></p>
</section>

<script src="src/js/location.js"></script>
<?php
include 'src/include/footer.php';
?>