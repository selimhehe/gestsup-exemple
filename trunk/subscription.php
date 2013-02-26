<?php
/*
Author: Flox
FileName: index.php
Description: main page include sub-pages
Version: 1.3
Creation date: 07/03/2010
Last update: 06/11/2012
*/

//starting session
session_start();

//initialize variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_SESSION['profile_id'])) $_SESSION['profile_id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['keywords'])) $_GET['keywords'] = '';
if(!isset($_POST['keyword'])) $_POST['keyword'] = '';

//connexion script with database parameters
require "connect.php";

//Load parameters table
$qparameters = mysql_query("SELECT * FROM `tparameters`");
$rparameters= mysql_fetch_array($qparameters);

//Load variables
$daydate=date('Y-m-d');
$msg = "";
//if user is connected
if ($_SESSION['user_id'])
{
  // #TODO redirection
}

if(isset($_GET['code']) || isset($_POST['code'])){
  if (isset($_GET['code']))
  $code = (isset($_GET['code'])) ? $_GET['code'] : '';

  if (isset($_POST['code']))
  $code = (isset($_POST['code'])) ? $_POST['code'] : '';

  $query = mysql_query("SELECT code FROM tcompany where code='$code'");

	$r = mysql_fetch_array($query);
  if($r['0']=='')
		{
			echo "<div id='erreur'><img src='./images/access.png' alt='erreur' style='border-style: none' alt='img' /> Merci de vérifier votre code d'inscription.</div>";
      $www = "./codesubscription.php";
      echo "<SCRIPT LANGUAGE='JavaScript'>
							<!--
							function redirect()
							{
							window.location='$www'
							}
							setTimeout('redirect()',$rparameters[time_display_msg]);
							-->
						</SCRIPT>";
    } 
}

