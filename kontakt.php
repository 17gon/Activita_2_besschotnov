<!DOCTYPE html>
<html lang="sk">
<?php include "parts/head.php"; ?>
<body>
<?php include "parts/header.php"; ?>

  <main>
    <section class="banner">
      <div class="container text-white">
        <h1>Kontakt</h1>
      </div>
    </section>
    <section>
      <div class="container">
        <div class="col-100 text-center">
          <p><strong><em>Elit culpa id mollit irure sit. Ex ut et ea esse culpa officia ea incididunt elit velit veniam qui. Mollit deserunt culpa incididunt laborum commodo in culpa.</em
          ></strong></p>
        </div>
      </div>
    </section>
    <section class="container">
      <div class="row">
        <div class="col-50"> 
          <h3>Máte otázky?</h3>
          <p>Incididunt mollit quis eiusmod tempor voluptate duis eu enim amet excepteur cupidatat magna velit. </p> 
          <p>Velit id ad laborum velit commodo.</p>
          <p>Consectetur laborum aliqua nulla anim cupidatat consectetur est veniam cupidatat.</p>
        </div>
        <div class="col-50 text-right">
          <h3>Napíšte nám</h3>
            <form id="contact" method="post" action="dataBase/writeFormular.php">
                <input type="text" placeholder="Vaše meno" id="firstName" name="firstName" required><br>
                <input type="text" placeholder="Vaše priezvisko" id="lastName" name="lastName"><br>
                <input type="email" placeholder="Váš email" id="email" name="email" required><br>
                <textarea name="sprava" placeholder="Vaša správa" id="sprava"></textarea><br>
                <input type="checkbox" name="agree" id="agree" required>
                <label for="agree"> Súhlasím so spracovaním osobných údajov.</label><br>
                <input type="submit" value="Odoslať">
            </form>
        </div>
      </div>
    </section>
  </main>
  <?php include "parts/footer.php"; ?>
  <script src="js/menu.js"></script>
</body>
</html>