<?php
    require_once('../db/connection.db.php');
    require_once('../db/class_restaurant.php');
    require_once('../db/class_menu.php');
    require_once('../db/class_review.php');
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

    if($session->isLoggedIn())
        $rest->fillFavorite($db, $session->getId());

    $menus = Menu::getMenus($db, $id);
    foreach($menus as $menu){
        foreach($menu->products as $prod){
            if($session->isLoggedIn())
                $prod->fillFavorite($db, $session->getId());
        }
    }
    $reviews = Review::getReviews($db, $id);

    $isOwner = $session->isLoggedIn() ? Restaurant::isOwner($db, $id, $session->getId()) : false;

    $canReview = $session->isLoggedIn() ? User::canReviewRestaurant($db, $id, $session->getId()) : false;

    drawHeader($session, array("restaurant.js"), array("restaurant.css"));
?>

<main>
    <?php drawRestHeader($rest, $isOwner) ?>
    <?php drawMenuNav($menus, $rest->id, $isOwner) ?>
    <?php drawMenus($menus, $id, $isOwner) ?>

    <section id="reviews">
        <h1>Reviews</h1>
        <?php if(empty($reviews)){ ?>
            <h2>No reviews yet, be the first!</h2>
        <?php } else {
            drawReviews($reviews, $isOwner, $rest->id);
        } ?>

        <?php if($canReview) { ?>
            <section>
                <form action="../actions/action_review_create.php" class="reviewForm" method="post">
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                    <legend>Leave a review:</legend>
                    <input type="hidden" name="rest" value=<?=$id?>>
                    <label>Rating:<input name="rating" type="number"><br></label>
                    <label>Text:<textarea name="text"></textarea></label>
                    <button type="submit">Submit</button>
                </form>
            </section>
        <?php } ?>
    </section>
</main>

<?php drawProductPopup() ?>
<?php drawFooter(); ?>