<?php
    require_once('../templates/commom.php');

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    if($session->isLoggedIn()){
        header('Location: index.php');
        die();
    }
?>

<!DOCTYPE html>
    <html lang="en-US">
    <head>
        <title>Big Eats</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/login.css" rel="stylesheet">
        <script src="../js/login.js" defer></script>
    </head>
        <body>
            <div id="spaceFooter">
                <header>
                    <h1><a href="../pages/index.php">Big Eats</a></h1>
                </header>

                <?php if($session->getMessages()){ ?>
                    <section id="messages">
                    <?php foreach ($session->getMessages() as $messsage) { ?>
                        <article class="<?=$messsage['type']?>">
                        <?=$messsage['text']?>
                        </article>
                    <?php } ?>
                    </section>
                <?php } ?>

                <section id="forms">
                    <form action="../actions/action_login.php" method="post">
                        <h2>Login</h2>
                        <input type="text" name="email" placeholder="Email" autofocus>
                        <input type="password" name="password" placeholder="Password">
                        <button type="submit">Login</button>
                        <a href="#"> Don't have an account? Create account!</a>
                    </form>

                    <form action="../actions/action_register.php" method="post" class=hidden>
                        <h2>Register</h2>
                        <input type="text" name="first_name" placeholder="First Name" autofocus>
                        <input type="text" name="last_name" placeholder="Last Name">
                        <input type="text" name="email" placeholder="Email">
                        <input type="text" name="address" placeholder="Address">
                        <input type="text" name="phone_number" placeholder="Phone number">
                        <input type="password" name="password" placeholder="Password">
                        <button class = "btn" type = "submit">Register</button>
                        <a href="#"> Already have an account? Login here!</a>
                    </form>
                </section>
            </div>
<?php
    drawFooter();
?>
   