<?php
    /* This action handles the user login */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    // The user shouldn't be already logged in
    if($session->isLoggedIn()){
        header("Location: pages/index.php");
        die();
    }

    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_user.php');

    $db = getDatabaseConnection();

    $user = User::getUserWithPassword($db, $_POST['email'], $_POST['password']);

    if ($user) {
        $session->setId($user->id);
        $session->setName($user->name());
        $session->addMessage('success', 'Login successful!');
        header('Location: ../pages/index.php');
        die();
    } else {
        $session->addMessage('error', 'Wrong password!');
    }

    header('Location: ../pages/login.php');
?>