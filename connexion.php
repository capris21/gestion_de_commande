<?php

$serveur = "localhost";
$db_name = "genie";
$user = "root";
$pwd = "";

try{
    // $connect = new PDO('mysql:host'.$localhost.';dbname='.$db_name.'',$user,$pwd);
    $connect = new PDO('mysql:host=localhost;dbname=genie','root','');
    $connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    // echo "connexion reussie";
}catch(PDOException $e){
    echo "Erreur de connection a la base de donnees";
    die();
}

?>