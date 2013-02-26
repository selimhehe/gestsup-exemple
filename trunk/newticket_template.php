<?php
/*
Author: Flox
FileName: newticket_template.php
Description: select template incident
Version: 1.1
Last Update: 13/10/2012
*/

// initialize variables 
if(!isset($_GET['id'])) $_GET['id'] = ''; 
if(!isset($_POST['Valider'])) $_POST['Valider'] = ''; 
	if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';

$today = date("d/m/Y");
$today = "$today: ";

?>
<script LANGUAGE="JavaScript">
<!--
function openURL(sURL) {
    opener.document.location = sURL;
	window.close();
}
//-->
</script>

<link href="style2.css" rel="stylesheet" type="text/css" />
<div id="content">
	<?php
	$today = date("Y-m-d");
	$today = "$today: ";
	require "./connect.php";
	if($_POST['Valider'])
	{
		$query= mysql_query("SELECT * FROM `tincidents` WHERE id='$_POST[template]'");
		$row=mysql_fetch_array($query);
		
		$row['description']  = str_replace('\\','\\\\',$row['description']);
		$row['resolution']  = str_replace("\\","\\\\",$row['resolution']);
		$row['title']  = str_replace("\\","\\\\",$row['title']);
		$row['description'] = str_replace("'","\'",$row['description']); 
		$row['resolution']  = str_replace("'","\'",$row['resolution']); 
		$row['title']  = str_replace("'","\'",$row['title']);
		
		
		$query= "INSERT INTO tincidents (user,title,description,resolution,priority,state,time,category,subcat,date_create,date_hope,technician,criticality) VALUES ('$row[user]','$row[title]','$row[description]','$row[resolution]','$row[priority]','$row[state]','$row[time]','$row[category]','$row[subcat]','$today','$today','$_GET[uid]','$row[criticality]')";
		$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
		
		echo"
			<script type=\"text/javascript\" language=\"javascript\">
			javascript:openURL('./index.php?page=ticket&id=$_GET[id]')
			</script>
		";
	}
	
		echo "
		<center><b>Liste des modèles</b></center> 
		<br />
		<form method=\"POST\" action=\"\" id=\"valider\">
		
			<select class=\"textfield\" id=\"template\" name=\"template\">
				";
				$query= mysql_query("SELECT * FROM `ttemplates` order by name ASC");
						while ($row=mysql_fetch_array($query)) {
						echo "<option value=\"$row[incident]\">$row[name]</option>";
						} 
				echo "
			</select>

		<div  class=\"buttons2\">
			<br />
			<button name=\"Valider\" value=\"Valider\" type=\"submit\"  class=\"positive\">
				<img src=\"images/apply2.png\" alt=\"\"/>
				Valider
			</button>

			<button name=\"cancel\" value=\"cancel\" onclick='window.close()'  type=\"submit\" class=\"negative\">
				<img src=\"images/cross.png\" alt=\"\"/>
				Fermer
			</button>
			<br /><br /><br />
		</div>
				
		</form>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		";
	
	?>
</div>