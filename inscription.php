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
    <title>Inscription</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        afficherMenu();
    ?>
    <div class="page">
        <div class="container">
            
            <h1>Remplissez ce formulaire pour votre inscription</h1>
            <form action="index.php" method="post">
                <table class="container">
                    <tr>
                        <td>Nom</td>
                        <td><input type="text" name="nom" required></td>
                    </tr>
                    <tr>
                        <td>Prenom</td>
                        <td><input type="text" name="prenom" required></td>
                    </tr>
                    <tr>
                        <td>Adresse</td>
                        <td><input type="text" name="adresse" required></td>
                    </tr>
                    <tr>
                        <td>Ville</td>
                        <td><input type="text" name="ville" required></td>
                    </tr>
                    <tr>
                        <td>Province</td>
                        <td><input type="text" name="province" required></td>
                    </tr>
                    <tr>
                        <td>Code postal</td>
                        <td><input type="text" name="codePostal" required></td>
                    </tr>
                    <tr>
                        <td>Numero de telephone</td>
                        <td><input type="text" name="telephone" required></td>
                    </tr>
                    <tr>
                        <td>Courriel</td>
                        <td><input type="email" name="courriel" required></td>
                    </tr>
                    <tr>
                        <td>Mot de passe</td>
                        <td><input type="password" name="password" required></td>
                    </tr>
                    <tr>
                        <td>Confirmer le mot de passe</td>
                        <td><input type="password" name="password2" required></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type='submit' name='valider' value='valider'>Valider</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const pass = document.querySelector("input[name='password']").value;
        const pass2 = document.querySelector("input[name='password2']").value;
        if (pass !== pass2) {
            e.preventDefault();
            alert("Les mots de passe ne correspondent pas !");
        }

        const codePostal = document
            .querySelector("input[name='codePostal']")
            .value.trim()
            .toUpperCase();

        // Regex format canadien
        const regex = /^[A-Z]\d[A-Z]\s?\d[A-Z]\d$/;

        if (!regex.test(codePostal)) {
            e.preventDefault();
            alert("Veuillez entrer le code postal au format A1A 1A1");
            return;
        }
    });
    </script>
    <script src="script.js"></script>
</body>
</html>