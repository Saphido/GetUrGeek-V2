<?php
include "src/include/header.php";
?>

<section class="login-hero">
    <h1 class="login-hero__title">Search profiles</h1>
    <p class="login-hero__text">HOME - Search profiles</p>
</section>

<section class="search-profile-filterbar">
    <div class="search-profile-filterbar__list">
        <div class="dropdown">
            <form action="search_profile.php" method="GET">
                <div>
                    <label>User connected</label>
                    <input type="checkbox" name="connected">
                </div>
                <div>
                    <label>Gender</label>
                    <select name="gender">
                        <option value="0" selected>No specified</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                        <option value="3">No gender</option>
                    </select>
                </div>
                <div>
                    <label>here for</label>
                    <select name="hereFor">
                        <option value="0" selected>No specified</option>
                        <option value="1">Chatting</option>
                        <option value="2">Meeting</option>
                        <option value="3">Find teammate</option>
                        <option value="4">We will see</option>
                    </select>
                </div>
                <div>
                    <label>Not smoker</label>
                    <input type="checkbox" name="smoker">
                </div>
                <div>
                    <label>Not drink</label>
                    <input type="checkbox" name="drink">
                </div>
                <input type="submit" name="search" value="Search">
            </form>
        </div>
    </div>
</section>

<section class="search-profile-profiles">
    <?php

    if (!isset($_GET['search'])) {

        $req = selectInTable(
            $pdo,
            'user',
            [
                'user_id', 'username', 'verified', 'email', 'birthday', 'idgender', 'idcountry', 'zipcode',
                'city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
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
            $request = selectInTable($pdo, 'pays', ['nom_en_gb'], ['id'], [$user['idcountry']], []);
            $pays = $request->fetch();

    ?>
            <div class="search-profile-profiles__block">
                <img class="search-profile-profiles__block__image" src="src/img/profile/gallery.webp">
                <p class="search-profile-profiles__block__username"><?php echo $user["username"]; ?></p>
                <p class="search-profile-profiles__block__text">(<?php echo $age; ?> ans)</p>
                <p class="search-profile-profiles__block__text"><?php echo $user['city'] . ', ' . $pays['nom_en_gb']; ?></p>
            </div>
        <?php }
    } else if (isset($_GET['search'])) {
        $whereName = [];
        $whereValue = [];

        if (isset($_GET['connected'])) {
            array_unshift($whereName, 'isConnected');
            array_unshift($whereValue, 1);
        }
        if ($_GET['gender'] != 0) {
            array_unshift($whereName, 'idgender');
            array_unshift($whereValue, $_GET['gender']);
        }
        if ($_GET['hereFor'] != 0) {
            array_unshift($whereName, 'id_here_for');
            array_unshift($whereValue, $_GET['hereFor']);
        }
        if (isset($_GET['smoker'])) {
            array_unshift($whereName, 'id_smoker');
            array_unshift($whereValue, 3);
        }
        if (isset($_GET['drink'])) {
            array_unshift($whereName, 'id_drink');
            array_unshift($whereValue, 3);
        }

        $req = selectInTable(
            $pdo,
            'user',
            [
                'user_id', 'username', 'verified', 'email', 'birthday', 'idgender', 'idcountry', 'zipcode',
                'city', 'biography', 'id_here_for', 'id_looking_for', 'id_children',
                'id_drink', 'id_smoker', 'steam_username', 'battlenet_username', 'lol_username',
                'psn_username', 'xbox_username', 'twitch_username', 'youtube_username', 'discord_username',
                'videogame_affinity', 'rpg_affinity', 'anime_affinity', 'comics_affinity',
                'cosplay_affinity', 'series_affinity', 'movies_affinity', 'literature_affinity',
                'science_affinity', 'music_affinity'
            ],
            [$whereName],
            [$whereValue],
            ['AND']
        );
        while ($user = $req->fetch()) {
            //CALCULATE THE AGE OF THE USER
            $aujourdhui = date("Y-m-d");
            $diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
            $age = $diff->format('%y');
            $request = selectInTable($pdo, 'pays', ['nom_en_gb'], ['id'], [$user['idcountry']], []);
            $pays = $request->fetch();

        ?>
            <div class="search-profile-profiles__block">
                <img class="search-profile-profiles__block__image" src="src/img/profile/gallery.webp">
                <p class="search-profile-profiles__block__username"><?php echo $user["username"]; ?></p>
                <p class="search-profile-profiles__block__text">(<?php echo $age; ?> ans)</p>
                <p class="search-profile-profiles__block__text"><?php echo $user['city'] . ', ' . $pays['nom_en_gb']; ?></p>
            </div>
    <?php }
    }
    ?>
</section>











<?php
include "src/include/footer.php";
?>