<?php
    require_once('../templates/commom.php');
    
    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    drawHeader($session, array(), array("about.css"));
?>

<main>
<section id="who">
    <h1>What we have in special?</h1>
    <h2>It's the easist way to receive dishes you love on your doorstep.</h2>
</section>

<section id="about"> 
    <h1> What can we offer? </h1>
    <section id="about_costumer" class="about_card"> 
        <img src = "../imgs/costumer.jpg">
        <div class="about_info">
            <h2>Costumer</h2>
            <p>With thousands of restaurants, we deliver the best of your neighborhood on-demand.</p>
            <a href="#"><button id="cstb">Start an order</button></a>
        </div>
    </section>
    <section id="about_owner" class="about_card"> 
        <img src = "../imgs/owner.jpeg">
        <div class="about_info">
            <h2>Owner</h2>
            <p>Reach new customers, market your store, and grow your business by offering delivery, pickup, and direct online ordering.</p>
            <a href="#"><button id="cstb">Register a restaurant</button></a>
        </div>
    </section>
    <section id="about_deliverman" class="about_card"> 
        <img src = "../imgs/deliveryman.jpg">
        <div class="about_info">
            <h2>Deliveryman</h2>
            <p>Delivering with Big Eats means earning money when and how you want. Deliver long term or for a goal, and do it all on your own terms.</p>
            <a href="#"><button id="cstb">Start delivering</button></a>
        </div>
    </section>
</section>
</main>
<?php
    drawFooter();
?>

