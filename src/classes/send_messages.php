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
insertInTable($pdo, 'messages', ['idUserSender', 'idUserReceiver', 'idMatch', 'message', 'lu'], [$_SESSION['user_login'], $get_id, $match['id'], $message, '1']);

?>


<div class="messages__chat__element-me">
    <?php
    if (is_dir("../img/users-img/user_" . $_SESSION["user_login"] . "/")) {
        $src = 'src/img/users-img/user_' . $_SESSION["user_login"] . '/pp.png';
    } else {
        $src = 'src/img/profile/default.png';
    }
    ?>
    <img class="messages__chat__element-me__image" src="<?php echo $src; ?>">
    <p class="messages__chat__element-me__message"><?php echo nl2br($get_message) ?></p>
</div>