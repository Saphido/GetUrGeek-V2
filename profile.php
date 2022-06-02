<?php

include 'src/include/header.php';
if (!isset($_SESSION["user_login"])) {
    header("location: login.php");
}

$req = selectInTable(
    $pdo,
    'user',
    [
        'username', 'email', 'birthday', 'idgender', 'idcountry', 'zipcode',
        'city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
        'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
        'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
        'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
        'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
        'science_affinity', 'music_affinity'
    ],
    ['user_id'],
    [$_SESSION["user_login"]],
    []
);
$user = $req->fetch();

//CALCULATE THE AGE OF THE USER
$aujourdhui = date("Y-m-d");
$diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
$age = $diff->format('%y');

$req = selectInTable($pdo, 'pays', ['nom_en_gb'], ['id'], [$user['idcountry']], []);
$pays = $req->fetch();

$req = selectInTable($pdo, 'gender', ['gender_en'], ['id'], [$user['idgender']], []);
$gender = $req->fetch();

$req = selectInTable($pdo, 'herefor', ['herefor_en'], ['id'], [$user['id_here_for']], []);
$herefor = $req->fetch();

$req = selectInTable($pdo, 'lookingfor', ['lookingfor_en'], ['id'], [$user['id_looking_for']], []);
$lookingfor = $req->fetch();

$req = selectInTable($pdo, 'children', ['children_en'], ['id'], [$user['id_children']], []);
$children = $req->fetch();

$req = selectInTable($pdo, 'drink', ['drink_en'], ['id'], [$user['id_drink']], []);
$drink = $req->fetch();

$req = selectInTable($pdo, 'smoker', ['smoker_en'], ['id'], [$user['id_smoker']], []);
$smoker = $req->fetch();

$req = selectInTable($pdo, 'crop_images', ['title'], ['id'], ['4'], []);
$image = $req->fetch();



