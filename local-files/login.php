<?php
// Démarrer une session
session_start();

// Connexion à la base de données
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

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête pour chercher l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM user WHERE name = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['password'] == $password) {
        // Stocker les informations utilisateur dans la session
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['usertype'] = $user['user_type'];
        header("Location: conferences.php"); // Rediriger après connexion
        exit;
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css" /> 
  </head>
  <body>
      <a class="connect-button" href="login_page.html">Se connecter</a>
  </body>
</html>