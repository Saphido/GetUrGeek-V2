<?php
include "src/include/header.php";
?>

<section class="login-hero">
    <h1 class="login-hero__title">Search profiles</h1>
    <p class="login-hero__text">HOME - Search profiles</p>
</section>

<section class="search-profile-filterbar">
    <p class="search-profile-filterbar__text">Check out our <span class="green">filters</span> and find your <span class="green">perfect</span> geek</p>
    <button class="search-profile-filterbar__button" onclick="openAdvancedSearch()">GO</button>
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
            <div class="search-profile-profiles__block" onclick="window.location.href='user_profile.php?userId=<?php echo $user['user_id'] ?>'">
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
        $sign = [];

        if ($_GET['gender'] != 0) {
            array_push($whereName, 'idgender');
            array_push($whereValue, [$_GET['gender']]);
            array_push($sign, '=');
        }
        if ($_GET['hereFor'] != 0) {
            array_push($whereName, 'id_here_for');
            array_push($whereValue, [$_GET['hereFor']]);
            array_push($sign, '=');
        }

        if ($_GET['smoker'] != 0) {
            array_push($whereName, 'id_smoker');
            if ($_GET['smoker'] == 1) {
                array_push($whereValue, [1, 2]);
            } else {
                array_push($whereValue, [3]);
            }
            array_push($sign, '=');
        }
        if ($_GET['alcohol'] != 0) {
            array_push($whereName, 'id_drink');
            array_push($whereValue, [3]);
            array_push($sign, '=');
        }
        if ($_GET['country'] != 0) {
            array_push($whereName, 'id_country');
            array_push($whereValue, [$_GET['country']]);
            array_push($sign, '=');
        }
        if ($_GET['state'] != 0) {
            array_push($whereName, 'id_state');
            array_push($whereValue, [$_GET['state']]);
            array_push($sign, '=');
        }
        if ($_GET['city'] != 0) {
            array_push($whereName, 'id_city');
            array_push($whereValue, [$_GET['city']]);
            array_push($sign, '=');
        }
        if ($_GET['videogame_affinity'] != 0) {
            array_push($whereName, 'videogame_affinity');
            array_push($whereValue, [$_GET['videogame_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['rpg_affinity'] != 0) {
            array_push($whereName, 'rpg_affinity');
            array_push($whereValue, [$_GET['rpg_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['anime_affinity'] != 0) {
            array_push($whereName, 'anime_affinity');
            array_push($whereValue, [$_GET['anime_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['comics_affinity'] != 0) {
            array_push($whereName, 'comics_affinity');
            array_push($whereValue, [$_GET['comics_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['cosplay_affinity'] != 0) {
            array_push($whereName, 'cosplay_affinity');
            array_push($whereValue, [$_GET['cosplay_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['series_affinity'] != 0) {
            array_push($whereName, 'series_affinity');
            array_push($whereValue, [$_GET['series_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['movies_affinity'] != 0) {
            array_push($whereName, 'movies_affinity');
            array_push($whereValue, [$_GET['movies_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['literature_affinity'] != 0) {
            array_push($whereName, 'literature_affinity');
            array_push($whereValue, [$_GET['literature_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['science_affinity'] != 0) {
            array_push($whereName, 'science_affinity');
            array_push($whereValue, [$_GET['science_affinity']]);
            array_push($sign, '>=');
        }
        if ($_GET['music_affinity'] != 0) {
            array_push($whereName, 'music_affinity');
            array_push($whereValue, [$_GET['music_affinity']]);
            array_push($sign, '>=');
        }

        $req = selectInTableImprovedSigned(
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
            $whereValue,
            $sign
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
            <div class="search-profile-profiles__block" onclick="window.location.href='user_profile.php?userId=<?php echo $user['user_id'] ?>'">
                <img class="search-profile-profiles__block__image" alt="Profile picture" src="
                <?php
                if (is_dir("src/img/users-img/user_" . $user["user_id"] . "/")) {
                    echo 'src/img/users-img/user_' . $user['user_id'] . '/pp.png';
                } else {
                    echo 'src/img/profile/default.png';
                }
                ?>">
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
                        <p class="popup-formular__form__subtitle">Select the <span style="color: #7EFF7B;">minimal</span> score you want</p>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER VIDEO GAME -->
                            <p class="popup-formular__form__text">Video games <input class="formular__form__input-show" type="number" readonly name="videogame_affinity" id="value-VG" value=""></p>
                            <div id="slider-VG"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER RPG/LARPG -->
                            <p class="popup-formular__form__text">RPG/LARPG <input class="formular__form__input-show" type="number" readonly name="rpg_affinity" id="value-RPG" value=""></p>
                            <div id="slider-RPG"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Anime/Manga -->
                            <p class="popup-formular__form__text">Anime/Manga <input class="formular__form__input-show" type="number" readonly name="anime_affinity" id="value-AM" value=""></p>
                            <div id="slider-AM"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Comics -->
                            <p class="popup-formular__form__text">Comics <input class="formular__form__input-show" type="number" readonly name="comics_affinity" id="value-COM" value=""></p>
                            <div id="slider-COM"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Cosplay -->
                            <p class="popup-formular__form__text">Cosplay <input class="formular__form__input-show" type="number" readonly name="cosplay_affinity" id="value-COS" value=""></p>
                            <div id="slider-COS"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Series -->
                            <p class="popup-formular__form__text">Series <input class="formular__form__input-show" type="number" readonly name="series_affinity" id="value-SER" value=""></p>
                            <div id="slider-SER"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- Movies -->
                            <p class="popup-formular__form__text">Movies <input class="formular__form__input-show" type="number" readonly name="movies_affinity" id="value-MOV" value=""></p>
                            <div id="slider-MOV"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Literature -->
                            <p class="popup-formular__form__text">Literature <input class="formular__form__input-show" type="number" readonly name="literature_affinity" id="value-LIT" value=""></p>
                            <div id="slider-LIT"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Science -->
                            <p class="popup-formular__form__text">Science <input class="formular__form__input-show" type="number" readonly name="science_affinity" id="value-SC" value=""></p>
                            <div id="slider-SC"></div>
                        </div>

                        <div class="formular__form__slider-block">
                            <!-- SLIDER Musique -->
                            <p class="popup-formular__form__text">Music <input class="formular__form__input-show" type="number" readonly name="music_affinity" id="value-MUS" value=""></p>
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

<!-- SLIDERS -->
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var sliderVG = document.getElementById('slider-VG');
        var valueVG = document.getElementById('value-VG');

        var sliderRPG = document.getElementById('slider-RPG');
        var valueRPG = document.getElementById('value-RPG');

        var sliderAM = document.getElementById('slider-AM');
        var valueAM = document.getElementById('value-AM');

        var sliderCOM = document.getElementById('slider-COM');
        var valueCOM = document.getElementById('value-COM');

        var sliderCOS = document.getElementById('slider-COS');
        var valueCOS = document.getElementById('value-COS');

        var sliderSER = document.getElementById('slider-SER');
        var valueSER = document.getElementById('value-SER');

        var sliderMOV = document.getElementById('slider-MOV');
        var valueMOV = document.getElementById('value-MOV');

        var sliderLIT = document.getElementById('slider-LIT');
        var valueLIT = document.getElementById('value-LIT');

        var sliderSC = document.getElementById('slider-SC');
        var valueSC = document.getElementById('value-SC');

        var sliderMUS = document.getElementById('slider-MUS');
        var valueMUS = document.getElementById('value-MUS');


        var attributeValue = valueVG.getAttribute('innerText');

        noUiSlider.create(sliderVG, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderVG.noUiSlider.on('update', function() {
            valueVG.setAttribute('value', sliderVG.noUiSlider.get())
        });

        noUiSlider.create(sliderRPG, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderRPG.noUiSlider.on('update', function() {
            valueRPG.setAttribute('value', sliderRPG.noUiSlider.get())

        });

        noUiSlider.create(sliderAM, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderAM.noUiSlider.on('update', function() {
            valueAM.setAttribute('value', sliderAM.noUiSlider.get())

        });

        noUiSlider.create(sliderCOM, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderCOM.noUiSlider.on('update', function() {
            valueCOM.setAttribute('value', sliderCOM.noUiSlider.get())

        });

        noUiSlider.create(sliderCOS, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderCOS.noUiSlider.on('update', function() {
            valueCOS.setAttribute('value', sliderCOS.noUiSlider.get())

        });

        noUiSlider.create(sliderSER, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderSER.noUiSlider.on('update', function() {
            valueSER.setAttribute('value', sliderSER.noUiSlider.get())

        });

        noUiSlider.create(sliderMOV, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderMOV.noUiSlider.on('update', function() {
            valueMOV.setAttribute('value', sliderMOV.noUiSlider.get())

        });


        noUiSlider.create(sliderLIT, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderLIT.noUiSlider.on('update', function() {
            valueLIT.setAttribute('value', sliderLIT.noUiSlider.get())

        });

        noUiSlider.create(sliderSC, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderSC.noUiSlider.on('update', function() {
            valueSC.setAttribute('value', sliderSC.noUiSlider.get())

        });

        noUiSlider.create(sliderMUS, {
            start: 0,
            step: 1,
            connect: 'lower',
            format: {
                to: (v) => v | 0,
                from: (v) => v | 0
            },
            range: {
                'min': 0,
                'max': 10
            }
        });

        sliderMUS.noUiSlider.on('update', function() {
            valueMUS.setAttribute('value', sliderMUS.noUiSlider.get())

        });
    });
</script>
<script src="src/js/location.js"></script>
<?php
include "src/include/footer.php";
?>