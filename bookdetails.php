<?php
    require "include/header.inc.php";
    require "include/function.inc.php";
    require "include/util.inc.php";
   
?>

    <main>
<?php
/**
 * 
 * 
 * Code qui permet de visualiser les détails d'un livre 
 * 
 * @return void
 */
  if(isset($_GET['id'])) {
    
    $url = 'https://www.googleapis.com/books/v1/volumes/' . $id;
    //$response2 = makeGoogleBooksRequest($url);
    $response = file_get_contents($url);

    $data = json_decode($response, true);
    //$file = fopen('livres.csv', 'a'); 

    $title = $data['volumeInfo']['title'];
    if (isset($data['volumeInfo']['authors']) && is_array($data['volumeInfo']['authors'])) {
      $authors = implode(', ', $data['volumeInfo']['authors']);
  } else {
      $authors = 'Auteur inconnu';
  }
  
    $description = isset($data['volumeInfo']['description']) ? $data['volumeInfo']['description'] : '';
    $thumbnail = isset($data['volumeInfo']['imageLinks']['thumbnail']) ? $data['volumeInfo']['imageLinks']['thumbnail'] : '';
    $language = isset($data['volumeInfo']['language']) ? $data['volumeInfo']['language'] : '';
    $country = isset($data['saleInfo']['country']) ? $data['saleInfo']['country'] : '';
    $availability = isset($data['saleInfo']['saleability']) ? $data['saleInfo']['saleability'] : '';
    $is_ebook = isset($data['saleInfo']['isEbook']) ? $data['saleInfo']['isEbook'] : '';
    $digital_price = isset($data['saleInfo']['retailPrice']['amount']) ? $data['saleInfo']['retailPrice']['amount'] : '';
    $physical_price = isset($data['saleInfo']['listPrice']['amount']) ? $data['saleInfo']['listPrice']['amount'] : '';
    $NbPage = isset($data['volumeInfo']['pageCount']) ? $data['volumeInfo']['pageCount'] : '';
    $date_livre = isset($data['volumeInfo']['publishedDate']) ? $data['volumeInfo']['publishedDate'] : '';
    $isbn = isset($data['volumeInfo']['industryIdentifiers']['0']['identifier']) ? $data['volumeInfo']['industryIdentifiers']['0']['identifier'] : '';
    if (isset($_POST['add_to_favorites'])) {
      // Récupère le contenu actuel du cookie, s'il existe
      $favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : array();
      // Ajoute le livre en favoris
      $favorites[$id] = array('title' => $title, 'authors' => $authors, "thumbnails"=> $thumbnail);
      // Encode les favoris en JSON et enregistre le cookie
      setcookie('favorites', json_encode($favorites), time() + (86400 * 30), "/"); // expire dans 30 jours
    }

    echo '<div class="book-details">';
    echo '<img class="imginfo" src="' . str_replace('&', '&amp;',$thumbnail) . '"  alt=""/>';
    echo '<div class="details">';
    echo "<article class='description_span'>";
    $book_title = str_replace('&', 'et', $title);
    echo '<h3 style="color: white;"> Titre : ' . $book_title . '</h3>';
    if ($authors != ''){
      echo "<span class='span_details' style='color: white;'>Nom de l'auteur :</span>";
      echo '<p style="color: white;">' . $authors . '</p>';
    }else{
      echo "</p>N/A<p>";
    }
    if ($language != '') {
      echo '<span class="span_details" style="color: white;">Langue:</span>';
      echo '<p style="color: white;">' . $language . '</p>';
    }
    if ($date_livre != '') {
      echo '<span class="span_details" style="color: white;">Date de sortie:</span>';
      echo '<p style="color: white;">' . $date_livre . '</p>';
    }
    if ($isbn != '') {
      echo '<span class="span_details" style="color: white;">ISBN:</span>';
      echo '<p style="color: white;">' . $isbn . '</p>';
    }

    echo "</article>";
    echo '<article class="description_span">
            <h3> Informations liée à son achat </h3>';

    if ($country != '') {
      echo '<span class="span_details" >Pays de vente:</span>';
        echo '<p>' . $country . '</p>';
    }
    if ($availability != '') {
      echo '<span class="span_details" >Disponibilité:</span>';
      echo '<p>' . $availability . '</p>';
    }
    if ($is_ebook != '') {
      echo '<span class="span_details">Disponible numériquement:</span>';
      echo '<p>' . $is_ebook . '</p>';
    }
    if ($digital_price != '') {
      echo '<span class="span_details">Prix numérique:</span>';
      echo '<p>' . $digital_price . '</p>';
    }
    if ($physical_price != '') {
      echo '<p class="span_details">Prix physique:</p>';
      echo '<p>' . $physical_price . '</p>';
    }
    if ($NbPage != '') {
      echo '<span>Nombre de page:</span>';
      echo '<p>' . $NbPage . '</p>';
    }
    echo '</article>';
    if ($description != '') {
      echo '<article class="description_span">
          <h3 class="span_details" style="color: white;">Description complète du livre:</h3>';
        echo      '<p>' . str_replace('&', '&amp;', $description) . '<p>';
            echo  '</article>';
    }
    echo '<form method="post">';
    echo '<input type="hidden" name="id" value="' . $id . '"/>';
    echo '<input type="submit" name="add_to_favorites" value="Ajouter à mes préférences"/>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
?>

<?php
  $dataa = array();
  if (($handle = fopen("livres.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if ($row[0] !== $title) {
          $dataa[] = $row;
        }
    }
    fclose($handle);
  }
  $dataa[] = array($title, $consults); 
  $file = fopen('livres.csv', 'w');
  foreach ($dataa as $row) {
    fputcsv($file, $row);
  }
  fclose($file);

  }
