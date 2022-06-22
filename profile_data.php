<?php
include 'src/include/header.php';
if (!isset($_SESSION["user_login"])) { //Check if user session is not open, redirect to login.php
    echo ("<script>location.href = 'login.php';</script>");
}

$req_overlike = selectInTable($pdo, 'user', ['superLike', 'user_rank'], ['user_id'], [$_SESSION['user_login']], []);
$userData = $req_overlike->fetch();

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
    <section class="community">
        <h3 class="community__title">Your actual <span style="color: #7EFF7B;">powerup</span></h3>
        <div class="community__blockarea">
            <div class="community__blockarea__blocks">
                <img class="community__blockarea__blocks__imageOne" alt="Members icon" src="src/img/index/member_icon.png">
                <h3 class="community__blockarea__blocks__title">OVERLIKE</h3>
                <p class="community__blockarea__blocks__number"><?php echo $userData['superLike']; ?></p>
                <a href="shop.php"><button id="seeMore" class="buttonSeeMoreMessages">GET MORE</button></a>
            </div>
            <div class="community__blockarea__blocks">
                <img class="community__blockarea__blocks__imageTwo" alt="Matches icon" src="src/img/index/matches_icon.png">
                <h3 class="community__blockarea__blocks__title">ACCOUNT STATUS</h3>
                <p class="community__blockarea__blocks__number"><?php 
                if($userData['user_rank'] === 0) {
                    echo 'Member';
                } else if($userData['user_rank'] === 1) {
                    echo 'Overliker';
                } else if($userData['user_rank'] === 2) {
                    echo 'Like viewer';
                } else if($userData['user_rank'] === 3) {
                    echo 'Premium member';
                } else if($userData['user_rank'] === 4) {
                    echo 'Moderator';
                } else if($userData['user_rank'] === 5) {
                    echo 'Administrator';
                }
                ?></p>
                <a href="shop.php"><button class="buttonSeeMoreMessages">MORE INFO</button></a>
            </div>
        </div>
    </section>

</main>
<?php
include 'src/include/footer.php'
?>