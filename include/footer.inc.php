<?php

?>
		<footer>

            <ul class="menuu">	
                <li><a href="tech.php"> Tech</a></li>
                <li><a href="sitemap.php"> Plan du site</a></li>
				<li><a href="about.php">K.Soufiane et N.Yassine</a></li>
            </ul>
			<ul class="menuu">
                <li> <?php echo get_navigateur() ?></li>
                <li> <?php echo dates() ?>	</li>
				<li> <?php echo compteur() ?></li>
				<li>CY Cergy Paris Université </li>
            </ul>
			<a href="#top" class="scroll-to-top"><img src="images/arrow_up.png" alt="Remonter en haut de la page"/></a>
            
        </footer>
		
		
		
		<script>
			window.addEventListener('scroll', function() {
				var scrollPosition = window.pageYOffset;
				if (scrollPosition > 500) {
					document.querySelector('.scroll-to-top').style.display = 'block';
				} else {
					document.querySelector('.scroll-to-top').style.display = 'none';
				}
			});

			document.querySelector('.scroll-to-top').addEventListener('click', function(e) {
				e.preventDefault();
				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			});
		</script>

    </body>
</html>