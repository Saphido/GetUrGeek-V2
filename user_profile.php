<?php

include 'src/include/header.php';
if (!isset($_SESSION["user_login"])) {
    echo ("<script>location.href = 'login.php';</script>");
}

if ($_GET['userId'] == $_SESSION['user_login']) {
    echo ("<script>location.href = 'profile.php';</script>");
}

$req = selectInTable(
    $pdo,
    'user',
    [
        'username', 'verified', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
        'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
        'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
        'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
        'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
        'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
        'science_affinity', 'music_affinity'
    ],
    ['user_id'],
    [$_GET["userId"]],
    []
);

$user = $req->fetch();

$req_userlogin = selectInTable(
    $pdo,
    'user',
    [
        'username', 'superLike', 'verified', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
        'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
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

$userlogin = $req_userlogin->fetch();
if (isset($_POST['like'])) {
    if (verifyLiked($pdo, $_SESSION['user_login'], $_GET['userId'])) {
    } else {
        insertInTable($pdo, 'liked', ['idUserLike', 'idUserLiked'], [$_SESSION['user_login'], $_GET['userId']]);
        insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_GET['userId'], $_SESSION['user_login'], 'YOU GET LIKED', 'It looks like you made a good impression, <span style="color: #7EFF7B;">' . $userlogin['username'] . ' </span> just liked you ! ']);
    }
    if (verifyMatch($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        if (verifyAlreadyMatched($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        } else {
            insertInTable($pdo, 'matches', ['idUser1', 'idUser2'], [$_SESSION['user_login'], $_GET['userId']]);
            insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_SESSION['user_login'], $_GET['userId'], 'NEW MATCH', '<span style="color: #7EFF7B;">You</span> and <span style="color: #7EFF7B;">' . $user['username'] . ' </span> have just matched. You can now chat together ! ']);
            insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_GET['userId'], $_SESSION['user_login'], 'NEW MATCH', '<span style="color: #7EFF7B;">You</span> and <span style="color: #7EFF7B;">' . $userlogin['username'] . '</span> have just matched. You can now chat together ! ']);

            //AFFICHER LE POPUP DES MATCHS
        }
    }
}

if (isset($_POST['superlike'])) {
    if ($userlogin['superLike'] >= 1) {
        $newSuperLikeValue = $userlogin['superLike'] - 1;
        if (verifyLiked($pdo, $_SESSION['user_login'], $_GET['userId'])) {
        } else {
            insertInTable($pdo, 'liked', ['idUserLike', 'idUserLiked'], [$_SESSION['user_login'], $_GET['userId']]);
            insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_GET['userId'], $_SESSION['user_login'], 'YOU GET OVERLIKED', 'It seems that you please a lot to <span style="color: #7EFF7B;">' . $userlogin['username'] . '</span> ! ']);
            updateInTable($pdo, 'user', ['superLike'], [$newSuperLikeValue], ['user_id'], [$_SESSION['user_login']]);
        }
        if (verifyMatch($pdo, $_SESSION['user_login'], $_GET['userId'])) {
            if (verifyAlreadyMatched($pdo, $_SESSION['user_login'], $_GET['userId'])) {
            } else {
                insertInTable($pdo, 'matches', ['idUser1', 'idUser2'], [$_SESSION['user_login'], $_GET['userId']]);
                insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_SESSION['user_login'], $_GET['userId'], 'NEW MATCH', '<span style="color: #7EFF7B;">You</span> and <span style="color: #7EFF7B;">' . $user['username'] . ' </span> have just matched. You can now chat together ! ']);
                insertInTable($pdo, 'notifications', ['id_user', 'relation_id', 'subject', 'text'], [$_GET['userId'], $_SESSION['user_login'], 'NEW MATCH', '<span style="color: #7EFF7B;">You</span> and <span style="color: #7EFF7B;">' . $userlogin['username'] . '</span> have just matched. You can now chat together ! ']);

                //AFFICHER LE POPUP DES MATCHS
            }
        }
    } else {
        $errorMsg = 'It seems that you don\'t have any more overlike, you can stock up in our store';
    }
}

//CALCULATE THE AGE OF THE USER
$aujourdhui = date("Y-m-d");
$diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
$age = $diff->format('%y');

$req = selectInTable($pdo, 'countries', ['name'], ['id'], [$user['id_country']], []);
$countries = $req->fetch();

$req = selectInTable($pdo, 'states', ['name'], ['id'], [$user['id_state']], []);
$states = $req->fetch();

$req = selectInTable($pdo, 'cities', ['name'], ['id'], [$user['id_city']], []);
$cities = $req->fetch();

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

//ADJUSTING AFFINITY TO MATCH THE PROGRESSBAR SYSTEM
$videogames = $user["videogame_affinity"] * 10 . "%";
$rpg = $user["rpg_affinity"] * 10 . "%";
$anime = $user["anime_affinity"] * 10 . "%";
$comics = $user["comics_affinity"] * 10 . "%";
$cosplay = $user["cosplay_affinity"] * 10 . "%";
$series = $user["series_affinity"] * 10 . "%";
$movies = $user["movies_affinity"] * 10 . "%";
$literature = $user["literature_affinity"] * 10 . "%";
$science = $user["science_affinity"] * 10 . "%";
$music = $user["music_affinity"] * 10 . "%";

