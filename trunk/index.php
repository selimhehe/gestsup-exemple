<?php
/*
Author: Flox
FileName: index.php
Description: main page include sub-pages
Version: 1.4
Creation date: 07/03/2010
Last update: 20/12/2012
*/
session_start();

//initialize variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_SESSION['profile_id'])) $_SESSION['profile_id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['keywords'])) $_GET['keywords'] = '';
if(!isset($_POST['keyword'])) $_POST['keyword'] = '';
if(!isset($_GET['page'])) $_GET['page'] = '';

//if prompt logoff users and redirect to home page
	
	if ($_GET['action'] == 'logout')
	{
		$_SESSION = array();
		session_destroy();
		session_start();
		$www = "./index.php?page=dashboard";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
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
	//load variables
	$uid=$_SESSION['user_id'];
	
	//Find profile id of connected user
	$qprofile = mysql_query("SELECT profile FROM `tusers` WHERE id LIKE '$uid'"); 
	$_SESSION['profile_id'] = mysql_fetch_array($qprofile);
	$_SESSION['profile_id'] = $_SESSION['profile_id'][0];

	//Load rights table
	//echo "SELECT * FROM `trights` WHERE profile=$_SESSION[profile_id]"; die;
	$qright = mysql_query("SELECT * FROM `trights` WHERE profile=$_SESSION[profile_id]");
	//echo "SELECT * FROM `trights` WHERE profile=$_SESSION[profile_id]"; die;
	$rright= mysql_fetch_array($qright);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (($rparameters['auto_refresh']!=0)&&($_GET['page']=='dashboard')) echo '<meta http-equiv="Refresh" content="'.$rparameters['auto_refresh'].';">'; ?>
<title>GestSup | Gestion de Support</title>
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="./components/lightbox2.05/css/lightbox.css" rel="stylesheet" type="text/css" media="screen" />

<script type="text/javascript" src="./components/lightbox2.05/js/prototype.js"></script>
<script type="text/javascript" src="./components/lightbox2.05/js/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="./components/lightbox2.05/js/lightbox.js"></script>

<script type="text/javascript" src="js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.validate.js" charset="utf-8"></script>
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
				<?php
				// Display search and nav bar if user is connected
				if ($_SESSION['user_id'])
				{
					if ($rright['search']!='0')
					{
					echo '
						<form method="post" action="./index.php?page=dashboard&amp;searchengine=1">
							<div id="search"><input type="text" id="keywords" name="keyword" class="keyword" value="'; if ($_POST['keyword']) {echo $_POST['keyword'];} else if($_GET['keywords']) {echo $_GET['keywords'];} echo '" /></div>
						</form>';
					} 
					echo '
						<div id="topmenu">
							<ul>';
							//Display each menu if user have right
							if ($rright['task']!=0) {echo'<li ';if (isset($_GET['page']) && ($_GET['page']=="dashboard" || $_GET['page']=="ticket" || $_GET['page']=="newticket" || $_GET['page']=="newticket_u")) {echo "class=\"active\"";}echo '><a href="./index.php?page=dashboard&amp;techid='; echo $_SESSION['user_id']; echo '&amp;state=1"><img style="border-style: none" alt="tache" src="./images/check.png" />  Tâches</a></li>';}
							// TODO :: gestion d'acces sur la page Groupes
							//if (isset($rright['company']) && isset($rparameters['company']) && ($rright['company']!=0) && ($rparameters['company']==1)) {
							if (isset($rright['company']) && ($rright['company']!=0)) {
							echo'<li '; if (isset($_GET['page']) && $_GET['page']=="company") echo "class=\"active\""; echo '><a href="./index.php?page=company"><img style="border-style: none" alt="groupes" src="./images/check.png" />  Groupes</a></li>';
							}
							if (($rright['planning']!=0) && ($rparameters['planning']==1)) {echo'<li '; if (isset($_GET['page']) && $_GET['page']=="planning") echo "class=\"active\""; echo '><a href="./index.php?page=planning"><img style="border-style: none" alt="stat" src="./images/planning.png" />  Planning</a></li>';}
							if ($rright['stat']!=0) {echo'<li '; if (isset($_GET['page']) && $_GET['page']=="stat") echo "class=\"active\""; echo '><a href="./index.php?page=stat"><img style="border-style: none" alt="stat" src="./images/stat.png" />  Statistiques</a></li>';}
							if ($rright['admin']!=0) {echo'<li '; if (isset($_GET['page']) && $_GET['page']=="admin") echo "class=\"active\""; echo '><a href="./index.php?page=admin"><img style="border-style: none" alt="administration" src="./images/parametre.png" />  Administration</a></li>';}
							if(isset($_SESSION['profile_id']) && $_SESSION['profile_id'] == 3){
								echo'<li '; if (isset($_GET['page']) && $_GET['page']=="admin") echo "class=\"active\""; echo '><a href="./index.php?page=admin&subpage=user&profileid=%"><img style="border-style: none" alt="administration" src="./images/parametre.png" />Utilisateurs</a></li>';
							} 
							echo '
							</ul>
						</div>
						';
					// temporary variables to migrate to trights table
					if ($_SESSION['profile_id']==0)	{$profile="technician";}
					elseif ($_SESSION['profile_id']==1)	{$profile="user";}
					elseif ($_SESSION['profile_id']==4)	{$profile="technician";}
					elseif ($_SESSION['profile_id']==3) {$profile="user";}
					else {$profile="user";}
				}
				?>
			</div>
			<div class="clear"></div>
			<?php
			// Display user bar if user is connected
			if ($_SESSION['user_id'])
			{
				//user bar queries 
				$reqnb = mysql_query("SELECT count(*) FROM `tincidents` WHERE $profile='$uid' and (state LIKE '1' OR state LIKE '2' OR state LIKE '6') AND disable='0'"); 
				$nbatt= mysql_fetch_array($reqnb);
				$reqnb = mysql_query("SELECT count(*) FROM `tincidents` WHERE $profile='$uid' and state LIKE '3' AND disable='0'"); 
				$nbres = mysql_fetch_array($reqnb);
				$reqfname = mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$uid'"); 
				$reqfname = mysql_fetch_array($reqfname);
				$reqnb = mysql_query("SELECT count(*) FROM `tincidents` WHERE technician LIKE '$uid' and date_create LIKE '$daydate' AND disable='0'"); 
				$nbday = mysql_fetch_array($reqnb);
				$req15 = mysql_query("SELECT count(*) FROM `tincidents` WHERE TO_DAYS(NOW()) - TO_DAYS(date_create) >= $rparameters[lign_yellow] and TO_DAYS(NOW()) - TO_DAYS(date_create) <= $rparameters[lign_orange] and (state LIKE '2' or state LIKE '1') and technician LIKE '$uid' AND disable='0'" ); 
				$nb15 = mysql_fetch_array($req15);
				$req30 = mysql_query("SELECT count(*) FROM `tincidents` WHERE TO_DAYS(NOW()) - TO_DAYS(date_create) > $rparameters[lign_orange] and (state LIKE '2' or state LIKE '1') and technician LIKE '$uid' AND disable='0'" ); 
				$nb30 = mysql_fetch_array($req30);
				$reqtps = mysql_query("SELECT SUM(time_hope-time) FROM `tincidents` WHERE time_hope-time>0 and technician LIKE '$uid' AND disable='0' AND (state='1' OR state='2' OR state='6')" ); 
				$nbtps = mysql_fetch_array($reqtps);
				$reqrat1 = mysql_query("select count(*) from tincidents where technician LIKE '$uid' and date_res LIKE '$daydate' AND disable='0';" ); 
				$ra1 = mysql_fetch_array($reqrat1);
				$reqrat2 = mysql_query("select count(*) from tincidents where technician LIKE '$uid' and date_create LIKE '$daydate' AND disable='0';" ); 
				$ra2 = mysql_fetch_array($reqrat2);
				if (($ra2[0]==0)&&($ra1[0]==0)){$ratio=0;}
				else if ($ra2[0]==0){$ratio=0;}
				else {
				$ratio=$ra1[0]/$ra2[0];
				$ratio= substr($ratio, 0, 3);
				}
				$nbtps=round($nbtps[0]/60);
				if ($rright['userbar']!=0)
				{
					echo '
					<div class="postbottom">
						<img  style="border-style: none" alt="img" src="./images/admin.png" /><font color="#FFFFFF"><b><a href="./index.php?page=admin/user&amp;action=edit&amp;id='.$_SESSION['user_id'].'"> '.$reqfname['firstname'].' '.$reqfname['lastname'].'</a></b></font>&nbsp;&nbsp;
						<img  style="border-style: none" alt="img" src="./images/ico_cat.png" /><a href="./index.php?page=dashboard&amp;techid='.$_SESSION['user_id'].'&amp;state=3"> Résolues: <b>'.$nbres[0].'</b></a>&nbsp;&nbsp;
						<img  style="border-style: none" alt="img" src="./images/ico_more.png" /><a href="./index.php?page=dashboard&amp;techid='.$_SESSION['user_id'].'&amp;state=1"> A traiter: <b>'.$nbatt[0].'</b></a>&nbsp;&nbsp;
						<img style="border-style: none" alt="img" src="./images/ico_date.png" /><font color="#FFFFFF"> Aujourd\'hui: <b>'.$nbday[0].'</b></font>&nbsp;&nbsp;
						<img style="border-style: none" alt="img" src="./images/warning_min.png" /><font color="#FFFFFF"> Anciennes: <b>'.$nb15[0].'</b></font>&nbsp;&nbsp;
						<img style="border-style: none" alt="img" src="./images/critical_min.png" /><font color="#FFFFFF"> Trés anciennes: <b>'.$nb30[0].'</b></font>&nbsp;&nbsp;
						<img style="border-style: none" alt="img" src="./images/calc.png" /><font color="#FFFFFF"> Ratio du jour: <b>'.$ratio.'</b></font>&nbsp;&nbsp;
						<img style="border-style: none" alt="img" src="./images/chronometer.png" /><font color="#FFFFFF"> Charge: <b>'.$nbtps.'h</b></font>&nbsp;&nbsp;
						<a  href="./index.php?action=logout"><img title="Déconnexion" align="right" style="border-style: none" alt="img" src="./images/logoff.png" />&nbsp;</a>
					</div>
					';
				} else {
					echo '
					<div class="postbottom">
							<img  style="border-style: none" alt="img" src="./images/admin.png" /><font color="#FFFFFF"><b><a href="./index.php?page=admin/user&amp;action=edit&amp;id='.$_SESSION['user_id'].'"> '.$reqfname['firstname'].' '.$reqfname['lastname'].'</a></b></font>&nbsp;&nbsp;
								<img  style="border-style: none" alt="img" src="./images/ico_cat.png" /><a href="./index.php?page=dashboard&amp;techid='.$_SESSION['user_id'].'&amp;state=3"> Résolues: <b>'.$nbres[0].'</b></a>&nbsp;&nbsp;
								<img  style="border-style: none" alt="img" src="./images/ico_more.png" /><a href="./index.php?page=dashboard&amp;techid='.$_SESSION['user_id'].'&amp;state=1"> En cours de traitement: <b>'.$nbatt[0].'</b></a>&nbsp;&nbsp;
								<a  href="./index.php?action=logout"><img title="Déconnexion" align="right" style="border-style: none" alt="img" src="./images/logoff.png" />&nbsp;</a>
					</div>
					';
				}
				
			}
			
			?>
		</div>
		<div id="content">
			<?php			
			include "./index_auth.php";
			// Close database access
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