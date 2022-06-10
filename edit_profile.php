<?php
include 'src/include/header.php';

if (!isset($_SESSION["user_login"])) { //Check if user session is not open, redirect to login.php
    echo ("<script>location.href = 'index.php';</script>");
}

$req = selectInTable(
    $pdo,
    'user',
    [
        'username', 'email', 'birthday', 'idgender', 'id_country', 'id_state',
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
$user = $req->fetch();

$req = selectInTable($pdo, 'countries', ['name', 'id'], ['id'], [$user['id_country']], []);
$countries = $req->fetch();

$req = selectInTable($pdo, 'states', ['name', 'id'], ['id'], [$user['id_state']], []);
$states = $req->fetch();

$req = selectInTable($pdo, 'cities', ['name', 'id'], ['id'], [$user['id_city']], []);
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

if (isset($_POST['edit-profile'])) {

    if (empty($_POST['username'])) {
        $errorMsg[] = "Please enter an username"; //check username not empty
    } else if (empty($_POST['email'])) {
        $errorMsg[] = "Please enter an email"; //check email not empty
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errorMsg[] = "Please enter a valid email adress"; //check email valid
    } else {
        if (!valeursEntre(
            [
                $_POST['videogame_affinity'],
                $_POST['rpg_affinity'],
                $_POST['anime_affinity'],
                $_POST['comics_affinity'],
                $_POST['cosplay_affinity'],
                $_POST['series_affinity'],
                $_POST['movies_affinity'],
                $_POST['literature_affinity'],
                $_POST['science_affinity'],
                $_POST['music_affinity']
            ],
            0,
            10
        )) {
            $errorMsg[] = "Error in hobbies affinity value";
        } else {
            try {
                $req = updateInTable(
                    $pdo,
                    'user',
                    [
                        'username', 'email', 'id_country', 'id_state', 'id_city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
                        'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
                        'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
                        'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
                        'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
                        'science_affinity', 'music_affinity'
                    ],
                    [
                        $_POST['username'], $_POST['email'], $_POST['country'], $_POST['state'], $_POST['city'], $_POST['biography'], $_POST['herefor'], $_POST['lookingfor'], $_POST['children'],
                        $_POST['drink'], $_POST['smoker'], $_POST['steam_username'], $_POST['battlenet_username'], $_POST['lol_username'],
                        $_POST['psn_username'], $_POST['xbox_username'], $_POST['twitch_username'], $_POST['youtube_username'], $_POST['discord_username'],
                        $_POST['videogame_affinity'], $_POST['rpg_affinity'], $_POST['anime_affinity'], $_POST['comics_affinity'],
                        $_POST['cosplay_affinity'], $_POST['series_affinity'], $_POST['movies_affinity'], $_POST['literature_affinity'],
                        $_POST['science_affinity'], $_POST['music_affinity']
                    ],
                    ['user_id'],
                    [$_SESSION["user_login"]]
                );

                $successMsg = "Profile saved !";
                echo ("<script>location.href = 'profile.php';</script>");
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }
}
?>
<script src="src/js/croppie.js"></script>
<link rel="stylesheet" href="src/css/croppie.css" />


<section class="login-hero">
    <h1 class="login-hero__title">Edit profile</h1>
    <p class="login-hero__text">HOME - PROFILE - Edit profile</p>
</section>

<section class="formular">
    <h3 class="formular__title"><span style="color: #7EFF7B;">Edit</span> profile</h3>
    <p class="formular__text">Fill your missing data or edit them</p>

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
    <form class="formular__form" method="POST" action="edit_profile.php" enctype="multipart/form-data" Append="?submit=true">
        <h3 class="formular__form__title">Global informations</h3>
        <p class="formular__form__text">Profile Picture</p>
        <input type="file" name="upload_image" id="upload_image" />
        <br />
        <div id="uploaded_image"></div>
        <p class="formular__form__text">Username</p>
        <input class="formular__form__input" type="text" name="username" value=<?php echo $user['username']; ?>>
        <p class="formular__form__text">Email</p>
        <input class="formular__form__input" type="text" name="email" value=<?php echo $user['email']; ?>>
        <p class="formular__form__text">Birthday</p>
        <input class="formular__form__input" type="date" name="birth" placeholder="dd-mm-yyyy" placeholder="dd-mm-yyyy" value=<?php echo $user['birthday']; ?> disabled>
        <p class="formular__form__text">Gender</p>
        <select class="formular__form__input" id="select" name="gender" disabled>
            <option value="" selected disabled hidden><?php echo $gender['gender_en']; ?></option>
        </select>
        <p class="formular__form__text">Here for</p>
        <select class="formular__form__input" id="select" name="herefor" required>
            <?php
            $req = selectInTable($pdo, 'herefor', ['id', 'herefor_en'], [], [], '');

            while ($herefor = $req->fetch()) {
                if ($user['id_here_for'] == $herefor['id']) {
                    echo '<option selected class="formular__form__input__options" value="' . $herefor['id'] . '">' . $herefor['herefor_en'] . '</option>';
                } else {
                    echo '<option class="formular__form__input__options" value="' . $herefor['id'] . '">' . $herefor['herefor_en'] . '</option>';
                }
            }
            ?>
        </select>
        <p class="formular__form__text">Interested by</p>
        <select class="formular__form__input" id="select" name="lookingfor">
            <?php
            $req = selectInTable($pdo, 'lookingfor', ['id', 'lookingfor_en'], [], [], '');

            while ($lookingfor = $req->fetch()) {
                if ($user['id_looking_for'] == $lookingfor['id']) {
                    echo '<option selected class="formular__form__input__options" value="' . $lookingfor['id'] . '">' . $lookingfor['lookingfor_en'] . '</option>';
                } else {
                    echo '<option class="formular__form__input__options" value="' . $lookingfor['id'] . '">' . $lookingfor['lookingfor_en'] . '</option>';
                }
            }
            ?>
        </select>
        <p class="formular__form__text">Biography</p>
        <textarea class="formular__form__inputarea" id="biography" name="biography" value="" maxlength="255" placeholder="Biography"><?php echo $user['biography']; ?></textarea>
        <div id="the-count">
            <span id="current">0</span>
            <span id="maximum">/ 255</span>
        </div>
        <p class="formular__form__text">Children</p>
        <select class="formular__form__input" id="select" name="children">
            <?php
            $req = selectInTable($pdo, 'children', ['id', 'children_en'], [], [], '');

            while ($children = $req->fetch()) {
                if ($user['id_children'] == $children['id']) {
                    echo '<option selected class="formular__form__input__options" value="' . $children['id'] . '">' . $children['children_en'] . '</option>';
                } else {
                    echo '<option class="formular__form__input__options" value="' . $children['id'] . '">' . $children['children_en'] . '</option>';
                }
            }
            ?>
        </select>

        <p class="formular__form__text">Alcohol</p>
        <select class="formular__form__input" id="select" name="drink">
            <?php
            $req = selectInTable($pdo, 'drink', ['id', 'drink_en'], [], [], '');

            while ($drink = $req->fetch()) {
                if ($user['id_drink'] == $drink['id']) {
                    echo '<option selected class="formular__form__input__options" value="' . $drink['id'] . '">' . $drink['drink_en'] . '</option>';
                } else {
                    echo '<option class="formular__form__input__options" value="' . $drink['id'] . '">' . $drink['drink_en'] . '</option>';
                }
            }
            ?>
        </select>

        <p class="formular__form__text">Smoker</p>
        <select class="formular__form__input" id="select" name="smoker">
            <?php
            $req = selectInTable($pdo, 'smoker', ['id', 'smoker_en'], [], [], '');

            while ($smoker = $req->fetch()) {
                if ($user['id_smoker'] == $smoker['id']) {
                    echo '<option selected class="formular__form__input__options" value="' . $smoker['id'] . '">' . $smoker['smoker_en'] . '</option>';
                } else {
                    echo '<option class="formular__form__input__options" value="' . $smoker['id'] . '">' . $smoker['smoker_en'] . '</option>';
                }
            }
            ?>
        </select>
        <h3 class="formular__form__title">LOCATION</h3>
        <p class="formular__form__text">Country</p>
        <select name="country" class="countries formular__form__input" id="countryId">
            <option class="formular__form__input__options" value="">Select Country</option>
            <?php
            echo '<option selected hidden class="formular__form__input__options" value="' . $countries['id'] . '">' . $countries['name'] . '</option>';
            ?>
        </select>
        <p class="formular__form__text">State</p>
        <select name="state" class="states formular__form__input" id="stateId">
            <option class="formular__form__input__options" value="">Select Country first</option>
            <?php
            echo '<option selected hidden class="formular__form__input__options" value="' . $states['id'] . '">' . $states['name'] . '</option>';
            ?>
        </select>
        <p class="formular__form__text">City</p>
        <select name="city" class="cities formular__form__input" id="cityId">
            <option class="formular__form__input__options" value="">Select State first</option>
            <?php
            echo '<option selected hidden class="formular__form__input__options" value="' . $cities['id'] . '">' . $cities['name'] . '</option>';
            ?>
        </select>
        <h3 class="formular__form__title">GAME TAGS</h3>
        <p class="formular__form__text">Steam Profile</p>
        <input class="formular__form__input" type="text" name="steam_username" value="<?php echo $user['steam_username']; ?>" placeholder="URL to your profile">
        <p class="formular__form__text">Battle.net</p>
        <input class="formular__form__input" type="text" name="battlenet_username" value="<?php echo $user['battlenet_username']; ?>" placeholder="Battle.net ID">
        <p class="formular__form__text">League of Legends</p>
        <input class="formular__form__input" type="text" name="lol_username" value="<?php echo $user['lol_username']; ?>" placeholder="LoL ID">
        <p class="formular__form__text">PSN</p>
        <input class="formular__form__input" type="text" name="psn_username" value="<?php echo $user['psn_username']; ?>" placeholder="PSN ID">
        <p class="formular__form__text">XBOX</p>
        <input class="formular__form__input" type="text" name="xbox_username" value="<?php echo $user['xbox_username']; ?>" placeholder="XBOX ID">
        <p class="formular__form__text">Discord</p>
        <input class="formular__form__input" type="text" name="discord_username" value="<?php echo $user['discord_username']; ?>" placeholder="Discord ID">
        <p class="formular__form__text">Twitch channel</p>
        <input class="formular__form__input" type="text" name="twitch_username" value="<?php echo $user['twitch_username']; ?>" placeholder="Twitch username">
        <p class="formular__form__text">Youtube channel</p>
        <input class="formular__form__input" type="text" name="youtube_username" value="<?php echo $user['youtube_username']; ?>" placeholder="Youtube username">
        <p class="formular__form__info">* if you have some suggestions to add in this list, just contact us</p>
        <h3 class="formular__form__title">Hobbies affinity</h3>

        <div class="formular__form__slider-block">
            <!-- SLIDER VIDEO GAME -->
            <p class="formular__form__text">Video games <input class="formular__form__input-show" type="number" readonly name="videogame_affinity" id="value-VG" value="<?php echo $user['videogame_affinity']; ?>"></p>
            <div id="slider-VG"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER RPG/LARPG -->
            <p class="formular__form__text">RPG/LARPG <input class="formular__form__input-show" type="number" readonly name="rpg_affinity" id="value-RPG" value="<?php echo $user['rpg_affinity']; ?>"></p>
            <div id="slider-RPG"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Anime/Manga -->
            <p class="formular__form__text">Anime/Manga <input class="formular__form__input-show" type="number" readonly name="anime_affinity" id="value-AM" value="<?php echo $user['anime_affinity']; ?>"></p>
            <div id="slider-AM"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Comics -->
            <p class="formular__form__text">Comics <input class="formular__form__input-show" type="number" readonly name="comics_affinity" id="value-COM" value="<?php echo $user['comics_affinity']; ?>"></p>
            <div id="slider-COM"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Cosplay -->
            <p class="formular__form__text">Cosplay <input class="formular__form__input-show" type="number" readonly name="cosplay_affinity" id="value-COS" value="<?php echo $user['cosplay_affinity']; ?>"></p>
            <div id="slider-COS"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Series -->
            <p class="formular__form__text">Series <input class="formular__form__input-show" type="number" readonly name="series_affinity" id="value-SER" value="<?php echo $user['series_affinity']; ?>"></p>
            <div id="slider-SER"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- Movies -->
            <p class="formular__form__text">Movies <input class="formular__form__input-show" type="number" readonly name="movies_affinity" id="value-MOV" value="<?php echo $user['movies_affinity']; ?>"></p>
            <div id="slider-MOV"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Literature -->
            <p class="formular__form__text">Literature <input class="formular__form__input-show" type="number" readonly name="literature_affinity" id="value-LIT" value="<?php echo $user['literature_affinity']; ?>"></p>
            <div id="slider-LIT"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Science -->
            <p class="formular__form__text">Science <input class="formular__form__input-show" type="number" readonly name="science_affinity" id="value-SC" value="<?php echo $user['science_affinity']; ?>"></p>
            <div id="slider-SC"></div>
        </div>

        <div class="formular__form__slider-block">
            <!-- SLIDER Musique -->
            <p class="formular__form__text">Music <input class="formular__form__input-show" type="number" readonly name="music_affinity" id="value-MUS" value="<?php echo $user['music_affinity']; ?>"></p>
            <div id="slider-MUS"></div>
        </div>

        <input class="formular__form__button" type="submit" name="edit-profile" value="SAVE">
    </form>
</section>
<div class="modal-overlay" id="uploadimageModal">
    <div id="uploadimageModal" class="modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <h4 class="popup-formular__title"><span style="color: #7EFF7B;">Upload</span> & <span style="color: #7EFF7B;">Crop</span></h4>
                <p class="popup-formular__text">Crop the part you like and save it</p>
                <div id="image_demo" style="width:350px; margin-top:30px"></div>
                <div class="modal-overlay__buttonarea">
                    <button class="btn modal-overlay__buttonarea__button crop_image">Crop & Save</button>
                    <button type="button" class="btn modal-overlay__buttonarea__button" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CROP IMAGES -->
<script>
    function closeModal() {
        $('#uploadimageModal').css("display", "none")
    }
    $(document).ready(function() {

        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width: 300,
                height: 300,
                type: 'square' //circle
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $('#upload_image').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').css("display", "block")
        });

        $('.crop_image').click(function(event) {
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                $.ajax({
                    url: "upload.php",
                    type: "POST",
                    data: {
                        "image": response
                    },
                    success: function(data) {
                        $('#uploadimageModal').css("display", "none");
                        $('#uploaded_image').html(data);
                    }
                });
            })
        });

    });
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
            start: <?php echo $user['videogame_affinity']; ?>,
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
            start: <?php echo $user['rpg_affinity']; ?>,
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
            start: <?php echo $user['anime_affinity']; ?>,
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
            start: <?php echo $user['comics_affinity']; ?>,
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
            start: <?php echo $user['cosplay_affinity']; ?>,
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
            start: <?php echo $user['series_affinity']; ?>,
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
            start: <?php echo $user['movies_affinity']; ?>,
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
            start: <?php echo $user['literature_affinity']; ?>,
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
            start: <?php echo $user['science_affinity']; ?>,
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
            start: <?php echo $user['music_affinity']; ?>,
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

    /* Textarea showing numbers*/
    $('textarea').keyup(function() {

        var characterCount = $(this).val().length,
            current = $('#current'),
            maximum = $('#maximum'),
            theCount = $('#the-count');

        current.text(characterCount);

        /*Changing color with text lenght*/
        if (characterCount < 70) {
            current.css('color', '#ffffff');
        } else if (characterCount > 70 && characterCount < 100) {
            current.css('color', '#fdb7b7')
        } else if (characterCount > 100 && characterCount < 160) {
            current.css('color', '#f89393')
        } else if (characterCount > 160 && characterCount < 255) {
            current.css('color', '#fa7272')
        } else if (characterCount == 255) {
            current.css('color', '#fa2222')
        }


    });
</script>
<script src="src/js/location.js"></script>

<?php
include 'src/include/footer.php';
?>