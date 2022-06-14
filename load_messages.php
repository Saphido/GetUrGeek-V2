<?php
include_once 'src/libs/pdo.php';
include_once 'src/libs/db_easy.php';

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


updateInTable($pdo, 'messages', ['lu'], ['0'], ['idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $get_id]);

foreach ($showMessages as $sm) {
        echo '<div class="messages__chat__element-other">';
    if (is_dir("src/img/users-img/user_" . $get_id . "/")) {
        $src = 'src/img/users-img/user_' . $get_id . '/pp.png';
    } else {
        $src = 'src/img/profile/default.png';
    }
    echo '<img class="messages__chat__element-other__image" src="' . $src . '">
<p class="messages__chat__element-other__message">' . nl2br($sm['message']) . '</p>
</div>';
}
?>