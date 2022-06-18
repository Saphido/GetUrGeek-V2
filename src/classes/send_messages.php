<?php
include_once '../libs/pdo.php';
include_once '../libs/db_easy.php';

if (!isset($_SESSION['user_login'])) {
    exit;
}

$get_id = (int) $_POST['id'];
$get_message = (string) urldecode(trim($_POST['message']));
$message = convertStringToDB($get_message);

if ($get_id <= 0 || empty($message)) {
    exit;
}

if (!verifyMatch($pdo, $_SESSION['user_login'], $get_id)) {
    exit;
}
$match =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$_SESSION['user_login'], $get_id, $get_id, $_SESSION['user_login']], ['AND', 'OR', 'AND']);
$match = $match->fetch();


$req_myusername = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_SESSION['user_login']], []);
$myusername = $req_myusername->fetch();
insertInTable($pdo, 'messages', ['idUserSender', 'idUserReceiver', 'idMatch', 'message', 'lu'], [$_SESSION['user_login'], $get_id, $match['id'], $message, '1']);

?>

<li class="clearfix">
    <div class="message-data align-right">
        <span class="message-data-time"><?php echo dateMessage(date("Y-m-d H:i:s"))?></span> &nbsp; &nbsp;
        <span class="message-data-name"><?php echo $myusername['username']; ?></span> <i class="fa fa-circle me"></i>
    </div>
    <div class="message other-message float-right">
        <?php echo nl2br($get_message); ?>
    </div>
</li>