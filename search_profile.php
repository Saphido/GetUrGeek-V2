<?php
include "src/include/header.php";
?>

<section class="login-hero">
    <h1 class="login-hero__title">Search profiles</h1>
    <p class="login-hero__text">HOME - Search profiles</p>
</section>

<section class="search-profile-filterbar">
    <form class="search-profile-filterbar__list" action="search_profile.php" method="GET">
        <div class="search-profile-filterbar__block">
            <p class="search-profile-filterbar__text">User connected</p>
            <input class="formular__form__checkbox-small" type="checkbox" name="connected">
        </div>
        <div class="search-profile-filterbar__block">
            <p class="search-profile-filterbar__text">Gender</p>
            <select name="gender">
                <option value="0" selected>No specified</option>
                <option value="1">Male</option>
                <option value="2">Female</option>
                <option value="3">No gender</option>
            </select>
        </div>
        <div class="search-profile-filterbar__block">
            <p class="search-profile-filterbar__text">here for</p>
            <select class="" name="hereFor">
                <option value="0" selected>No specified</option>
                <option value="1">Chatting</option>
                <option value="2">Meeting</option>
                <option value="3">Find teammate</option>
                <option value="4">We will see</option>
            </select>
        </div>
        <!--         <div class="search-profile-filterbar__block">
            <p class="search-profile-filterbar__text">Don't smoke</p>
            <input type="checkbox" name="smoker">
        </div>
        <div class="search-profile-filterbar__block">
            <p class="search-profile-filterbar__text">Don't drink</p>
            <input type="checkbox" name="drink">
        </div> -->
        <input type="submit" name="search" value="Search">
    </form>
    <button onclick="openAdvancedSearch()">Advanced filters</button>
</section>

<section class="search-profile-profiles">
    <?php

    if (!isset($_GET['search'])) {

        $req = selectInTable(
            $pdo,
            'user',
            [
                'user_id', 'username', 'verified', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
                'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
                'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
                'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
                'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
                'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
                'science_affinity', 'music_affinity'
            ],
            [],
            [],
            []
        );

        while ($user = $req->fetch()) {
            //CALCULATE THE AGE OF THE USER
            $aujourdhui = date("Y-m-d");
            $diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
            $age = $diff->format('%y');
            $request = selectInTable($pdo, 'countries', ['name'], ['id'], [$user['id_country']], []);
            $country = $request->fetch();

            $request = selectInTable($pdo, 'cities', ['name'], ['id'], [$user['id_city']], []);
            $city = $request->fetch();

    ?>
            <div class="search-profile-profiles__block">
                <img class="search-profile-profiles__block__image" alt="Profile picture" src="
                <?php
                if (is_dir("src/img/users-img/user_" . $user["user_id"] . "/")) {
                    echo 'src/img/users-img/user_' . $user['user_id'] . '/pp.png';
                } else {
                    echo 'src/img/profile/default.png';
                }
                ?>">
                <p class=" search-profile-profiles__block__username"><?php echo $user["username"]; ?></p>
                <p class="search-profile-profiles__block__text">(<?php echo $age; ?> ans)</p>
                <p class="search-profile-profiles__block__text"><?php echo $city['name'] . ', ' . $country['name']; ?></p>
            </div>
        <?php }
    } else if (isset($_GET['search'])) {

        $whereName = [];
        $whereValue = [];

        if (isset($_GET['connected'])) {
            array_push($whereName, 'isConnected');
            array_push($whereValue, [1]);
        }
        if ($_GET['gender'] != 0) {
            array_push($whereName, 'idgender');
            array_push($whereValue, [$_GET['gender']]);
        }
        if ($_GET['hereFor'] != 0) {
            array_push($whereName, 'id_here_for');
            array_push($whereValue, [$_GET['hereFor']]);
        }

        if ($_GET['smoker'] != 0) {
            array_push($whereName, 'id_smoker');
            if ($_GET['smoker'] == 1) {
                array_push($whereValue, [1, 2]);
            } else {
                array_push($whereValue, [3]);
            }
        }
        if ($_GET['alcohol'] != 0) {
            array_push($whereName, 'id_drink');
            array_push($whereValue, [3]);
        }
        if ($_GET['country'] != 0) {
            array_push($whereName, 'id_country');
            array_push($whereValue, [$_GET['country']]);
        }
        if ($_GET['state'] != 0) {
            array_push($whereName, 'id_state');
            array_push($whereValue, [$_GET['state']]);
        }
        if ($_GET['city'] != 0) {
            array_push($whereName, 'id_city');
            array_push($whereValue, [$_GET['city']]);
        }

        $req = selectInTableImproved(
            $pdo,
            'user',
            [
                'user_id', 'username', 'verified', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
                'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
                'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
                'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
                'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
                'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
                'science_affinity', 'music_affinity'
            ],
            $whereName,
            $whereValue
        );

        $exist = false;
        while ($user = $req->fetch()) {
            $exist = true;
            //CALCULATE THE AGE OF THE USER
            $aujourdhui = date("Y-m-d");
            $diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
            $age = $diff->format('%y');
            $request = selectInTable($pdo, 'countries', ['name'], ['id'], [$user['id_country']], []);
            $country = $request->fetch();

            $request = selectInTable($pdo, 'cities', ['name'], ['id'], [$user['id_city']], []);
            $city = $request->fetch();

        ?>
            <div class="search-profile-profiles__block">
                <img class="search-profile-profiles__block__image" alt="Profile picture" src="<?php echo 'src/img/users-img/user_' . $user['user_id'] . '/pp.png' ?>">
                <p class="search-profile-profiles__block__username"><?php echo $user["username"]; ?></p>
                <p class="search-profile-profiles__block__text">(<?php echo $age; ?> ans)</p>
                <p class="search-profile-profiles__block__text"><?php echo $city['name'] . ', ' . $country['name']; ?></p>
            </div>
    <?php }
        if (!$exist) {
            echo '<h3 class="search-profile-profiles__notfound">No profile found, please try with other filters.</h3>';
        }
    } ?>
