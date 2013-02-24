<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>GestSup | Gestion de Support</title>
<link rel="shortcut icon" type="image/ico" href="../images/favicon.ico" />
<link href="../style.css" rel="stylesheet" type="text/css" />
</head>
<?php
/*
Author: Flox
Filename: index.php
Description: install page
Version: 1.2
Last update: 14/10/2012
*/

// initialize variables 
if(!isset($step)) $step = '';
if(!isset($_POST['1'])) $_POST['1'] = '';
if(!isset($_POST['2'])) $_POST['2'] = '';
if(!isset($_POST['3'])) $_POST['3'] = '';
if(!isset($_POST['retour1'])) $_POST['retour1'] = '';
if(!isset($_POST['retour2'])) $_POST['retour2'] = '';
if(!isset($_POST['retour3'])) $_POST['retour3'] = '';
if(!isset($_POST['serveur'])) $_POST['serveur'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($requetes)) $requetes= '';
if(!isset($valid)) $valid = '';
if(!isset($vphp)) $vphp = '';
if(!isset($i)) $i = '';
if(!isset($textension[$i])) $textension[$i] = '';
if(!isset($openssl)) $openssl = '';
if(!isset($phpinfo)) $phpinfo = '';
if(!isset($match)) $match = '';
if(!isset($ldap)) $ldap = '';


//Extraction des paramètres du phpinfo
ob_start();
phpinfo();
$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
    foreach($matches as $match)
        if(strlen($match[1]))
            $phpinfo[$match[1]] = array();
        elseif(isset($match[3])) {
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}
        else {
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][] = $match[2];
		}
			
			
// case for old version php, php info tab is PHP CORE 			
if (isset($phpinfo['Core'])!='') $vphp='Core'; else $vphp='PHP Core';

// initialize variables 
if(!isset($phpinfo[$vphp]['register_globals'][0])) $phpinfo[$vphp]['register_globals'][0] = '';
if(!isset($phpinfo[$vphp]['magic_quotes_gpc'][0])) $phpinfo[$vphp]['magic_quotes_gpc'][0] = '';
if(!isset($phpinfo[$vphp]['file_uploads'][0])) $phpinfo[$vphp]['file_uploads'][0] = '';
if(!isset($phpinfo[$vphp]['memory_limit'][0])) $phpinfo[$vphp]['memory_limit'][0] = '';
if(!isset($phpinfo[$vphp]['upload_max_filesize'][0])) $phpinfo[$vphp]['upload_max_filesize'][0] = '';

if ($step=="") $step=1;
if ($_POST['1']){$step='2';}
if ($_POST['2']){$step='3';}
if ($_POST['3']){$step='4';}
if ($_POST['retour1']){$step='1';}
if ($_POST['retour2']){$step='2';}
if ($_POST['retour3']){$step='3';}
?>

