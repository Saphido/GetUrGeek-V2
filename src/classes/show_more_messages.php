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

$req_matchId = selectInTableOperator($pdo, 'matches', ['id'], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$_SESSION['user_login'], $get_id, $get_id, $_SESSION['user_login']], ['AND', 'OR', 'AND']);
$matchId = $req_matchId->fetch()['id'];

//Number of message to display
$totalMessageToDisplay = 15;
$limit_min = 0;
$limit_max = 0;

$req = selectInTableWithCount($pdo, 'messages', 'id', 'NbMessage', ['idMatch'], [$matchId], []);
$numberOfMessages = $req->fetch();
//19

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

$req_username = selectInTable($pdo, 'user', ['username'], ['user_id'], [$get_id], []);
$username = $req_username->fetch();

$req_myusername = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_SESSION['user_login']], []);
$myusername = $req_myusername->fetch();

$req_messages = selectInTableWithOrderAndLimit($pdo, 'messages', [], ['idMatch'], [$matchId], [], 'date', $limit_min  . ', ' . $limit_max);

if ($limit_min <= 0) {
?>
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
while ($sm = $req_messages->fetch()) {
    if ($sm) {
        if ($sm['idMatch'] == $matchId) {
            if ($sm['idUserSender'] == $_SESSION['user_login']) {
?>
                <li class="clearfix">
                    <div class="message-data align-right">
                        <span class="message-data-time"><?php echo dateMessage($message['date'])?></span> &nbsp; &nbsp;
                        <span class="message-data-name"><?php echo $myusername['username']; ?></span> <i class="fa fa-circle me"></i>
                    </div>
                    <div class="message other-message float-right">
                        <?php echo nl2br($sm['message']); ?>
                    </div>
                </li>
            <?php
            } else {
            ?>
                <li>
                    <div class="message-data">
                        <span class="message-data-name"><i class="fa fa-circle online"></i><?php echo $username['username']; ?></span>
                        <span class="message-data-time"><?php echo dateMessage($message['date'])?></span>
                    </div>
                    <div class="message my-message">
                        <?php echo nl2br($sm['message']); ?>
                    </div>
                </li>
<?php
            }
        }
    }
}
?>