<?php
    require_once("../templates/t_index.php");
    require_once("../templates/commom.php");

    require_once(__DIR__ . '/../utils/session.php');
    $session = new Session();

    require_once("../db/connection.db.php");

    $db = getDatabaseConnection();
    $categ = Category::getCategories($db);

    drawHeader($session, array('search.js'));
?>

<main>
    <?php drawCategories($categ); ?>
    <?php drawFilters(); ?>

    <section id="rest_not_found" class="hidden">
        <h1>
            Nenhum restaurante encontrado.
            <h2>
                Experimente alterar os filtros.
            </h2>
        </h1>
    </section>

    <section id="lists"></section>
</main>

<?php
    drawFooter();
?>