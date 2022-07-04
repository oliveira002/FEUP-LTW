<?php
    require_once("../templates/t_profile.php");
    require_once("../templates/commom.php");
    require_once("../db/connection.db.php");
    require_once("../db/class_user.php");

    $db = getDatabaseConnection();

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    if(!$session->isLoggedIn()) {
        header('Location: index.php');
        die();
    }

    $user = User::getCurrentUser($db,$session->getId());
    $rests = Restaurant::getRestaurants($db, $session->getId());
?>

<?php drawProfileHeader($user, $session) ?>

<div class="recentOrders">
    <h2> Favourite Restaurants </h2>
    <div class = "picBlock">
        <?php drawRestaurantsProfile($rests); ?>
    </div>
</div>
</main>
    
<?php
    drawFooter();
?>