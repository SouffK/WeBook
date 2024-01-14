<?php

/**
 * @file
 * @author KHOMSI Soufiane et NAJMAOUI Yassine
 * @version 1.78.0
 */



/**
 * Fonction qui affiche les détails du dernier livre consulté en utilisant les cookies. 
 * Si les cookies ne sont pas présents, rien n'est affiché.
 * @return void
 */

 /*function makeGoogleBooksRequest($url) {
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
}*/

    function dernierlivreconsulte(){
        if (isset($_COOKIE['last_book_id']) && isset($_COOKIE['last_book_date'])) {
            date_default_timezone_set('Europe/Paris');
        
            $lastBookId = $_COOKIE['last_book_id'];
            $lastBookDate = $_COOKIE['last_book_date'];
        
            // Get the details of the last book consulted
            $url = 'https://www.googleapis.com/books/v1/volumes/' . $lastBookId;
            $response2 = makeGoogleBooksRequest($url);

            $response = file_get_contents($url);
            $data = json_decode($response, true);
        
            // Display the details of the last book consulted
            if (isset($data['volumeInfo'])) {
            $lastBookTitle = $data['volumeInfo']['title'];
            $lastBookImg = isset($data['volumeInfo']['imageLinks']['thumbnail']) ? $data['volumeInfo']['imageLinks']['thumbnail'] : '';
            $lastBookDateFormatted = date('d/m/Y à H:i:s', $lastBookDate); 

            echo "<section>";
            echo "<h2> Votre dernière consultation : </h2>";

            if ($lastBookImg != ''){
                echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $lastBookId)  .'">
                        <p class="pdernierconsulte">Vous avez consulté le livre ' . str_replace('&', '&amp;', $lastBookTitle) . ' le ' . $lastBookDateFormatted . '</p>
                        <img class="imgdernierconsulte" src="' .  str_replace('&', '&amp;', $lastBookImg) . '" alt="livre"/></a>';
                }else{
                    echo '<a href="bookdetails.php?id='. $lastBookId .'">
                            <p class="pdernierconsulte">Vous avez consulté le livre ' . $lastBookTitle . ' le ' . $lastBookDateFormatted . '</p>
                            <img class="imgdernierconsulte" src= "images/NF.jpg" alt="livre" style="width:190px;"/>
                        </a>';
                }
                echo "</section>";
            }
            
        }
    }


    /**
     * 
     * Fonction qui affiche la sélection de genre
     * 
     * @return void
     */
    function choix_genre() {
        $images_par_genre = array(
            "Manga" => "images/image1.png",
            "Science-fiction" => "images/image4.png",
            "Romans" => "images/images2.png",
            "Horreur" => "images/image7.png",
            "Biographies" => "images/image8.png",
            "Sport" => "images/image9.png",
            "Enfant" => "images/image10.png",
            "Aventure" => "images/images11.png",
            "Policier" => "images/images12.png",
            "Poesie" => "images/images13.png",
        );
        foreach ($images_par_genre as $genre => $image) {
            echo " <a href='livres.php?genre=" . $genre . "'>
                    <img class='imgdecouv' src='" . $image . "' alt='' />
                </a>";
        } 
    }
    /**
     * Fonction qui affche le genre selon le livre
     * 
     * @return  void
     */
    function livre_par_genre(){


    $livres_par_genre = array("Manga", "Science-fiction");

    // Récupération du genre sélectionné dans l'URL
    if (isset($_GET['genre'])) {
            $genre = $_GET['genre'];

            // Vérification que le genre sélectionné existe dans le tableau des livres par genre
            if (array_key_exists($genre, $livres_par_genre)) {
                // Affichage des livres du genre sélectionné
                echo "<h1>Liste des livres de genre " . $genre . "</h1>";
                foreach ($livres_par_genre[$genre] as $livre) {
                    echo "<p>" . $livre . "</p>";
                }
            }  
            
            

            if($genre =="Science-fiction"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:science%20fiction&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);

                // Récupération des données à partir de l'API
                $data = file_get_contents($url);
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                // Affichage des résultats
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
                
            }else if($genre == "Manga"){
            $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";

            // URL de l'API Google Books pour récupérer tous les romans
            $url = "https://www.googleapis.com/books/v1/volumes?q=subject:manga&maxResults=40&key=" . $api_key;
            $response = makeGoogleBooksRequest($url);

            // Récupération des données à partir de l'API
            $data = file_get_contents($url);

            // Conversion des données en tableau associatif
            $books = json_decode($data, true);

            // Affichage des résultats

            echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Romans"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:romans&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Horreur"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:horror&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);

                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Biographies"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:biographies&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Sport"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:sport&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
            echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Enfant"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:enfants&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Aventure"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:adventure&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Policier"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:police&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                        foreach ($books['items'] as $book) {
                            $book_id = $book['id'];
                            echo "<article class='book-card'> ";
                            // Vérifier si le livre a une page de garde
                            if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                                echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                            <figure>
                                                <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                            </figure>
                                    </a>";
                            } else {
                                echo '<a href="bookdetails.php?id='. $book_id .'">
                                        <figure>
                                            <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                        </figure>
                                    </a>';
                            }
                            if (strlen($book['volumeInfo']['title']) > 15) {
                                $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                            }
                            
                            $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                            
                            echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                            
                            echo "</article>";
                        }
                        echo "</section>";
            }else if($genre == "Poesie"){
                $api_key = "AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4";
                
                // URL de l'API Google Books pour récupérer tous les romans
                $url = "https://www.googleapis.com/books/v1/volumes?q=subject:poetry&maxResults=40&key=" . $api_key;
                $response = makeGoogleBooksRequest($url);
                // Récupération des données à partir de l'API
                $data = file_get_contents($url);    
                
                // Conversion des données en tableau associatif
                $books = json_decode($data, true);
                
                // Affichage des résultats
                
                echo "<section>
                        <h2> Quelque livres de ". $genre. "</h2>";
                foreach ($books['items'] as $book) {
                    $book_id = $book['id'];
                    echo "<article class='book-card'> ";
                    // Vérifier si le livre a une page de garde
                    if (isset($book['volumeInfo']['imageLinks']['thumbnail'])) {
                        echo "<a href='bookdetails.php?id=". str_replace('&', '&amp;', $book_id) ."'>
                                    <figure>
                                        <img class='imgsearch' src='" . str_replace('&', '&amp;', $book['volumeInfo']['imageLinks']['thumbnail']) . "' alt='' />
                                    </figure>
                            </a>";
                    } else {
                        echo '<a href="bookdetails.php?id='. $book_id .'">
                                <figure>
                                    <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                                </figure>
                            </a>';
                    }
                    if (strlen($book['volumeInfo']['title']) > 15) {
                        $book['volumeInfo']['title'] = substr($book['volumeInfo']['title'], 0, 15) . '...';
                    }
                    
                    $book_title = str_replace('&', 'et', $book['volumeInfo']['title']);
                    
                    echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3></a>';
                    
                    echo "</article>";
                }
                echo "</section>";
            }
        }else {
            echo choix_genre();
        }
    }
    /**
     * 
     * Affiche le résultat d'une recherche de livre.
     * @return void
     * 
     */
    function search(){
        // Google Books API endpoint
        /*$url = 'https://www.googleapis.com/books/v1/volumes';

        // Search parameters
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        str_replace('&', '&amp;', $search);
        $startIndex = isset($_GET['start']) ? intval($_GET['start']) : 0;
        $maxResults = 20;
        $search_by = $_GET['search_by'];

        $requestUrl = $url . '?q=' . urlencode($search) . '&amp;startIndex=' . $startIndex . '&amp;maxResults=' . $maxResults;

        $response = file_get_contents($requestUrl);

        // Parse JSON response
        $data = json_decode($response, true);

        switch ($search_by) {
        case 'author':
            $url .= 'inauthor:';
            break;
        default:
            $url .= 'intitle:';
            break;
        }


            // Display search results
            echo "<h2> Voici les résultats pour la recherche ".str_replace('&', '&amp;', $search)." </h2>"; 
            if (isset($data['items'])) {
            foreach ($data['items'] as $item) {
                $title = $item['volumeInfo']['title'] /*&&  str_replace('&', 'et', $book['volumeInfo']['title'])*/;
               /* $authors = isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'N/A';
                $publisher = isset($item['volumeInfo']['publisher']) ? $item['volumeInfo']['publisher'] : 'N/A';
                $publishedDate = isset($item['volumeInfo']['publishedDate']) ? $item['volumeInfo']['publishedDate'] : 'N/A';
                $description = isset($item['volumeInfo']['description']) ? $item['volumeInfo']['description'] : 'N/A';
                $thumbnail = isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : '';
                $book_id = $item['id'];
                
                echo '<div class="book-card">';
                if ($thumbnail != ''){
                    echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) .'">
                                <img class="imgsearch" src="' . str_replace('&', '&amp;', $thumbnail) . '" alt="' . str_replace('&', '&amp;', $title) . '"/>
                        </a>';
                }else{
                    echo '<a href="bookdetails.php?id='. $book_id .'">
                                <img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/>
                        </a>';
                }
                if (strlen($title) > 15) {
                    $title = substr($title, 0, 15) . '...';
                }
                $book_title = str_replace('&', 'et', $title);
                    
                echo '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '">
                        <h3 class="infolivre">' . str_replace('&', '&amp;', $book_title). '</h3>
                    </a>';
                if ($authors != '') {
                    echo '<p>De '. str_replace('&', '&amp;', $authors).'</p>';
                }
                echo '</div>';
            }

            // Display pagination
            $totalItems = $data['totalItems'];
            $currentPage = $startIndex / $maxResults + 1;
            $totalPages = ceil($totalItems / $maxResults);

            echo '<div class="pagination">';
            if ($currentPage > 1) {
                echo '<a href="?q=' . urlencode($search) . '&amp;start=' . ($startIndex - $maxResults) . '&amp;search_by='.$search_by.'">Page précédente</a>';
            }
            if ($currentPage < $totalPages) {
                echo '<a href="?q=' . urlencode($search) . '&amp;start=' . ($startIndex + $maxResults) . '&amp;search_by='.$search_by.'">Page Suivante</a>';
            }
            switch ($search_by) {
                case 'author':
                    $url .= 'inauthor:';
                    break;
                default:
                    $url .= 'intitle:';
                    break;
                }
            echo '</div>';
            }*/

             // Google Books API endpoint
             $url = 'https://www.googleapis.com/books/v1/volumes';
             //$response = makeGoogleBooksRequest($url);
             // Search parameters
             $search = isset($_GET['q']) ? $_GET['q'] : '';
             $startIndex = isset($_GET['start']) ? intval($_GET['start']) : 0;
             $maxResults = 20;

             // API request URL with search parameters
             $requestUrl = $url . '?q=' . urlencode($search) . '&startIndex=' . $startIndex . '&maxResults=' . $maxResults;

             // Make API request
             $response = file_get_contents($requestUrl);

             // Parse JSON response
             $data = json_decode($response, true);


             // Display search results
             if (isset($data['items'])) {
             foreach ($data['items'] as $item) {
                 $title = $item['volumeInfo']['title'];
                 $authors = isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : 'N/A';
                 $publisher = isset($item['volumeInfo']['publisher']) ? $item['volumeInfo']['publisher'] : 'N/A';
                 $publishedDate = isset($item['volumeInfo']['publishedDate']) ? $item['volumeInfo']['publishedDate'] : 'N/A';
                 $description = isset($item['volumeInfo']['description']) ? $item['volumeInfo']['description'] : 'N/A';
                 $thumbnail = isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : '';
                 $book_id = $item['id'];

                 
                 echo '<div class="book-card">';
                 if ($thumbnail != ''){
                     echo '<a href="bookdetails.php?id='.  str_replace('&', '&amp;',$book_id) .'"><img class="imgsearch" src="' .  str_replace('&', '&amp;', $thumbnail) . '" alt="' .  str_replace('&', '&amp;', $title) . '"/></a>';
                 }else{
                     echo '<a href="bookdetails.php?id='.  str_replace('&', '&amp;', $book_id).'"><img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/></a>';
                 }
                 if (strlen($title) > 15) {
                     $title = substr($title, 0, 15) . '...';
                 }
                 echo '<a href="bookdetails.php?id='. $book_id .'"><h3 class="infolivre">' .  str_replace('&', '&amp;', $title) . '</h3></a>';
                 echo '</div>';
             }

             // Display pagination
             $totalItems = $data['totalItems'];
             $currentPage = $startIndex / $maxResults + 1;
             $totalPages = ceil($totalItems / $maxResults);

             echo '<div class="pagination">';
             if ($currentPage > 1) {
                 echo '<a href="?q=' . urlencode($search) . '&amp;start=' . ($startIndex - $maxResults) . '">Page précédente</a>';
             }
             if ($currentPage < $totalPages) {
                 echo '<a href="?q=' . urlencode($search) . '&amp;start=' . ($startIndex + $maxResults) . '">Page Suivante</a>';
             }
             echo '</div>';
             }
    }


    /**
     * Fonction qui affiche à l'utilisateur dès son entré dans le site une séléction de livres aléatoires par genre.
     * @return void
     *  */ 
    function livre_aleatoire(){
        // Clé d'API Google Books
        $api_key = 'AIzaSyA0QMUIhUlfRDSHRq4RzFy86IBabj-GOr4';

        // Tableau de genres
        $genres = array('fiction', 'romance', 'thriller', 'horror', 'fantasy');

        // Sélection aléatoire d'un genre
        $random_genre = $genres[array_rand($genres)];

        // Requête pour récupérer les livres du jour
        $query = 'q=subject:' . $random_genre . '&orderBy=newest&maxResults=8';

        // URL de l'API de Google Books
        $url = 'https://www.googleapis.com/books/v1/volumes?' . $query . '&key=' . $api_key;
        $response = makeGoogleBooksRequest($url);
        // Récupération des données JSON depuis l'API
        $response = file_get_contents($url);

        // Décodage des données JSON en tableau PHP
        $data = json_decode($response, true);

        // Affichage des livres du jour
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $title = $item['volumeInfo']['title'];
                $author = implode(', ', $item['volumeInfo']['authors']);
                $description = $item['volumeInfo']['description'];
                $thumbnail = $item['volumeInfo']['imageLinks']['thumbnail'];
                $book_id = $item['id'];
                $h = '<article class="bookk">
                ';

                $h.= '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '"><img class="imgsearch" src="'  . str_replace('&', '&amp;', $thumbnail) . '" alt="' . str_replace('&', '&amp;', $title) . '" id="' . str_replace('&', '&amp;', $book_id) . '"/></a>';
                if (strlen($title) > 15) {
                    $title = substr($title, 0, 15) . '...';
                }
                $h.= '<a href="bookdetails.php?id='. str_replace('&', '&amp;', $book_id) . '">

                
                        <h3 class="infolivre">' . str_replace('&', '&amp;', $title) . '</h3>
                    </a>';

                    $h.= '<p>Par ' . str_replace('&', '&amp;', $author) . '</p>
                ';
                $h.= '</article>
                ';
                echo $h;    
            }
            

        }
    }

    /**
     * Fonction qui parcours dynamiquement un tableau contenant des images et qui va en afficher une au hasard à chaque rafraichissement de la page.
     * 
     * @return : Retourne une image aléatoire stocké dans un dossier imagealeatoire/.
     */

    function image_aleatoire(){
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
        $f = '<figure>';
        $f .= '<img class="imgalea" src="' . $cheminFichierAleatoire . '" alt="' . $fichierAleatoire . '"/>';
        $f .= '<figcaption class="figcaption_alea">' . $fichierAleatoire . '</figcaption>';
        $f .= '</figure>';

        return $f;
    
    }

    /**
     * Fonction qui permet à l'utilisateur de mettre en favoris les livres qu'il souhaite.
     * @return void
     */

    function favoris(){
        $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : array();

        if (!empty($favorites)) {
            echo '<h2>Mes favoris</h2>';
            echo '<ul>';
            foreach ($favorites as $id => $book) {
            // Récupère les détails du livre à partir de l'API Google Books
                $url = 'https://www.googleapis.com/books/v1/volumes/' . $id;
                $response = makeGoogleBooksRequest($url);
                $response = file_get_contents($url);
                $data = json_decode($response, true);
                $title = $data['volumeInfo']['title'];
                if (isset($data['volumeInfo']['authors']) && is_array($data['volumeInfo']['authors'])) {
                    $authors = implode(', ', $data['volumeInfo']['authors']);
                } else {
                    $authors = 'Auteur inconnu';
                }
                $thumbnail = isset($data['volumeInfo']['imageLinks']['thumbnail']) ? $data['volumeInfo']['imageLinks']['thumbnail'] : '';
                
                echo '<li>';
                echo '<div>';
                echo '<a href="bookdetails.php?id='. $id . '" ><img src="' . str_replace('&', '&amp;', $thumbnail) . '" alt="" /></a>';
                echo '<div>';
                echo '<h3>' . str_replace('&', '&amp;', $title) . '</h3>';
                echo '<p>' . str_replace('&', '&amp;', $authors) . '</p>';
                echo '</div>';
                echo '</div>';
                // Affiche le bouton "Supprimer"
                echo '<form method="post">';
                echo '<input type="hidden" name="id" value="' . $id . '"/>';
                echo '<input type="submit" name="remove_from_favorites" value="Supprimer"/>';
                echo '</form>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<h2> Aucun livre a été mis en favoris</h2>';
        }  
    }

    /**
     * Fonction qui affiche les dix derniers livres consulté sur le site par l'ensembles des utilisateurs.
    * @return void 
    */
    function dixdernierslivre(){
        $results = [];
    $count = 0;
    $cacheDir = 'book_cache/';
    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }

    $file = fopen('consulted_books.csv', 'r');
    while (($row = fgetcsv($file)) !== false) {
        $bookId = $row[0];
        $bookTime = $row[1];
        $cacheFile = $cacheDir . $bookId . '.json';
        
        // Vérification du cache
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 3600)) {
        $data = json_decode(file_get_contents($cacheFile), true);
        } else {
        // Requête API Google Books
        $url = 'https://www.googleapis.com/books/v1/volumes/' . $bookId;
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        // Sauvegarde en cache
        file_put_contents($cacheFile, json_encode($data));
        }

        if (isset($data['volumeInfo'])) {
        $bookTitle = $data['volumeInfo']['title'];
        $bookImg = isset($data['volumeInfo']['imageLinks']['thumbnail']) ? $data['volumeInfo']['imageLinks']['thumbnail'] : '';
        $results[] = [
            'id' => $bookId,
            'time' => $bookTime,
            'title' => $bookTitle,
            'image' => $bookImg
        ];
        }
    }
    fclose($file);

    // Tri des livres consultés par ordre décroissant de temps
    usort($results, function($a, $b) {
        return $b['time'] - $a['time'];
    });

    // Récupération des 10 derniers livres consultés
    $results = array_slice($results, 0, 10);

    // Affichage des livres consultés
    echo "<h2> Les 10 derniers livres consultées </h2>";
    for ($i = 0; $i < count($results); $i++) {
        $bookId = $results[$i]['id'];
        $bookTime = $results[$i]['time'];
        $bookTitle = $results[$i]['title'];
        $bookImg = $results[$i]['image'];
        echo '<div class="book-card">';
        echo '<img class="consulted_book_img" src="' . str_replace('&', '&amp;', $bookImg) . '" alt=""/>';
        echo '<a href="bookdetails.php?id=' . str_replace('&', '&amp;', $bookId) . '"><p class="consulted_book_title">' . $bookTitle . '</p></a>';
        //echo '<p class="consulted_book_time">' . date('d/m/Y H:i:s', $bookTime) . '</p>';
        echo '</div>';
    }
    }
    /**
     * 
     * 
     * Fonction qui affiche l'histogramme des 5 livres les plus consulté du site.
     * 
     * @return void 
     */
    function graph(){
        $data = array();
        if (($handle = fopen("livres.csv", "r")) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $data[$row[0]] = $row[1];
            }
            fclose($handle);
        }
    
    
        arsort($data);
        $data = array_slice($data, 0, 5, true);
    
    
    
        $width = 1000;
        $height = 300;
        $padding = 50;  
    
        $max_value = max($data);
        $bar_width = ($width - 2 * $padding) / 5;
        echo '<svg width="' . $width . '" height="' . $height . '">';
    
        $i = 0;
        foreach ($data as $title => $value) {
        if (strlen($title) > 20) {
            $title = substr($title, 0, 20) . '...';
        }
        $x = $padding + $i * $bar_width;
        $bar_height = ($value / $max_value) * ($height - 2 * $padding);
        $y = $height - $padding - $bar_height;
        echo '<rect x="' . $x . '" y="' . $y . '" width="' . $bar_width . '" height="' . $bar_height . '" fill="#4286f4" />';
        echo '<text class="txtgraph" x="' . ($x + $bar_width / 2 ) . '" y="' . ($y - 5) . '" text-anchor="middle" fill="#fff">' . $title . '</text>';
        echo '<text x="' . ($x + $bar_width / 2) . '" y="' . ($y + $bar_height + 15) . '" text-anchor="middle" fill="#fff">' . $value . ' </text>';
        $i++;  
        }
        echo '</svg>';
    }


?>