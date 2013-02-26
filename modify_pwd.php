<?php
/*
Author: Flox
filename: event.php
Description: change password popup
Version: 1.0
Creation date: 05/02/2012
last update: 26/01/2013
*/

// initialize variables 
if(!isset($_POST['Valider'])) $_POST['Valider'] = ''; 
if(!isset($_POST['oldpwd'])) $_POST['oldpwd'] = ''; 
if(!isset($_POST['newpwd1'])) $_POST['newpwd1'] = ''; 
if(!isset($_POST['newpwd2'])) $_POST['newpwd2'] = ''; 
if(!isset($updated)) $updated = ''; 
if(!isset($oldpassword)) $oldpassword = ''; 
if(!isset($secure_password)) $secure_password = ''; 
 
$qu = mysql_query("SELECT * FROM tusers WHERE id=$_SESSION[user_id]");
$ru=mysql_fetch_array($qu);
 
if($_POST['Valider'])
{
	//find uncrypted or crypted old password
	$oldpassword=0;
	if ($_POST['oldpwd']==$ru['password']) $oldpassword=1;
	if (md5($ru['salt'] . md5($_POST['oldpwd']))==$ru['password']) $oldpassword=1;
		
	// check empty password
	if ($_POST['oldpwd']=="" || $_POST['newpwd1']=="" || $_POST['newpwd2']=="")
	{
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Veuillez saisir votre mot de passe.</div><br /><br />";
	}
	// check old password
	else if ($oldpassword!='1')
	{
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Ancien mot de passe éronné.</div><br /><br />";
	}
	// check new passwords
	else if ($_POST['newpwd1']!=$_POST['newpwd2'])
	{
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Vos nouveaux mots de passes sont différents.</div><br /><br />";
	}
	else
	{
		//crypt password md5 + salt
		if($_POST['newpwd1']!='') {
		$salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
		$_POST['newpwd1']=md5($salt . md5($_POST['newpwd1'])); // store in md5, md5 password + salt.
		}
		
		$query = "UPDATE tusers SET chgpwd='0' where id like '$_SESSION[user_id]'";
		$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
		$query = "UPDATE tusers SET password='$_POST[newpwd1]', salt='$salt' where id like '$_SESSION[user_id]'";
		$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
		echo "
		<script type=\"text/javascript\" language=\"javascript\">
		$().ready(function() { jqmCloseBtn}
		</script>
		";
		$updated=1;
	} 
}
if ($updated==1)
{
	echo "<div id=\"valide\"><img src=\"./images/valide.png\" border=\"0\" /> Le mot de passe à été changé.</div><br /><br />
	<div class=\"buttons1\">
		<button name=\"Fermer\" value=\"Fermer\" type=\"submit\"  class=\"jqmClose\"  id=\"jqmCloseBtn\">
			<img src=\"images/apply2.png\" alt=\"\"/>
			Fermer
		</button>
	</div>
	
	
	";
}
else
{
	echo "
	<center><b><img src=\"./images/auth.png\" border=\"0\" />  $ru[firstname] $ru[lastname] veuillez modifier votre mot de passe:</b></center><br />
	<br /><br />
	<form method=\"post\" action=\"\">
		<b>Ancien mot de passe: </b></td><td><input name=\"oldpwd\" type=\"password\" size=\"20\" /><br /><br />
		<b>Nouveau mot de passe: </b></td><td><input name=\"newpwd1\" type=\"password\" size=\"20\" /><br /><br />
		<b>Nouveau mot de passe: </b></td><td><input name=\"newpwd2\" type=\"password\" size=\"20\" /><br /><br />
		<div class=\"buttons1\">
			<button name=\"Valider\" value=\"Valider\" type=\"submit\"  class=\"positive\"  id=\"Valider\">
			<img src=\"images/apply2.png\" alt=\"\"/>
			Valider
			</button>
		</div>
	</form>
	";
}
?>