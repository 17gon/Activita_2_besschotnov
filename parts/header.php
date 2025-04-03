<?php
include "genNavbar.php";

$theme = $_GET["theme"];
?>
<header style="background-color: <?php echo $theme === "dark" ? "grey" : "white"; ?>" class="container main-header">
    <div>
        <a href="index.php">
            <img src="img/logo.png" height="40" alt="logo">
        </a>
    </div>
    <nav class="main-nav">
        <ul class="main-menu" id="main-menu">
            <a href=<?php echo $theme === "dark" ? "?theme=light" : "?theme=dark"; ?> >Zmena témy</a>

            <?php echo generateNavbar() ?>
        </ul>
        <a class="hamburger" id="hamburger">
            <i class="fa fa-bars"></i>
        </a>
    </nav>
</header>