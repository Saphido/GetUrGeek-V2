<?php
include 'src/include/header.php';

$req_account = selectInTable($pdo, 'user', ['user_id'], [], [], []);
$data_account = 0;
while ($req_account->fetch()) {
    $data_account++;
}

$req_matches = selectInTable($pdo, 'matches', ['id'], [], [], []);
$data_matches = 0;
while ($req_matches->fetch()) {
    $data_matches++;
}

$req_messages = selectInTable($pdo, 'messages', ['id'], [], [], []);
$data_messages = 0;
while ($req_messages->fetch()) {
    $data_messages++;
}

?>
<!-- MAIN -->
<main>
    <section class="whatis">
        <h1 class="whatis__title">What is <span style="color: #7EFF7B;">GetUrGeek</span> ?</h1>
        <p class="whatis__text">Simply, the first free chatting, meeting and dating site for the Geek community.
            Let’s create your account and join us, it’s totally free and no subscription needed to message each others.
        </p>
        <?php
        if (!isset($_SESSION["user_login"])) {
        ?>
            <button class="whatis__button" onclick="window.location.href='register.php'">REGISTER NOW !</button>
        <?php
        }
        ?>

        <img class="whatis__image" alt="Girl and boy are speaking together in a gaming room" src="src/img/index/image_gamerRoom.png">
    </section>

    <section class="carousel">
        <div class="carousel__inner">
            <div class="carousel__item">
                <img class="carousel__image" alt="Shield with hand and money inside" src="src/img/index/free-img.png">
                <div class="carousel__textarea">
                    <h2 class="carousel__textarea__title">Totally <span style="color: #7EFF7B;">free</span> !</h3>
                        <p class="carousel__textarea__text">
                            Our website is totally free to use but we also need to pay the server where it is hosted.
                            That’s why we need to have some ads on our site. We know it is annoying sometimes, but to garentee a
                            long life to this platform we don’t have the choice, so please, disable your adblocker on our website.</i>
                        </p>
                </div>
            </div>
            <div class="carousel__item">
                <img class="carousel__image" alt="Gamepad & cinema tickets" src="src/img/index/passion-img.png">
                <div class="carousel__textarea">
                    <h2 class="carousel__textarea__title">Find <span style="color: #7EFF7B;">someone</span> with the same <span style="color: #7EFF7B;">passions</span></h3>
                        <p class="carousel__textarea__text">
                            Do you like video games ? Cosplay ? Anime ? Or something else ? You will probably find someone with the same hobbies.
                            Complete your profile with all the things you like and let us make for you a selection of our best profile.
                        </p>
                </div>
            </div>
        </div>
        <div class="carousel__controls">
            <span class="carousel__controls__prev"></span>
            <span class="carousel__controls__next"></span>
        </div>
        <div class="carousel__indicators"></div>
    </section>

    <section class="community">
        <h3 class="community__title">Our <span style="color: #7EFF7B;">community</span> stats</h3>
        <div class="community__blockarea">
            <div class="community__blockarea__blocks">
                <img class="community__blockarea__blocks__imageOne" alt="Members icon" src="src/img/index/member_icon.png">
                <h3 class="community__blockarea__blocks__title">MEMBERS</h3>
                <p class="community__blockarea__blocks__number"><?php echo $data_account; ?></p>
            </div>
            <div class="community__blockarea__blocks">
                <img class="community__blockarea__blocks__imageTwo" alt="Matches icon" src="src/img/index/matches_icon.png">
                <h3 class="community__blockarea__blocks__title">MATCHES</h3>
                <p class="community__blockarea__blocks__number"><?php echo $data_matches; ?></p>
            </div>
            <div class="community__blockarea__blocks">
                <img class="community__blockarea__blocks__imageThree" alt="Message icon" src="src/img/index/message_icon.png">
                <h3 class="community__blockarea__blocks__title">MESSAGES</h3>
                <p class="community__blockarea__blocks__number"><?php echo $data_messages; ?></p>
            </div>
        </div>
    </section>

</main>
<?php
include 'src/include/footer.php'
?>