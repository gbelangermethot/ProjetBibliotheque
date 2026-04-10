<?php
    require_once 'login.php';
    require_once 'fonctions.php';

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start(); // toujours au début de chaque page qui utilise $_SESSION
    }
    if(isset($_POST['connexion'])){ 
        validerUtilisateur($attr, $user, $pass, $opts);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        afficherMenu();
    ?>
    <div class="page">
        <div class="container">
            <form action="main.php" method="post">
                <table class="container">
                    <tr>
                        <td>Courriel</td>
                        <td><input type="text" name="courriel" required></td>
                    </tr>
                    <tr>
                        <td>Mot de passe</td>
                        <td><input type="password" name="password" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type='submit' name='connexion' value='connexion'>Connexion</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</body>
</html>