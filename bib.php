<?php
  require "include/header.inc.php";
  require "include/function.inc.php";
  require "include/util.inc.php";
?>


        <main>
            <?php
                echo dernierlivreconsulte();
            ?>
            <section>
            <?php
                echo favoris();
            ?>
            </section>
        </main>


<?php
  require "include/footer.inc.php";
?>


