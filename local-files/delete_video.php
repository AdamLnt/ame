<?php
session_start();
include('functions.php');

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

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $sql2 = "SELECT * FROM video WHERE vid_id = :id";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();
    $video = $stmt2->fetch(PDO::FETCH_ASSOC);

    unlink($video['vid_link']);
    unlink($video['vid_img']);
    var_dump($video);

    $sql = "DELETE FROM video WHERE vid_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    

    
    if ($stmt->execute()) {
        header("Location: conferences.php?message=Vidéo supprimée avec succès");
        exit();
    } else {
        echo "Problème de suppression";
    }
} else {
    echo "Erreur dans la requête";
}