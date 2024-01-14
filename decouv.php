<?php
  require "include/header.inc.php";
  require "include/function.inc.php";
  require "include/util.inc.php";
?>
        <main>
            <section>
                <h2> Découvrir les différents genres </h2>
            </section>
            
        <?php
       
            echo choix_genre();
        ?>

        </main>
  

<?php
  require "include/footer.inc.php";
?>