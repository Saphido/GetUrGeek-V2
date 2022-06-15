<?php
include 'src/include/header.php';

$req_nbConversation =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2'], [$_SESSION['user_login'], $_SESSION['user_login']], ['OR']);

if (isset($_GET['userId'])) {
    if (verifyMatch($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        $match =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$_SESSION['user_login'], $_GET['userId'], $_GET['userId'], $_SESSION['user_login']], ['AND', 'OR', 'AND']);
        $match = $match->fetch();

        //Number of message to display
        $totalMessageToDisplay = 15;
        $req = selectInTableWithCount($pdo, 'messages', 'id', 'NbMessage', [], ['idMatch'], [$match['id']], []);
        $numberOfMessages = $req->fetch();
        $verify_numberOfMessages = 0;

        if (($numberOfMessages['NbMessage'] - $totalMessageToDisplay) > 0) {
            $verify_numberOfMessages = ($numberOfMessages['NbMessage'] - $totalMessageToDisplay);
        }

        $messages = selectInTableWithOrderAndLimit($pdo, 'messages', [], ['idMatch'], [$match['id']], [], 'date', ' ' . $verify_numberOfMessages . ', ' . $totalMessageToDisplay);

        $req_username = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_GET['userId']], []);
        $username = $req_username->fetch();

        updateInTable($pdo, 'messages', ['lu'], ['0'], ['idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $_GET['userId']]);

?>
        <!-- IF USERS HAVE MATCH -->
        <section class="login-hero">
            <h1 class="login-hero__title">Messages</h1>
            <p class="login-hero__text">HOME - Messages</p>
        </section>

        <section class="messages">
            <div class="messages__assembly">

                <div class="messages__matches__titlearea">
                    <h3 class="messages__matches__titlearea__title">Matches</h3>
                </div>
                <div class="messages__matches">
                    <?php
                    while ($nbConversation = $req_nbConversation->fetch()) {
                        if ($nbConversation['idUser1'] != $_SESSION['user_login']) {
                            $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser1']], ['']);
                            $convUsername = $req->fetch();
                        } else if ($nbConversation['idUser2'] != $_SESSION['user_login']) {
                            $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser2']], ['']);
                            $convUsername = $req->fetch();
                        }

                    ?>
                        <div class="messages__matches__element" onclick="window.location.href='messages.php?userId=<?php echo $convUsername['user_id'] ?>'">
                            <img class="messages__matches__element__image" src="
                <?php
                        if (is_dir("src/img/users-img/user_" . $convUsername["user_id"] . "/")) {
                            echo 'src/img/users-img/user_' . $convUsername['user_id'] . '/pp.png';
                        } else {
                            echo 'src/img/profile/default.png';
                        }
                ?>">
                            <p class="messages__matches__element__pseudo"><?php echo $convUsername['username']; ?></p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="messages__assembly">
                <div class="messages__chat__titlearea">
                    <h3 class="messages__chat__titlearea__title"><?php echo $username['username']; ?></h3>
                </div>
                <div class="messages__chat" id="msg">

                    <?php

                    if ($numberOfMessages['NbMessage'] > $totalMessageToDisplay) {
                    ?>
                        <button id="seeMore" class="buttonSeeMoreMessages">Voir plus</button>

                    <?php
                    }
                    ?>
                    <div id="see-more-messages"></div>
                    <?php
                    while ($message = $messages->fetch()) {
                        if (is_dir("src/img/users-img/user_" . $message["idUserSender"] . "/")) {
                            $src = 'src/img/users-img/user_' . $message["idUserSender"] . '/pp.png';
                        } else {
                            $src = 'src/img/profile/default.png';
                        }
                        if ($message['idUserSender'] == $_SESSION['user_login']) {
                            echo '<div class="messages__chat__element-me">
                            <img class="messages__chat__element-me__image" src="' . $src . '">
                            <p class="messages__chat__element-me__message">' . nl2br($message['message']) . '</p>
                            </div>';
                        } else {
                            echo '<div class="messages__chat__element-other">
                            <img class="messages__chat__element-other__image" src="' . $src . '">
                            <p class="messages__chat__element-other__message">' . nl2br($message['message']) . '</p>
                            </div>';
                        }
                    }
                    ?>
                    <div id="show-message"></div>
                    <div id="load-message"></div>
                </div>
                <div class="messages__textboxarea">
                    <img class="messages__textboxarea__emoji" src="src/img/messages/emoji.png">

                    <form method="POST" id="send">
                        <textarea class="messages__textboxarea__textarea" name="message" id="message" placeholder="Type your message here !"> </textarea>
                        <input type="submit" name="send" value="Send" />
                    </form>

                </div>
            </div>
        </section>
    <?php } else { ?>

        <!-- IF USERS DON'T HAVE MATCH -->
        <section class="login-hero">
            <h1 class="login-hero__title">Messages</h1>
            <p class="login-hero__text">HOME - Messages</p>
        </section>

        <section class="messages">
            <div class="messages__matches">
                <div class="messages__matches__titlearea">
                    <h3 class="messages__matches__titlearea__title">Matches</h3>
                </div>
                <?php
                while ($nbConversation = $req_nbConversation->fetch()) {
                    if ($nbConversation['idUser1'] != $_SESSION['user_login']) {
                        $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser1']], ['']);
                        $convUsername = $req->fetch();
                    } else if ($nbConversation['idUser2'] != $_SESSION['user_login']) {
                        $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser2']], ['']);
                        $convUsername = $req->fetch();
                    }

                ?>
                    <div class="messages__matches__element" onclick="window.location.href='messages.php?userId=<?php echo $convUsername['user_id'] ?>'">
                        <img class="messages__matches__element__image" src="
                    <?php
                    if (is_dir("src/img/users-img/user_" . $convUsername["user_id"] . "/")) {
                        echo 'src/img/users-img/user_' . $convUsername['user_id'] . '/pp.png';
                    } else {
                        echo 'src/img/profile/default.png';
                    }
                    ?>">
                        <p class="messages__matches__element__pseudo"><?php echo $convUsername['username']; ?></p>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="messages__chat">
                <div class="messages__chat__titlearea">
                    <h3 class="messages__chat__titlearea__title">Match not found</h3>
                </div>
        </section>
    <?php
    }
} else {
    ?>

    <!-- IF NO ID IN REQUEST -->
    <section class="login-hero">
        <h1 class="login-hero__title">Messages</h1>
        <p class="login-hero__text">HOME - Messages</p>
    </section>

    <section class="messages">
        <div class="messages__matches">
            <div class="messages__matches__titlearea">
                <h3 class="messages__matches__titlearea__title">Matches</h3>
            </div>
            <?php
            while ($nbConversation = $req_nbConversation->fetch()) {
                if ($nbConversation['idUser1'] != $_SESSION['user_login']) {
                    $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser1']], ['']);
                    $convUsername = $req->fetch();
                } else if ($nbConversation['idUser2'] != $_SESSION['user_login']) {
                    $req = selectInTable($pdo, 'user', ['username', 'user_id'], ['user_id'], [$nbConversation['idUser2']], ['']);
                    $convUsername = $req->fetch();
                }

            ?>
                <div class="messages__matches__element" onclick="window.location.href='messages.php?userId=<?php echo $convUsername['user_id'] ?>'">
                    <img class="messages__matches__element__image" src="
        <?php
                if (is_dir("src/img/users-img/user_" . $convUsername["user_id"] . "/")) {
                    echo 'src/img/users-img/user_' . $convUsername['user_id'] . '/pp.png';
                } else {
                    echo 'src/img/profile/default.png';
                }
        ?>">
                    <p class="messages__matches__element__pseudo"><?php echo $convUsername['username']; ?></p>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="messages__chat">
            <div class="messages__chat__titlearea">
                <h3 class="messages__chat__titlearea__title">Select a conversation</h3>
            </div>
        </div>
    </section>
<?php
}
?>

<script>
    $(document).ready(function() {

        document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;

        $('#send').on("submit", function(e) {
            e.preventDefault();

            var id;
            var message;
            id = <?= json_encode($_GET['userId'], JSON_UNESCAPED_UNICODE); ?>;
            message = document.getElementById('message').value;

            document.getElementById('message').value = '';

            if (id > 0 && message != "") {
                $.ajax({
                    url: 'src/classes/send_messages.php',
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        id: id,
                        message: message
                    },

                    success: function(data) {
                        $("#show-message").append(data);
                        document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;
                    },

                    error: function(e, xhr, s) {
                        let error = e.responseJSON;
                        if (e.status == 403 && typeof error !== 'undefined') {
                            alert('Error 403');
                        } else if (e.status == 404) {
                            alert('Error 404');
                        } else if (e.status == 401) {
                            alert('Error 401');
                        } else {
                            alert('Error Ajax');
                        }
                    },
                });
            }
        });

        var auto_loading_messages = 0;

        auto_loading_messages = clearInterval(auto_loading_messages);

        auto_loading_messages = setInterval(AutoLoadMessages, 2000);

        function AutoLoadMessages() {
            var id = <?= json_encode($_GET['userId'], JSON_UNESCAPED_UNICODE); ?>;
            if (id > 0) {
                $.ajax({
                    url: 'src/classes/load_messages.php',
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        id: id,
                    },

                    success: function(data) {
                        if (data.trim() != "") {
                            $("#show-message").append(data);
                            document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;
                        }
                    },

                    error: function(e, xhr, s) {
                        let error = e.responseJSON;
                        if (e.status == 403 && typeof error !== 'undefined') {
                            alert('Error 403');
                        } else if (e.status == 404) {
                            alert('Error 404');
                        } else if (e.status == 401) {
                            alert('Error 401');
                        } else {
                            alert('Error Ajax');
                        }
                    },
                });
            }
        }

        <?php

        if ($numberOfMessages['NbMessage'] > $totalMessageToDisplay) {

        ?>

            var req = 0;


            $('#seeMore').click(function() {

                var id;
                var el;

                req += <?= $totalMessageToDisplay ?>;
                id = <?= json_encode($_GET['userId'], JSON_UNESCAPED_UNICODE); ?>;

                $.ajax({
                    url: 'src/classes/show_more_messages.php',
                    method: 'POST',
                    dataType: 'html',
                    data: {
                        limit: req,
                        id: id
                    },

                    success: function(data) {
                        $(data).hide().appendTo("#see-more-messages").fadeIn(2000);
                        document.getElementById('see-more-messages').removeAttribute('id');
                    },

                    error: function(e, xhr, s) {
                        let error = e.responseJSON;
                        if (e.status == 403 && typeof error !== 'undefined') {
                            alert('Error 403');
                        } else if (e.status == 404) {
                            alert('Error 404');
                        } else if (e.status == 401) {
                            alert('Error 401');
                        } else {
                            alert('Error Ajax');
                        }
                    },
                });
            });
        <?php
        }
        ?>

    });
</script>

<?php
include 'src/include/footer.php';
?>