<body>
	<div id="wrap">
		<div id="header">
			<h1 id="sitename"><a href="#"><img border="none" src="../images/gestsup.png" /></a><span class="caption">Gestion de Support <br /> Installation </span></h1>
		</div>
		<div id="content">
			<br />
			<?php
			////ETAPE 1	
				//Actions
				if ($_POST['1'])
				{
					// Ecriture du fichier de connection a la base de données connect.php
					$fichier = fopen('../connect.php','w+');
					fputs($fichier,"<?php\r\n");
					fputs($fichier,"\$serveur=\"$_POST[serveur]\";//nom du serveur\r\n");
					fputs($fichier,"\$user=\"$_POST[user]\";//votre nom utilisateur\r\n");
					fputs($fichier,"\$password=\"$_POST[password]\";//mot de passe\r\n");
					fputs($fichier,"\$base=\"bsup\";//nom de la base de donnée\r\n");
					fputs($fichier,"\$connexion = mysql_connect(\$serveur,\$user,\$password) or die(\"impossible de se connecter : \". mysql_error());\r\n");
					fputs($fichier,"\$db = mysql_select_db(\$base, \$connexion)  or die(\"impossible de sélectionner la base : \". mysql_error());\r\n");
					fputs($fichier,"?>");
					fclose($fichier);

					// Connexion à la base
					mysql_connect($_POST['serveur'],$_POST['user'],$_POST['password']);
					mysql_query("create database bsup;");
					mysql_select_db('bsup');

					// Envoie le contenu de base.sql vers la variable $sql_file
					$sql_file=file_get_contents('../_SQL/skeleton.sql');
					$sql_file=explode(";", $sql_file);
					foreach ($sql_file as $value) {
						mysql_query($value);
					}
					$step=2;
				}
				//form
				if ($step=='1')
				{
					echo "<h2 class=\"sec_head\">Installation étape 1/3: Paramètres de connexion à la base de donnée</h2>";
					echo'
					<br />
					<br />
					<form method="post" name="1" action="">
						<b>Serveur:</b> <br /><input name="serveur" value="localhost" type="text"  ><br />
						<b>Utilisateur:</b> <br /><input name="user" value="root" type="text"/><br />
						<b>Mot de passe</b> <br /><input name="password" value="" type="password"/><br />
						<br />
						<div  class="buttons2">
							<br />
							<button name="1" value="1" type="submit"  class="positive">
								<img src="../images/apply2.png" alt=""/>
								Suivant
							</button>
							<br /><br /><br />
						</div>
					</form>
					';
				}
			/////ETAPE 2
			if ($step=='2')
			{
							$valid==1;
							echo "<h2 class=\"sec_head\">Installation étape $step/3: Vérification des parametres php</h2>";
							echo'<br />
							<img src="../images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>Système d\'exploitation:</u></b><br />
								<blockquote>
									&nbsp;&nbsp;&nbsp;&nbsp;	<img src="../images/windows.png" style="border-style: none" alt="img" /> ';echo "{$phpinfo['phpinfo']['System']}<br />\n";echo'<br />
								</blockquote>
							<img src="../images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>État des paramètres PHP:</u></b>
							<blockquote>
								';
								$smtp=$phpinfo[$vphp]['SMTP'][0];
								$smtpport=$phpinfo[$vphp]['smtp_port'][0];
								$sendmail=$phpinfo[$vphp]['sendmail_from'][0];
								if ($phpinfo[$vphp]['file_uploads'][0]=="On") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b>: Activé<br />'; else {echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/critical_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b> Désactivé, le chargement des fichiers dans les incidents ne pourra fonctionner.<br />'; $valid='0';}
								if ($phpinfo[$vphp]['memory_limit'][0]!="") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>Mémoire Allouée:</b> '.$phpinfo[$vphp]['memory_limit'][0].'<br />'; else {echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/critical_min.png" style="border-style: none" alt="img" /> Pas d\'adresse mail renseigné '.$sendmail.', certaines fonctions sont indisponibles.<br />'; $valid='0';}
								if ($phpinfo[$vphp]['upload_max_filesize'][0]!="") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>Upload max:</b> '.$phpinfo[$vphp]['upload_max_filesize'][0].'<br />'; else {echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/critical_min.png" style="border-style: none" alt="img" /> Pas d\'adresse mail renseigné '.$sendmail.', certaines fonctions sont indisponibles.<br />'; $valid='0';}

								echo '
								<br />
							</blockquote>
							<img src="../images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>État des Extensions PHP:</u></b>
							<blockquote>
								';
								$textension = get_loaded_extensions();
								$nblignes = count($textension);
								for ($i;$i<$nblignes;$i++)
								{
								if(!isset($textension[$i])) $textension[$i] = '';
								if ($textension[$i]=='mysql') $mysql="1";
								if ($textension[$i]=='openssl') $openssl="1";
								if ($textension[$i]=='ldap') $ldap="1";
								}
								if ($mysql=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;	<img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>php_mysql</b> Activée'; else {echo '<img src="../images/critical_min.png" style="border-style: none" alt="img" /> <b>php_mysql</b> Désactivé, certaines fonctions sont indisponibles.';  $valid=0;}
								echo "<br />";
								if ($openssl=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp; <img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>php_openssl:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_openssl</b> Désactivé, si vous utilisé un serveur SMTP sécurisé les mails ne seront pas envoyés.';
								echo "<br />";
								if ($ldap=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp; <img src="../images/ok_min.png" style="border-style: none" alt="img" /> <b>php_ldap:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_ldap</b> Désactivé, la synchronisation des utilisateurs et l\'authentification via LDAP se seront pas disponible.';
								echo '
							</blockquote>';
							
							if ($valid=="0")
							{echo '<br /> <br /><img src="../images/critical_min.png" style="border-style: none" alt="img" /> Configuration invalide, veuillez modifier votre fichier php.ini pour activée les fonctions manquante <a href="./index.php?step=2"><img alt="title" src="../images/actualiser.png" style="border-style: none" alt="img" /></a>';} 
							else
							{
							echo '
							<center>
								<form method="post" style="display:inline" name="1" action="">
									<div  class="buttons2">
										<br />
										<button name="2" value="2" type="submit"  class="positive">
											<img src="../images/apply2.png" alt=""/>
											Suivant
										</button>
										<br /><br /><br />
									</div>
								</form>
							</center>
							';}
			}
			/////ETAPE 3			
						if ($step=='3')
						{
							
							echo "<h2 class=\"sec_head\">Installation étape 3/3: Fin de l'installation</h2>";
							echo "<br /><br />";
							echo '
							L\'application à été installé vous pouvez y acceder via l\'url: <a href=\"http://localhost/gestsup\">http://localhost/gestsup</a>. <br />
							<br />Les identifiants initiaux sont admin / admin <br /><br />
							<font color=\"red\">!!! Attention pour des raisons de sécurité nous vous conseillons de supprimer le répertoire /install .</font>
							<br /><br />
							<center>
							<form method="post" name="3" action="../index.php">
							
					
							<div  class="buttons2">
			<br />
			<button name="3" value="3" type="submit"  class="positive">
				<img src="../images/apply2.png" alt=""/>
				Acceder à l\'URL
			</button>
			<br /><br /><br />
			</div>
							</form>
							</center>';
						}
			/////ETAPE 4
						if ($step=='4')
						{
						echo "<h2>Installation étape 4/4: Installation terminée</h2>";
						echo "<blockquote>";
						echo '<img src="../images/ok_min.png" style="border-style: none" alt="img" /> Installation terminée vous pouvez acceder à l\'application via les identifiants suivant:<br /><br />';
						echo "<u>identifiant administrateur:</u> admin/admin<br />";
						echo "<u>identifiant utilisateur:</u> user/user<br /><br />";
						echo '<a href="../index.php>=> Se connecter à l\'application';
						echo "</blockquote>";
						}
			?>
			<div class="clear"></div>
		</div>
		<div id="footer">
			<div id="credits">
				<a href="http://ramblingsoul.com">CSS Template</a>
			</div>
		</div>
	</div>
</body>
</html>
