<?php
    require "include/header.inc.php";
    require "include/function.inc.php";
    require "include/util.inc.php";

?>

    <main>
		<h1>Page Tech</h1>
		<section>
			<h2> Prise en main des formats d’échanges JSON et XML des API Web </h2>
            <article>
                <h3>Image ou vidéo du jour de la NASA  </h3>
                <?php
                    echo nasa();
                ?>
            </article>
            <article>
                <h3> En utilisant geoplugin </h3>
                <?php
                    echo ipgeoplugin();
                ?>
            </article>
            <article>
                <h3> En utilisant IPInfo </h3>
                <?php
                    echo ipinfo();
                ?>
            </article>

        </section>
    </main>

<?php
    require "include/footer.inc.php";
?>
            
