<?php
    require_once("../templates/t_profile.php");
    require_once("../templates/commom.php");
    require_once("../db/connection.db.php");
    require_once("../db/class_user.php");

    $db = getDatabaseConnection();
    
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();
    if(!$session->isLoggedIn()) {
        header('Location: index.php');
        die();
    }

    $rests = Restaurant::getFavorites($db, $session->getId());
    $prods = Product::getFavorites($db, $session->getId());
    
    $user = User::getCurrentUser($db,$session->getId());
?>

<?php drawProfileHeader($user, $session) ?>
    <div class="profile">
        <div id="pic">
            <form action="../actions/action_updateimg.php" method = "post" enctype="multipart/form-data">
                <div id="personPic">
                    <div class="image-upload">
                        <label for="file-input">
                            <img id = "person" src=<?=User::getImage($user->id)?>>
                        </label>
                        <input id = "file-input" type="file" name="image" accept="image/*" style="display:none;">
                    </div>
                    <div id="shortInfo">
                        <p><?=$user->name()?></p>
                        <p><?=$user->phoneNumber?></p>
                        <button type = "submit" name = "upload" id = "profPic">
                            <p>Upload</p>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="updateInfo">
            <form class="formProf" id="login" method = "POST" action = "../actions/action_update.php">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                
                <h2 class="header"> Edit Profile</h2>
                <h3 class="header2nd"> First Name</h3>
                <div class="data">
                    <input type="text" class="formData" name = "firstName" value = "<?=$user->firstName?>">
                </div>
                <h3 class="header2nd"> Last Name</h3>
                <div class="data">
                    <input type="text" class="formData" name = "lastName" value = "<?=$user->lastName?>">
                </div>
                <h3 class="header2nd"> Password</h3>
                <div class="data">
                    <input type="password" class="formData" name = "password" value="Password">
                </div>
                <h3 class="header2nd"> Email</h3>
                <div class="data">
                    <input type="text" class="formData" name = "email" value="<?=$user->email?>">
                </div>
                <h3 class="header2nd"> Adress</h3>
                <div class="data">
                    <input type="text" class="formData" name = "adress" value="<?=$user->address?>">
                </div>
                <h3 class="header2nd"> Phone Number</h3>
                <div class="data">
                    <input type="text" class="formData" name = "phone" value="<?=$user->phoneNumber?>">
                </div>
                <div class="btn">
                    <button type="submit" value = "Update" name ="update">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
        <?php if(sizeof($rests) != 0) { ?>
            <div class="recentOrders resp1500">
                <h2> Favourite Restaurants </h2>
                <div class = "pic">
                <?php drawRestaurantsProfile($rests); ?>
                </div>
                <div id = "seeMore">
                        <a href = "profile_favorite_restaurants.php"> View All </a>
                    </div>
            </div>
        <?php } ?>
        <?php if(sizeof($prods) != 0) { ?>
            <div class="recentOrders resp1500">
                <h2> Favourite Food </h2>
                <div class = "pic">
                <?php drawProductsProfile($prods); ?>
                </div>
                <div id = "seeMore">
                    <a href = "profile_favorite_products.php"> View All </a>
                </div>
            </div>
        <?php } ?>
</main>

<?php
    drawFooter();
?>