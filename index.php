  <?php
    require "include/header.inc.php";
    require "include/function.inc.php";
    require "include/util.inc.php";
  ?>
    <main>
      <section>
        <h2> WeBook </h2> 
      </section>
      <section>
        <h2> "Un livre est un outil de liberté" </h2>
      </section>
        <?php echo dernierlivreconsulte(); ?>
      <section class="section_bas">
        <h2 style="color : white;"> Une petite sélection de livre </h2>
        <?php
          echo livre_aleatoire();
        ?>
      </section>
      <section>
        <h2> Image aléatoire </h2>
        <?php
          echo image_aleatoire();    
        ?>   
        </section>
    </main>
<?php
      require "include/footer.inc.php";
?>