<?php
/*
Author: Flox
FileName: edit_categories.php
Description: add and modify categories
Version: 2.1
Last update: 13/10/2012
*/

// initialize variables 
if(!isset($_GET['id'])) $_GET['id'] = ''; 
if(!isset($_GET['subcat'])) $_GET['subcat'] = ''; 
if(!isset($_POST['Valider'])) $_POST['Valider'] = ''; 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = ''; 
if(!isset($subcat)) $subcat = '';
if(!isset($subcatname)) $subcatname = '';
?>

<link href="style2.css" rel="stylesheet" type="text/css" />
<?php if ($_GET['id']) echo "Modifier une sous categorie"; else  echo "<title>Ajouter une sous catégorie</title>"; ?>

<div id="content">
	<?php
	//db modify
	require "./connect.php";
	if($_POST['Valider']){
	//special char
	$_POST['subcatname'] = str_replace("'","\'",$_POST['subcatname']); 
	$requete = "INSERT INTO tsubcat (cat,name) VALUES ('$_GET[cat]','$_POST[subcatname]')";
	$execution = mysql_query($requete);
	echo"
	<script type=\"text/javascript\" language=\"javascript\">
	window.close();
	</script>
	";
	}
	if($_POST['Modifier']){
	//special char
	$name = str_replace("'","\'",$name); 
	$requete = "UPDATE tsubcat SET name='$_POST[name]' where id like '$_GET[subcat]'";
	$execution = mysql_query($requete);
	echo"
	<script type=\"text/javascript\" language=\"javascript\">
	window.close();
	</script>
	";
	}
	// new subcat
	if ($_GET['subcat']=="")
	{
	echo "
	<center><b>Ajout d'une sous-catégories</b></center>
	<br />
	<form method=\"POST\" action=\"\" id=\"chgmodele\">
		<label for=\"cat\">catégories:</label>
		<select class=\"textfield\" id=\"cat\" name=\"cat\">
			";
			$query= mysql_query("SELECT * FROM `tcategory` order by name ASC");
					while ($row=mysql_fetch_array($query)) {
					echo "<option value=\"$row[id]\">$row[name]</option>";
					} 
			$query= mysql_query("SELECT * FROM `tcategory` WHERE id like '$_GET[cat]'");
			$row=mysql_fetch_array($query);	
			echo "<option value=\"$row[id]\" selected>$row[name]</option>";
					
			echo "
		</select>
		<br />
		<br />
		<label for=\"subcat\"> Sous-catégories:</label>
		<input class=\"textfield\" name=\"subcatname\" type=\"text\" value=\"$subcatname\" size=\"26\">
		<br />
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
			<br /><br />
	</form>
	<br />
	";
	}
	//edit subcat
	else
	{
	echo "
	<center><b>Modification d'une sous-categorie</b></center> 
	<br />
	<form method=\"POST\" action=\"\" id=\"chgmodele\">
		<br />
		catégories:
		<select class=\"textfield\" id=\"cat\" name=\"cat\">
		";
		$query= mysql_query("SELECT * FROM `tcategory` order by name ASC");
		while ($row=mysql_fetch_array($query)) 	echo "<option value=\"$row[id]\">$row[name]</option>";
		
		$query= mysql_query("SELECT * FROM `tcategory` WHERE id like '$_GET[cat]'");
		$row=mysql_fetch_array($query);	
		echo "<option value=\"$row[id]\" selected>$row[name]</option>";
		
		$query = mysql_query("SELECT * FROM tsubcat WHERE id LIKE '$_GET[subcat]'");
		$row=mysql_fetch_array($query);
		echo "
		</select>
		<br />
		Sous-catégories:
		<input class=\"textfield\" name=\"name\" type=\"text\" size=\"26\" value=\"$row[name]\">
		<br /><br />
			<div  class=\"buttons2\">
			<br />
			<button name=\"Modifier\" value=\"Modifier\" type=\"submit\"  class=\"positive\">
				<img src=\"images/apply2.png\" alt=\"\"/>
				Valider
			</button>

			<button name=\"cancel\" value=\"cancel\" onclick='window.close()'  type=\"submit\" class=\"negative\">
				<img src=\"images/cross.png\" alt=\"\"/>
				Fermer
			</button>
			<br /><br />
	</form>
	<br />
	";
	}
	?>
</div>

