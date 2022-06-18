<?php
include 'src/include/header.php';

if (!isset($_SESSION["user_login"])) {
    echo ("<script>location.href = 'login.php';</script>");
}
$req_nbConversation =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2'], [$_SESSION['user_login'], $_SESSION['user_login']], ['OR']);


if (isset($_GET['userId'])) {
    if (verifyMatch($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        $matches =  selectInTableOperator($pdo, 'matches', [], ['idUser1', 'idUser2', 'idUser1', 'idUser2'], [$_SESSION['user_login'], $_GET['userId'], $_GET['userId'], $_SESSION['user_login']], ['AND', 'OR', 'AND']);
        $match = $matches->fetch();

        //Number of message to display
        $totalMessageToDisplay = 15;
        $req = selectInTableWithCount($pdo, 'messages', 'id', 'NbMessage', ['idMatch'], [$match['id']], []);
        $numberOfMessages = $req->fetch();
        $verify_numberOfMessages = 0;

        if (($numberOfMessages['NbMessage'] - $totalMessageToDisplay) > 0) {
            $verify_numberOfMessages = ($numberOfMessages['NbMessage'] - $totalMessageToDisplay);
        }

        $messages = selectInTableWithOrderAndLimit($pdo, 'messages', [], ['idMatch'], [$match['id']], [], 'date', '' . $verify_numberOfMessages . ', ' . $totalMessageToDisplay . '');
        $req_username = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_GET['userId']], []);
        $username = $req_username->fetch();

        $req_myusername = selectInTable($pdo, 'user', ['username'], ['user_id'], [$_SESSION['user_login']], []);
        $myusername = $req_myusername->fetch();

        updateInTable($pdo, 'messages', ['lu'], ['0'], ['idUserReceiver', 'idUserSender'], [$_SESSION['user_login'], $_GET['userId']]);

        $aujourdhui = date("Y-m-d H:i:s");
        $diff = date_diff(date_create($match['date']), date_create($aujourdhui));
        $datematch = $diff->format('%Y-%m-%d %H:%i:%s %Y');

?>

        <section class="login-hero">
            <h1 class="login-hero__title">My messages</h1>
            <p class="login-hero__text">HOME - My messages</p>
        </section>
        <div class="container clearfix">
            <div class="people-list" id="people-list">
                <div class="search">
                    <input type="text" id="searchbox" placeholder="search" />
                    <i class="fa fa-search"></i>
                </div>
                <ul class="list">

                    <?php
                    while ($nbConversation = $req_nbConversation->fetch()) {
                        if ($nbConversation['idUser1'] != $_SESSION['user_login']) {
                            $req = selectInTable($pdo, 'user', ['username', 'user_id', 'isConnected'], ['user_id'], [$nbConversation['idUser1']], ['']);
                            $convUsername = $req->fetch();
                        } else if ($nbConversation['idUser2'] != $_SESSION['user_login']) {
                            $req = selectInTable($pdo, 'user', ['username', 'user_id', 'isConnected'], ['user_id'], [$nbConversation['idUser2']], ['']);
                            $convUsername = $req->fetch();
                        }

                    ?>
                        <li class="clearfix" id="element" onclick="window.location.href='messages.php?userId=<?php echo $convUsername['user_id'] ?>'">
                            <img class="avatar" alt="avatar" src="
                        <?php
                        if (is_dir("src/img/users-img/user_" . $convUsername["user_id"] . "/")) {
                            echo 'src/img/users-img/user_' . $convUsername['user_id'] . '/pp.png';
                        } else {
                            echo 'src/img/profile/default.png';
                        }
                        ?>">
                            <div class="about">
                                <div class="name" id="test"><?php echo $convUsername['username']; ?></div>
                                <div class="status">
                                    <?php
                                    if ($convUsername['isConnected'] === 1) {
                                    ?>
                                        <i class="fa fa-circle online" style></i> online
                                    <?php
                                    } else {
                                    ?>
                                        <i class="fa fa-circle offline"></i> offline
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="chat">
                <div class="chat-header clearfix">
                    <img class="avatar" alt="avatar" src="
                        <?php
                        if (is_dir("src/img/users-img/user_" . $_GET['userId'] . "/")) {
                            echo 'src/img/users-img/user_' . $_GET['userId'] . '/pp.png';
                        } else {
                            echo 'src/img/profile/default.png';
                        }
                        ?>">

                    <div class="chat-about">
                        <div class="chat-with">Chat with <span style="color:#7EFF7B;"><?php echo $username['username']; ?></span></div>
                        <div class="chat-num-messages"><?php echo calculDateToString($diff) ?></div>
                    </div>
                </div> <!-- end chat-header -->

                <div class="chat-history" id="msg">
                    <ul>
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
                            if ($message['idUserSender'] == $_SESSION['user_login']) {
                        ?>
                                <li class="clearfix">
                                    <div class="message-data align-right">
                                        <span class="message-data-time"><?php echo dateMessage($message['date']) ?></span> &nbsp; &nbsp;
                                        <span class="message-data-name"><?php echo $myusername['username']; ?></span> <i class="fa fa-circle me"></i>
                                    </div>
                                    <div class="message other-message float-right">
                                        <?php echo nl2br($message['message']); ?>
                                    </div>
                                </li>
                            <?php
                            } else {
                            ?>
                                <li>
                                    <div class="message-data">
                                        <span class="message-data-name"><i class="fa fa-circle online"></i><?php echo $username['username']; ?></span>
                                        <span class="message-data-time"><?php echo dateMessage($message['date']) ?></span>
                                    </div>
                                    <div class="message my-message">
                                        <?php echo nl2br($message['message']); ?>
                                    </div>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        <div id="show-message"></div>
                        <div id="load-message"></div>

                        <!-- IS WRITING... <li>
                    <div class="message-data">
                        <span class="message-data-name"><i class="fa fa-circle online"></i> Vincent</span>
                        <span class="message-data-time">10:31 AM, Today</span>
                    </div>
                    <i class="fa fa-circle online"></i>
                    <i class="fa fa-circle online" style="color: #AED2A6"></i>
                    <i class="fa fa-circle online" style="color:#DAE9DA"></i>
                </li> -->
                    </ul>
                </div> <!-- end chat-history -->

                <div class="chat-message clearfix">
                    <form method="POST" id="send">
                        <textarea name="message-to-send" id="message" name="message" placeholder='Type your message and press "Enter"' rows="3"></textarea>
                        <input type="submit" name="send" id="btnSend" value="Send" />
                    </form>
                    <i class="fa fa-file-o"></i>
                    <i class="fa fa-file-image-o"></i>

                </div> <!-- end chat-message -->

            </div> <!-- end chat -->

        </div> <!-- end container -->
    <?php
    } else {
        echo ("<script>location.href = 'messages.php';</script>");
    }
} else {
    ?>
    <section class="login-hero">
        <h1 class="login-hero__title">My messages</h1>
        <p class="login-hero__text">HOME - My messages</p>
    </section>
    <div class="container clearfix">
        <div class="people-list" id="people-list">
            <div class="search">
                <input type="text" placeholder="search" />
                <i class="fa fa-search"></i>
            </div>
            <ul class="list">

                <?php
                while ($nbConversation = $req_nbConversation->fetch()) {
                    if ($nbConversation['idUser1'] != $_SESSION['user_login']) {
                        $req = selectInTable($pdo, 'user', ['username', 'user_id', 'isConnected'], ['user_id'], [$nbConversation['idUser1']], ['']);
                        $convUsername = $req->fetch();
                    } else if ($nbConversation['idUser2'] != $_SESSION['user_login']) {
                        $req = selectInTable($pdo, 'user', ['username', 'user_id', 'isConnected'], ['user_id'], [$nbConversation['idUser2']], ['']);
                        $convUsername = $req->fetch();
                    }

                ?>
                    <li class="clearfix" onclick="window.location.href='messages.php?userId=<?php echo $convUsername['user_id'] ?>'">
                        <img class="avatar" alt="avatar" src="
                        <?php
                        if (is_dir("src/img/users-img/user_" . $convUsername["user_id"] . "/")) {
                            echo 'src/img/users-img/user_' . $convUsername['user_id'] . '/pp.png';
                        } else {
                            echo 'src/img/profile/default.png';
                        }
                        ?>">
                        <div class="about">
                            <div class="name"><?php echo $convUsername['username']; ?></div>
                            <div class="status">
                                <?php
                                if ($convUsername['isConnected'] === 1) {
                                ?>
                                    <i class="fa fa-circle online" style></i> online
                                <?php
                                } else {
                                ?>
                                    <i class="fa fa-circle offline"></i> offline
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>

        <div class="chat">
            <div class="chat-header clearfix">
                <div class="chat-about">
                    <div class="chat-with">Select an user to <span style="color:#7EFF7B;">start chatting</span></div>
                </div>
            </div> <!-- end chat-header -->

            <div class="chat-history" id="msg">

            </div> <!-- end chat-history -->

            <div class="chat-message clearfix">
                <form method="POST" id="send">
                    <textarea name="message-to-send" id="message" name="message" placeholder="Type your message" rows="3"></textarea>
                    <input type="submit" name="send" value="Send" />
                </form>
                <i class="fa fa-file-o"></i>
                <i class="fa fa-file-image-o"></i>
            </div> <!-- end chat-message -->

        </div> <!-- end chat -->

    </div> <!-- end container -->
<?php
}
?>

<script>
    $(document).ready(function() {

        document.getElementById('msg').scrollTop = document.getElementById('msg').scrollHeight;
        document.getElementById('message')
            .addEventListener('keyup', function(event) {
                if (event.code === 'Enter') {
                    event.preventDefault();
                    document.getElementById('btnSend').click();
                }
            });

        $('#send').on("submit", function(e) {
            e.preventDefault();

            var id;
            var message;
            id = <?php echo $_GET['userId']; ?>;
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
            id = <?php echo $_GET['userId']; ?>;
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

                req += <?php echo $totalMessageToDisplay ?>;
                id = <?php echo $_GET['userId']; ?>;

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

    let cards = document.querySelectorAll('#element');
    let test = document.querySelectorAll('#test')

    function liveSearch() {
        let search_query = document.getElementById("searchbox").value;

        //Use innerText if all contents are visible
        //Use textContent for including hidden elements
        for (var i = 0; i < cards.length; i++) {
            if (test[i].innerText.toLowerCase()
                .includes(search_query.toLowerCase())) {
                cards[i].classList.remove("is-hidden");
            } else {
                cards[i].classList.add("is-hidden");
            }
        }
    }

    //A little delay
    let typingTimer;
    let typeInterval = 500;
    let searchInput = document.getElementById('searchbox');

    searchInput.addEventListener('keyup', () => {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(liveSearch, typeInterval);
    });
</script>

<?php
include 'src/include/footer.php';
?>