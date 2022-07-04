<?php
    declare(strict_types = 1);
    require_once("../db/class_restaurant.php");
    require_once("../db/class_product.php");
    require_once("../db/class_order.php");
?>

<?php function drawOrders(PDO $db,array $orders) { ?>
    <?php foreach($orders as $order){ ?>
        <?php $rest = Restaurant::getRestaurant($db,$order->idRestaurant) ?>

        <span class="tr">
            <span class="td">#<?=$order->id?></span>
            <span class="td"><?=$order->date?></span>
            <span class="td"><?=$order->state?></span>
            <span class="td"><?=$rest->name?></span>
            <a href="profile_order_page.php?id=<?= $order->id?>" class="td">View</a>
        </span>
    <?php } ?> 
<?php } ?>

<?php function drawOrder(PDO $db,int $idO){ ?>
    <?php $ord = Order::getOrder($db,$idO) ?>
    <?php $prods = Product::getOrderProducts($db,$ord->id) ?>
    <?php $rest = Restaurant::getRestaurant($db,$ord->idRestaurant)?>
    <?php $price = 0 ?>
    <div class="recentOrders">
            <h2> <?=$rest->name ?> </h2>
            <div class = "orders">
                <section class="table">
                    <h2 class="th tr">
                        <span class="td">Product</span>
                        <span class="td">Quantity</span>
                        <span class="td">Price</span>
                    </h2>

                    <?php foreach($prods as $prod) { ?>
                        <span class="tr">
                            <span class="td">
                                <img src= <?=Product::getImage($prod->idProduct)?>>
                            </span>
                            <span class="td"><?=$prod->quantity?></span>
                            <span class="td"><?=number_format($prod->price * $prod->quantity, 2)?> €</span>
                        </span>

                        <?php $price += $prod->price * $prod->quantity ?>
                    <?php } ?>

                    <span class="tr">
                        <span class="td"></span>
                        <span class="td">Total:</span>
                        <span class="td"><?=number_format($price,2)?> €</span>
                    </span>
                </section>
        </div>
<?php } ?>
