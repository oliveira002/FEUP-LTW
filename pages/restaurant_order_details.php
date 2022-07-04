<?php
    require_once('../db/connection.db.php');
    require_once('../db/class_restaurant.php');
    require_once('../db/class_order.php');
    require_once('../db/class_user.php');

    require_once('../templates/commom.php');
    require_once('../templates/t_restaurant.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    $idRestaurant = $_GET['idRestaurant'];
    if($idRestaurant == NULL){
        header("Location: index.php");
        die();
    }

    $idOrder = $_GET['idOrder'];
    if($idOrder == NULL){
        header("Location: index.php");
        die();
    }

    $db = getDatabaseConnection();
    $rest = Restaurant::getRestaurant($db, $idRestaurant);
    if($rest == NULL){
        header("Location: index.php");
        die();
    }

    $isOwner = $session->isLoggedIn() ? Restaurant::isOwner($db, $idRestaurant, $session->getId()) : false;
    if(!$isOwner){
        header("Location: index.php");
        die();
    }

    $order = Order::getOrder($db, intval($idOrder));
    $products = Product::getOrderProducts($db, intval($idOrder));

    drawHeader($session, array("restaurant.js"), array("restaurant.css"));
?>

<main>
    <h1 id="table_title">Details of order #<?=$order->id?></h1>
    <section class="table">
        <h2 class="th tr">
            <span class="td">Image</span>
            <span class="td">Name</span>
            <span class="td">Quantity</span>
            <span class="td">Price</span>
        </h2>
        <?php foreach($products as $prod) { ?>
            <article class="tr">
                <span class="td"><img src=<?=Product::getImage($prod->idProduct)?> alt=""></span>
                <span class="td"><?=$prod->name?></span>
                <span class="td"><?=$prod->quantity?></span>
                <span class="td"><?=number_format($prod->price * $prod->quantity, 2)?>€</span>
            </article>
        <?php } ?>
    </section>
</main>

<?php drawFooter(); ?>