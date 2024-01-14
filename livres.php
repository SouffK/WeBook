<?php
    require "include/header.inc.php";
    require "include/function.inc.php";
    require "include/util.inc.php";
?>

        <main>
            <?php
                echo livre_par_genre();
            ?>
        </main>
<?php
    require "include/footer.inc.php";
?>
