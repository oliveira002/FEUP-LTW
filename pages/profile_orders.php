<?php
    require_once("../templates/t_index.php");
    require_once("../templates/commom.php");
    require_once("../db/connection.db.php");
    require_once("../db/class_user.php");
    require_once("../templates/t_order.php");
    require_once("../templates/t_profile.php");


    $db = getDatabaseConnection();

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()) {
        header('Location: index.php');
        die();
    }

    $orders = Order::getOrders($db,$session->getId());
    $user = User::getCurrentUser($db,$session->getId());
?>

<?php drawProfileHeader($user, $session) ?>

<div class="recentOrders">
    <h2> Your Orders </h2>
    <div class = "orders">
        <section class="table">
            <h2 class="th tr">
                <span class="td">Order</span>
                <span class="td">Date</span>
                <span class="td">Status</span>
                <span class="td">Restaurant</span>
                <span class="td"></span>
            </h2>
                
            <?php drawOrders($db, $orders) ?>
        </section>
    </div>
</div>
</main>
    
<?php
    drawFooter();
?>