<?php
    /* This action handles the edit of a restaurant */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    // Check if the user is logged in
    if(!$session->isLoggedIn()){
        header("Location: pages/index.php");
        die();
    }

    // CSRF
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }

    // Check restaurant id from $_POST
    if(!isset($_POST['id'])){
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        die();
    }

    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_category.php');

    $db = getDatabaseConnection();
    $allCategories = Category::getCategories($db);

    // Check if user is owner of the restaurant
    if(!Restaurant::isOwner($db, intval($_POST['id']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Get Restaurant
    $restaurant = Restaurant::getRestaurant($db, intval($_POST['id']));
    if($restaurant == null){
        header('Location: ../pages/index.php');
        die();
    }

    // Check form fields
    $fields = verifyAllFields($session);

    // Update restaurant
    $restaurant->updateInformations($db, $fields);

    // Check if the user inserted new files
    if(insertedNewFile('image')){
        $rest_image = "../imgs/rest/background_" . strval($restaurant->id) . ".png";
        move_uploaded_file($_FILES['image']['tmp_name'], $rest_image);
    }
    if(insertedNewFile('logo')){
        $rest_logo = "../imgs/rest/logo_" . strval($restaurant->id) . ".png";
        move_uploaded_file($_FILES['logo']['tmp_name'], $rest_logo);
    }

    // Update restaurant categories with the form checks
    updateCategories($db, $restaurant, $allCategories);

    // Success
    $session->addMessage('success', 'The restaurant was edited with success.');
    header('Location: ../pages/index.php');
?>

<?php function onInvalidField(Session $session, string $msg){
    $session->addMessage('error', $msg);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
} ?>

<?php function verifyAllFields(Session $session) : array{
    $name = strip_tags($_POST['name']);
    $address = strip_tags($_POST['address']);

    if($name == "")
        onInvalidField($session, 'Invalid restaurant name.');

    if($address == "")
        onInvalidField($session, 'Invalid restaurant address.');

    if($_POST['phone'] == "")
        onInvalidField($session, 'Invalid restaurant phone number.');

    $minTime = intval($_POST['min_time']);
    if(is_null($minTime) || $minTime <= 0 || $minTime > 120)
        onInvalidField($session, 'Invalid min. delivery time.');

    $maxTime = intval($_POST['max_time']);
    if(is_null($maxTime) || $maxTime <= 0 || $maxTime > 120 || $maxTime < $minTime)
        onInvalidField($session, 'Invalid max. delivery time.');

    $tax = intval($_POST['tax']);
    if(is_null($tax) || $tax < 0 || $tax > 10)
        onInvalidField($session, 'Invalid tax.');

    return array($name, $_POST['phone'], $tax, $minTime, $address, $maxTime);
} ?>

<?php function insertedNewFile(string $name) : bool{
    return file_exists($_FILES[$name]['tmp_name']) && is_uploaded_file($_FILES[$name]['tmp_name']);
} ?>

<?php function updateCategories(PDO $db, Restaurant $restaurant, array $categories){
    foreach($categories as $category){
        $checked = $_POST['categ_' . $category->id];
        if($checked === 'on')
            $restaurant->addCategory($db, $category->id);
        else
            $restaurant->removeCategory($db, $category->id);
    }
} ?>