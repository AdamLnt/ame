<!DOCTYPE html>

<?php
session_start();

include ('functions.php');

is_connected();

?>

<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Homepage</title>
    <link rel="shortcut icon" href="/pictures-movies/IMAGES/video2.ico">
    <link rel="stylesheet" href="style.css" /> <!-- Lien vers le fichier CSS externe -->
  </head>

  <!-- PAGE WEB -->
  <body>
    <div class="content a">
      <h1><i>[Titre]</i></h1>
            
      <!-- menu de navigation -->
      <div class="menu">
        <div><a class="button films" href="./conferences.php">Conférences | Rediffusions</a></div>
        <div><a class="button index" href="./index.php">Index</a></div>
      </div>
      
      <br>
      
      <!-- Image placée sous les boutons et centrée verticalement -->
      <div class="image-container">
        <img src="/medias/images/ngrok.jpg" alt="Ngrok" height="250" width="200" />
      </div>
    </div>

    <a class="disconnect-button" href="logout.php">Déconnexion</a>
    <i>mantenersi al sicuro</i>
  </body>
</html>
