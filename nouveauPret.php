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
    <title>Nouveau pret</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        afficherMenu();
        
    ?>
    <div class="page">
        <div class="container">
            <?php
                
                if (!empty($_SESSION['isEmploye']) && isset($_POST['utilisateurId'])) {
                    $_SESSION['pret_utilisateurId'] = (int)$_POST['utilisateurId'];
                }
                if(isset($_POST['resetPretUser'])){
                    $_SESSION['pret_utilisateurId'] = (int)$_SESSION['ID'];
                }
                if (!empty($_SESSION['isEmploye'])) {
                    $utilisateurId = (int)($_SESSION['pret_utilisateurId'] ?? 0);
                    if ($utilisateurId <= 0) {
                        die("Aucun utilisateur sélectionné.");
                    }
                } else {
                    $utilisateurId = (int)$_SESSION['ID'];
                }
                $documentId = $_POST['selected_id'] ?? $_POST['documentId'] ?? null;
                
                if(isset($_POST['preter'])){
                    creerPret($attr, $user, $pass, $opts, $utilisateurId, $documentId);
                }

                if(isset($_POST['reserver'])){ 
                            reserverDocument($attr, $user, $pass, $opts, $documentId, $utilisateurId);
                }

                $pdo = openConnexion($attr, $user, $pass, $opts);
                $sql = "SELECT prenom, nom, courriel FROM utilisateurs WHERE ID = :id LIMIT 1";
                $command = $pdo->prepare($sql);
                $command->bindValue(':id', $utilisateurId, PDO::PARAM_INT);
                $command->execute();

                $utilisateur = $command->fetch(PDO::FETCH_ASSOC);
                if (!$utilisateur) {
                    die("Utilisateur introuvable.");
                }
                $pdo = null;
                $prenom   = $utilisateur['prenom'];
                $nom      = $utilisateur['nom'];
                $courriel = $utilisateur['courriel'];
            ?>
            <h1>Creation d'un pret pour <?php echo $prenom . " " . $nom . ", " . $courriel ?></h1>
             
            <br>
            <br>
            <div class="center">
                <table>
                    <tr>
                        <th></th>
                        <th>Document selectionné</th>
                        <?php
                            if (!$documentId) {
                                echo "<tr><td>Aucun document trouvé.</td></tr>";
                            } else {
                                $pdo = openConnexion($attr, $user, $pass, $opts);

                                try{
                                    // preparation de la commande preparee avec les placeholders
                                    $sql = "SELECT 
                                            ID,
                                            titre AS Titre,
                                            auteur AS Auteur,
                                            annee AS Année,
                                            categorie AS Catégorie,
                                            type AS Type,
                                            genre AS Genre,
                                            description AS Description,
                                            isbn AS ISBN
                                            FROM `documents` WHERE ID = :id";

                                    $command =  $pdo->prepare($sql);
                                    $command->bindValue(':id', $documentId, PDO::PARAM_STR);
                                    
                                    // Executer la commande
                                    $command->execute();
                                }
                                catch (PDOException $e) {
                                    // message d'erreur si la conection echoue
                                    echo "Erreur : " . $e->getMessage();
                                }
                                
                                $document = $command->fetch(PDO::FETCH_ASSOC);

                                // 2) Afficher le panneau "Document sélectionné"
                                if ($document){
                                    foreach ($document as $fieldName => $value) {
                                        echo "<tr><td>" . htmlspecialchars($fieldName) . ":</td><td> " . htmlspecialchars($value) . "</td></tr>";
                                    }
                                    // 2) Statut PRET
                                    $sql = "SELECT dateRetour
                                                FROM prets
                                                WHERE documentId = :docId AND isRetourne = 0
                                                LIMIT 1";
                                    $command = $pdo->prepare($sql);
                                    $command->bindValue(':docId', (int)$documentId, PDO::PARAM_INT);
                                    $command->execute();
                                    $pretRow = $command->fetch(PDO::FETCH_ASSOC);

                                    if ($pretRow) {
                                        $pretTexte = "Document prêté jusqu'au " . $pretRow['dateRetour'];
                                    } else {
                                        $pretTexte = "Document disponible";
                                    }
                                    echo "<tr><td>Pret</td><td>" . htmlspecialchars($pretTexte) . "</td></tr>";

                                    $sql = "SELECT ID
                                                FROM reservations
                                                WHERE documentId = :docId AND UtilisateurID <>". $utilisateurId ." AND isActive = 1
                                                LIMIT 1";
                                    $command = $pdo->prepare($sql);
                                    $command->bindValue(':docId', (int)$documentId, PDO::PARAM_INT);
                                    $command->execute();
                                    $reservationRow = $command->fetch(PDO::FETCH_ASSOC);

                                    if ($reservationRow) {
                                        $reservationTexte = "Document reverve par un autre membere";
                                    } else {
                                        $reservationTexte = "Document disponible";
                                    }
                                    echo "<tr><td>Réservation</td><td>" . htmlspecialchars($reservationTexte) . "</td></tr>";
                                }
                                else{
                                    echo "<tr><td>Aucun document trouvé.</td></tr>";
                                }
                                $pdo = null;
                            }
                        ?>
                    </tr>
                    <?php

                   
                    ?>
                    <form action="nouveauPret.php" method="post">
                        <tr>
                            <td>
                                <label for="documentId">ID du document</label>
                            </td>
                            <td>
                                
                                <input type="number" name="documentId" id="documentId"
                                    value="<?php echo htmlspecialchars((string)($documentId ?? '')); ?>"
                                    min="1" required>

                                <button type="submit" name="rechercher" value="1">Rechercher</button>
                                
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <?php if (!empty($document)): ?>
                                    <button type="submit" name="preter" value="1">Prêter</button>
                                <?php endif; ?>

                                <?php if (!empty($document)): ?>
                                    <button type='submit' name='reserver' value='1'>Reserver</button>
                                <?php endif; ?>
                            </td>
                            
                        </tr>
                    </form>
                </table>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>