if (isset($_POST['submit'])){
  $_POST['ville']  = str_replace("\\","\\\\",$_POST['ville']);
  $_POST['ville']  = str_replace("'","\'",$_POST['ville']);

  $_POST['nom']  = str_replace("\\","\\\\",$_POST['nom']);
  $_POST['nom']  = str_replace("'","\'",$_POST['nom']);

  $_POST['prenom']  = str_replace("\\","\\\\",$_POST['prenom']);
  $_POST['prenom']  = str_replace("'","\'",$_POST['prenom']);

  $_POST['note']  = str_replace("\\","\\\\",$_POST['note']);
  $_POST['note']  = str_replace("'","\'",$_POST['note']);

  $_POST['tva']  = str_replace("\\","\\\\",$_POST['tva']);
  $_POST['tva']  = str_replace("'","\'",$_POST['tva']);

  $_POST['service']  = str_replace("\\","\\\\",$_POST['service']);
  $_POST['service']  = str_replace("'","\'",$_POST['service']);

  $_POST['company']  = str_replace("\\","\\\\",$_POST['company']);
  $_POST['company']  = str_replace("'","\'",$_POST['company']);

  $_POST['company']  = str_replace("\\","\\\\",$_POST['company']);
  $_POST['company']  = str_replace("'","\'",$_POST['company']);
  $email = $_POST['email'];

  $query = mysql_query("SELECT mail FROM tusers where mail='$email'");
  $r = mysql_fetch_array($query);
  if($r['0']=='')
	{
	  $requete = "INSERT INTO tusers (code, civility, firstname,lastname,mail,phone,mobil,company,numero_rue, address1,zip,city,login,service, code_tva, note) VALUES ('$_POST[code]','$_POST[civility]', '$_POST[firstname]','$_POST[lastname]','$_POST[email]','$_POST[fixe]','$_POST[mobile]','$_POST[company]','$_POST[rue]','$_POST[address1]','$_POST[zip]','$_POST[vile]','$_POST[email]','$_POST[service]','$_POST[tva]','$_POST[note]')";
		echo $requete;
    $execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	} else {
	  $msg = "Adresse email existe déjà";
	}  
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>GestSup | Gestion de Support</title>
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="./components/lightbox2.05/css/lightbox.css" rel="stylesheet" type="text/css" media="screen" />


<script type="text/javascript" src="js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.ata.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jqModal.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>

</head>

<body>
	<div id="wrap">
		<div id="header">
			<h1 id="sitename">
				<a href="#">
					<img alt="gestsup" style="border-style: none" src="./images/gestsup.png" />
				</a>
				<span class="caption">
					<table style="border-style: none">
						<tr>
						<td><img style="border-style: none" alt="logo" src="./upload/logo/<?php  if (isset($rparameters['logo'])) echo $rparameters['logo']; ?>" /></td>
						<td>&nbsp;</td>
						<td>Gestion de Support <br /><?php  if (isset($rparameters['company'])) echo $rparameters['company']; ?></td>
						</tr>
					</table>
				</span>
			</h1>
			<div id="nav">
			</div>
			<div class="clear"></div>

		</div>
		<div id="content">
      
		<center>

<h2 class="sec_head" style="margin-top:0px">Inscription</h2>
<?php
 if($msg != ""){
   echo "<div class='error'>$msg</div>";
 }
?>
<div id="catalogue" style="width:500px;">
  
	<form id="myForm" name="myForm" method="post"  action="">
		<table width="100%">
			<tr>
				 <th colspan="2" ><img alt="user" src="./images/user.png" sytle="border-style:none;"/>   Inscription utilisateur</th>
			<tr>
			<tr>
				<td width="200"><label for="mail"><span class="required validate-email">*</span>E-mail:</label></td>
				<td><input name="email" id="email" type="text" class="required email"  value="<?php echo $_POST['email']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="civilite"><span class="required">*</span>Civilité:</label></td>
				<td>
          <select name="civilite">
            <option>M.</option>
            <option>Mme</option>
            <option>Mlle</option>
          </select>
					
					
				</td>
			</tr>
			<tr>
				<td><label for="nom"><span class="required validate" required>*</span>Nom:</label></td>
				<td><input name="nom" id="nom" type="text" class="required"  value="<?php echo $_POST['nom']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="prenom"><span class="required">*</span>Prénom:</label></td>
				<td><input name="prenom" id="prenom" type="text" class="required"  value="<?php echo $_POST['prenom']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="fixe">Tél. fixe:</label></td>
				<td><input name="fixe" id="fixe" type="text"  value="<?php echo $_POST['fixe']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="mobile"><span class="required">*</span>Tél. mobile:</label></td>
				<td><input name="mobile" id="mobile" type="text" class="required"  value="<?php echo $_POST['mobile']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="ville"><span class="required">*</span>Ville:</label></td>
				<td><input name="ville" id="ville" type="text" class="required"  value="<?php echo $_POST['ville']; ?>" size="20" /></td>
			</tr>
        <tr>
				<td><label for="rue">N° rue :</label></td>
				<td><input name="rue" id="rue" type="text" size="20" value="<?php echo $_POST['rue']; ?>" /></td>
			</tr>
        <tr>
				<td><label for="Adresse">Adresse :</label></td>
				<td><input name="address1" id="address1" type="text" size="20" value="<?php echo $_POST['address1']; ?>" /></td>
			</tr>
			<tr>
				<td><label for="cp">Code postal :</label></td>
				<td><input name="zip" id="zip" type="text"  size="20" value="<?php echo $_POST['zip']; ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="note">Note:</label></td>
				<td><input name="note" id="note" type="text"  size="20" value="<?php echo $_POST['note']; ?>" /></td>
			</tr>
			<tr>
				<td><label for="tva">N° TVA :</label></td>
				<td><input name="tva" id="tva" type="text"   size="20" value="<?php echo $_POST['tva']; ?>" /></td>
			</tr>

        <tr>
				<td><label for="societe">Société :</label></td>
				<td><input name="company" id="company" type="text" size="20" value="<?php echo $_POST['company']; ?>" /></td>
			</tr>

        <tr>
				<td><label for="service">Service :</label></td>
				<td><input name="service" id="service" type="text" size="20" value="<?php echo $_POST['service']; ?>" /></td>
			</tr>

		</table>

    <div class="buttons1">
      <input type="hidden" class="textbox" id="code" name="code" value="<?php echo $_GET['code']; ?>" />
										<button name="submit" value="Enregistrer" type="submit"  class="positive"  id="submit">
											<img src="images/apply2.png" alt=""/>
											Valider
										</button>
									</div>
                  <div class="newUser"><a href="index.php">Retour</a></div>
	</form>
</div>
</center>
		
			<?php
			mysql_close($connexion);
			?>
			<div class="clear"></div>
			<div id="bottom">
				<p><a  href="./changelog.txt" title="Changelog" target="_blank">GestSup <?php echo $rparameters['version']; ?> </a> | <a target="about_blank" title="Site Web" href="http://gestsup.fr/index.php?page=forum">GestSup.fr</a></p>
			</div>
		</div>
		<div id="footer">
			<div id="credits">
				<a href="http://ramblingsoul.com">CSS Template</a>
			</div>
		</div>
	</div>

  <script language="javascript">
	$(document).ready(function() {
      $("#myForm").validate();
    });
  </script>
</body>
</html>