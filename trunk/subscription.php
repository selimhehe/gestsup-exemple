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

//if user is connected
if ($_SESSION['user_id'])
{
  // #TODO redirection
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
<div id="catalogue" style="width:500px;">
  
	<form id="myForm" name="myForm" method="post"  action="" onSubmit="return myValidate();">
		<table width="100%">
			<tr>
				 <th colspan="2" ><img alt="user" src="./images/user.png" sytle="border-style:none;"/>   Inscription utilisateur</th>
			<tr>
			<tr>
				<td width="200"><label for="mail"><span class="required validate-email">*</span>Adresse mail:</label></td>
				<td><input name="mail" id="mail" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="civilite"><span class="required">*</span>Civilité:</label></td>
				<td>
					
					<input type="radio" name="civilite" id="civilite_m" value="M."  /> <label for="civilite_m">M.</label><br />
					<input type="radio" name="civilite" id="civilite_mme" value="Mme"  /> <label for="civilite_mme">Mme</label><br />
					<input type="radio" name="civilite" id="civilite_mlle" value="Mlle"  /> <label for="civilite_mlle">Mlle</label><br />
					
				</td>
			</tr>
			<tr>
				<td><label for="nom"><span class="required validate" required>*</span>Nom:</label></td>
				<td><input name="nom" id="nom" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="prenom"><span class="required">*</span>Prénom:</label></td>
				<td><input name="prenom" id="prenom" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="fixe">Tél. fixe:</label></td>
				<td><input name="fixe" id="fixe" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="mobile"><span class="required">*</span>Tél. mobile:</label></td>
				<td><input name="mobile" id="mobile" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="ville"><span class="required">*</span>Ville:</label></td>
				<td><input name="ville" id="ville" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="cp">Code postal:</label></td>
				<td><input name="cp" id="cp" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="rue">N° rue:</label></td>
				<td><input name="rue" id="rue" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="note">Note:</label></td>
				<td><input name="note" id="note" type="text" value="" size="20" /></td>
			</tr>
			<tr>
				<td><label for="tva">N° TVA:</label></td>
				<td><input name="tva" id="tva" type="text" value="" size="20" /></td>
			</tr>
		</table>

    <div class="buttons1">
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
    $().ready(function() {
      $("#myForm").validate();
    });
  </script>
</body>
</html>