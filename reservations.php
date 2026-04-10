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
    <title>Reservations</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="page">
        <div class="container">
             <?php
                afficherMenu();
                if(isset($_POST['annulerReservation'])){
                    $reservationId = isset($_POST['annulerReservation']) ? $_POST['annulerReservation'] : null;
                    annulerReservation($attr, $user, $pass, $opts, $reservationId);
                }
            ?>
            <br>
            <div class="horizontal">
               <?php
                    $pdo = openConnexion($attr, $user, $pass, $opts);
                    $reservationId = isset($_POST['reservationId']) ? $_POST['reservationId'] : null;
                    $titre = isset($_POST['titre']) ? $_POST['titre'] : null;
                    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : null ;
                    $documentId = isset($_POST['documentId']) ? $_POST['documentId'] : null ;
                    $nom = isset($_POST['nom']) ? $_POST['nom'] : null ;
                    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null ;
                    $courriel = isset($_POST['courriel']) ? $_POST['courriel'] : null ;
                    $date = isset($_POST['date']) ? trim($_POST['date']) : null ;
                    
                    $sql = ($date) ?
                    "SELECT
                    r.ID AS 'ID de la réservation',
                    d.titre AS Titre,
                    d.categorie AS Catégorie,
                    r.documentID AS 'ID du document',
                    u.nom AS 'Nom du membre',
                    u.prenom AS'Prénom du membre',
                    u.courriel AS 'Courriel du membre',
                    r.dateReservation AS 'Date de la réservation'
                    FROM reservations r
                    INNER JOIN utilisateurs u on r.utilisateurID = u.ID
                    INNER JOIN documents d on r.documentID = d.ID
                    WHERE r.isActive = 1
                    AND r.ID              LIKE :reservationId
                    AND titre           LIKE :titre
                    AND categorie       LIKE :categorie
                    AND r.documentID    LIKE :documentId
                    AND nom             Like :nom
                    AND prenom          LIKE :prenom
                    AND courriel        LIKE :courriel
                    AND r.dateReservation = :date
                    ":
                   "SELECT
                    r.ID AS 'ID de la réservation',
                    d.titre AS Titre,
                    d.categorie AS Catégorie,
                    r.documentID AS 'ID du document',
                    u.nom AS 'Nom du membre',
                    u.prenom AS'Prénom du membre',
                    u.courriel AS 'Courriel du membre',
                    r.dateReservation AS 'Date de la réservation'
                    FROM reservations r
                    INNER JOIN utilisateurs u on r.utilisateurID = u.ID
                    INNER JOIN documents d on r.documentID = d.ID
                    WHERE r.isActive = 1
                    AND r.ID              LIKE :reservationId
                    AND titre           LIKE :titre
                    AND categorie       LIKE :categorie
                    AND r.documentID    LIKE :documentId
                    AND nom             Like :nom
                    AND prenom          LIKE :prenom
                    AND courriel        LIKE :courriel
                    ";

                    $command = $pdo->prepare($sql);

                    $command->bindValue(':reservationId', '%'.($reservationId ?? '').'%', PDO::PARAM_INT);
                    $command->bindValue(':titre', '%'.($titre ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':categorie', '%'.($categorie ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':documentId', '%'.($documentId ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':nom', '%'.($nom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':prenom', '%'.($prenom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':courriel', '%'.($courriel ?? '').'%', PDO::PARAM_STR);
                    if($date){
                        $command->bindValue(':date', $date, PDO::PARAM_STR);
                    }
                    
                    $command->execute();

                    $rows = $command->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <table>
                    <tr>
                        <th></th>
                        <th>Filtres</th>
                    </tr>
                    <form action="reservations.php" method="post">
                        <tr>
                            <td>ID de la réservation</td>
                            <td><input type="text" name="reservationId"></td>
                        </tr>
                        <tr>
                            <td>Titre</td>
                            <td><input type="text" name="titre"></td>
                        </tr>
                        <tr>
                            <td>Catégorie</td>
                            <td><input type="text" name="categorie"></td>
                        </tr>
                        <tr>
                            <td>ID du document</td>
                            <td><input type="text" name="documentId"></td>
                        </tr>
                        <tr>
                            <td>Nom</td>
                            <td><input type="text" name="nom"></td>
                        </tr>
                        <tr>
                            <td>Prénom</td>
                            <td><input type="text" name="prenom"></td>
                        </tr>
                        <tr>
                            <td>Courriel</td>
                            <td><input type="text" name="courriel"></td>
                        </tr>
                        <tr>
                            <td>Date de la réservation</td>
                            <td><input type="date" name="date"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type='submit' name='recherche' value='recherche'>Rechercher</button></td>
                        </tr>
                    </form>
                </table>

                <table>
                    <tr>
                        <th></th>
                        <th>Reservation Selectionnee</th>
                    </tr>
                    <?php
                    // Si aucun doc : message + pas de liste
                    if (!$rows) {
                        echo "<tr><td>Aucun document trouvé.</td></tr>";
                        $selected = null;
                    } 
                    else {
                        // 1) Déterminer l'ID sélectionné : POST -> sinon 1er enregistrement
                        $selectedId = $_POST['selected_id'] ?? $rows[0]['reservation_ID'] ?? null;
                        
                        // 2) Trouver la ligne sélectionnée (elle existe puisque l'ID vient d'un <tr> cliqué)
                        $selected = null;
                        foreach ($rows as $r) {
                            if ((string)$r['ID de la réservation'] === (string)$selectedId) {
                                $selected = $r;
                                break;
                            }
                        }
                        // Par sécurité (au cas où), fallback au premier
                        if ($selected === null) $selected = $rows[0];

                        // 3) Afficher le panneau "Document sélectionné"
                        foreach ($selected as $fieldName => $value) {
                            echo "<tr><td>" . htmlspecialchars($fieldName) . ":</td><td> " . htmlspecialchars($value) . "</td></tr>";
                        }
                        
                    }
                    
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <form action="reservations.php" method="post">
                                <?php
                                    if($selected){
                                        echo "<button type='submit' name='annulerReservation' value='" . htmlspecialchars($selected["ID de la réservation"]) . "'>Annuler la reservation</button>";
                                    }
                                ?>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <table>
                <tr class="listRow">
                    <th class='list'>ID réservation</th>
                    <th class='list'>Titre</th>
                    <th class='list'>Catégorie</th>
                    <th class='list'>ID du document</th>
                    <th class='list'>Nom du membre</th>
                    <th class='list'>Prénom du membre</th>
                    <th class='list'>Courriel</th>
                    <th class='list'>Date de la réservation</th>
                </tr>
                <?php
                    foreach ($rows as $row){
                        echo '<tr class="listRow" data-id="' . htmlspecialchars($row['ID de la réservation']) . '" onclick="selectionerDocument(this)">';
                        foreach($row as $r){
                            echo "<td class='list'>" . htmlspecialchars($r) . "</td>";
                        }
                        echo "</tr>";
                    }
                    $pdo = null;
                ?>
            </table>
        </div>
    </div>
    <form id="selectForm" method="post" action="reservations.php">
        <input type="hidden" name="selected_id" id="selected_id">
    </form>
    <script src="script.js"></script>
</body>
</html>