<?php
include 'src/include/header.php';

if (!isset($_SESSION["user_login"])) {
    echo ("<script>location.href = 'login.php';</script>");
}

updateInTable($pdo, 'notifications', ['lu'], ['1'], ['id_user'], [$_SESSION['user_login']]);

//SELECT USER INFO
$req_userlogin = selectInTable(
    $pdo,
    'user',
    [
        'username', 'user_rank', 'verified', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
        'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
        'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
        'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
        'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
        'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
        'science_affinity', 'music_affinity'
    ],
    ['user_id'],
    [$_SESSION["user_login"]],
    []
);

$userlogin = $req_userlogin->fetch();

//COUNT AND SELECT NOTIFICATIONS
$req_selectNotifications = selectInTableWithOrderAndLimit($pdo, 'notifications', [], ['id_user'], [$_SESSION['user_login']], [], 'id', 'DESC', '15');
$selectNotifications = $req_selectNotifications->fetchAll();
$countNotifications = count($selectNotifications);

//DELETE NOTIFICATIONS SYSTEM

if (isset($_POST['deleteOne'])) {
    $idNotif = $_POST['notifId'];

    if (is_numeric($idNotif)) {
        deleteInTable($pdo, 'notifications', ['id'], [$idNotif]);
        echo "<meta http-equiv='refresh' content='0'>";
    }
}

if (isset($_POST['deleteAll'])) {

    deleteInTable($pdo, 'notifications', ['id_user'], [$_SESSION['user_login']]);
    echo "<meta http-equiv='refresh' content='0'>";
}

?>

<section class="login-hero">
    <h1 class="login-hero__title">Notifications</h1>
    <p class="login-hero__text">HOME - Notifications</p>
</section>

<div class="notification-container">
    <div class="notification-container__titleblock">
        <h3 class="notification-container__titleblock__title">My notifications</h3>
        <div class="notification-container__titleblock__buttonarea">
            <?php
            if ($countNotifications >= 2) {
            ?>
                <form action="notifications.php" method="POST">
                    <button type="submit" name="deleteAll" class="notification-container__titleblock__button">Delete all</button>
                </form>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="notification-container__notifarea">
        <ul class="notification-container__notifarea__list">
            <?php
            if ($countNotifications <= 0) {
                echo '<h3 class="notification-container__titleblock__title" style="color: #FFFFFF;">No notifications yet</h3>';
            } else {
                foreach ($selectNotifications as $sn) {

                    //NEW MATCH STYLE
                    if ($sn['subject'] == 'NEW MATCH') {
            ?>
                        <li class="notification-container__notifarea__items">
                            <div class="notification-container__notifarea__items__block">
                                <a href="messages.php?userId=<?php echo $sn['relation_id'] ?>">
                                    <div class="notification-container__notifarea__items__flexcollumn">
                                        <p class="notification-container__notifarea__items__title"><?php echo $sn['subject']; ?></p>
                                        <p class="notification-container__notifarea__items__date"><?php echo $sn['date']; ?></p>
                                        <p class="notification-container__notifarea__items__text"><?php echo $sn['text']; ?></p>
                                    </div>
                                </a>
                                <div class="notification-container__notifarea__items__buttonarea">
                                    <form action="notifications.php" method="POST">
                                        <input hidden type="number" name="notifId" value="<?php echo $sn['id']; ?>">
                                        <button class="hiddenBtn" type="submit" name="deleteOne"><i class="fa-solid fa-trash fa-3x notif-delete"></i></button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        <?php
                    } else if ($sn['subject'] == 'YOU GET LIKED') {

                        //IF USER HAVE PERM TO SEE LIKE
                        if ($userlogin['user_rank'] === 2 || $userlogin['user_rank'] === 3) {

                        ?>
                            <li class="notification-container__notifarea__items">
                                <div class="notification-container__notifarea__items__block">
                                    <a href="https://geturgeek.com/user_profile.php?userId=<?php echo $sn['relation_id'] ?>">
                                        <div class="notification-container__notifarea__items__flexcollumn">
                                            <p class="notification-container__notifarea__items__title"><?php echo $sn['subject']; ?></p>
                                            <p class="notification-container__notifarea__items__date"><?php echo $sn['date']; ?></p>
                                            <p class="notification-container__notifarea__items__text"><?php echo $sn['text']; ?></p>
                                        </div>
                                    </a>
                                    <div class="notification-container__notifarea__items__buttonarea">
                                        <form action="notifications.php" method="POST">
                                            <input hidden type="number" name="notifId" value="<?php echo $sn['id']; ?>">
                                            <button class="hiddenBtn" type="submit" name="deleteOne"><i class="fa-solid fa-trash fa-3x notif-delete"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        <?php

                        } else {

                        ?>
                            <li class="notification-container__notifarea__items">
                                <div class="notification-container__notifarea__items__block">
                                    <a href="subscription.php">
                                        <div class="notification-container__notifarea__items__flexcollumn">
                                            <p class="notification-container__notifarea__items__title"><?php echo $sn['subject']; ?></p>
                                            <p class="notification-container__notifarea__items__date"><?php echo $sn['date']; ?></p>
                                            <p class="notification-container__notifarea__items__text">It looks like you made a good impression, <span style="color: #7EFF7B;">Someone</span> just liked you !</p>
                                            <p class="notification-container__notifarea__items__text"><span style="color: #7EFF7B;">Click here</span> to discover who is the mysterious liker !</p>
                                        </div>
                                    </a>
                                    <div class="notification-container__notifarea__items__buttonarea">
                                        <form action="notifications.php" method="POST">
                                            <input hidden type="number" name="notifId" value="<?php echo $sn['id']; ?>">
                                            <button class="hiddenBtn" type="submit" name="deleteOne"><i class="fa-solid fa-trash fa-3x notif-delete"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </li>
            <?php
                        }
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>

<script>
    window.onload = function() {
        if (!window.location.hash) {
            window.location = window.location + '#loaded';
            window.location.reload();
        }
    }
</script>

<?php
include 'src/include/footer.php';
?>