<?php
    require_once(__DIR__ . '/../templates/commom.php');
    require_once(__DIR__ . '/../db/class_category.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()){
        header("Location: login.php");
        die();
    }

    $db = getDatabaseConnection();
    $categories = Category::getCategories($db);

    drawHeader($session, array("new_restaurant.js"), array("restaurant_form.css"));
?>

<main>
    <form action="../actions/action_restaurant_create.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
        <fieldset>
            <legend>General Information</legend>
            <label>Restaurant Name:<input type="text" name="name"></label>
            <label>Phone Number:<input type="text" name="phone"></label>
            <label>Min. Delivery Time:<input type="number" name="min_time"></label>
            <label>Max. Delivery TIme:<input type="text" name="max_time"></label>
            <label>Current Address:<input type="text" name="address"></label>
            <label>Tax:<input type="text" name="tax"></label>
            <label>
                Categories:
                <?php foreach($categories as $category) { ?>
                    <div class="categ">
                        <input type="checkbox" name="categ_<?=$category->id?>"><?=$category->name?>
                    </div>
                <?php } ?>
            </label>
            <label>
                Price Group:
                    <select class="td" name="priceGroup">
                        <option value="low-cost">Low-Cost</option>
                        <option value="med-cost">Medium-Cost</option>
                        <option value="high-cost">High-Cost</option>
                    </select>
            </label>
            <label>Restaurant Image:<input type="file" name="image" accept="image/*"></label>
            <label>Restaurant Logo:<input type="file" name="logo" accept="image/*"></label>
        </fieldset>
        <button type="submit">Finish</button>
    </form>
</main>

<?php drawFooter(); ?>