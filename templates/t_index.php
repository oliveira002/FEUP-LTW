<?php
    declare(strict_types = 1);

    require_once("../db/class_restaurant.php");
    require_once("../db/class_category.php");
?>

<?php function drawRestaurants(array $restaurants) { ?>
    <ul>
        <?php foreach($restaurants as $rest){ ?>
            <li>
                <a href="../pages/restaurant.php?id=<?=$rest->id?>">
                    <img src=<?=Restaurant::getImage($rest->id)?> alt="">
                    <span class="item_description" id="item_title"><?=$rest->name?></span>
                    <span class="item_description"><?=$rest->minTime?>-<?=$rest->maxTime?> min | Taxa de €<?=$rest->tax?></span>
                    <span id="rest_rating"><?=$rest->rating?></span>
                </a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>

<?php function drawCategories(array $categories) { ?>
    <section id="rest_categ">
        <h1>Explore by category</h1>
        <ul>
            <?php foreach($categories as $categ){ ?>
                <li>
                    <button>
                        <img src=<?= Category::getImage($categ->id); ?> alt="">
                        <?=$categ->name?>
                    </button>
                </li>
            <?php } ?>
        </ul>
    </section>
<?php } ?>

<?php function drawFilters() { ?>
    <nav id="rest_filters" class="sticky">
        <h1></h1>
        <ul>
            <li>
                <h2>Ordenar</h2>
                <input type="radio" id="foryou" name="order" checked="checked">
                <label for="foryou">Escolhido para si</label> 
                <input type="radio" id="popular" name="order" value="foryou">
                <label for="popular">Mais Populares</label> 
                <input type="radio" id="rating" name="order" value="foryou">
                <label for="rating">Classificação</label> 
                <input type="radio" id="time" name="order" value="foryou">
                <label for="time">Tempo de entrega</label> 
            </li>
            <li>
                <h2>Intervalo de preço</h2>
                <input type="radio" id="low-cost" name="price">
                <label for="low-cost">$</label> 
                <input type="radio" id="med-cost" name="price">
                <label for="med-cost">$$</label> 
                <input type="radio" id="high-cost" name="price">
                <label for="high-cost">$$$</label> 
            </li>
            <li>
                <h2>Tempo de entrega máxima</h2>
                <input type="radio" id="max5" name="time">
                <label for="max5">< 5 min</label> 
                <input type="radio" id="max15" name="time">
                <label for="max15">< 15 min</label> 
                <input type="radio" id="max30" name="time">
                <label for="max30">< 30 min</label> 
                <input type="radio" id="max60" name="time">
                <label for="max60">< 60 min</label> 
            </li>
        </ul>
    </nav>
<?php } ?>