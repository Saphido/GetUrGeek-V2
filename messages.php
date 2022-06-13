<?php
include 'src/include/header.php';

if (isset($_GET['userId'])) {
    if (verifyMatch($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        $match =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$_SESSION['user_login'], $_GET['userId'], $_GET['userId'], $_SESSION['user_login']], ['AND', 'OR', 'AND']);
        $match = $match->fetch();
        $messages = selectInTable($pdo, 'messages', [], ['idMatch'], [$match['id']], []);
    }



?>
    <section class="login-hero">
        <h1 class="login-hero__title">Messages</h1>
        <p class="login-hero__text">HOME - Messages</p>
    </section>

    <section class="messages">
        <div class="messages__matches">
            <div class="messages__matches__titlearea">
                <h3 class="messages__matches__titlearea__title">Matches</h3>
            </div>
            <div class="messages__matches__element">
                <img class="messages__matches__element__image" src="src/img/users-img/user_15/pp.png">
                <p class="messages__matches__element__pseudo">Saphido</p>
            </div>
            <div class="messages__matches__element">
                <img class="messages__matches__element__image" src="src/img/users-img/user_15/pp.png">
                <p class="messages__matches__element__pseudo">Saphido</p>
            </div>
            <div class="messages__matches__element">
                <img class="messages__matches__element__image" src="src/img/users-img/user_15/pp.png">
                <p class="messages__matches__element__pseudo">Saphido</p>
            </div>
        </div>

        <div class="messages__chat">
            <div class="messages__chat__titlearea">
                <h3 class="messages__chat__titlearea__title">Saphido</h3>
            </div>
            <?php
            while ($message = $messages->fetch()) {
                if ($message['idUserSender'] == $_SESSION['user_login']) {
                    echo '<div class="messages__chat__element-me">';
                } else {
                    echo '<div class="messages__chat__element-other">';
                }
                if (is_dir("src/img/users-img/user_" . $message["idUserSender"] . "/")) {
                    $src = 'src/img/users-img/user_' . $message["idUserSender"] . '/pp.png';
                } else {
                    $src = 'src/img/profile/default.png';
                }
                echo '<img class="messages__chat__element-me__image" src="' . $src . '">
            <p class="messages__chat__element-me__message">' . $message['message'] . '</p>
            </div>';
            }
            ?>
        </div>
        <div class="messages__textboxarea">
            <img class="messages__textboxarea__emoji" src="src/img/messages/emoji.png">
            <form method="POST">
                <input type="text" class="messages__textboxarea__textarea" name="message" placeholder="Type your message here !">
                <button class="formular__form__button" name="send" type="submit">Send</button>
            </form>
        </div>
    </section>
<?php } else {
    //
}


if (isset($_POST['send'])) {
    insertInTable($pdo, 'messages', ['idUserSender', 'idMatch', 'message'], [$_SESSION['user_login'], $match['id'], $_POST['message']]);
}
?>