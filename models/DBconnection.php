<?php
   $servername='localhost';
   $username='root';
   $password='';
   $database='essect_hub';

   $conn=mysqli_connect($servername,$username,$password,$database);
   if(!$conn){
    die('Erreur de connexion'.mysqli_connect_error());
   }
   else{
    echo'Connexion réussie';
   }
?>