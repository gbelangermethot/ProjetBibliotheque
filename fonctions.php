<?php
    require_once 'login.php';
    // fonction pour ouvrir la connection
    function openConnexion($attr, $user, $pass, $opts){
        try
        {
            $pdo = new PDO($attr, $user, $pass, $opts);
            return $pdo;
        } 
        catch (PDOException $e)
        {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }     
    }

    function enregistrerUtilisateur($attr, $user, $pass, $opts){
        $pdo = openConnexion($attr, $user, $pass, $opts);
        $nom = isset($_POST['valider']) ? $_POST['nom'] : null;
        $prenom = isset($_POST['valider']) ? $_POST['prenom'] : null;
        $adresse = isset($_POST['valider']) ? $_POST['adresse'] : null;
        $ville = isset($_POST['valider']) ? $_POST['ville'] : null;
        $province = isset($_POST['valider']) ? $_POST['province'] : null;
        $codePostal = isset($_POST['valider']) ? strtoupper(trim($_POST['codePostal'])) : null;
        $codePostal = str_replace(' ', '', $codePostal);
        echo "codePostal dans enregistrer utilisateur " . $codePostal;
        $telephone = isset($_POST['valider']) ? $_POST['telephone'] : null;
        $password = isset($_POST['valider']) ? $_POST['password'] : null;
        $courriel = isset($_POST['valider']) ? $_POST['courriel'] : null;
        
        
        try{
            // preparation de la commande preparee avec les placeholders
            $sql = "INSERT INTO `utilisateurs`
            (`nom`, `prenom`, `adresse`, `ville`, `province`, `codePostal`, `Telephone`, `courriel`, `password`, `isEmploye`, `isAdmin`)
            VALUES (:nom, :prenom, :adresse, :ville, :province, :codePostal, :telephone, :courriel, :password, 0, 0)";

            $command = $pdo->prepare($sql);
            $command->bindValue(':nom', $nom, PDO::PARAM_STR); 
            $command->bindValue(':prenom', $prenom, PDO::PARAM_STR);
            $command->bindValue(':adresse', $adresse, PDO::PARAM_STR);
            $command->bindValue(':ville', $ville, PDO::PARAM_STR);
            $command->bindValue(':province', $province, PDO::PARAM_STR);
            $command->bindValue(':codePostal', $codePostal, PDO::PARAM_STR);
            $command->bindValue(':telephone', $telephone, PDO::PARAM_STR);
            $command->bindValue(':password', password_hash($password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $command->bindValue(':courriel', $courriel, PDO::PARAM_STR);
            
            // Executer la commande
            $command->execute();

            // message de confirmationd e lèenregistrement
            echo "utilisateur $prenom $nom enregistré avec succès.";
        }
        catch (PDOException $e) {
            // message d'erreur si la conection echoue
            if ($e->getCode() == 23000) { // violation de contrainte unique
                echo "Erreur : Ce courriel est déjà utilisé.";
            } else {
                echo "Erreur : " . $e->getMessage();
            }
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    

    function afficherMenu(){
    echo "<nav class='menu' id='mainMenu'>
            <button class='menu-toggle' type='button' aria-expanded='false' aria-controls='menuList'>
                <span class='sr-only'>Ouvrir le menu</span>
                ☰
            </button>
            <ul id='menuList' class='menu-list'>";

    if(!isset($_SESSION['nom'])){
        echo "<li><a href='index.php'>Accueil</a></li>";
        echo "<li><a href='connexion.php'>Se connecter</a></li>";
        echo "<li><a href='inscription.php'>S'inscrire</a></li>";
        echo "<li><a href='main.php'>Page documents</a></li>";
    } else {
        echo "<li><a href='index.php'>Se deconnecter</a></li>";
        echo "<li><a href='main.php'>Page Document</a></li>";

        if(!empty($_SESSION['isAdmin']) || !empty($_SESSION['isEmploye'])){
            echo "<li><a href='admin.php'>Gestion de membre</a></li>";
            echo "<li><a href='prets.php'>Voir les prets</a></li>";
            echo "<li><a href='reservations.php'>Voir les reservations</a></li>";
        }
    }

    echo "  </ul>
          </nav>";
}

    function validerUtilisateur($attr, $user, $pass, $opts){
        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);
            $courrielTemp = isset($_POST['connexion']) ? assainir($_POST['courriel']) : null;
            $passwordTemp = isset($_POST['connexion']) ? assainir($_POST['password']) : null;
            $sql = "SELECT * FROM utilisateurs WHERE courriel = :courriel";

            $command = $pdo->prepare($sql);
            $command->bindValue(':courriel', $courrielTemp, PDO::PARAM_STR);

            // Executer la commande
            $command->execute();

            if(!$command->rowCount()) die("Utilisateur " . $courrielTemp . " introuvalble");

            $row = $command->fetch();
            $id = $row['ID'];
            $prenom = $row['prenom'];
            $nom = $row['nom'];
            $courriel = $row['courriel'];
            $isEmploye = $row['isEmploye'];
            $isAdmin = $row['isAdmin'];
            $password = $row['password'];


            if(password_verify(str_replace("'", "", $passwordTemp), $password)){
                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start(); // toujours au début de chaque page qui utilise $_SESSION
                }

                $_SESSION['ID'] = $id;
                $_SESSION['prenom'] = $prenom;
                $_SESSION['nom'] = $nom;
                $_SESSION['isEmploye'] = $isEmploye;
                $_SESSION['isAdmin'] = $isAdmin;
                $_SESSION['courriel'] = $courriel;
            }
            else{
                die("Nom d'utilisateur" . "" . " ou mot de passe invalide");
            }

        }
        catch (PDOException $e) {
            // message d'erreur si la conection echoue
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    function assainir($chaine){
        $chaine = htmlentities($chaine);
        return $chaine;
    }

    function reserverDocument($attr, $user, $pass, $opts, $documentId, $utilisateurId){
        
        if ($documentId <= 0) {
            echo "Erreur : document invalide.";
            return false;
        }

        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);

            $query = "SELECT COUNT(*) FROM reservations where documentID = " . $documentId . " AND isActive = 1";
            $result = $pdo ->query($query);
            $count = (int)$result->fetchColumn();
            if ($count == 0){
                $dateReservation = (new DateTime('now', new DateTimeZone('America/Toronto')))->format('Y-m-d H:i:s');

                // preparation de la commande preparee avec les placeholders
                $sql = "INSERT INTO `reservations`
                (`documentID`, `utilisateurID`, `dateReservation`, `isActive`)
                VALUES (:documentID, :utilisateurID, :dateReservation, 1)";

                $command = $pdo->prepare($sql);
                $command->bindValue(':documentID', $documentId, PDO::PARAM_INT); 
                $command->bindValue(':utilisateurID', $utilisateurId, PDO::PARAM_INT);
                $command->bindValue(':dateReservation', $dateReservation, PDO::PARAM_STR);
                
                // Executer la commande
                $command->execute();    

                $query2 = "SELECT titre FROM documents where ID = " . (int)$documentId ;
                $result2 = $pdo ->query($query2);
                $titre = $result2->fetchColumn();
                

                // message de confirmationd e lèenregistrement
                echo "document $titre reverve avec succes";
            }
            else{
                echo"Document deja reserve";
            }
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    function changerEmploye($attr, $user, $pass, $opts, $selectedUser){
        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);
            
            $sql = "";
            if($selectedUser['Employé'] == 0){
                $sql = "UPDATE utilisateurs SET isEmploye = 1 WHERE ID = :selectedId";

                $command = $pdo->prepare($sql);
                $command->bindValue(':selectedId', $selectedUser['ID'], PDO::PARAM_INT);

                $command->execute();
            }
            else{
                $sql = "UPDATE utilisateurs SET isEmploye = 0 WHERE ID = :selectedId";

                $command = $pdo->prepare($sql);
                $command->bindValue(':selectedId', $selectedUser['ID'], PDO::PARAM_INT);

                $command->execute();
            }
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    function retournerDocument($attr, $user, $pass, $opts){
        $id = intval($_POST['retournerDocument']);
        
        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);
            
            $sql = "UPDATE prets SET isRetourne = 1 WHERE ID = :selectedId";

            $command = $pdo->prepare($sql);
            $command->bindValue(':selectedId', $id, PDO::PARAM_INT);

            $command->execute();
            echo "retour reeussit pour le document " . $id;
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    function creerPret($attr, $user, $pass, $opts, $utilisateurId, $documentId){
        
        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);
            $sql = "SELECT dateRetour
                        FROM prets
                        WHERE documentId = :docId AND isRetourne = 0
                        LIMIT 1";
            $command = $pdo->prepare($sql);
            $command->bindValue(':docId', (int)$documentId, PDO::PARAM_INT);
            $command->execute();
            $pret = $command->fetch(PDO::FETCH_ASSOC);

            $sql = "SELECT ID
                        FROM reservations
                        WHERE documentId = :docId AND UtilisateurID <> ". $utilisateurId ." AND isActive = 1
                        LIMIT 1";
            $command = $pdo->prepare($sql);
            $command->bindValue(':docId', (int)$documentId, PDO::PARAM_INT);
            $command->execute();
            $reservation = $command->fetch(PDO::FETCH_ASSOC);
            
            if(!$pret){
                if(!$reservation){
                    $sql = "INSERT INTO prets (documentID, utilisateurID, datePret, dateRetour, isRetourne)
                            VALUES (:documentId, :utilisateurId, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 0)";

                    $command = $pdo->prepare($sql);
                    $command->bindValue(':utilisateurId', $utilisateurId, PDO::PARAM_INT);
                    $command->bindValue(':documentId', $documentId, PDO::PARAM_INT);

                    $command->execute();
                    echo "pret creer pour l'utilisateur " . $utilisateurId . " et document " . $documentId; 

                    $sql = "SELECT ID, documentID
                            FROM reservations r
                            WHERE r.documentID   = :documentId
                            AND r.isActive     = 1        
                            AND r.utilisateurID = :utilisateurId";

                    $command = $pdo->prepare($sql);
                    $command->bindValue(':utilisateurId', $utilisateurId, PDO::PARAM_INT);
                    $command->bindValue(':documentId', $documentId, PDO::PARAM_INT);

                    $command->execute();
                    $reservation = $command->fetch(PDO::FETCH_ASSOC);
                    if($reservation){
                        $reservationId = $reservation['ID'];
                        annulerReservation($attr, $user, $pass, $opts, $reservationId);
                        echo " Pret execute donc reservation active annulee pour le document " . $reservation['documentID'];
                    }
                }
                else {
                    echo "document reserve par un autre membre";
                    }
            }else{
                echo "Document prete ";
            }
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }

    function annulerReservation($attr, $user, $pass, $opts, $reservationId){
        // echo "dans annuler reservation pour reservation id " . $reservationId;
        try{
            $pdo = openConnexion($attr, $user, $pass, $opts);
            
            $sql = "UPDATE reservations SET isActive = 0
                    WHERE ID = :reservationId"; 

            $command = $pdo->prepare($sql);
            $command->bindValue(':reservationId', $reservationId, PDO::PARAM_INT);
            

            $command->execute();
            echo " Reservation Id " . $reservationId . " a ete annulee";
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        } 
        finally {
            $pdo = null; // fermer la connection
        }
    }
    
?>