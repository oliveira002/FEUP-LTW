<?php
    require_once('../db/connection.db.php');
    require_once('../db/class_restaurant.php');
    require_once('../db/class_menu.php');
    require_once('../db/class_review.php');

    require_once('../templates/commom.php');
    require_once('../templates/t_restaurant.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    $id = intval($_GET['id']);
    $menuID = $_GET['menuID'];
    if($id == NULL || $menuID == NULL){
        header("Location: index.php");
        die();
    }

    $db = getDatabaseConnection();
    $rest = Restaurant::getRestaurant($db, $id);
    if($rest == NULL){
        header("Location: index.php");
        die();
    }

    // Check if user is owner
    if(!Restaurant::isOwner($db, $id, $session->getId())){
        header("Location: index.php");
        die();
    }

    // Check if the menu exists
    if(!Menu::exists($db, $id, $menuID)){
        header("Location: index.php");
        die();
    }

    // Get all products of the restaurant
    $existingProducts = Product::getProductsOfRestaurant($db, intval($id));

    $menu = Menu::getMenu($db, $menuID);
    $isOwner = $session->isLoggedIn() ? Restaurant::isOwner($db, $id, $session->getId()) : false;

    drawHeader($session, array("restaurant.js"), array("restaurant.css", "restaurant_form.css", "restaurant_menu_edit.css"));
?>

<main>
    <h1>You are editing menu: <?=$menu->name?></h1>
    <h1>Remove a product:</h1>
    <?php drawEditableMenu($menu, $id) ?>

    <h1>Add a existing product:</h1>
    <?php drawExistingProducts($existingProducts) ?>

    <h1>Create a new product:</h1>
    <form action="../actions/action_product_create.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">    
        <input type="hidden" name="menu" value=<?=$menuID?>>
        <input type="hidden" name="rest" value=<?=$id?>>
        <label>Name:<input type="text" name="name"></label>
        <label>Price:<input type="number" name="price" step=0.1></label>
        <label>Image:<input type="file" name="image" accept="image/*"></label>
        <button type="submit">Finish</button>
    </form>
</main>

<?php function drawEditableMenu(Menu $menu, string $restID) { ?>
    <section id="menus">
        <div class="list">
            <ul>
                <?php foreach($menu->products as $prod) { ?>
                    <li>
                        <a href="../actions/action_menu_remove_prod.php?rest=<?=$_GET['id']?>&menu=<?=$_GET['menuID']?>&prod=<?=$prod->idProduct?>">
                            <button>
                                <span class="hidden"><?=$prod->id?></span>
                                <img src=<?=Product::getImage($prod->idProduct)?> alt="" height="180">
                                <span class="item_description" id="item_title"><?=$prod->name?></span>
                                <span class="item_description"><?=$prod->price?>€</span>
                                <img src="../imgs/circle-minus-solid.svg" alt="">
                            </button>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </section>
<?php } ?>

<?php function drawExistingProducts(array $existingProducts){
    if(empty($existingProducts)) { ?>
        No existing products found.
    <?php } else { ?>
        <section id="menus">
            <div class="list">
                <ul>
                    <?php foreach($existingProducts as $prod) { ?>
                        <li>
                            <a href="../actions/action_menu_add_prod.php?rest=<?=$_GET['id']?>&menu=<?=$_GET['menuID']?>&prod=<?=$prod->id?>">
                                <button>
                                    <span class="hidden"><?=$prod->id?></span>
                                    <img src=<?=Product::getImage($prod->idProduct)?> alt="" height="180">
                                    <span class="item_description" id="item_title"><?=$prod->name?></span>
                                    <span class="item_description"><?=$prod->price?>€</span>
                                    <img src="../imgs/plus.png" alt="">
                                </button>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </section>
    <?php } ?>
<?php } ?>

<?php drawFooter(); ?>