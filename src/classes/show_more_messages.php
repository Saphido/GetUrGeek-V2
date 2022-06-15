<?php
include_once '../libs/pdo.php';
include_once '../libs/db_easy.php';

if (!isset($_SESSION['user_login'])) {
    exit;
}

$limit = (int) trim($_POST['limit']);
$get_id = (int) urldecode(trim($_POST['id']));

if ($limit <= 0 || $get_id <= 0) {
    exit;
}

if (!verifyMatch($pdo, $_SESSION['user_login'], $get_id)) {
    exit;
}


//Number of message to display
$totalMessageToDisplay = 15;
$limit_min = 0;
$limit_max = 0;

$req = selectInTableWithCount($pdo, 'messages', 'id', 'NbMessage', [], ['idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $get_id], []);
$numberOfMessages = $req->fetch();

$limit_min = $numberOfMessages['NbMessage'] - $limit;

if ($limit_min > $totalMessageToDisplay) {
    $limit_max = $totalMessageToDisplay;
    $limit_min = $limit_min - $totalMessageToDisplay;
} else {
    if ($limit_min > 0) {
        $limit_max = $limit_min;
    } else {
        $limit_max = 0;
    }
    $limit_min = 0;
}

$req = selectInTableWithOrderAndLimit($pdo, 'messages', [], ['idUserReceiver', 'idUserSender', 'idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $get_id, $get_id, $_SESSION['user_login']], 'OR', 'date', '' . $limit_min . ', ' . $limit_max . '');
$messages = $req->fetchAll();

if ($limit_min <= 0) {
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div>
        <script>
            var el = document.getElementById('seeMore');
            el.classList.add('HideButtonSeeMoreMessages');
        </script>
    </div>
<?php
}
?>
<div id="see-more-messages"></div>

<?php
foreach ($messages as $sm) {
    if ($sm['idUserSender'] == $_SESSION['user_login']) {
        echo '<div class="messages__chat__element-me">';
    } else {
        echo '<div class="messages__chat__element-other">';
    }
    if (is_dir("src/img/users-img/user_" . $sm["idUserSender"] . "/")) {
        $src = 'src/img/users-img/user_' . $sm["idUserSender"] . '/pp.png';
    } else {
        $src = 'src/img/profile/default.png';
    }
    echo '<img class="messages__chat__element-me__image" src="' . $src . '">
<p class="messages__chat__element-me__message">' . nl2br($sm['message']) . '</p>
</div>';
}
?>