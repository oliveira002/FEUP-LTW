<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../db/connection.db.php');
  require_once(__DIR__ . '/../db/class_restaurant.php');

  $db = getDatabaseConnection();

  if(is_null($_GET['search'])
  || is_null($_GET['count'])
  || is_null($_GET['order'])
  || is_null($_GET['price'])
  || is_null($_GET['maxTime'])
  || is_null($_GET['category']))
    die();

  $restaurants = Restaurant::getListRestaurants(
    $db,
    intval($_GET['count']),
    $_GET['order'],
    $_GET['price'],
    intval($_GET['maxTime']),
    $_GET['search'],
    intval($_GET['category'])
  );

  echo json_encode($restaurants);
?>