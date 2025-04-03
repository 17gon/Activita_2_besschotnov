<!DOCTYPE html>
<html lang="en">
<?php include "parts/head.php"; ?>
    <body>
        <?php include "parts/header.php"; ?>

        <main>
            <section class="banner">
                <div class="container text-white">
                    <h1>Portfólio</h1>
                </div>
            </section>
            <section class="container">
                <?php include "parts/genPortfolio.php"; bakePortfolio();?>

            </section>   

        </main>
        <?php include "parts/footer.php"; ?>
    <script src="js/menu.js"></script>
    </body>
</html>