?>
<!-- MAIN -->
<main>
    <section class="login-hero">
        <h1 class="login-hero__title">My profile</h1>
        <p class="login-hero__text">HOME - My profile</p>
    </section>

    <section class="myprofile-hero">
        <div class="myprofile-header">
            <img class="myprofile-header__image" alt="My profile picture" src="<?php echo 'src/img/users-img/user_' . $_SESSION['user_login'] . '/pp.png' ?>">
            <div class="myprofile-header__textarea">
                <h3 class="myprofile-header__textarea__title"><span style="color: #7EFF7B;"><?php echo $user["username"]; ?></span></h3>
                <p class="myprofile-header__textarea__subtitle">(<?php echo $age; ?> ans)</p>
                <p class="myprofile-header__textarea__subtitle"><?php echo $user['city'] . ', ' . $pays['nom_en_gb']; ?></p>

                <p class="myprofile-header__textarea__text">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vestibulum, tortor a auctor tincidunt, justo arcu malesuada velit, quis fringilla risus nisl et odio. Cras dapibus quam in elit venenatis rhoncus. Aliquam orci erat, tincidunt vel posuere nec.
                </p>
            </div>
        </div>
        <div class="myprofile-attribute">
            <p class="myprofile-attribute__text">#CALIN</p>
            <p class="myprofile-attribute__text">#PATIENT</p>
            <p class="myprofile-attribute__text">#GENTIL</p>
            <p class="myprofile-attribute__text">#GENTIL</p>
        </div>
    </section>

    <section class="myprofile-nav">
        <h3 class="myprofile-nav__title"><span style="color: #7EFF7B;">About</span> me</h3>
        <div class="myprofile-nav__blockarea">
            <div class="myprofile-nav__blockarea__blocks" onclick="openGlobal()">
                <img class="myprofile-nav__blockarea__blocks__imageOne" alt="Members icon" src="src/img/profile/global.png">
                <h3 class="myprofile-nav__blockarea__blocks__title" id="nav_global">GLOBAL</h3>
            </div>
            <div class="myprofile-nav__blockarea__blocks" onclick="openGamestag()">
                <img class="myprofile-nav__blockarea__blocks__imageTwo" alt="Matches icon" src="src/img/profile/gametag.png">
                <h3 class="myprofile-nav__blockarea__blocks__title" id="nav_gamestag">GAMES TAG</h3>
            </div>
            <div class="myprofile-nav__blockarea__blocks" onclick="openGallery()">
                <img class="myprofile-nav__blockarea__blocks__imageThree" alt="Message icon" src="src/img/profile/gallery.webp">
                <h3 class="myprofile-nav__blockarea__blocks__title" id="nav_gallery">GALLERY</h3>
            </div>
        </div>
    </section>

    <section class="profile-info">
        <div class="profile-info__global" id="global">
            <h3 class="profile-info__titles">Global</h3>
            <div class="profile-info__items">
                <p class="profile-info__title">Is here for :</p>
                <p class="profile-info__text">
                    <?php echo $herefor['herefor_en']; ?>
                </p>
            </div>
            <div class="profile-info__items">
                <p class="profile-info__title">Is looking for :</p>
                <p class="profile-info__text">
                    <?php echo $lookingfor['lookingfor_en']; ?>
                </p>
            </div>
            <div class="profile-info__items">
                <?php
                if ($user['id_children'] == 0) {
                } else { ?>
                    <p class="profile-info__title">Children :</p>
                    <p class="profile-info__text">
                    <?php echo $children['children_en'];
                } ?>
                    </p>
            </div>
            <div class="profile-info__items">
                <?php
                if ($user['id_drink'] == 0) {
                } else { ?>
                    <p class="profile-info__title">Alcohol :</p>
                    <p class="profile-info__text">
                    <?php echo $drink['drink_en'];
                } ?>
                    </p>
            </div>
            <div class="profile-info__items">
                <?php
                if ($user['id_smoker'] == 0) {
                } else { ?>
                    <p class="profile-info__title">Smoker :</p>
                    <p class="profile-info__text">
                    <?php echo $smoker['smoker_en'];
                } ?>
                    </p>
            </div>
        </div>

        <div class="profile-info__skills" id="gamestag">
            <h3 class="profile-info__titles">Games Tag</h3>
            <div class="profile-info__items">
                <?php
                if (empty($user['steam_username'])) {
                } else { ?>
                    <p class="profile-info__title">Steam profile :</p>
                <?php echo '<a class="profile-info__text__link" target="_blank" href=" ' . $user['steam_username'] . '"> Link to steam profile</a>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['battlenet_username'])) {
                } else { ?>
                    <p class="profile-info__title">BATTLE.NET ID :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['battlenet_username'] . '</p>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['lol_username'])) {
                } else { ?>
                    <p class="profile-info__title">Riot Games ID :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['lol_username'] . '</p>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['psn_username'])) {
                } else { ?>
                    <p class="profile-info__title">PSN ID :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['psn_username'] . '</p>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['xbox_username'])) {
                } else { ?>
                    <p class="profile-info__title">XBOX ID :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['xbox_username'] . '</p>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['twitch_username'])) {
                } else { ?>
                    <p class="profile-info__title">Twitch channel :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['twitch_username'] . '</p>';
                } ?>

            </div>
            <div class="profile-info__items">
                <?php
                if (empty($user['youtube_username'])) {
                } else { ?>
                    <p class="profile-info__title">Youtube channel :</p>
                    <?php echo '<p class="profile-info__text"> ' .$user['youtube_username'] . '</p>';
                } ?>

            </div>
        </div>

        <div class="profile-info__gallery" id="gallery">
            <h3 class="profile-info__titles">Gallery</h3>

            <div class="profile-info__items">
                <p class="profile-info__title">Image1</p>
                <p class="profile-info__text">src/srs</p>
            </div>
            <div class="profile-info__items">
                <p class="profile-info__title">image2</p>
                <p class="profile-info__text">test</p>
            </div>
        </div>
    </section>
</main>

<script>
    var global = document.getElementById("global");
    var gamestag = document.getElementById("gamestag");
    var gallery = document.getElementById("gallery");
    var nav_global = document.getElementById("nav_global")
    var nav_gamestag = document.getElementById("nav_gamestag")
    var nav_gallery = document.getElementById("nav_gallery")

    function openGlobal() {

        global.style.display = "flex";
        nav_global.style.color = "#7EFF7B";
        gamestag.style.display = "none";
        nav_gamestag.style.color = "white";
        gallery.style.display = "none";
        nav_gallery.style.color = "white";
    }

    function openGamestag() {

        gamestag.style.display = "flex";
        nav_gamestag.style.color = "#7EFF7B";
        global.style.display = "none";
        nav_global.style.color = "white";
        gallery.style.display = "none";
        nav_gallery.style.color = "white";
    }

    function openGallery() {
        console.log(gamestag);
        gallery.style.display = "flex";
        nav_gallery.style.color = "#7EFF7B";
        gamestag.style.display = "none";
        nav_gamestag.style.color = "white";
        global.style.display = "none";
        nav_global.style.color = "white";
    }
</script>
<?php
include 'src/include/footer.php'
?>