/**
 * 
 * 
 * Code qui affiche les livre du même auteurs que celui consulté
 * @return void
 */
  if(isset($_GET['id'])){
    if (isset($data['volumeInfo']['authors']) && is_array($data['volumeInfo']['authors'])) {
      $authors = implode(', ', $data['volumeInfo']['authors']);
  } else {
      $authors = 'Auteur inconnu';
  }
    $searchUrl = 'https://www.googleapis.com/books/v1/volumes?q=inauthor:"' . urlencode($authors) . '"&maxResults=4';
    $searchResponse = file_get_contents($searchUrl);
    $searchData = json_decode($searchResponse, true);

    echo "<h3 class='InfoAuteur'> D'autre livre de : ".$authors."</h3>";
    echo '<div class="books-by-author">';
    foreach ($searchData['items'] as $item) {
        $bookId = $item['id'];
        $bookTitle = $item['volumeInfo']['title'];
        $bookThumbnail = isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : '';

        echo '<div class="book">';
      if ($bookThumbnail != ''){
          echo '<a href="?id=' . str_replace('&', '&amp;',$bookId) . '"><img src="' . str_replace('&', '&amp;', $bookThumbnail) . '" alt="' . str_replace('&', '&amp;',$bookTitle) . '"/></a>';
      }else{
        echo '<a href="?id=' . $bookId . '"><img class="imgsearch" src= "images/NF.jpg" alt="' . str_replace('&', '&amp;',$bookTitle). '" style="width:190px;"/></a>';
      }
        echo '<p>' . str_replace('&', '&amp;',$bookTitle) . '</p>';
        echo '</div>';
    }
    echo '</div>';

  }else {
    echo "";
  }
/**
 * 
 * Code qui affiche les livres similaire à celui consulté
 * 
 * @return void
 */
  if (isset($data['volumeInfo']['categories'][0])) {
    $category = $data['volumeInfo']['categories'][0];
    $searchUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($category) . '&maxResults=4';
    $searchResponse = file_get_contents($searchUrl);
    $searchData = json_decode($searchResponse, true);
    
    if (isset($searchData['items'])) {
      echo '<h3 class="InfoAuteur" style="color: white;">Livres similaires :</h3>';
      echo '<div class="similar-books">';
      echo '<div class="books">';
      foreach ($searchData['items'] as $item) {
        $similarId = $item['id'];
        $similarTitle = $item['volumeInfo']['title'];
        $similarAuthors = isset($item['volumeInfo']['authors']) ? implode(', ', $item['volumeInfo']['authors']) : '';
        $similarThumbnail = isset($item['volumeInfo']['imageLinks']['thumbnail']) ? $item['volumeInfo']['imageLinks']['thumbnail'] : '';
        echo '<div class="book">';
        if ($similarThumbnail != ''){
          echo '<a href="?id=' . $similarId . '"><img src="' . str_replace('&', '&amp;',$similarThumbnail) . '" alt="' . str_replace('&', '&amp;', $similarTitle) . '"/></a>';
        }else{
          echo '<a href="?id='. $similarId .'"><img class="imgsearch" src= "images/NF.jpg" alt="" style="width:190px;"/></a>';
      }
      echo '<p>' .str_replace('&', '&amp;', $similarTitle) . '</p>';
        if ($similarAuthors != '') {
          echo '<p>Auteur: ' . str_replace('&', '&amp;',$similarAuthors) . '</p>';
        }
        echo '</div>';
      }
      echo '</div>';
      echo '</div>';
    }
  }else {
    echo "";
  }
    if (isset($_GET['id'])) {
      $lastBookId = $_GET['id'];
    
      // Ajouter l'identifiant du dernier livre consulté au fichier CSV
      $file = fopen('consulted_books.csv', 'a');
      fputcsv($file, [$lastBookId, time()]);
      fclose($file);
    }
?>
    </main>

<?php
    require "include/footer.inc.php";
?>