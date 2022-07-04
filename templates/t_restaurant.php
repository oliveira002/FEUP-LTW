<?php declare(strict_types = 1); ?>

<?php function drawMenus(array $menus, string $restID, bool $owner = false) { ?>
    <section id="menus">
        <?php foreach($menus as $menu){ ?>
            <div class="list">
                <h1 id=<?=$menu->id?>><?=$menu->name?></h1>
                <ul>
                    <?php foreach($menu->products as $prod) { ?>
                        <li>
                            <button>
                                <span class="hidden"><?=$prod->idProduct?></span>
                                <img src=<?=Product::getImage($prod->idProduct)?> alt="" height="180">
                                <span class="item_description" id="item_title"><?=$prod->name?></span>
                                <span class="item_description"><?=$prod->price?>€</span>
                                <img src=<?=$prod->favorite ? "../imgs/heart-solid.svg" : "../imgs/heart-regular.svg"?> alt="" class="prod_btn">
                                <img src="../imgs/plus.png" alt="" class="prod_btn">
                            </button>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <?php if($owner) { ?>
            <form action="../actions/action_menu_create.php" method="post" id="addMenuForm">
                <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">
                <legend>Create a new menu:</legend>
                <input type="hidden" name="rest" value=<?=$restID?>>
                <label>Menu name:<input type="text" name="name"></label>
                <button type="submit">Create</button>
            </form>
        <?php } ?>
    </section>
<?php } ?>

<?php function drawMenuNav(array $menus, int $restID, bool $isOwner) { ?>
    <nav id="rest_menus" class="sticky">
        <ul>
            <?php foreach($menus as $menu){ ?>
                <li>
                    <a href="#<?=$menu->id?>"><?=$menu->name?></a>
                    <?php if($isOwner) { ?>
                        <a href="../pages/restaurant_menu_edit.php?menuID=<?=$menu->id?>&id=<?=$restID?>" class="editMenu">
                            <img src="../imgs/pen-to-square-solid.svg" alt="Edit menu">
                        </a>
                        <a href="../actions/action_menu_erase.php?menu=<?=$menu->id?>&rest=<?=$restID?>" class="editMenu">
                            <img src="../imgs/trash-can-solid.svg" alt="Edit menu">
                        </a>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
    </nav>
<?php } ?>

<?php function drawRestHeader(Restaurant $rest, bool $isOwner) { ?>
    <img src=<?=Restaurant::getImage($rest->id)?> alt="" id="rest_img">
    <section id="rest_header">
        <h1>
            <?php 
                $filePath = Restaurant::getLogo($rest->id);
                if(file_exists($filePath)){ ?>
                    <img src=<?=$filePath?>>
                <?php }
            ?>
            <?=$rest->name?>
            <?php if($isOwner) { ?>
                <a href="../pages/restaurant_edit.php?id=<?=$rest->id?>" class="editMenu">
                    <img src="../imgs/pen-to-square-solid.svg" alt="Edit menu">
                </a>
                <a href="../actions/action_restaurant_delete.php?id=<?=$rest->id?>" class="editMenu">
                    <img src="../imgs/trash-can-solid.svg" alt="Edit menu">
                </a>
                <a href="../pages/restaurant_orders.php?id=<?=$rest->id?>" class="editMenu">
                    <img src="../imgs/clipboard-list-solid.svg" alt="See order list">
                </a>
            <?php } ?>
            <a href="../actions/action_favorite_restaurant.php?id=<?=$rest->id?>" class="editMenu">
                <img src=<?=$rest->favorite ? "../imgs/heart-solid.svg" : "../imgs/heart-regular.svg"?> alt="Edit menu">
            </a>
        </h1>
        <div id = "rating">
            <img src="../imgs/star.png" alt="">
            <?=$rest->rating?> (<?=$rest->numReviews?> classificações)
        </div>
        <div>
            <?=$rest->minTime?>-<?=$rest->maxTime?> min - Taxa de €<?=$rest->tax?>
        </div>
        <div>
            <?=$rest->address?>
        </div>
    </section>
<?php } ?>

<?php function drawReviews(array $reviews, bool $isOwner, int $restID) { ?>
    <ul>
        <?php foreach($reviews as $review){ ?>
            <li>
                <article class="review">
                    <span id="review_user"><?=$review->username?></span>
                    <div id="reviews_details">
                        <?php 
                            $r = $review->rating;
                            for($i = 1; $i <= 5; $i++){
                                if($r >= $i){
                        ?>
                            <img src="../imgs/star.png" alt="">
                        <?php } else { ?>
                            <img src="../imgs/white_star.png" alt="">
                        <?php } } ?>
                        
                        <?=$review->date?>
                    </div>
                    <p><?=$review->comment?></p>
                </article>
                <?php if($review->answer !== "") { ?>
                    <article id="review_answer">
                        <h1>Answer:</h1>
                        <div id="reviews_details">    
                            <?=$review->date?>
                        </div>
                        <p><?=$review->answer?></p>
                    </article>
                <?php } else {
                    if($isOwner){ ?>
                        <form action="../actions/action_review_answer.php" class="reviewForm" method="post">
                            <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']?>">    
                            <legend>Answer comment:</legend>
                            <input type="hidden" name="reviewID" value=<?=$review->id?>>
                            <input type="hidden" name="rest" value=<?=$restID?>>
                            <label>Text:<textarea name="text"></textarea></label>
                            <button type="submit">Submit</button>
                        </form>
                    <?php }
                } ?>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

<?php // Data will be replaced when popup is opened
function drawProductPopup() { ?>
    <div id="product_popup" class="hidden">
        <span class="hidden">id</span>
        <button type="button" id="outside_product_popup"></button>
        <section>
            <div class="close">x</div>
            <img src="../imgs/prod/10.webp" alt="">
            <h1>Menu Double Cheese Bacon XXL ®</h1>
            <h2>9,50 €</h2>
            <div id="custom_input_number">
                <div>
                    <button id="product_decrement" onclick="stepper(this)">-</button>
                    <input type="number" min="1" max="15" step="1" value="1" id="product_quantity" onkeydown="return false">
                    <button id="product_increment" onclick="stepper(this)">+</button>
                </div>
                <button id="place_product">
                    Adicionar ao carrinho - &nbsp;
                    <span id="product_price">8.30€</span>
                </button>
            </div>
        </section>
    </div>
<?php } ?>