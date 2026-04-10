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
    <title>Gestion d'utilisateur</title>
    <link rel="stylesheet" href="./css/style.css">    
</head>
<body>
    <?php
        afficherMenu();
    ?>
    <div class="page">
        <div class='container'>
            
            <?php
                echo "<h1>Bonjour " . $_SESSION['prenom'] . " " . $_SESSION['nom']  . "</h1>";
                // ouverture de la connection et rechercher de la selection
                $pdo = openConnexion($attr, $user, $pass, $opts);
                $id = isset($_POST['id']) ? $_POST['id'] : null ;
                $nom = isset($_POST['nom']) ? $_POST['nom'] : null ;
                $prenom = isset($_POST['prenom']) ? $_POST['prenom'] : null ;
                $adresse = isset($_POST['adresse']) ? $_POST['adresse'] : null ;
                $ville = isset($_POST['ville']) ? $_POST['ville'] : null ;
                $province = isset($_POST['province']) ? $_POST['province'] : null ;
                $codePostal = isset($_POST['codePostal']) ? $_POST['codePostal'] : null ;
                $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : null ;
                $isEmploye = isset($_POST['isEmploye']) ? 1 : 0 ;
                $courriel = isset($_POST['courriel']) ? $_POST['courriel'] : null ;

                try{
                    // preparation de la commande preparee avec les placeholders
                    $sql = ($isEmploye == 1) ?
                    "SELECT  
                    ID,
                    nom AS Nom,
                    prenom AS Prénom,
                    adresse AS Adresse,
                    ville AS Ville,
                    province As Province,
                    codePostal AS 'Code postal',
                    telephone AS Téléphone,
                    courriel AS Courriel,
                    isEmploye AS Employé
                    FROM `utilisateurs`
                    WHERE ID        LIKE :id
                    AND nom         LIKE :nom
                    AND prenom      LIKE :prenom
                    AND adresse     LIKE :adresse
                    AND ville       LIKE :ville
                    AND province    LIKE :province
                    AND codePostal  LIKE :codePostal
                    AND telephone   LIKE :telephone
                    AND courriel    LIKE :courriel
                    AND isEmploye   = :isEmploye":
                    "SELECT 
                    ID,
                    nom AS Nom,
                    prenom AS Prénom,
                    adresse AS Adresse,
                    ville AS Ville,
                    province As Province,
                    codePostal AS 'Code postal',
                    telephone AS Téléphone,
                    courriel AS Courriel,
                    isEmploye AS Employé
                    FROM `utilisateurs`
                    WHERE ID        LIKE :id
                    AND nom         LIKE :nom
                    AND prenom      LIKE :prenom
                    AND adresse     LIKE :adresse
                    AND ville       LIKE :ville
                    AND province    LIKE :province
                    AND codePostal  LIKE :codePostal
                    AND telephone   LIKE :telephone
                    AND courriel    LIKE :courriel";

                    $command =  $pdo->prepare($sql);
                    $command->bindValue(':id', '%'.($id ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':nom', '%'.($nom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':prenom', '%'.($prenom ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':adresse', '%'.($adresse ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':ville', '%'.($ville ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':province', '%'.($province ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':codePostal', '%'.($codePostal ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':telephone', '%'.($telephone ?? '').'%', PDO::PARAM_STR);
                    if($isEmploye == 1){
                        $command->bindValue(':isEmploye', ($isEmploye ?? ''), PDO::PARAM_INT);
                    }
                    $command->bindValue(':courriel', '%'.($courriel ?? '').'%', PDO::PARAM_STR);

                    // Executer la commande
                    $command->execute();
                }
                catch (PDOException $e) {
                    // message d'erreur si la conection echoue
                    echo "Erreur : " . $e->getMessage();
                } 
            ?>
            
            <br>
            <br>
            <div class="horizontal">
                <table>
                    <form action="admin.php" method="post">
                        <tr>
                            <th></th>
                            <th>Filtres</th>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td><input type="text" name="id"></td>
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
                            <td>Adresse</td>
                            <td><input type="text" name="adresse"></td>
                        </tr>
                        <tr>
                            <td>Ville</td>
                            <td><input type="text" name="ville"></td>
                        </tr>
                        <tr>
                            <td>Province</td>
                            <td><input type="text" name="province"></td>
                        </tr>
                        <tr>
                            <td>Code postal</td>
                            <td><input type="text" name="codePostal"></td>
                        </tr>
                        <tr>
                            <td>téléphone</td>
                            <td><input type="text" name="telephone"></td>
                        </tr>
                        <tr>
                            <td>courriel</td>
                            <td><input type="text" name="courriel"></td>
                        </tr>
                        <tr>
                            <td>Est un employé</td>
                            <td><input type="checkbox" name="isEmploye" value="1" <?php if($isEmploye == 1)  {echo'checked';} ?> onchange="this.form.requestSubmit()"></td>
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
                        <th>Utilisateur selectionné</th>
                    </tr>
                    <?php

                    // Récupère TOUTES les lignes une seule fois
                    $rows = $command->fetchAll(PDO::FETCH_ASSOC);

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
                        if ((string)$r['ID'] === (string)$selectedId) {
                            $selected = $r;
                            break;
                        }
                    }
                        // Par sécurité (au cas où), fallback au premier
                        if ($selected === null) $selected = $rows[0];
                        
                        if(isset($_POST['changerEmploye'])){ 
                            changerEmploye($attr, $user, $pass, $opts, $selected);
                        }
                        $command->execute();
                        $rows = $command->fetchAll(PDO::FETCH_ASSOC);
                        $selected = null;
                        foreach ($rows as $r) {
                            if ((string)$r['ID'] === (string)$selectedId) {
                                $selected = $r;
                                break;
                            }
                        }

                        // 3) Afficher le panneau "Document sélectionné"
                        foreach ($selected as $fieldName => $value) {
                            if($fieldName != 'password' && $fieldName != 'isAdmin'){
                                if($fieldName != 'Employé'){
                                    echo "<tr><td>" . htmlspecialchars($fieldName) . ":</td><td> " . htmlspecialchars($value) . "</td></tr>";
                                }
                                else{
                                    $valuePrint  = $value ? "oui" : "non";
                                    echo "<tr><td>" . htmlspecialchars($fieldName) . ":</td><td> " . $valuePrint. "</td></tr>"; 
                                }
                            }
                        }
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <form action="admin.php" method="post">
                                <input type="hidden" name="selected_id" value="<?php echo htmlspecialchars((string)$selectedId); ?>">
                                <?php
                                if($selected){
                                    if($_SESSION['isAdmin']==1){
                                        if($selected['Employé'] == 0) {
                                            echo "<button type='submit' name='changerEmploye' value='changerEmploye'>Mettre employe</button>";
                                        }
                                        else{
                                            echo "<button type='submit' name='changerEmploye' value='changerEmploye'>Retirer employe</button>";
                                        }
                                    }
                                }
                                ?>
                            </form>
                            <br>
                            <form action="prets.php" method="post">
                                <?php
                                    if($selected){
                                        echo '<input type="hidden" name="courriel" value="' . htmlspecialchars($selected["Courriel"]) . '">';
                                        echo "<button type='submit' name='voirPrets' value='voirPrets'>Voir les Prets</button>";
                                    }
                                ?>
                            </form>
                            <br>
                            <form action="nouveauPret.php" method="post">
                                <?php
                                    if($selected){
                                        echo '<input type="hidden" name="utilisateurId" value="' . htmlspecialchars($selected["ID"]) . '">';
                                        echo '<input type="hidden" name="courriel" value="' . htmlspecialchars($selected["Courriel"]) . '">';
                                        echo '<input type="hidden" name="nom" value="' . htmlspecialchars($selected["Nom"]) . '">';
                                        echo '<input type="hidden" name="prenom" value="' . htmlspecialchars($selected["Prénom"]) . '">';
                                        echo "<button type='submit' name='nouveauPret' value='" . htmlspecialchars($selected["ID"]) . "'>Creer un pret</button>";
                                    }
                                ?>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <table>
                <tr>
                    <th class='list'>ID<br></th>
                    <th class='list'>Nom<br></th>
                    <th class='list'>Prénom<br></th>
                    <th class='list'>Adresse<br></th>
                    <th class='list'>Ville<br></th>
                    <th class='list'>Province<br></th>
                    <th class='list'>Code Postal<br></th>
                    <th class='list'>Téléphone<br></th>
                    <th class='list'>Employé<br></th>
                    <th class='list'>Courriel<br></th>
                </tr>
                
                <?php
                    $cols = ['ID','Nom','Prénom','Adresse','Ville','Province','Code postal','Téléphone','Employé', 'Courriel'];
                    foreach ($rows as $row) {
                        $idAttr = htmlspecialchars((string)($row['ID'] ?? ''), ENT_QUOTES, 'UTF-8');
                        echo '<tr class="listRow" data-id="'.$idAttr.'" onclick="selectionerDocument(this)">';
                        foreach ($cols as $c) {
                            $val = $row[$c] ?? '';
                            if ($c === 'Employé') {
                                $out = ((int)$val === 1) ? 'Oui' : 'Non';
                            } else {
                                $out = htmlspecialchars((string)$val);
                            }
                            echo '<td class="list">'.$out.'</td>';
                        }
                        echo '</tr>';
                    }
                    $pdo = null;
                ?>
            </table>
        </div>
    </div>
    <form id="selectForm" method="post" action="admin.php">
        <input type="hidden" name="selected_id" id="selected_id">
    </form>
    <script src="script.js"></script>
</body>
</html>