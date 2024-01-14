<?php
// Chemin vers le dossier contenant les images
$cheminPhotos = 'imagesaleatoire/';

// Récupération de la liste des fichiers dans le dossier
$listeFichiers = scandir($cheminPhotos);

// Suppression des fichiers . et ..
$listeFichiers = array_diff($listeFichiers, array('.', '..'));

// Sélection d'un fichier au hasard
$fichierAleatoire = $listeFichiers[array_rand($listeFichiers)];

// Chemin complet vers le fichier sélectionné
$cheminFichierAleatoire = $cheminPhotos . $fichierAleatoire;

// Affichage de l'image dans une balise <figure>
echo '<figure>';
echo '<img src="' . $cheminFichierAleatoire . '" alt="' . $fichierAleatoire . '">';
echo '<figcaption class="figcaption_alea">' . $fichierAleatoire . '</figcaption>';
echo '</figure>';
?>
