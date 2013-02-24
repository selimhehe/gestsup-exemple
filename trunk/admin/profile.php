<?php
/*
Author: Florent Hauville
File name: profile.php
Description: admin profile
Version: 1.1
creation date: 26/04/2011
last update: 14/10/2012
*/

// initialize variables 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['profileid'])) $_GET['profileid'] = '';
if(!isset($set)) $set = '';
if(!isset($nbprofile)) $nbprofile = '';
$cnt=0;

// default values 
if ($_GET['profileid']=='') {$_GET['profileid']='0'; $_GET['action']='edit';}

if($_POST['Modifier'])
{
	////Dynamic query
	$query= mysql_query("show fields FROM trights "); 
	while ($row=mysql_fetch_array($query)) 
	{	
		//exclude id and profil
		if ($row[0]!='id' && $row[0]!='profile')
		{
			$cnt=$cnt+1;
			//encaps variable problem
			$r=$row[0];
			if ($cnt==1) $sep=''; else $sep=',';
			$set=" $set $sep $r='$_POST[$r]'";
		}
	}
	
	$query = "UPDATE trights SET
	$set
	WHERE profile='$_GET[profileid]'";

	$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
}

if($_POST['cancel']){
	//redirection vers la page d'accueil
	$www = "./index.php?page=admin&subpage=profile";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}

// Else display profile
else
{
	//Dynamic Right table
		//Display user table
					echo "<center>";
					echo "<table  >
						<tr class=\"blue\">";
						$query = mysql_query("select * FROM tprofiles ORDER BY level");
						while ($row=mysql_fetch_array($query)) 
						{
						echo "
							<td ><a title=\"Editer\" href=\"./index.php?page=admin&amp;subpage=profile&amp;action=edit&amp;profileid=$row[level]\">"; if ($row['level']=="$_GET[profileid]") echo "<b>"; echo "$row[name]"; if ($row['level']=="$_GET[profileid]") echo "</b>"; echo "</a></td>";
						}
				echo "
						</tr>
						</table>";
				echo "</center><br /><br />";
				
	//Find actual value
	$qv= mysql_query("SELECT * FROM `trights` where profile LIKE '$_GET[profileid]'"); 
	$rv = mysql_fetch_array($qv);

	echo '
	<form method="post" action="">
		<table >
			<tr>
				<th>Fonction</th>
				<th>Droit</th>
			</tr>
			';
			//diplay tables column
			$query= mysql_query("show fields FROM trights"); 
			while ($row=mysql_fetch_array($query)) 
			{	
				//exclude id and profile
				if ($row[0]!='id' && $row[0]!='profile')
				{
					echo '
					<tr class="blue">
						<td>'.$row[0].'</td>
						<td >
							<select name="'.$row[0].'">
								<option '; if($rv[$row[0]]=='0') echo 'selected'; echo ' value="0" >Pas d\'accès</option>
								<option '; if($rv[$row[0]]=='1') echo 'selected'; echo ' value="1" >Visualisation</option>
								<option '; if($rv[$row[0]]=='2') echo 'selected'; echo ' value="2" >Modification</option>
							<select>
						</td>
					</tr>
					';
				}
			}
			

			echo '
			
		</table>
		
		<div  class="buttons2">
			<br />
				<button name="Modifier" value="Modifier" type="submit"  class="positive"  id="modifier">
					<img src="images/apply2.png" alt=""/>
					Modifier
				</button>

				<button name="cancel" value="cancel" type="submit" class="negative">
					<img src="images/cross.png" alt=""/>
					Annuler
			</button>
			<br /><br /><br />
		</div>
	</form>
	';
}

?>