</section>

<div class="popup_overlay" id="advanced_search">
    <div class="popup-block">
        <div class="popup">
            <div class="popup-content">
                <div class="popup-formular">
                    <h3 class="popup-formular__title"><span style="color: #7EFF7B;">Advanced</span> search</h3>
                    <p class="popup-formular__text">Find the perfect <span style="color:#7EFF7B; font-weight: 700;">GEEK</span> for you</p>

                    <form class="popup-formular__form" action="search_profile.php" method="GET">
                        <h3 class="popup-formular__form__title">Global filters</h3>
                        <p class="popup-formular__form__text">Show users connected</p>
                        <div class="md-switch">
                            <input type="checkbox" id="switch" class="md-switch-input" name="connected" />
                            <label for="switch" class="md-switch-label">
                                <div class="md-switch-label-rail">
                                    <div class="md-switch-label-rail-slider">
                                    </div>
                            </label>
                        </div>
                        <div class="marginbottom"></div>
                        <p class="popup-formular__form__text">Gender :</p>
                        <select class="formular__form__input" id="select" name="gender">
                            <option class="formular__form__input__options" value="0" selected>All</option>
                            <option class="formular__form__input__options" value="1">Male</option>
                            <option class="formular__form__input__options" value="2">Female</option>
                            <option class="formular__form__input__options" value="3">No gender</option>
                        </select>
                        <p class="popup-formular__form__text">Is here for :</p>
                        <select class="formular__form__input" id="select" name="hereFor">
                            <option class="formular__form__input__options" value="0" selected>No specified</option>
                            <option class="formular__form__input__options" value="1">Chatting</option>
                            <option class="formular__form__input__options" value="2">Meeting</option>
                            <option class="formular__form__input__options" value="3">Find teammate</option>
                            <option class="formular__form__input__options" value="4">We will see</option>
                        </select>
                        <p class="popup-formular__form__text">Children :</p>
                        <select class="formular__form__input" id="select" name="children">
                            <option class="formular__form__input__options" value="0" selected>No specified</option>
                            <option class="formular__form__input__options" value="1">Have already</option>
                            <option class="formular__form__input__options" value="2">Want it latter</option>
                            <option class="formular__form__input__options" value="3">Don't want it</option>
                        </select>

                        <p class="popup-formular__form__text">Smoker :</p>
                        <select class="formular__form__input" id="select" name="smoker">
                            <option class="formular__form__input__options" value="0" selected>No specified</option>
                            <option class="formular__form__input__options" value="1">Yes</option>
                            <option class="formular__form__input__options" value="3">No</option>
                        </select>

                        <p class="popup-formular__form__text margin">Alcohol :</p>
                        <select class="formular__form__input" id="select" name="alcohol">
                            <option class="formular__form__input__options" value="0" selected>No specified</option>
                            <option class="formular__form__input__options" value="1">Often</option>
                            <option class="formular__form__input__options" value="2">Occasionally</option>
                            <option class="formular__form__input__options" value="3">Never</option>
                        </select>
                        <h3 class="popup-formular__form__title">Location filters</h3>
                        <p class="popup-formular__form__text">Country</p>
                        <select name="country" class="countries formular__form__input" id="countryId">
                            <option class="" value="">Select Country</option>
                        </select>
                        <p class="popup-formular__form__text">Country</p>

                        <select name="state" class="states formular__form__input" id="stateId">
                            <option class="" value="">Select State</option>
                        </select>
                        <p class="popup-formular__form__text">Country</p>

                        <select name="city" class="cities formular__form__input" id="cityId">
                            <option class="" value="">Select City</option>
                        </select>

                        <h3 class="popup-formular__form__title">Hobbies filters</h3>
                        <div class="formular__form__slider-block">
                            <!-- SLIDER VIDEO GAME -->
                            <p class="popup-formular__form__text">Video games <input class="-show" type="number" readonly name="videogame_affinity" id="value-VG" value=""></p>
                            <div id="slider-VG"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER RPG/LARPG -->
                            <p class="popup-formular__form__text">RPG/LARPG <input class="-show" type="number" readonly name="rpg_affinity" id="value-RPG" value=""></p>
                            <div id="slider-RPG"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Anime/Manga -->
                            <p class="popup-formular__form__text">Anime/Manga <input class="-show" type="number" readonly name="anime_affinity" id="value-AM" value=""></p>
                            <div id="slider-AM"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Comics -->
                            <p class="popup-formular__form__text">Comics <input class="-show" type="number" readonly name="comics_affinity" id="value-COM" value=""></p>
                            <div id="slider-COM"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Cosplay -->
                            <p class="popup-formular__form__text">Cosplay <input class="-show" type="number" readonly name="cosplay_affinity" id="value-COS" value=""></p>
                            <div id="slider-COS"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Series -->
                            <p class="popup-formular__form__text">Series <input class="-show" type="number" readonly name="series_affinity" id="value-SER" value=""></p>
                            <div id="slider-SER"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- Movies -->
                            <p class="popup-formular__form__text">Movies <input class="-show" type="number" readonly name="movies_affinity" id="value-MOV" value=""></p>
                            <div id="slider-MOV"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Literature -->
                            <p class="popup-formular__form__text">Literature <input class="-show" type="number" readonly name="literature_affinity" id="value-LIT" value=""></p>
                            <div id="slider-LIT"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Science -->
                            <p class="popup-formular__form__text">Science <input class="-show" type="number" readonly name="science_affinity" id="value-SC" value=""></p>
                            <div id="slider-SC"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Musique -->
                            <p class="popup-formular__form__text">Music <input class="-show" type="number" readonly name="music_affinity" id="value-MUS" value=""></p>
                            <div id="slider-MUS"></div>
                        </div>

                        <input class="formular__form__button" type="submit" name="search" value="Search">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openAdvancedSearch() {
        $("#advanced_search").hide().fadeIn(1000);

        //close the POPUP if the button with id="close" is clicked

        $("#closeAdv").on("click", function(e) {
            e.preventDefault();
            $("#advanced_search").fadeOut(1000);
        });
    };
</script>
<script src="src/js/location.js"></script>
<?php
include "src/include/footer.php";
?>