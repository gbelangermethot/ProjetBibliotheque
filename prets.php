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
    <title>Prets par documents</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="page">
        <div class="container">
             <?php
                afficherMenu();
                if(isset($_POST['retournerDocument'])){
                    retournerDocument($attr, $user, $pass, $opts);
                }
                if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                    $_SESSION['selectedUser'] = null;
                    $_SESSION['isRetourne']= 0;
                    $_SESSION['isRetard'] = 0;
                }
                else{
                    $_SESSION['isRetourne'] = (int)($_POST['isRetourne'] ?? 0);
                    $_SESSION['isRetard']   = (int)($_POST['isRetard'] ?? 0);
                }
                if(isset($_POST['voirPrets'])) {
                    $_SESSION['selectedUser'] = $_POST['courriel'];
                }
                if($_SESSION['selectedUser']){
                    echo "<h1>prets actif pour "  . $_SESSION['selectedUser'] . "</h1>";
                }
                else{
                    echo "<h1>liste des prets</h1>";
                }
                
            ?>
            <br>
            <div class="horizontal">
               <?php
                    $pdo = openConnexion($attr, $user, $pass, $opts);
                    $id = isset($_POST['id']) ? $_POST['id'] : null ;
                    $titre = isset($_POST['titre']) ? $_POST['titre'] : null;
                    $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : null ;
                    $nom = isset($_POST['nom']) ? $_POST['nom'] : null ;
                    $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null ;
                    $courriel = $_SESSION['selectedUser'] ;
                    $dateCourrante = (new DateTime('now', new DateTimeZone('America/Toronto')))->format('Y-m-d H:i:s');
                    
                    $sql = ($_SESSION['isRetard']== 0) ?
                    "SELECT
                        p.ID           AS 'ID du pret',
                        d.titre AS Titre,
                        d.categorie AS Catégorie,
                        u.nom As Nom,
                        u.prenom AS Prénom,
                        u.courriel AS Courriel,
                        p.datePret AS 'Date du pret',
                        p.dateRetour AS 'Date de retour',
                        CASE WHEN p.isRetourne = 1 THEN 'oui' ELSE 'non' END AS 'Pret retourné'
                        FROM prets p
                        INNER JOIN documents d     ON p.documentID     = d.ID
                        INNER JOIN utilisateurs u  ON p.utilisateurID  = u.ID
                        WHERE p.ID        LIKE :id
                        AND titre       LIKE :titre
                        AND categorie   LIKE :categorie
                        AND nom         Like :nom
                        AND prenom      LIKE :prenom
                        AND courriel    LIKE :courriel
                        AND p.isRetourne  = :isRetourne;":
                        "SELECT
                        p.ID           AS 'ID du pret',
                        d.titre AS Titre,
                        d.categorie AS Catégorie,
                        u.nom As Nom,
                        u.prenom AS Prénom,
                        u.courriel AS Courriel,
                        p.datePret AS 'Date du pret',
                        p.dateRetour AS 'Date de retour',
                        CASE WHEN p.isRetourne = 1 THEN 'oui' ELSE 'non' END AS 'Pret retourné'
                        FROM prets p
                        INNER JOIN documents d     ON p.documentID     = d.ID
                        INNER JOIN utilisateurs u  ON p.utilisateurID  = u.ID
                        WHERE p.ID        LIKE :id
                        AND titre       LIKE :titre
                        AND categorie   LIKE :categorie
                        AND nom         Like :nom
                        AND prenom      LIKE :prenom
                        AND courriel    LIKE :courriel
                        AND p.isRetourne  = 0
                        AND dateRetour < :dateCourrante;" ;

                    $command = $pdo->prepare($sql);

                    $command->bindValue(':id', '%'.($id ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':titre', '%'.($titre ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':categorie', '%'.($categorie ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':nom', '%'.($nom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':prenom', '%'.($prenom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':courriel', '%'.($courriel ?? '').'%', PDO::PARAM_STR);
                    if ($_SESSION['isRetard'] == 1) {
                        $command->bindValue(':dateCourrante', $dateCourrante, PDO::PARAM_STR);
                    }else{
                        $command->bindValue(':isRetourne', $_SESSION['isRetourne'], PDO::PARAM_STR);
                    }
                    $command->execute();

                    $rows = $command->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <table>
                    <tr>
                        <th></th>
                        <th>Filtres</th>
                    </tr>
                    <form action="prets.php" method="post">
                        <tr>
                            <td>ID</td>
                            <td><input type="text" name="id"></td>
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
                            <td>Nom</td>
                            <td><input type="text" name="nom"></td>
                        </tr>
                        <tr>
                            <td>Prénom</td>
                            <td><input type="text" name="prenom"></td>
                        </tr>
                        <tr>
                            <td>courriel</td>
                            <td><input type="text" name="courrielFiltre"></td>
                        </tr>
                        <tr>
                            <td>Est retourné</td>
                            <td><input type="checkbox" name="isRetourne" value="1" <?php if($_SESSION['isRetourne'] == 1)  {echo'checked';} ?> onchange="this.form.requestSubmit()"></td>
                        </tr>
                        <tr>
                            <td>Est en retard</td>
                            <td><input type="checkbox" name="isRetard" value="1" <?php if($_SESSION['isRetard'] == 1)  {echo'checked';} ?> onchange="this.form.requestSubmit()"></td>
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
                        <th>Pret Selectionné</th>
                    </tr>
                    <?php
                    // Si aucun doc : message + pas de liste
                    if (!$rows) {
                        echo "<tr><td>Aucun document trouvé.</td></tr>";
                        $selected = null;
                    } 
                    else {
                        // 1) Déterminer l'ID sélectionné : POST -> sinon 1er enregistrement
                        $selectedId = $_POST['selected_id'] ?? $rows[0]['ID'] ?? null;
                        
                        // 2) Trouver la ligne sélectionnée (elle existe puisque l'ID vient d'un <tr> cliqué)
                        $selected = null;
                        foreach ($rows as $r) {
                            if ((string)$r['ID du pret'] === (string)$selectedId) {
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
                        if($selected['Pret retourné'] == 'non'){
                            echo '<form action="prets.php" method="post">';
                                echo "<tr><td></td><td><button type='submit' name='retournerDocument' value='" . $selected['ID du pret'] . "'>Retour</button></td></tr>";
                                echo "<input type='hidden' name='selected_id' value='" . $selected['ID du pret'] . "'>";
                            echo '</form>';
                        }
                    }
                    ?>
                </table>
            </div>
            <br>
            <div>
                <table>
                    <tr class="listRow">
                        <th class='list'>Pret ID</th>
                        <th class='list'>Titre</th>
                        <th class='list'>Catégorie</th>
                        <th class='list'>Nom du membre</th>
                        <th class='list'>Prénom du membre</th>
                        <th class='list'>Courriel</th>
                        <th class='list'>Date du pret</th>
                        <th class='list'>Date de retour</th>
                        <th class='list'> Est retourné</th>
                    </tr>
                    <?php
                        foreach ($rows as $row){
                            echo '<tr class="listRow" data-id="' . htmlspecialchars($row['ID du pret']) . '" onclick="selectionerDocument(this)">';
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
    </div>
    <form id="selectForm" method="post" action="prets.php">
        <input type="hidden" name="isRetard" id="isRetard" value="<?php echo $_SESSION['isRetard']; ?>">
        <input type="hidden" name="isRetourne" id="isRetourne" value="<?php echo $_SESSION['isRetourne']; ?>">
        <input type="hidden" name="selected_id" id="selected_id">
    </form>
    <script src="script.js"></script>
</body>
</html>