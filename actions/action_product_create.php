<?php
    /* This action handles the creation of an product */
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../db/connection.db.php');
    require_once(__DIR__ . '/../db/class_restaurant.php');
    require_once(__DIR__ . '/../db/class_product.php');

    // Check if user is logged in
    $session = new Session();
    if(!$session->isLoggedIn()){
        header("Location: ../pages/index.php");
        die();
    }

    // CSRF
    if ($_SESSION['csrf'] !== $_POST['csrf']){
        header("Location: ../pages/index.php");
        die();
    }

    // Check $_POST
    if(is_null($_POST['rest'])){
        header("Location: ../pages/index.php");
        die();
    }

    // Check if user is owner
    $db = getDatabaseConnection();
    if(!Restaurant::isOwner($db, intval($_POST['rest']), $session->getId())){
        header("Location: ../pages/index.php");
        die();
    }

    // Check form fields
    $fields = verifyAllFields($session);

    // Create the product
    $createdProduct = Product::create($db, $fields);

    // Store the image
    $imageLocation = "../imgs/prod/" . strval($createdProduct->idProduct) . ".webp";
    move_uploaded_file($_FILES['image']['tmp_name'], $imageLocation);

    // Success
    $session->addMessage('success', 'The product was created with success.');
    header('Location: ../pages/restaurant.php?id=' . $_POST['rest']);
?>

<?php function onInvalidField(Session $session, string $msg){
    $session->addMessage('error', $msg);
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die();
} ?>

<?php function verifyAllFields(Session $session) : array{
    $name = strip_tags($_POST['name']);

    if($_POST['menu'] == "")
        onInvalidField($session, 'Invalid menu.');

    if($_POST['rest'] == "")
        onInvalidField($session, 'Invalid restaurant.');

    if($name == "")
        onInvalidField($session, 'Invalid restaurant.');

    $price = floatval($_POST['price']);
    if(is_null($price) || $price <= 0 || $price > 120)
        onInvalidField($session, 'Invalid price.');

    if(!file_exists($_FILES['image']['tmp_name']) || !is_uploaded_file($_FILES['image']['tmp_name']))
        onInvalidField($session, 'Invalid image.');

    return array($name, $price, $_POST['menu']);
} ?>