<?php
    /* This action handles the create of a restaurant */
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

    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_menu.php');
    require_once(__DIR__ . '/../db/class_category.php');

    $db = getDatabaseConnection();
    $allCategories = Category::getCategories($db);

    // Check form fields
    $fields = verifyAllFields($session);

    // Create the restaurant
    $createdRestaurant = Restaurant::create($db, $fields);
    $createdRestaurant->addOwner($db, $session->getId());

    // Store the images
    $imageLocation = "../imgs/rest/background_" . strval($createdRestaurant->id) . ".png";
    $logoLocaiton = "../imgs/rest/logo_" . strval($createdRestaurant->id) . ".png";

    move_uploaded_file($_FILES['image']['tmp_name'], $imageLocation);
    move_uploaded_file($_FILES['logo']['tmp_name'], $logoLocaiton);

    // Assign restaurant categories
    verifyCategoriesChecked($db, $createdRestaurant, $allCategories);

    // Success
    $session->addMessage('success', 'The restaurant was created with success.');
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

    if($_POST['phone'] == "")
        onInvalidField($session, 'Invalid restaurant phone number.');

    if($address == "")
        onInvalidField($session, 'Invalid restaurant address.');

    if($_POST['priceGroup'] == "")
        onInvalidField($session, 'Invalid price group.');

    $minTime = intval($_POST['min_time']);
    if(is_null($minTime) || $minTime <= 0 || $minTime > 120)
        onInvalidField($session, 'Invalid min. delivery time.');

    $maxTime = intval($_POST['max_time']);
    if(is_null($maxTime) || $maxTime <= 0 || $maxTime > 120 || $maxTime < $minTime)
        onInvalidField($session, 'Invalid max. delivery time.');

    $tax = intval($_POST['tax']);
    if(is_null($tax) || $tax < 0 || $tax > 10)
        onInvalidField($session, 'Invalid tax.');

    if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name']))
        onInvalidField($session, 'Invalid image.');

    if(!file_exists($_FILES['logo']['tmp_name']) || !is_uploaded_file($_FILES['logo']['tmp_name']))
        onInvalidField($session, 'Invalid logo.');

    return array($name, $_POST['phone'], $tax, $minTime, $address, $maxTime, $_POST['priceGroup']);
} ?>

<?php function verifyCategoriesChecked(PDO $db, Restaurant $rest, array $categories){
    foreach($categories as $category){
        $checked = $_POST['categ_' . $category->id];
        if($checked === 'on')
            $rest->addCategory($db, $category->id);
    }
} ?>