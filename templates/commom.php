<?php declare(strict_types = 1); ?>

<?php function drawHeader(Session $session, array $scripts = array(), array $styles = array(),bool $checkout = true ) { ?>
    <!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>Big Eats</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/style.css" rel="stylesheet">
        <link href="../css/layout.css" rel="stylesheet">
        <link href="../css/responsive.css" rel="stylesheet">
        <?php foreach($styles as $style) { ?>
            <link href="../css/<?=$style?>" rel="stylesheet">
        <?php } ?>
        <script src="../js/cart.js" defer></script>
        <script src="../js/script.js" defer></script>
        <?php foreach($scripts as $script) { ?>
            <script src="../js/<?=$script?>" defer></script>
        <?php } ?>
    </head>
    <body>
        <div class="wrapper">
            <header>
                <div id="leftHeader">
                    <button type="button" id="hamburguer">
                        <span class="hamburguer_trace"></span>
                        <span class="hamburguer_trace"></span>
                        <span class="hamburguer_trace"></span>
                    </button>
                    <h1><a href="../pages/index.php">Big Eats</a></h1>
                </div>

                <ul id="options">
                    <li class="hidden">
                        <button id="search">
                            <img src="../imgs/search.png" alt="">
                            <span id="search_label">Search</span>
                            <input type="text" placeholder="Type something..." id="search_content" class="hidden">
                            <span id="search_clear" class="hidden">X</span> <!-- <button> bugs, idk why, it closes the parent -->
                        </button>
                    </li>
                    <?php if($checkout){ ?>
                        <li><button id="btn_cart"><img src="../imgs/cart.png">Checkout</button></li>
                    <?php } ?>
                    <?php 
                        if($session->isLoggedIn()) 
                            drawProfileLogout($session);
                        else 
                            drawLoginRegister(); 
                    ?>
                </ul>

                <a id="responsive_cart"><img src="../imgs/black_cart.png"></a>
            </header>

        <?php
        drawMessages($session);
        drawSideBar($session);
        if($checkout)
            drawCartBar();
} ?>

<?php function drawFooter(){ ?>
    </div>
    <footer>
            <p>Big Eats &copy; LTW 2021/2022</p>
        </footer>
    </body>
    </html>
<?php } ?>

<?php function drawLoginRegister(){ ?>
    <li><a href="../pages/login.php"><button id="login"><img src="../imgs/profile.jpg">Login</button></a></li>
    <li><a href="../pages/login.php?register=true"><button id="register">Register</button></a></li>
<?php } ?>

<?php function drawProfileLogout(Session $session){ ?>
    <li><a href="../pages/profile.php"><button id="profile"><img src="../imgs/profile.jpg"><?=$session->getName()?></button></a></li>
    <li><a href="../actions/action_logout.php"><button id="logout">Logout</button></a></li>
<?php } ?>

<?php function drawSideBar(Session $session){ ?>
    <nav id="sideBar" class="hidden">
        <ul>
            <?php if($session->isLoggedIn()) { ?>
                <li><a href="../pages/profile.php"><button class="sideBarButton"><?=$session->getName()?></button></a></li>
                <li><a href="../actions/action_logout.php"><button class="sideBarButton">Logout</button></a></li>
            <?php } else { ?>
                <li><a href="../pages/login.php?register=true"><button class="sideBarButton">Register</button></a></li>
                <li><a href="../pages/login.php"><button class="sideBarButton">Login</button></a></li>
            <?php } ?>

            <li><a href="../pages/index.php"><img src="../imgs/home.png" height="22" width="22">Home</a></li>
            <li><a href="../pages/restaurant_create.php"><img src="../imgs/resta.png" height="22" width="22">Add a restaurant</a></li>
            <li><a href="../pages/about.php"><img src="../imgs/faq.png" height="22" width="22">About Us</a></li>
        </ul>
        <button type="button" id="outsideSideBar"></button>
    </nav>
<?php } ?>

<?php function drawCartBar(){ ?>
    <section id="cartBar" class="hidden">
        <div class="close">x</div>

        <div id="cartBar_notEmpty" class="hidden">
            <h2>Your cart from</h2>
            <a id="cartBar_rest">Stella Restaurant</a>
            
            <a onclick="openCheckout('../pages/checkout.php');"id="cb_check">
                <button>
                    <span>Checkout</span>
                    <span id="cb_total">23.70€</span>
                </button>
            </a>
        </div>

        <div id="cartBar_empty" class="hidden">
            <p>Ainda não tens produtos no carrinho.</p>
        </div>
    </section>
<?php } ?>

<?php function drawMessages(Session $session){
    if($session->getMessages()){ ?>
            <section id="messages">
            <?php foreach ($session->getMessages() as $messsage) { ?>
                <article class="<?=$messsage['type']?>">
                <?=$messsage['text']?>
                </article>
            <?php } ?>
            </section>
    <?php } 
} ?>
