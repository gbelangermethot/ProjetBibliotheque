<?php // login.php
  $host = 'localhost';           // Adresse du serveur MySQL
  $data = 'bibliothequeGit';        // Nom de la base de données
  $user = 'root';                // Utilisateur MySQL
  $pass = 'votre_mot_de_passe'; // Mot de passe MySQL
  $chrs = 'utf8mb4';             // Jeu de caractères
  // $port = '8889';         // MySQL port
  $attr = "mysql:host=$host;dbname=$data;charset=$chrs";
  $opts =
  [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ];
?>
