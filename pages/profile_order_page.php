<?php
    require_once("../templates/t_index.php");
    require_once("../templates/commom.php");
    require_once("../templates/t_order.php");
    require_once("../templates/t_profile.php");
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
    $id = $_GET["id"];
?>

<?php 
drawProfileHeader($user, $session);
drawOrder($db,$id);
?>

</main>

<?php drawFooter(); ?>