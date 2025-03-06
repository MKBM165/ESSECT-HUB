<?php
   $servername = 'localhost';
   $username = 'root';
   $password = '';
   $database = 'essect_hub';

   try {
       $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
       // Set the PDO error mode to exception
       $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   } catch (PDOException $e) {
       die('Erreur de connexion: ' . $e->getMessage());
   }
?>
