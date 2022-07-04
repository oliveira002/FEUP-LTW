<?php
    /* This action handles the favorite of a product */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_product.php');
    
    // Check if user is logged in
    $session = new Session();
    if(!$session->isLoggedIn()){
        header("Location: ../pages/login.php");
        die();
    }

    // Check $_GET
    if(is_null($_GET['id'])){
        header("Location: ../pages/index.php");
        die();
    }

    $db = getDatabaseConnection();
    
    // Toggle product favorite
    $nowIsFavorite = Product::favorite($db, intval($_GET['id']), $session->getId());

    // Success
    $session->addMessage('success', 'The product was ' . ($nowIsFavorite ? 'added to' : 'removed from') . ' the favorites.');
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>