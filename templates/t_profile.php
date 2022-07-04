<?php
    declare(strict_types = 1);

    require_once("../db/class_restaurant.php");
    require_once("../db/class_product.php");
    require_once("../db/class_user.php");
?>

<?php function drawProfileHeader(User $user, Session $session) { ?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Big Eats</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/profile.css" rel="stylesheet">
    <link href="../css/table.css" rel="stylesheet">
    <script src="../js/orders.js" defer></script>
</head>

<body>
    <div class="wrapper">
        <header>
            <div class="leftHeader">
                <h1><a href="../pages/index.php">Big Eats</a></h1>
            </div>
            <ul id="options">
                <li><a href="#">Help</a></li>
                <li><a href="#"><?=$user->name()?></a></li>
            </ul>
        </header>

        <?php drawMessages($session) ?>
        <main>
        <?php drawProfileNav() ?>
<?php } ?>

<?php function drawRestaurantsProfile(array $restaurants) { ?>
        <?php foreach($restaurants as $rest){ ?>
            <div class = "order">
            <a href="../pages/restaurant.php?id=<?=$rest->id?>">
                <button>
                        <img src=<?=Restaurant::getImage($rest->id)?> alt="" width = "90" height="90">
                        <div id = "prodInfo">
                            <p class="item_description" id="item_title"><?=$rest->name?></p>
                            <p class="item_description"><?=$rest->minTime?>-<?=$rest->maxTime?> min | Taxa de €<?=$rest->tax?></p>
                         </div>
                </button>
            </a>
        </div>
        <?php } ?>
<?php } ?>

<?php function drawProductsProfile(array $products) { ?>
        <?php foreach($products as $prod){ ?>
            <div class = "order">
            <a>
                <button>
                        <img src=<?=Product::getImage($prod->idProduct)?> alt="" width = "90" height="90">
                        <div id = "prodInfo">
                            <p class="item_description" id="item_title"><?=$prod->name?></p>
                            <p class="item_description"><?=$prod->price?></p>
                         </div>
                </button>
            </a>
        </div>
        <?php } ?>
<?php } ?>

<?php function drawProfileNav() { ?>
    <div class="container">
        <ul>
            <li> <a href = "index.php"> <button class="nav">Order food</button> </li> </a>
            <li> <a href = "profile_orders.php"> <button class="nav">My Orders </button> </li> </a>
            <li> <a href = "profile_favorite_restaurants.php"> <button class="nav">Favourite Restaurants </button> </li> </a>
            <li> <a href = "profile_favorite_products.php"> <button class="nav">Favourite Products </button> </li> </a>
            <li> <a href = "profile.php"> <button class="nav">Edit Profile </button></li> </a>
        </ul>
    </div>
<?php } ?>