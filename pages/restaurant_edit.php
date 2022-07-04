<?php
    require_once(__DIR__ . '/../templates/commom.php');
    require_once(__DIR__ . '/../db/class_category.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if(!$session->isLoggedIn()){
        header("Location: login.php");
        die();
    }

    $id = $_GET['id'];
    if(!isset($id)){
        header("Location: index.php");
        die();
    }

    $db = getDatabaseConnection();

    if(!Restaurant::isOwner($db, $id, $session->getId())){
        header("Location: index.php");
        die();
    }

    $db = getDatabaseConnection();
    $rest = Restaurant::getRestaurant($db, $id);
    $categories = Category::getCategories($db);
    $restCategories = $rest->getCategories($db);

    drawHeader($session, array("new_restaurant.js"), array("restaurant_form.css"));
?>

<main>
    <form action="../actions/action_restaurant_edit.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
        <fieldset>
            <legend>General Information</legend>
            <input type="hidden" name="id" value=<?=$id?>>
            <label>Restaurant Name:<input type="text" name="name" value="<?=$rest->name?>"></label>
            <label>Phone Number:<input type="text" name="phone" value=<?=$rest->phoneNumber?>></label>
            <label>Min. Delivery Time:<input type="number" name="min_time" value=<?=$rest->minTime?>></label>
            <label>Current Address:<input type="text" name="address" value="<?=$rest->address?>"></label>
            <label>Max. Delivery TIme:<input type="number" name="max_time" value=<?=$rest->maxTime?>></label>
            <label>Tax:<input type="number" name="tax" value=<?=$rest->maxTime?>></label>
            <label>
                Categories:
                <?php foreach($categories as $category) { ?>
                    <div class="categ">
                        <input type="checkbox" name="categ_<?=$category->id?>" <?php echo (in_array($category->id, $restCategories) ? 'checked=' : '')?>><?=$category->name?>
                    </div>
                <?php } ?>
            </label>
            <label>New Restaurant Image:<input type="file" name="image" accept="image/*"></label>
            <label>New Restaurant Logo:<input type="file" name="logo" accept="image/*"></label>
        </fieldset>
        <button type="submit">Save Changes</button>
    </form>
</main>

<?php drawFooter(); ?>