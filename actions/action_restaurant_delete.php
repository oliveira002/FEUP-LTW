<?php
    /* This action handles the removal of a restaurant */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    // Check if the user is logged in
    if(!$session->isLoggedIn()){
        header("Location: pages/index.php");
        die();
    }

    // CSRF
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }

    // Check restaurant id from $_GET
    if(!isset($_GET['id'])){
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');

    $db = getDatabaseConnection();

    // Check if user is owner of the restaurant
    if(!Restaurant::isOwner($db, intval($_GET['id']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Get restaurant
    $restaurant = Restaurant::getRestaurant($db, intval($_GET['id']));
    if($restaurant == null){
        header('Location: ../pages/index.php');
        die();
    }

    // Delete restaurant
    deleteImages($restaurant);
    $restaurant->erase($db);

    // Success
    $session->addMessage('success', 'The restaurant was deleted with success.');
    header('Location: ../pages/index.php');
?>

<?php function deleteImages(Restaurant $restaurant){
    $rest_image = "../imgs/rest/background_" . strval($restaurant->id) . ".png";
    $rest_logo = "../imgs/rest/logo_" . strval($restaurant->id) . ".png";
    unlink($rest_image);
    unlink($rest_logo);
} ?>