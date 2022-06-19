<?php
include_once '../libs/pdo.php';
include_once '../libs/db_easy.php';

if (!isset($_SESSION['user_login'])) {
    exit;
}

$get_id = (int) $_POST['id'];


if ($get_id <= 0) {
    exit;
}

if (!verifyMatch($pdo, $_SESSION['user_login'], $get_id)) {
    exit;
}

$req = selectInTable($pdo, 'messages', [], ['idUserReceiver', 'idUserSender', 'lu'], [$_SESSION['user_login'], $get_id, '1'], 'AND');
$showMessages = $req->fetchAll();

$req_username = selectInTable($pdo, 'user', ['username'], ['user_id'], [$get_id], []);
$username = $req_username->fetch();


updateInTable($pdo, 'messages', ['lu'], ['0'], ['idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $get_id]);


foreach ($showMessages as $sm) {
    ?>
    <li>
    <div class="message-data">
        <span class="message-data-name"><i class="fa fa-circle online"></i><?php echo $username['username']; ?></span>
        <span class="message-data-time"><?php echo dateMessage($sm['date'])?></span>
    </div>
    <div class="message my-message">
        <?php echo nl2br($sm['message']); ?>
    </div>
</li>

<?php
}
?>