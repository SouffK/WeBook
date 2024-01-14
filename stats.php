<?php
  require "include/header.inc.php";
  require "include/function.inc.php";
    require "include/util.inc.php";
?>
    <main>
      <section>
        <h2> Statistiques du sites</h2>
      <?php
        echo graph();
      ?>
      </section>
      <section>
      <?php
        echo dixdernierslivre();
      ?>
      </section>
  </main>
<?php
  require "include/footer.inc.php";
?>