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
      echo "
				<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
				</SCRIPT>
				";
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

<script type="text/javascript" src="./components/lightbox2.05/js/prototype.js"></script>
<script type="text/javascript" src="./components/lightbox2.05/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="./components/lightbox2.05/js/lightbox.js"></script>

<script type="text/javascript" src="js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.ata.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jqModal.js"></script>
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
      <br /><br /><br /><br /><br /><br /><br /><br />
       <?php
        if (isset($_POST['submit'])){
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
    } else {
      $www = "./subscription.php?code=$code";
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
      ?>
		<center>
		<div style="width:300px" id="catalogue">
     
		<table style="height:300; valign:middle; width:300px; text-align:center;"   style="border-style: none" alt="img" cellpadding="0" cellspacing="0">
		<tr>
		<td>
			<center>
				<fieldset >
					<legend class="h2"><img alt="authentification" src="./images/auth.png" style="border-style: none" alt="img" />&nbsp;Code d'inscription</legend>

					<br />
					<form id="conn" method="post" action="">
						<table>
							<tr>
								<td><b>Code d'inscription :</b></td>
								<td><input type="text" class="textbox" id="code" name="code" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="2">
									<div class="buttons1">
										<button name="submit" value="Enregistrer" type="submit"  class="positive"  id="submit">
											<img src="images/apply2.png" alt=""/>
											Valider
										</button>
									</div>
                  <div class="newUser"><a href="index.php">Retour</a></div>
								</td>
							</tr>
						</table>
					</form>
					<br />
				</fieldset>
			</center>
		</td>
		</tr>
		</table>

		</div>
		</center>
		<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
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
</body>
</html>