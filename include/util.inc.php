<?php

define("DEFAULT_LANG", "fr");

    /**
     * Jour et Heure locale
     * @param $dl (string): qui prend par défaut la langue attribué "DEFAULT_LANG"
     * @return (string): La date et heure locale actuelle dans la langue entrée en paramètre
     */
function dates(string $dl = DEFAULT_LANG) : string {
        $jours = array(1 => "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche");
        $joursEN = array(1 => "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
        $mois = array(1 => "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"); 
        $moisEN = array(1 => "January", "February", "Marsh", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $date = "<time datetime=\"".date("Y")."-".date("m")."-".date("d")."\">";
    
         switch($dl) {
            case "en" : 
                $date .= $joursEN[date("N")].", ".$moisEN[date("n")]." ".date("j").", ".date("Y");
                break;
            default :
                $date .= $jours[date("N")]." ".date("j")." ".$mois[date("n")]." ".date("Y");
                break;
                
        }
        $date .= "</time>";
        return $date;
} 
    

    /**
      * Détecter le navigateur de l'internaute
      * @return (string): Le navigateur de l'internaute
      */
     
function get_navigateur() : string{
        $UA = $_SERVER['HTTP_USER_AGENT'];
        $navigateur = "Navigateur Inconu";
    
        if (strstr($UA, 'Firefox')) {
            $navigateur = 'Firefox';
        }
        elseif (strstr($UA, 'OPR')) {
            $navigateur = 'Opera';
        } 
        elseif (strstr($UA, 'Edg')) {
            $navigateur = 'Microsoft Edge';
        }
        elseif (strstr($UA, 'Chrome')) {
            $navigateur = 'Google Chrome';
        }
        elseif (strstr($UA, 'Safari')) {
            $navigateur = 'Safari';
        }
        elseif (strstr($UA, 'MSIE')) {
            $navigateur = 'Internet Explorer';
        }  
    
        return "<p> Navigateur : ".$navigateur . "</p>";
}
    /**
 * 
 * Fonction qui permet de voir le nombre de visiteur du site
 *@return : Retourne le nombre de visiteurs après chaque clique.
 */
function compteur(){
    $file_path = "compteur.txt";

	// Vérifie si le fichier existe, sinon le crée
	if (!file_exists($file_path)) {
		$compteur = 0;
		file_put_contents($file_path, $compteur);
	}

	// Lit la valeur actuelle du compteur dans le fichier
	$compteur = intval(file_get_contents($file_path));

	// Incrémente le compteur à chaque rafraîchissement de la page
	$compteur++;

	// Écrit la nouvelle valeur du compteur dans le fichier
	file_put_contents($file_path, $compteur);

	// Affiche le nombre de hits sur la page
	$c = "<span> Cette page a été visitée " . $compteur . " fois.</span> ";
                 
    return $c;
}


/**
 * 
 * Fonction qui affiche l'image du jour de la nasa.
 * @return void 
 */
function nasa(){
    // Clé d'API valide pour APOD
    $api_key = "nilaXaKHGyrvsmWtqGQMxt7MfgXDbxkYxoUMYyfV";

    // URL de l'API APOD
    $url = "https://api.nasa.gov/planetary/apod?api_key=".$api_key;

    // Récupération des données JSON de l'API
    $data = file_get_contents($url);

    // Conversion des données JSON en objet PHP
    $obj = json_decode($data);

    // Vérification si l'image du jour est une vidéo
    if ($obj->media_type == "video") {
        // Affichage de la vidéo du jour
        //echo "<video src='".$obj->url."' controls width='640' height='480'></video>";
        echo"<iframe class='iframeNasa' src=". $obj->url."></iframe>";                

    } else {
        // Affichage de l'image du jour et de sa description
        echo "<figure>";
            echo "<img src='".$obj->url."' width='640' height='480' class='imgnasa' alt=''/>";
        echo "</figure>";
             echo "<h3> Description de l'image </h3>";
        echo "<p>".$obj->explanation."</p>";
    }
}

/**
 * 
 * Fonction qui permet à l'utilisateur de voir son adresse ip.
 * @return void 
 */

 function ipgeoplugin() {
    // Adresse IP du visiteur
    $ip = $_SERVER['REMOTE_ADDR'];

    // URL de l'API Geoplugin
    $url = "http://www.geoplugin.net/xml.gp?ip=".$ip;

    // Récupération des données XML de l'API
    $data = file_get_contents($url);

    // Conversion des données XML en objet PHP
    $xml = simplexml_load_string($data);

    // Affichage des informations de géolocalisation
    echo "<p>Votre adresse IP : ".$ip."</p>";
    echo "<p>Votre ville : ".$xml->geoplugin_city."</p>";
    echo "<p>Votre région : ".$xml->geoplugin_region."</p>";
    echo "<p>Votre pays : ".$xml->geoplugin_countryName."</p>";
}
/**
 * Fonction qui permet à l'utilisateur de voir son adresse ip.
 * @return : Retourne l'adresse IP de l'utilisateur avec Ipinfo.
 */


 function ipinfo(){

    // Récupérer l'adresse IP de l'utilisateur
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Construire l'URL de l'API ipinfo.io avec l'adresse IP de l'utilisateur
    $api_url = "https://ipinfo.io/$user_ip/geo";

    // Récupérer les informations depuis l'API en utilisant la fonction file_get_contents()
    $api_response = file_get_contents($api_url);

    // Convertir la réponse JSON en tableau associatif
    $location_data = json_decode($api_response, true);

    // Afficher les informations géographiques de l'utilisateur
    echo "<p> Votre adresse IP : " . $location_data['ip'] . "</p> ";
    if (array_key_exists('city', $location_data)) {
        echo "<p>Votre ville : " . $location_data['city'] . "</p>";
    }
    if (array_key_exists('region', $location_data)) {
        echo "<p>Votre région : " . $location_data['region'] . "</p>";
    }
    if (array_key_exists('country', $location_data)) {
        echo "<p>Votre pays : " . $location_data['country'] . "</p>";
    }
    if (array_key_exists('postal', $location_data)) {
        echo "<p>Votre code postal : " . $location_data['postal'] . "</p>";
    }
    if (array_key_exists('loc', $location_data)) {
        $location = explode(",", $location_data['loc']);
        echo "<p>Votre latitude : " . $location[0] . "</p>";
        echo "<p>Votre longitude : " . $location[1] . "</p>";
    }
}

?>