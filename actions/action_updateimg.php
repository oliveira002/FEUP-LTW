<?php
    require_once("../templates/t_index.php");
    require_once("../templates/commom.php");
    require_once("../db/connection.db.php");
    require_once("../db/class_user.php");
    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $db = getDatabaseConnection();

    $user = User::getCurrentUser($db,$session->getId());

    if(!$session->isLoggedIn()) {
        header('Location: index.php');
        die();
    }

    $imageLocation = "../imgs/user/" . strval($user->id) . ".png";

    function onInvalidField(Session $session, string $msg){
        $session->addMessage('error', $msg);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    } 

    if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name'])) {
        onInvalidField($session, 'Invalid image.');
    }

    if(isset($_POST['upload'])) {
        move_uploaded_file($_FILES['image']['tmp_name'], $imageLocation);
        $session->addMessage('success', 'Imaged Uploaded Successfully!');
    }

    header('Location: ../pages/profile.php');
?>