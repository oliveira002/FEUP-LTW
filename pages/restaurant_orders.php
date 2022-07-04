<?php
    require_once('../db/connection.db.php');
    require_once('../db/class_restaurant.php');
    require_once('../db/class_order.php');
    require_once('../db/class_user.php');

    require_once('../templates/commom.php');
    require_once('../templates/t_restaurant.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    $id = $_GET['id'];
    if($id == NULL){
        header("Location: index.php");
        die();
    }

    $db = getDatabaseConnection();
    $rest = Restaurant::getRestaurant($db, $id);
    if($rest == NULL){
        header("Location: index.php");
        die();
    }

    $isOwner = $session->isLoggedIn() ? Restaurant::isOwner($db, $id, $session->getId()) : false;
    if(!$isOwner){
        header("Location: index.php");
        die();
    }

    $orders = Order::getRestaurantOrders($db, intval($id));

    drawHeader($session, array("order_state.js"), array("restaurant.css", "table.css"));
?>

<main>
    <h1 id="table_title">Restaurant active orders:</h1>
    <section class="table">
        <h2 class="th tr">
            <span class="td">ID</span>
            <span class="td">Date</span>
            <span class="td">Status</span>
            <span class="td">User</span>
            <span class="td"></span>
        </h2>
        <?php foreach($orders as $order) { ?>
            <form class="tr">
                <span class="td">#<?=$order->id?></span>
                <span class="td"><?=$order->date?></span>
                <select class="td" name="state">
                    <option value="received" <?php echo $order->state === "received" ? "selected" : ""?>>Received</option>
                    <option value="preparing" <?php echo $order->state === "preparing" ? "selected" : ""?>>Preparing</option>
                    <option value="ready" <?php echo $order->state === "ready" ? "selected" : ""?>>Ready</option>
                    <option value="delivered" <?php echo $order->state === "delivered" ? "selected" : ""?>>Delivered</option>
                </select>
                <span class="td"><?=User::getName($db, $order->idUser)?></span>
                <a href="restaurant_order_details.php?idRestaurant=<?=$_GET['id']?>&idOrder=<?=$order->id?>" class="td">View Details</a>
            </form>
        <?php } ?>
    </section>
</main>

<?php drawFooter(); ?>