<?php
$serveur="localhost";//nom du serveur
$user="root";//votre nom utilisateur
$password="";//mot de passe
$base="bsup";//nom de la base de donn�e
$connexion = mysql_connect($serveur,$user,$password) or die("impossible de se connecter : ". mysql_error());
$db = mysql_select_db($base, $connexion)  or die("impossible de s�lectionner la base : ". mysql_error());
?>