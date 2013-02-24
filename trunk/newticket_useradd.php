<?php
/*
Author: Florent Hauville
FileName: newticket_useradd.php
Description: add and modify user
Version: 1.2
Last Update: 17/10/2012
*/

//Initialize variables 
if(!isset($_GET ['id'])) $_GET['id'] = ''; 
if(!isset($_POST['Valider'])) $_POST['Valider'] = ''; 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = ''; 
if(!isset($_POST['firstname'])) $_POST['firstname'] = ''; 
if(!isset($_POST['lastname'])) $_POST['lastname'] = ''; 
?>

<link href="style2.css" rel="stylesheet" type="text/css" />
<div id="content">
	<?php
	//Special char rename
	$_POST['firstname'] = str_replace("'","\'",$_POST['firstname']); 
	$_POST['lastname'] = str_replace("'","\'",$_POST['lastname']); 
	
	//Db modify
	require "./connect.php";
	if($_POST['Valider'])
	{
		$requete = "INSERT INTO tusers (profile,firstname,lastname,phone,mail) VALUES ('2','$_POST[firstname]','$_POST[lastname]','$_POST[phone]','$_POST[mail]')";
		$execution = mysql_query($requete);
		echo"
			<script type=\"text/javascript\" language=\"javascript\">
			window.close();
			</script>
		";
	}
	if($_POST['Modifier'])
	{
		$requete = "UPDATE tusers SET lastname='$_POST[lastname]', phone='$_POST[phone]', mail='$_POST[mail]', firstname='$_POST[firstname]' where id like '$_GET[id]'";
		$execution = mysql_query($requete);
		echo"
			<script type=\"text/javascript\" language=\"javascript\">
			window.close();
			</script>
		";
	}
	// case for create new user
	if ($_GET['id']=="")
	{
		echo "
		<center><b>Ajout d'un utilisateur</b></center> 
		<br />
		<form method=\"POST\" action=\"\" id=\"chgmodele\">
			<br />
			<label for=\"firstname\">Prénom:</label> 
			<input class=\"textfield\" name=\"firstname\" type=\"text\" size=\"26\">
			<br />
			<label for=\"name\">Nom:</label> 
			<input class=\"textfield\" name=\"lastname\" type=\"text\" size=\"26\">
			<br />
			<label for=\"phone\">Tel:</label> 
			<input class=\"textfield\" name=\"phone\" type=\"text\" size=\"26\">
			<br />
			<label for=\"mail\">Mail:</label> 
			<input class=\"textfield\" name=\"mail\" type=\"text\" value=\"\" size=\"26\">
			<br /><br />
			<div  class=\"buttons2\">
			<button name=\"Valider\" value=\"Valider\" type=\"submit\"  class=\"positive\">
				<img src=\"images/apply2.png\" alt=\"\"/>
				Valider
			</button>
			<button onclick='window.close()' name=\"cancel\" value=\"cancel\" onclick='window.close()'  type=\"submit\" class=\"negative\">
				<img src=\"images/cross.png\" alt=\"\"/>
				Fermer
			</button>
			<a target=\"_blank\" title=\"Plus de champs\" href=\"./index.php?page=admin&subpage=user&action=add\"><img border=\"0\"src=\"images/plus.png\" alt=\"Plus de champs\"/> +</a>
		</form>
		<br /><br /><br />
		";
	}
	else
	{
		$query = mysql_query("SELECT * FROM tusers WHERE id LIKE '$_GET[id]'");
		$row=mysql_fetch_array($query);
		echo "
		<center><b>Modification d'un utilisateur</b></center> 
		<br />
		<form method=\"POST\" action=\"\" id=\"chgmodele\">
			<br />
			<label>Prénom:</label> 
			<input class=\"textfield\" name=\"firstname\" type=\"text\" size=\"26\" value=\"$row[firstname]\">
			<br />
			<label>Nom:</label> 
			<input class=\"textfield\" name=\"lastname\" type=\"text\" size=\"26\" value=\"$row[lastname]\">
			<br />
			<label>Tel:</label> 
			<input class=\"textfield\" name=\"phone\" type=\"text\" size=\"26\" value=\"$row[phone]\">
			<br />
			<label>Mail:</label> 
			<input class=\"textfield\" name=\"mail\" type=\"text\" size=\"26\" value=\"$row[mail]\" >
			<br /><br />
			<div  class=\"buttons2\">
			<button name=\"Modifier\" value=\"Modifier\" type=\"submit\"  class=\"positive\">
			<img src=\"images/apply2.png\" alt=\"\"/>
				Valider
			</button>
			<button onclick='window.close()' name=\"cancel\" value=\"cancel\" onclick='window.close()'  type=\"submit\" class=\"negative\">
			<img src=\"images/cross.png\" alt=\"\"/>
				Fermer
			</button>
			<a target=\"_blank\" href=\"./index.php?page=admin&subpage=user&action=edit&id=$_GET[id]\">Plus de champs</a>
			<br /><br />
		</form>
		<br />
		";
	}
	?>
</div>

