<?php
include "src/include/header.php";
?>

<section class="login-hero">
    <h1 class="login-hero__title">Search profiles</h1>
    <p class="login-hero__text">HOME - SEARCH PROFILES - Advanced</p>
</section>

<section class="formular">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Advanced</span> search</h3>
    <p class="formular__text">Find the perfect <span style="color:#7EFF7B; font-weight: 700;">GEEK</span> for you</p>

    <?php
    if (isset($errorMsg)) {
        foreach ($errorMsg as $error) {
    ?>
            <div class="alert alert-danger">
                <strong><?php echo $error; ?></strong>
            </div>
        <?php
        }
    }
    if (isset($successMsg)) {
        ?>
        <div class="alert alert-sucess">
            <strong><?php echo $successMsg; ?> </strong>
        </div>
    <?php
    }
    ?>
    <form class="formular__form" action="search_profile.php" method="GET">
        <h3 class="formular__form__title">Global filters</h3>
        <p class="formular__form__text">Show users connected</p>
        <input class="formular__form__checkboxarea__checkbox" style="margin-left: 1rem;" type="checkbox" name="connected">
        <p class="formular__form__text">Gender :</p>
        <select class="formular__form__input" id="select" name="gender">
            <option class="formular__form__input__options" value="0" selected>All</option>
            <option class="formular__form__input__options" value="1">Male</option>
            <option class="formular__form__input__options" value="2">Female</option>
            <option class="formular__form__input__options" value="3">No gender</option>
        </select>
        <p class="formular__form__text">Is here for :</p>
        <select class="formular__form__input" id="select" name="hereFor">
            <option class="formular__form__input__options" value="0" selected>No specified</option>
            <option class="formular__form__input__options" value="1">Chatting</option>
            <option class="formular__form__input__options" value="2">Meeting</option>
            <option class="formular__form__input__options" value="3">Find teammate</option>
            <option class="formular__form__input__options" value="4">We will see</option>
        </select>
        <p class="formular__form__text">Children :</p>
        <select class="formular__form__input" id="select" name="children">
            <option class="formular__form__input__options" value="0" selected>No specified</option>
            <option class="formular__form__input__options" value="1">Have already</option>
            <option class="formular__form__input__options" value="2">Want it latter</option>
            <option class="formular__form__input__options" value="3">Don't want it</option>
        </select>

        <p class="formular__form__text">Smoker :</p>
        <select class="formular__form__input" id="select" name="smoker">
            <option class="formular__form__input__options" value="0" selected>No specified</option>
            <option class="formular__form__input__options" value="1">Yes</option>
            <option class="formular__form__input__options" value="3">No</option>
        </select>

        <p class="formular__form__text">Alcohol :</p>
        <select class="formular__form__input" id="select" name="alcohol">
            <option class="formular__form__input__options" value="0" selected>No specified</option>
            <option class="formular__form__input__options" value="1">Often</option>
            <option class="formular__form__input__options" value="2">Occasionally</option>
            <option class="formular__form__input__options" value="3">Never</option>
        </select>
        <h3 class="formular__form__title">Location filters</h3>
        <p class="formular__form__text">Country</p>
        <select name="country" class="countries formular__form__input" id="countryId">
            <option class="formular__form__input__options" value="">Select Country</option>
        </select>
        <select name="state" class="states formular__form__input" id="stateId">
            <option class="formular__form__input__options" value="">Select State</option>
        </select>
        <select name="city" class="cities formular__form__input" id="cityId">
            <option class="formular__form__input__options" value="">Select City</option>
        </select>

        <h3 class="formular__form__title">Hobbies filters</h3>
        <div class="formular__form__slider-block">
            <!-- SLIDER VIDEO GAME -->
            <p class="formular__form__text">Video games <input class="formular__form__input-show" type="number" readonly name="videogame_affinity" id="value-VG" value=""></p>
            <div id="slider-VG"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER RPG/LARPG -->
            <p class="formular__form__text">RPG/LARPG <input class="formular__form__input-show" type="number" readonly name="rpg_affinity" id="value-RPG" value=""></p>
            <div id="slider-RPG"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Anime/Manga -->
            <p class="formular__form__text">Anime/Manga <input class="formular__form__input-show" type="number" readonly name="anime_affinity" id="value-AM" value=""></p>
            <div id="slider-AM"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Comics -->
            <p class="formular__form__text">Comics <input class="formular__form__input-show" type="number" readonly name="comics_affinity" id="value-COM" value=""></p>
            <div id="slider-COM"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Cosplay -->
            <p class="formular__form__text">Cosplay <input class="formular__form__input-show" type="number" readonly name="cosplay_affinity" id="value-COS" value=""></p>
            <div id="slider-COS"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Series -->
            <p class="formular__form__text">Series <input class="formular__form__input-show" type="number" readonly name="series_affinity" id="value-SER" value=""></p>
            <div id="slider-SER"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- Movies -->
            <p class="formular__form__text">Movies <input class="formular__form__input-show" type="number" readonly name="movies_affinity" id="value-MOV" value=""></p>
            <div id="slider-MOV"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Literature -->
            <p class="formular__form__text">Literature <input class="formular__form__input-show" type="number" readonly name="literature_affinity" id="value-LIT" value=""></p>
            <div id="slider-LIT"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Science -->
            <p class="formular__form__text">Science <input class="formular__form__input-show" type="number" readonly name="science_affinity" id="value-SC" value=""></p>
            <div id="slider-SC"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Musique -->
            <p class="formular__form__text">Music <input class="formular__form__input-show" type="number" readonly name="music_affinity" id="value-MUS" value=""></p>
            <div id="slider-MUS"></div>
        </div>

        <input type="submit" name="adv_search" value="Search">
    </form>
</section>

<script src="src/js/location.js"></script>