?>
<!-- MAIN -->
<main>

    <div class="popup_overlay" id="matched">
        <div class="popup-block">
            <div class="popup-matched">
                <div class="popup-content-matched">
                    <h3 class="popup-content-matched__title">It's a match !</h3>
                    <p class="popup-content-matched__text">You and <?php echo $user["username"]; ?> have liked each other.</p>
                    <div class="popup-content-matched__picturesarea">
                        <img class="popup-content-matched__picturesarea__image" alt="Matched user profile picture" src="<?php
                                                                                                                        if (is_dir("src/img/users-img/user_" . $_GET["userId"] . "/")) {
                                                                                                                            echo 'src/img/users-img/user_' . $_GET["userId"] . '/pp.png';
                                                                                                                        } else {
                                                                                                                            echo 'src/img/profile/default.png';
                                                                                                                        }
                                                                                                                        ?>">
                        <img class="popup-content-matched__picturesarea__image" alt="My profile picture" src="<?php
                                                                                                                if (is_dir("src/img/users-img/user_" . $_SESSION['user_login'] . "/")) {
                                                                                                                    echo 'src/img/users-img/user_' . $_SESSION['user_login'] . '/pp.png';
                                                                                                                } else {
                                                                                                                    echo 'src/img/profile/default.png';
                                                                                                                }
                                                                                                                ?>">
                    </div>
                    <div class="popup-content-matched__buttonarea">
                        <button class="popup-content-matched__buttonarea__button">Send message</button>
                        <button class="popup-content-matched__buttonarea__button">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="login-hero">
        <h1 class="login-hero__title"><?php echo $user["username"]; ?>'s profile</h1>
        <p class="login-hero__text">HOME - <?php echo $user["username"]; ?>'s profile</p>
        <a href="#" onclick="openMatched()">
            <li>MATCHED</li>
        </a>
    </section>

    <section class="myprofile-hero">
        <div class="myprofile-header">
            <img class="myprofile-header__image" alt="<?php echo $user["username"]; ?>'s profile picture" src="<?php
                                                                                                                if (is_dir("src/img/users-img/user_" . $_GET["userId"] . "/")) {
                                                                                                                    echo 'src/img/users-img/user_' . $_GET["userId"] . '/pp.png';
                                                                                                                } else {
                                                                                                                    echo 'src/img/profile/default.png';
                                                                                                                }
                                                                                                                ?>">
            <div class="myprofile-attribute">
                <p class="myprofile-attribute__text">#CALIN</p>
                <p class="myprofile-attribute__text">#PATIENT</p>
                <p class="myprofile-attribute__text">#GENTIL</p>
                <p class="myprofile-attribute__text">#GENTIL</p>
            </div>
            <?php
            if (isset($errorMsg)) {
            ?>
                <div class="alert alert-danger">
                    <strong><?php echo $errorMsg; ?> </strong>
                </div>
            <?php
            }
            ?>
            <form method="POST" action="user_profile.php?userId=<?php echo $_GET["userId"]; ?>">
                <button type="submit" class="like-btn" <?php if (verifyLiked($pdo, $_SESSION['user_login'], $_GET['userId'])) {
                                                            echo ' disabled ';
                                                        }
                                                        ?> name="like" value="<?php echo $_GET["userId"]; ?>">LIKE</button>
            </form>

            <form method="POST" action="user_profile.php?userId=<?php echo $_GET["userId"]; ?>">
                <button type="submit" class="like-btn" <?php if (verifyLiked($pdo, $_SESSION['user_login'], $_GET['userId'])) {
                                                            echo ' disabled ';
                                                        }
                                                        ?> name="superlike" value="<?php echo $_GET["userId"]; ?>">LIKE</button>
            </form>
            <div class="myprofile-header__textarea">
                <h3 class="myprofile-header__textarea__title"><span style="color: #7EFF7B;">
                        <?php echo $user["username"];
                        if ($user["verified"] === 0) {
                        } else {
                            echo '<i style="padding-left:2rem;" class="fa-solid fa-circle-check"></i>';
                        } ?>
                    </span></h3>
                <p class="myprofile-header__textarea__subtitle">(<?php echo $age; ?> ans)</p>
                <p class="myprofile-header__textarea__subtitle"><?php echo $cities['name'] . ', ' . $countries['name']; ?></p>

                <p class="myprofile-header__textarea__text">
                    <?php if (empty($user["biography"])) {
                        echo "User have not added his biography";
                    } else {
                        echo $user["biography"];
                    } ?>
                </p>
            </div>
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
            <?php
            if ($user['id_children'] != 0) {
            ?>
                <div class="profile-info__items">
                    <p class="profile-info__title">Children :</p>
                    <p class="profile-info__text">
                        <?php echo $children['children_en'];
                        ?>
                    </p>
                </div>
            <?php } ?>

            <?php
            if ($user['id_drink'] != 0) { ?>
                <div class="profile-info__items">
                    <p class="profile-info__title">Alcohol :</p>
                    <p class="profile-info__text">
                        <?php echo $drink['drink_en'];
                        ?>
                    </p>
                </div>
            <?php } ?>

            <?php
            if ($user['id_smoker'] != 0) { ?>
                <div class="profile-info__items">
                    <p class="profile-info__title">Smoker :</p>
                    <p class="profile-info__text">
                        <?php echo $smoker['smoker_en'];
                        ?>
                    </p>
                </div>
            <?php } ?>

            <h3 class="profile-info__titles">Hobbies affinity</h3>

            <div class="profile-info__wrapper">
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Video games : <?php echo $videogames ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $videogames ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">RPG/LARPG : <?php echo $rpg ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $rpg ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">anime/manga : <?php echo $anime ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $anime ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Comics/BD : <?php echo $comics ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $comics ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Cosplay : <?php echo $cosplay ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $cosplay ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Series/Web series : <?php echo $series ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $series ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Movies : <?php echo $movies ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $movies ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Literature : <?php echo $literature ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $literature ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items">
                    <p class="profile-info__bar-title">Science : <?php echo $science ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $science ?>"></span>
                    </div>
                </div>
                <div class="profile-info__bar-items margin-bottom">
                    <p class="profile-info__bar-title">Musics : <?php echo $music ?></p>
                    <div class="meter">
                        <span style="width: <?php echo $music ?>"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-info__skills" id="gamestag">
            <h3 class="profile-info__titles">Games Tags</h3>
            <?php
            if (empty($user['steam_username']) && empty($user['battlenet_username']) && empty($user['lol_username']) && empty($user['psn_username']) && empty($user['xbox_username']) && empty($user['twitch_username']) && empty($user['youtube_username'])) {
                echo
                '<div class="profile-info__items">
                <p class="profile-info__title" style="text-align:center">No games tags found.</p>
                </div>';
            } else {
                if (!empty($user['steam_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">Steam profile :</p>
                        <?php echo '<a class="profile-info__text__link" target="_blank" href=" ' . $user['steam_username'] . '"> Link to steam profile</a>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['battlenet_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">BATTLE.NET ID :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['battlenet_username'] . '</p>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['lol_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">Riot Games ID :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['lol_username'] . '</p>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['psn_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">PSN ID :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['psn_username'] . '</p>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['xbox_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">XBOX ID :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['xbox_username'] . '</p>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['twitch_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">Twitch channel :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['twitch_username'] . '</p>';
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (!empty($user['youtube_username'])) { ?>
                    <div class="profile-info__items">
                        <p class="profile-info__title">Youtube channel :</p>
                        <?php echo '<p class="profile-info__text"> ' . $user['youtube_username'] . '</p>';
                        ?>
                    </div>
            <?php }
            } ?>

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

<!-- NAVIGATION SCRIPT -->
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

<!-- AFFINITIES PROGRESS BAR -->
<script>
    $(".meter > span").each(function() {
        $(this)
            .data("origWidth", $(this).width())
            .width(0)
            .animate({
                    width: $(this).data("origWidth")
                },
                1200
            );
    });
</script>
<!-- Matched popup -->
<script>
    function openMatched() {
        $("#matched").hide().fadeIn(1000);
    };
</script>
<script src="https://unpkg.com/@mojs/core"></script>

<script>
    $(document).ready(function() {
        var scaleCurve = mojs.easing.path('M0,100 L25,99.9999983 C26.2328835,75.0708847 19.7847843,0 100,0');
        var el = document.querySelector('.button'),
            // mo.js timeline obj
            timeline = new mojs.Timeline(),

            // tweens for the animation:

            // burst animation
            tween1 = new mojs.Burst({
                parent: el,
                radius: {
                    0: 100
                },
                angle: {
                    0: 45
                },
                y: -10,
                count: 10,
                radius: 100,
                children: {
                    shape: 'circle',
                    radius: 30,
                    fill: ['red', 'white'],
                    strokeWidth: 15,
                    duration: 500,
                }
            });


        tween2 = new mojs.Tween({
            duration: 900,
            onUpdate: function(progress) {
                var scaleProgress = scaleCurve(progress);
                el.style.WebkitTransform = el.style.transform = 'scale3d(' + scaleProgress + ',' + scaleProgress + ',1)';
            }
        });
        tween3 = new mojs.Burst({
            parent: el,
            radius: {
                0: 100
            },
            angle: {
                0: -45
            },
            y: -10,
            count: 10,
            radius: 125,
            children: {
                shape: 'circle',
                radius: 30,
                fill: ['white', 'red'],
                strokeWidth: 15,
                duration: 400,
            }
        });

        // add tweens to timeline:
        timeline.add(tween1, tween2, tween3);


        // when clicking the button start the timeline/animation:
        $(".button").click(function() {
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
            } else {
                timeline.play();
                $(this).addClass('active');
            }
        });


    });
</script>
<?php
include 'src/include/footer.php'
?>