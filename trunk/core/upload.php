<?php
/*
Author: Florent Hauville
FileName: upload.php
Description: upload file 
Version: 1.2
Last update: 13/10/2012
*/

// initialize variables 
if(!isset($extensionFichier)) $extensionFichier = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($nomorigine)) $nomorigine = '';
if(!isset($number)) $number = '';
if(!isset($_FILES['file1']['name'])) $_FILES['file1']['name'] = '';
if(!isset($_FILES['file2']['name'])) $_FILES['file2']['name'] = '';
if(!isset($_FILES['file3']['name'])) $_FILES['file3']['name'] = '';

//change special character in filename
$file1_rename  = str_replace('\'',' ',$_FILES['file1']['name']);
$file2_rename  = str_replace('\'',' ',$_FILES['file2']['name']);
$file3_rename = str_replace('\'',' ',$_FILES['file3']['name']);

// for new ticket
if ($_GET['id']=="") $_GET['id']=$number;

	if($_FILES['file1']['name'])
	{
		//if id directory not exist, create it
		if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0700);
		$filename = $_FILES['file1']['name'];
		$repertoireDestination = dirname(__FILE__)."../../upload/$_GET[id]/";
		if (move_uploaded_file($_FILES['file1']['tmp_name'], $repertoireDestination.$file1_rename)) 
		{
		} else {
			echo "Erreur de transfert vérifier le chemin ".$repertoireDestination;
		}
		$query = "UPDATE tincidents SET img1='$file1_rename' WHERE id='$_GET[id]'";
		$execution = mysql_query($query) or die('Erreur SQL !<br><br>'.mysql_error());
	}
	if($_FILES['file2']['name'])
	{ 
		//if id directory not exist, create it
		if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0700);
		$filename = $_FILES['file2']['name'];
		$repertoireDestination = dirname(__FILE__)."../../upload/$_GET[id]/";
		if (move_uploaded_file($_FILES['file2']['tmp_name'], $repertoireDestination.$file2_rename)   ) 
		{
		} else {
		echo "Erreur de transfert vérifier le chemin ".$repertoireDestination;
		}
		$query = "UPDATE tincidents SET img2='$file2_rename' WHERE id='$_GET[id]'";
		$execution = mysql_query($query) or die('Erreur SQL !<br><br>'.mysql_error());
	}
	if($_FILES['file3']['name'])
	{
		//if id directory not exist, create it
		if (is_dir("./upload/$_GET[id]")) echo ""; else mkdir ("./upload/$_GET[id]/", 0700);
		$filename = $_FILES['file3']['name'];
		$repertoireDestination = dirname(__FILE__)."../../upload/$_GET[id]/";
		if (move_uploaded_file($_FILES['file3']['tmp_name'], $repertoireDestination.$file3_rename)   ) 
		{
		} else {
		echo "Erreur de transfert vérifier le chemin ".$repertoireDestination;
		}
		$query = "UPDATE tincidents SET img3='$file3_rename' WHERE id='$_GET[id]'";
		$execution = mysql_query($query) or die('Erreur SQL !<br><br>'.mysql_error());
	}
?>