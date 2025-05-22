<?php
session_start();
include ('functions.php');

is_connected();

$config = include 'config.php';
$host = $config['db_host'];
$dbname = $config['db_name'];
$user = $config['db_user'];
$pass = $config['db_password'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Vidéos</title>
    <link rel="shortcut icon" href="/medias/images/video2.ico">
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <h1><i>Vidéos</i></h1>
    <?php
        if ($_SESSION['usertype'] == 1) {
    ?>
        <button class="add-video-button" onclick="openPopup()">Ajouter une vidéo</button> 
    <?php
        }
    ?>
    <div id="overlay" onclick="closePopup()"></div>
    <?php
    if ($_SESSION['usertype'] == 1) {
        echo '
    <div id="add_form">
        <form class="add_video_form" action="add_video.php" method="POST" enctype="multipart/form-data">
            <label for="name">Nom de la vidéo : </label>
            <input type="text" id="name" name="name" required>
            <label for="type">Type de vidéo : </label>
            <select name="type">
                <option value="cours">Cours</option>
                <option value="tutoriel">Tutoriel</option>
            </select><br>
            <label for="file">Fichier vidéo : </label>
            <input type="file" id="file" name="file" accept="video/*">
            <label for="image">Image : </label>
            <input type="file" id="image" name="image" accept="image/*">
            <button type="submit">Ajouter</button>
            <button type="button" class="close-btn" onclick="closePopup()">Fermer</button>
        </form>    
    </div>
    ';
    }
    ?>
<dl>
    <!--LISTE DES VIDEOS-->
    <div class="video_display">
        <?php
            $sql = "SELECT * FROM video";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            foreach ($stmt as $vid) {
        ?>  
        <div class="video_box">
            <a style="text-decoration: none;" href="#" onclick="loadVideo('<?= $vid['vid_link'] ?>')">
                <img class="video_img" src="<?= $vid['vid_img'] ?>" alt="<?= $vid['vid_name'] ?>">
            </a>
            <div class="title_and_delete">
                <p><?= $vid['vid_name']; ?></p>
                <p><?= ($vid['type'] == 1) ? "Cours" : "Tutoriel"; ?></p>
                <?php
                    if ($_SESSION['usertype'] == 1) {
                ?>
                <a href="delete_video.php?id=<?= $vid['vid_id'];?>"><img class="trash_can" src="../medias/images/trash_can.png"></a>
                <?php 
                    } 
                ?>
            </div>
        </div>
        <?php
            }    
        ?>
    </div>
</dl>

<script>
    // Fonction pour charger une vidéo dans le lecteur vidéo
    function loadVideo(videoUrl) {
        window.location.href = `lecteur.html?video=${encodeURIComponent(videoUrl)}`;
    }

    function openPopup() {
        document.getElementById('add_form').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    }

    function closePopup() {
            document.getElementById('add_form').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
</script>

<!-- Retour à l'accueil -->
<a class="disconnect-button" href="logout.php">Déconnexion</a>
</body>
</html>
