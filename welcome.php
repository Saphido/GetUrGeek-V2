<?php 
include 'src/include/header.php'
?>
<center>
    <h2>
        <?php

        if(!isset($_SESSION['user_login'])) {
            header("location: index.php");
        }

        $req = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_SESSION["user_login"]] , '');
        $row = $req->fetch();

        if(isset($_SESSION['user_login'])) {
            ?> Welcome,
            <?php
                echo $row['username'];
        }
        ?>
    </h2>

    <a href="logout.php">Logout</a>
</center>