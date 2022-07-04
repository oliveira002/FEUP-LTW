<?php
    require_once(__DIR__ . '/../templates/commom.php');
    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/class_menu.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/connection.db.php');

    // Check if user is logged in
    $session = new Session();
    if(!$session->isLoggedIn()){
        header("Location: login.php");
        die();
    }

    // Check GET parameters
    $restID = $_GET['rest'];
    $menuID = $_GET['menu'];
    if(is_null($restID) || is_null($menuID)){
        header("Location: index.php");
        die();
    }

    // Check if user is the owner of the restaurant
    $db = getDatabaseConnection();
    if(!Restaurant::isOwner($db, $restID, $session->getId())){
        header("Location: index.php");
        die();
    }

    // Check if the menu exists
    if(!Menu::exists($db, $restID, $menuID)){
        header("Location: index.php");
        die();
    }

    $existingProducts = Product::getProducts($db, $menuID);

    drawHeader($session, array(), array('restaurant_form.css'));
?>

<main>
    <h1>Remove a product from the menu:</h1>

    <section>
        <h2>Existing products:</h2>
        <?php if(empty($existingProducts)) { ?>
            No existing products found.
        <?php } else { ?>
            <ul>
                <?php foreach($existingProducts as $product) { ?>
                    <li><a href="../actions/action_menu_remove_prod.php?rest=<?=$_GET['rest']?>&menu=<?=$_GET['menu']?>&prod=<?=$product->idProduct?>">
                        <img src=<?=Product::getImage($product->idProduct)?> alt="">
                        <span><?=$product->name?></span>
                        <span><?=$product->price?>€</span>
                    </a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </section>
</main>

<?php drawFooter(); ?>