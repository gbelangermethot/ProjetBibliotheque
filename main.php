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
    <title>Page principale</title>
    <link rel="stylesheet" href="./css/style.css">    
</head>
<body>
    <?php
        afficherMenu();
    ?>
    <div class="page">
        <div class='container'>
            
            <?php
                if(isset($_SESSION['nom'])){
                    echo "<h1>Bonjour " . $_SESSION['prenom'] . " " . $_SESSION['nom'] . "</h1>";
                }
                // ouverture de la connection et rechercher de la selection
                

                $pdo = openConnexion($attr, $user, $pass, $opts);
                $id = isset($_POST['id']) ? $_POST['id'] : null ;
                $titre = isset($_POST['titre']) ? $_POST['titre'] : null ;
                $auteur = isset($_POST['auteur']) ? $_POST['auteur'] : null ;
                $annee = isset($_POST['annee']) ? $_POST['annee'] : null ;
                $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : null ;
                $type = isset($_POST['type']) ? $_POST['type'] : null ;
                $genre = isset($_POST['genre']) ? $_POST['genre'] : null ;
                $description = isset($_POST['description']) ? $_POST['description'] : null ;
                $isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null ;

                try{
                    // preparation de la commande preparee avec les placeholders
                    $sql = ($isbn) ?
                    "SELECT 
                    ID,           
                    titre AS Titre,
                    auteur AS Auteur,
                    annee AS Année,
                    categorie AS Catégorie,
                    type AS Type,
                    genre AS Genre,
                    description AS Description.
                    isbn AS ISBN
                    FROM `documents`
                    WHERE ID          LIKE :id
                    AND titre       LIKE :titre
                    AND auteur      LIKE :auteur
                    AND annee       LIKE :annee
                    AND categorie   LIKE :categorie
                    AND type        LIKE :type
                    AND genre       LIKE :genre
                    AND description LIKE :description
                    AND isbn        LIKE :isbn":
                    "SELECT 
                    ID,           
                    titre AS Titre,
                    auteur AS Auteur,
                    annee AS Année,
                    categorie AS Catégorie,
                    type AS Type,
                    genre AS Genre,
                    description AS Description,
                    isbn AS ISBN
                    FROM `documents`
                    WHERE ID          LIKE :id
                    AND titre       LIKE :titre
                    AND auteur      LIKE :auteur
                    AND annee       LIKE :annee
                    AND categorie   LIKE :categorie
                    AND type        LIKE :type
                    AND genre       LIKE :genre
                    AND description LIKE :description";

                    $command =  $pdo->prepare($sql);
                    $command->bindValue(':id', '%'.($id ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':titre', '%'.($titre ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':auteur', '%'.($auteur ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':annee', '%'.($annee ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':categorie', '%'.($categorie ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':type', '%'.($type ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':genre', '%'.($genre ?? '').'%', PDO::PARAM_STR);
                    $command->bindValue(':description', '%'.($description ?? '').'%', PDO::PARAM_STR);
                    if($isbn){
                    $command->bindValue(':isbn', '%'.($isbn ?? '').'%', PDO::PARAM_STR);
                    }
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
                    <form action="main.php" method="post">
                        <tr>
                            <th></th>
                            <th>Filtres</th>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td><input type="text" name="id"></td>
                        </tr>
                        <tr>
                            <td>Titre</td>
                            <td><input type="text" name="titre"></td>
                        </tr>
                        <tr>
                            <td>Auteur</td>
                            <td><input type="text" name="auteur"></td>
                        </tr>
                        <tr>
                            <td>Année</td>
                            <td><input type="text" name="annee"></td>
                        </tr>
                        <tr>
                            <td>Catégorie</td>
                            <td><input type="text" name="categorie"></td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td><input type="text" name="type"></td>
                        </tr>
                        <tr>
                            <td>Genre</td>
                            <td><input type="text" name="genre"></td>
                        </tr>
                        <tr>
                            <td>isbn</td>
                            <td><input type="text" name="isbn"></td>
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
                        <th>Document selectionné</th>
                    </tr>
                    <?php

                    // Récupère TOUTES les lignes une seule fois
                    $rows = $command->fetchAll(PDO::FETCH_ASSOC);

                    // Si aucun doc : message + pas de liste
                    if (!$rows) {
                        echo "<tr><td>Aucun document trouvé.</td></tr>";
                    } else {
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

                        // 3) Afficher le panneau "Document sélectionné"
                        foreach ($selected as $fieldName => $value) {
                            echo "<tr><td>" . htmlspecialchars($fieldName) . ":</td><td> " . htmlspecialchars($value) . "</td></tr>";
                        }
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td>
                            <form action="nouveauPret.php" method="post">
                                <input type="hidden" name="selected_id" value="<?php echo htmlspecialchars((string)$selectedId); ?>">
                                <input type="hidden" name="resetPretUser" value="1">
                                <?php
                                    if(isset($_SESSION['nom'])){
                                        echo "<button type='submit' name='nouveauPrets' value='nouveauPrets'>Nouveau Pret</button>";
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
                    <th class='list'>Titre<br></th>
                    <th class='list'>Auteur<br></th>
                    <th class='list'>Année<br></th>
                    <th class='list'>Catégorie<br></th>
                    <th class='list'>Type<br></th>
                    <th class='list'>Genre<br></th>
                    <th class='list'>ISBN<br></th>
                </tr>
                <?php
                    foreach ($rows as $row){
                        echo '<tr class="listRow" data-id="' . htmlspecialchars($row['ID']) . '" onclick="selectionerDocument(this)">';
                        foreach($row as $r){
                            if($r != $row['Description']){
                                echo "<td class='list'>" . htmlspecialchars($r) . "</td>";
                            }
                        }
                        echo "</tr>";
                    }
                $pdo = null;
                ?>
            </table>
        </div>
    </div>
    <form id="selectForm" method="post" action="main.php">
        <input type="hidden" name="selected_id" id="selected_id">
    </form>
    <script src="script.js"></script>
</body>
</html>