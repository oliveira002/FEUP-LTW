<?php
    /* This action handles the removal of an product to a menu */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_order.php');

    // Check if user is logged in
    $session = new Session();
    if(!$session->isLoggedIn()){
        header("Location: ../pages/index.php");
        die();
    }

    // Check $_GET
    if(is_null($_GET['idOrder']) || is_null($_GET['newState'])){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if user is owner of the restaurant
    $db = getDatabaseConnection();
    if(!Restaurant::isOwner($db, intval($_GET['idRest']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Change order state
    Order::changeState($db, intval($_GET['idOrder']), $_GET['newState']);
    
    // Success
    $session->addMessage('success', 'The state of order was changed with success.');
    header('Location: ../pages/restaurant_orders.php?id=' . $_GET['idRest']);
?>