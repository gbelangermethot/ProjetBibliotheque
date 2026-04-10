<?php
    require_once 'login.php';
    require_once 'fonctions.php';

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(); // toujours au début de chaque page qui utilise $_SESSION
    }
    if(isset($_POST['connexion'])){ 
        validerUtilisateur($attr, $user, $pass, $opts);
    }
    if (isset($_POST['courriel'])) {
        enregistrerUtilisateur($attr, $user, $pass, $opts);
    }

    if (!empty($_SESSION['nom'])) {
        // Supprime toutes les données de session
        $_SESSION = [];
        session_destroy();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>page titre</title>
    <link rel="stylesheet" href="./css/style.css">
    </head>
<body>
    <?php
        afficherMenu();
    ?>
    <div class="page">
        <div class="container">
            
            <h1 id="titre">Bienvenue a la bibiliothèque Port Cartier</h1>
            <br>
            <br>
            <a href="connexion.php">
                <div class="link">
                    <p>
                        se connecter
                    </p>
                </div>
            </a>
            <br>
            <a href="inscription.php">
                <div class="link">
                    <p>
                        s'inscrire
                    </p>
                </div>
            </a>
        </div>
    </div>
    <script src="script.js"></script>
  </body>
</body>
</html>