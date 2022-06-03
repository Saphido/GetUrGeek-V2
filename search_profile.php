<?php
include "src/include/header.php";

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
//CALCULATE THE AGE OF THE USER
/* $aujourdhui = date("Y-m-d");
$diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
$age = $diff->format('%y'); */

?>

<section class="login-hero">
    <h1 class="login-hero__title">Search profiles</h1>
    <p class="login-hero__text">HOME - Search profiles</p>
</section>

<section class="search-profile-filterbar">

</section>

<section class="search-profile-profiles">
    <?php
    while ($user = $req->fetch()) {
        //CALCULATE THE AGE OF THE USER
        $aujourdhui = date("Y-m-d");
        $diff = date_diff(date_create($user['birthday']), date_create($aujourdhui));
        $age = $diff->format('%y');
    ?>
        <div class="search-profile-profiles__block">
            <img class="search-profile-profiles__block__image" src="src/img/profile/gallery.webp">
            <p class="search-profile-profiles__block__username"><?php echo $user["username"]; ?></p>
            <p class="search-profile-profiles__block__text">(<?php echo $age; ?> ans)</p>
            <p class="search-profile-profiles__block__text"><?php echo $user['city'] . ', ' . $pays['nom_en_gb']; ?></p>
        </div>
    <?php }
    ?>
</section>











<?php
include "src/include/footer.php";
?>