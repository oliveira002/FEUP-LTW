<?php
    /* This action handles the answer of a review by the owner */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_review.php');
    require_once(__DIR__ . '/../db/class_user.php');

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
    if(is_null($_POST['text']) || is_null($_POST['rest']) || is_null($_POST['rating'])){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if user is owner of the restaurant
    $db = getDatabaseConnection();
    if(!User::canReviewRestaurant($db, intval($_POST['rest']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Prevent XSS
    $text = strip_tags($_POST['text']);
    
    // Create answer
    Review::create($db, $session->getId(), intval($_POST['rest']), $text, intval($_POST['rating']));

    // Success
    $session->addMessage('success', 'The review was registed with success.');
    header('Location: ../pages/restaurant.php?id=' . $_POST['rest']);
?>