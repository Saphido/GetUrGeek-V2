<?php
include 'src/include/header.php';

if (!isset($_SESSION["user_login"])) {
    echo ("<script>location.href = 'login.php';</script>");
}

updateInTable($pdo, 'notifications', ['lu'], ['1'], ['id_user'], [$_SESSION['user_login']]);

$req_selectNotifications = selectInTableWithOrderAndLimit($pdo, 'notifications', [], ['id_user'], [$_SESSION['user_login']], [], 'date', 'ASC', '15');
$selectNotifications = $req_selectNotifications->fetchAll();

?>

<section class="login-hero">
    <h1 class="login-hero__title">Notifications</h1>
    <p class="login-hero__text">HOME - Notifications</p>
</section>

<div class="notification-container">
    <div class="notification-container__titleblock">
        <h3 class="notification-container__titleblock__title">My notifications</h3>
        <div class="notification-container__titleblock__buttonarea">
            <button class="notification-container__titleblock__button">Delete all</button>
        </div>
    </div>
    <div class="notification-container__notifarea">
        <ul class="notification-container__notifarea__list">
            <?php
            foreach ($selectNotifications as $sn) {
            ?>
                <li class="notification-container__notifarea__items">
                    <div class="notification-container__notifarea__items__block">
                        <a href="#">
                            <div class="notification-container__notifarea__items__flexcollumn">
                                <p class="notification-container__notifarea__items__title"><?php echo $sn['subject']; ?></p>
                                <p class="notification-container__notifarea__items__date"><?php echo $sn['date']; ?></p>
                                <p class="notification-container__notifarea__items__text"><?php echo $sn['text']; ?>
                            </div>
                        </a>
                        <div class="notification-container__notifarea__items__buttonarea">
                            <i class="fa-solid fa-trash fa-3x notif-delete"></i>
                        </div>
                    </div>
                </li> 
            <?php
            }
            ?>
        </ul>
    </div>
</div>

<?php
include 'src/include/footer.php';
?>