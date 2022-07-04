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

    // Check $_GET
    if(is_null($_GET['rest']) || is_null($_GET['menu'])){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if user is owner of the restaurant
    $db = getDatabaseConnection();
    if(!Restaurant::isOwner($db, intval($_GET['rest']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if menu belongs to the restaurant
    if(!Menu::exists($db, intval($_GET['rest']), intval($_GET['menu']))){
        header("Location: ../pages/index.php");
        die();
    }

    // Erase the menu
    Menu::erase($db, intval($_GET['menu']));

    // Success
    $session->addMessage('success', 'The menu was deleted with success.');
    header('Location: ../pages/restaurant.php?id=' . $_GET['rest']);
?>