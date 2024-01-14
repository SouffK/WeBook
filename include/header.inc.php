<?php
 function makeGoogleBooksRequest2($url) {
    // Configuration des délais
    $requestDelay = 1; // Délai en secondes entre chaque requête
    $lastRequestTime = isset($_SESSION['last_request_time']) ? $_SESSION['last_request_time'] : 0;
    $currentTime = time();

    // Vérification du délai écoulé depuis la dernière requête
    $timeElapsed = $currentTime - $lastRequestTime;
    if ($timeElapsed < $requestDelay) {
        sleep($requestDelay - $timeElapsed);
    }

    // Faire la requête à l'API Google Books
    $response = file_get_contents($url);

    // Mettre à jour le temps de la dernière requête
    $_SESSION['last_request_time'] = time();

    return $response;
}
    $mode = isset($_COOKIE['mode']) ? $_COOKIE['mode'] : 'jour';
    if (isset($_GET['mode'])) {
        if ($_GET['mode'] === 'sombre') {
            // Changer en mode sombre
            $mode = 'sombre';
            // Définir le cookie de mode pour une durée de 30 jours
            setcookie('mode', 'sombre', time() + (30 * 24 * 60 * 60), '/');
        } else {
        // Changer en mode jour
            $mode = 'jour';
            // Définir le cookie de mode pour une durée de 30 jours
            setcookie('mode', 'jour', time() + (30 * 24 * 60 * 60), '/');
        }
    }
    $queryString = http_build_query(str_replace('&', '&amp;', $_GET));
     // Appliquer le mode actuel à la page
    if ($mode === 'sombre') {
        $stylesheet = 'modenuit.css';
    } else {
        $stylesheet = 'style.css';
    }
    
    if(isset($_GET['id'])) {
    
        $id = $_GET['id'];
        $url = 'https://www.googleapis.com/books/v1/volumes/' . $id;
        $response = makeGoogleBooksRequest2($url);

        $response = file_get_contents($url);

        $data = json_decode($response, true);
                        //$file = fopen('livres.csv', 'a'); 

        $title = $data['volumeInfo']['title'];
        //$consults = isset($_COOKIE['lastbook-graph']) ? $_COOKIE['lastbook-graph'] : 1;
        $lastBookDate = time();
        date_default_timezone_set('Europe/Paris');
        setcookie('last_book_date', $lastBookDate, time() + 3600, '/');
        setcookie('last_book_id', $id, time() + 3600, '/');
        $consults = 1;
        if (($handle = fopen("livres.csv", "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row[0] === $title) {
                    $consults = intval($row[1]) + 1;
                        break;
                }
            }
            fclose($handle);
        }
        //setcookie('last_book_id', $consults , time() + 3600, '/');

                    
        //setcookie('lastbook-graph', $consults, time() + 3600, '/');
    }   

    if (isset($_POST['remove_from_favorites'])) {
        $id = $_POST['id'];
        $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : array();
        if (isset($favorites[$id])) {
          unset($favorites[$id]);
          setcookie('favorites', json_encode($favorites), time() + (86400 * 30), "/"); // expire dans 30 jours
          header('Location: bib.php'); // recharge la page pour refléter les changements
        }
      }
        ?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="WeBook est un site de pour rechercher des livres." />
        <meta name="keywords" content="libraurie, livre, book" />
        <meta name="author" content="KHOMSI Soufiane, NAJMAOUI Yassine" />
        <meta name="date" content="2023" />
        <meta name="lieu" content="CY Cergy Paris Universite" />
        <link rel="stylesheet" href="style.css" />
        <link rel="stylesheet" href="<?php echo $stylesheet; ?>" />
        <link rel="shortcut icon" href="images/logoico.ico" />            
        <title>WeBook</title>
        <style>
            h2 h1  {
                text-align: center;
                font-family: 'Archivo', sans-serif;
                /*font-family: 'Courier New', Courier, monospace;*/
                color : white;
                margin-top: 20px;
            }
            h3 h1{
                color: white;
            }

            header .logo{
                position: relative;
                font-size : 1.5em;
                color:#fff;
                text-decoration: none;
                font-weight: 600;
            }

            header .navigation{
                display : flex;
                justify-content: center;
                flex-wrap: wrap;
                margin:10px 0;
            }

            header .navigation li{
                list-style: none;
                margin: 0 10px;
            }

            header .navigation li a {
                color : #fff;
                text-decoration: none;
                font-weight: 500;
                letter-spacing: 1px;
                font-family: 'Archivo', sans-serif;

            }
            a{
                text-decoration: none;
            }

            header .navigation li a:hover{
                color : sandybrown;
            }
        </style>
    </head>
    <body>
        <header>
            <a href ="index.php" class ="logo" ><img src="images/logo_wb.png" alt="logo du site" style="width: 140px;height: 60px;" /></a>
            <form method="GET" action="search.php"  class="inputdeux">
                    <label for="q"> </label>
                    <input type="text" id="q" name="q" placeholder="Quel livre ou auteur recherchez vous ?" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>" />
                    <button type="submit" > Rechercher </button>
                    <select name="search_by" id="search_by">
                        <option value="title">Titre</option>
                        <option value="author">Auteur</option>
                    </select>
                    <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
            </form> 

            <ul class="navigation">
                <li><a href="decouv.php"> Découvrir </a></li>
                <li><a href="stats.php"> Statistiques</a></li> 
                <li><a href="bib.php"> Mes préferences</a></li> 
                <li><a href="?<?php echo htmlentities($queryString, ENT_QUOTES) ?>&amp;mode=jour"><img src="images/modejour.png" alt="Mode jour" style="width: 25px;"/></a></li>
                <li><a href="?<?php echo htmlentities($queryString, ENT_QUOTES) ?>&amp;mode=sombre"><img src="images/modenuit.png" alt="Mode sombre" style="width: 25px;"/></a></li>
            </ul>
        
        </header>


