<?php
header("Location: conferences.php");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $typeInput = htmlspecialchars($_POST['type']);
    $type = ($typeInput === "conference") ? 1 : 2;

    $uploadDirVideo = "../medias/videos/";
    $uploadDirImage = "../medias/images/";


    if (!is_dir($uploadDirVideo)) {
        mkdir($uploadDirVideo, 0777, true);
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        
        $allowedVideoTypes = ['mp4', 'avi', 'mov', 'mkv']; 
        $allowedImageTypes = ['jpeg', 'jpg', 'png', 'gif'];
        $videoExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $imageExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($videoExtension, $allowedVideoTypes) && in_array($imageExtension, $allowedImageTypes)) {
            $fileName = $name . "_" . uniqid() . "." . $videoExtension;
            $imageName = $name . "_" . uniqid() . "." . $imageExtension;
            $filePath = $uploadDirVideo . $fileName;
            $imagePath = $uploadDirImage . $imageName;
            
            if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath) && move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $sql = "INSERT INTO video (vid_name, vid_link, vid_img, type) VALUES (:name, :vid_link, :vid_img, :type)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':name' => $name,
                    ':vid_link' => $filePath,
                    ':vid_img'=> $imagePath,
                    ':type' => $type,
                ]);

                echo "La vidéo <strong>" . htmlspecialchars($name) . "</strong> de type <strong>" . htmlspecialchars($type) . "</strong> a été ajoutée avec succès.";
                echo "<br>Fichier téléchargé : " . htmlspecialchars($fileName);
            } else {
                echo "Erreur lors du téléchargement du fichier.";
            }
        } else {
            echo "Type de fichier non autorisé. Seuls les fichiers vidéo (" . implode(", ", $allowedVideoTypes) . ") sont autorisés.";
        }
    } else {
        echo "Aucun fichier valide n'a été téléchargé.";
    }
} else {
    echo "Requête invalide.";
}
?>
