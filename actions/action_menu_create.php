<?php
    /* This action handles the removal of an product to a menu */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_menu.php');

    // Check if user is logged in
    $session = new Session();
    if(!$session->isLoggedIn()){
        header("Location: ../pages/index.php");
        die();
    }

    // CSRF
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }
    
    // Check $_POST
    if(is_null($_POST['rest'])){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if user is owner of the restaurant
    $db = getDatabaseConnection();
    if(!Restaurant::isOwner($db, intval($_POST['rest']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // XSS
    $menuName = strip_tags($_POST['name']);

    // Create the menu
    Menu::create($db, array($menuName, $_POST['rest']));

    // Success
    $session->addMessage('success', 'The menu was created with success.');
    header('Location: ../pages/restaurant.php?id=' . $_POST['rest']);
?>