<?php
    require_once('../db/connection.db.php');
    require_once('../db/class_user.php');
    require_once('../db/class_order.php');

    require_once('../templates/commom.php');
    require_once('../templates/t_restaurant.php');

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $db = getDatabaseConnection();

    if($session->isLoggedIn() == false){
        header('Location: login.php');
        die();
    }
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }
    
    $total = 0.0;
       
    
    $cartCookie = $_COOKIE["cart_to_checkout"];
    $cartList = explode(",",$cartCookie);
    foreach($cartList as $entry){ 
        $arr = explode(":",$entry);
        $quantity = $arr[1];
        $id = $arr[0];
        $prod = Product::getProduct($db,$id);
        $total = $total + ($prod->price * $quantity);
    } 

    $user = User::getCurrentUser($db,$session->getId());

    $arr = explode(":",$entry);

    $restID = Product::getProductRestaurantId($db,intval($arr[0]));

    $awnser = Order::PushOrderDB($db,$cartList,$total,$restID,$user);
    setcookie('cart_to_checkout',"",time() - 3600, "/");
    header('Location: /pages/profile_order_page.php?id=' . $awnser);
    die();
?>
