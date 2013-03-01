<?php
/*
Author: Flox
File name: list.php
Description: admin list
Version: 1.1
creation date: 15/03/2011
last update: 13/10/2010
*/

// initialize variables 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($_POST['Ajouter'])) $_POST['Ajouter'] = '';
if(!isset($_GET['table'])) $_GET['table'] = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($nbchamp)) $nbchamp = '';
if(!isset($champ0)) $champ0 = '';
if(!isset($champ1)) $champ1 = '';
if(!isset($champ3)) $champ3 = '';
if(!isset($reqchamp)) $reqchamp = '';
if(!isset($set)) $set = '';

if(!isset($i)) $i = '';
 
// default table
if ($_GET['table']=='') $_GET['table']='ttime'; 
// Retreive selcted table description
$query = mysql_query("DESC $_GET[table]");
while ($row=mysql_fetch_array($query)) 
{
${'champ' . $nbchamp} =$row[0];
$nbchamp++;
}
$nbchamp1=$nbchamp;
$nbchamp=$nbchamp-1;
?>

<?php
// Actions 
if ($_GET['action']=="delete") 
{
	$requete = "DELETE FROM $_GET[table] WHERE id = '$_GET[id]'";
	$execution = mysql_query($requete);
}

if ($_GET['action']=="update") 
{
	for ($i=0; $i <= $nbchamp; $i++)
	{
		if(!isset($_POST[$reqchamp])) $_POST[$reqchamp] = '';
		$reqchamp="${'champ' . $i}";
		if ($i=='1') $set="$reqchamp='$_POST[$reqchamp]'"; else $set="$set, $reqchamp='$_POST[$reqchamp]'";
	}
	$requete =  "UPDATE $_GET[table] SET $set WHERE id LIKE '$_GET[id]'";
	$execution = mysql_query($requete);
}

if ($_GET['action']=="add")
 {
	// on genere le champ champ de la requete update en conction de la table selectionner
	for ($i=1; $i <= $nbchamp; $i++)
	{
		if ($i!="1") {$reqchamp="$reqchamp,${'champ' . $i}";} else {$reqchamp="${'champ' . $i}";}
	}
	// on genere le champ values de la requete update en conction de la table selectionner
	for ($i=1; $i <= $nbchamp; $i++)
	{
		$nomchamp="${'champ' . $i}";
		if ($i!="1") {$reqvalue="$reqvalue,'$_POST[$nomchamp]'";} else {$reqvalue="'$_POST[$nomchamp]'";}
	}
	$requete = "INSERT INTO $_GET[table] ($reqchamp) VALUES ($reqvalue)";
	$execution = mysql_query($requete);
}
?>
<table>
	<tr>
		<td>
			<fieldset  valign="top">
				<legend class="h2">Liste Disponible: </legend>
				<table style="border-style: none" alt="img">
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=ttime">Temps</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=tpriority">Priorité</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=tcriticality">Criticité</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=tstates">Etats</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=tcategory">Catégories</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=tsubcat">Sous-Catégorie</a><br />
					- <a href="./index.php?page=admin&amp;subpage=list&amp;table=ttemplates">Modèles</a><br />
				</table>
			</fieldset>
		</td>
		<td>
			<fieldset>
				<legend class="h2">Liste: <?php echo $_GET['table']; ?></legend>
					<table style="border-style: none" alt="img">
						<?php
							$query = mysql_query("SELECT * FROM `$_GET[table]`");
								while ($row=mysql_fetch_array($query)) 
								{
									echo "
									<tr class=\"blue\">
									<td>
									<a title=\"Editer\" href=\"./index.php?page=admin&amp;subpage=list&amp;table=$_GET[table]&amp;action=edit&amp;id=$row[id]\"><img src=\"./images/edit.png\" border=\"0\" /></a>												</td>
									<td>
										<a title=\"Supprimer\" href=\"./index.php?page=admin&amp;subpage=list&amp;table=$_GET[table]&amp;id=$row[id]&amp;action=delete\"><img src=\"./images/delete.png\" border=\"0\" /></a>
									</td>
									";
									for($i=1; $i < $nbchamp1; ++$i)
									{
										echo "<td>$row[$i]</td>";
									}
								}
						?>
				</table>
			</fieldset>
		</td>
		<td valign="top">
			<fieldset style="width:250px">
				<legend class="h2"><?php if ($_GET['action']!="edit") echo "Ajouter une entrée"; else echo "Modifier une entrée"; ?></legend>
				<br />
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; if ($_GET['action']=="edit") {echo "&amp;action=update";} else {echo "&amp;action=add";} ?>" >
					<?php
						for ($i=1; $i <= $nbchamp; $i++)
						{
							$req = mysql_query("SELECT ${'champ' . $i} FROM `$_GET[table]` WHERE id LIKE '$_GET[id]'"); 
							$req = mysql_fetch_array($req);
							$req= $req[0];
							echo "<b>${'champ' . $i}</b>:<br /> <input size=\"30px\" name=\"${'champ' . $i}\" type=\"text\" value=\"$req\"size=\"15\" /><br />";
						}
					?>
					<br /><br />
						<div  class="buttons1">
						<br />
						<button <?php if ($_GET['action']!="edit") echo" name=\"Ajouter\" value=\"Ajouter\""; else  echo" name=\"Modifier\" value=\"Modifier\""; ?> type="submit"  class="positive">
						<img src="images/apply2.png" alt=""/>
						Valider
						</button>
						<br /><br /><br />
						</div>
						<br />
				</form>
			</fieldset>	
		</td>
	</tr>
</table>
<br /><br /><br /><br />

