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



?>

<section class="login-hero">
    <h1 class="login-hero__title">My profile</h1>
    <p class="login-hero__text">HOME - My profile</p>
</section>



<section class="profile-info">
    <div class="profile-header">
        <div class="profile-header__block">
            <img class="profile-header__block__image" src="<?php echo 'src/img/users-img/user_' . $_SESSION['user_login'] . '/pp.png' ?>">
            <h1 class="profile-header__block__username"><?php echo $user['username']; ?></p>
                <p class="profile-header__block__infos">(<?php echo $age; ?> ans)</p>
                <p class="profile-header__block__infos"><?php echo $user['city'] . ', ' . $pays['nom_en_gb']; ?></p>
        </div>
    </div>
    <div class="profile-info__nav">
        <ul class="profile-info__nav__list">
            <li class="profile-info__nav__items"><a class="profile-info__nav__links" id="nav_global" onclick="openGlobal()">GLOBAL</a></li>
            <li class="profile-info__nav__items"><a class="profile-info__nav__links" id="nav_games" onclick="openGames()">GAMES TAG</a></li>
            <li class="profile-info__nav__items"><a class="profile-info__nav__links" id="nav_gallery" onclick="openGallery()">GALLERY</a></li>
        </ul>
    </div>

    <div class="profile-info__global" id="global">
        <div class="profile-info__items">
            <?php
            if ($user['biography'] == NULL) {
            } else { ?>
                <p class="profile-info__title">Biography</p>
                <p class="profile-info__text">
                <?php
                echo $user['biography'];
            } ?>
                </p>
        </div>
        <div class="profile-info__items">
            <p class="profile-info__title">Is here for</p>
            <p class="profile-info__text">
                <?php echo $herefor['herefor_en']; ?>
            </p>
        </div>
        <div class="profile-info__items">
            <p class="profile-info__title">Is looking for</p>
            <p class="profile-info__text">
                <?php echo $lookingfor['lookingfor_en']; ?>
            </p>
        </div>
        <div class="profile-info__items">
            <?php
            if ($user['id_children'] == 0) {
            } else { ?>
                <p class="profile-info__title">Children</p>
                <p class="profile-info__text">
                <?php echo $children['children_en'];
            } ?>
                </p>
        </div>
        <div class="profile-info__items">
            <?php
            if ($user['id_drink'] == 0) {
            } else { ?>
                <p class="profile-info__title">Alcohol</p>
                <p class="profile-info__text">
                <?php echo $drink['drink_en'];
            } ?>
                </p>
        </div>
        <div class="profile-info__items">
            <?php
            if ($user['id_smoker'] == 0) {
            } else { ?>
                <p class="profile-info__title">Alcohol</p>
                <p class="profile-info__text">
                <?php echo $smoker['smoker_en'];
            } ?>
                </p>
        </div>
    </div>

    <div class="profile-info__skills" id="games">
        <div class="profile-info__items">
            <p class="profile-info__title">Video games</p>
            <p class="profile-info__text">100%</p>
        </div>
        <div class="profile-info__items">
            <p class="profile-info__title">Cosplay</p>
            <p class="profile-info__text">5%</p>
        </div>
    </div>

    <div class="profile-info__gallery" id="gallery">
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
<script>
    var global = document.getElementById("global");
    var skills = document.getElementById("skills");
    var gallery = document.getElementById("gallery");
    var nav_global = document.getElementById("nav_global")
    var nav_skills = document.getElementById("nav_skills")
    var nav_gallery = document.getElementById("nav_gallery")

    function openGlobal() {

        global.style.display = "flex";
        nav_global.style.color = "#7EFF7B";
        games.style.display = "none";
        nav_games.style.color = "white";
        gallery.style.display = "none";
        nav_gallery.style.color = "white";
    }

    function openGames() {

        games.style.display = "flex";
        nav_games.style.color = "#7EFF7B";
        global.style.display = "none";
        nav_global.style.color = "white";
        gallery.style.display = "none";
        nav_gallery.style.color = "white";
    }

    function openGallery() {

        gallery.style.display = "flex";
        nav_gallery.style.color = "#7EFF7B";
        games.style.display = "none";
        nav_games.style.color = "white";
        global.style.display = "none";
        nav_global.style.color = "white";
    }
</script>
<?php
include 'src/include/footer.php';
?>