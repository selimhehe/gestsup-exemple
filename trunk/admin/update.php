<?php
/*
Author: Flox
FileName: update.php
Description: page to update GestSup
Version: 1.2
creation date: 20/01/2011
last update: 06/11/2012
*/

//initialize variables 
if(!isset($contents[0])) $contents[0] = '';
if(!isset($_POST['check'])) $_POST['check'] = '';
if(!isset($_POST['download'])) $_POST['download'] = '';
if(!isset($_POST['install'])) $_POST['install'] = '';
if(!isset($_POST['install_update'])) $_POST['install_update'] = '';
if(!isset($_GET['install_update'])) $_GET['install_update'] = '';

//update server parameters
$ftp_server="gestsup.fr";
$ftp_user_name="gestsup";
$ftp_user_pass="gestsup";

//check lastest version
$conn_id = ftp_connect($ftp_server);
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
$contents = ftp_nlist($conn_id, ".");

$vserv=explode("_",$contents[0]);
if(!isset($vserv['1'])) $vserv['1'] = '';
$vserv=explode(".zip",$vserv['1']);
$vserv=$vserv[0];

echo'
<br>
<b>Version actuelle GestSup:</b> ';
$qvactu = mysql_query("SELECT * FROM `tparameters`");
$rvactu = mysql_fetch_array($qvactu);
$vactu="$rvactu[version]";
echo "$vactu<br /><br />";
echo"<b>Disponibilit� du serveur de mise � jour: </b>";
if ($vserv){
	echo "<img src=\"./images/ok_min.png\" border=\"0\" />";
} else {
	echo "<img src=\"./images/critical_min.png\" border=\"0\" />";
}
echo "<br /><br />";

if($_POST['check'])
{
	if ($vactu==$vserv)
	{
		echo '<div id="valide"><img src="./images/valide.png" style="border-style: none" alt="img" /> Votre version '.$vactu.' est � jour.</div>';
	}
	else if ($vactu<$vserv)
	{
		echo '<div id="valide"><img src="./images/valide.png" style="border-style: none" alt="img" /> La version '.$vserv.' est disponible.</div>';
	}
	else if ($vactu>$vserv)
	{
		echo '<div id="valide"><img src="./images/valide.png" style="border-style: none" alt="img" /> La version '.$vserv.' du serveur est inf�rieur � celle install�e.</div>';
	}
}

if($_POST['download'])
{
	if ($vactu<$vserv)
	{
		$serveur_file="/gestsup_$vserv.zip";
		$monmicro_file="./download/gestsup_$vserv.zip";
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		if ((!$conn_id) || (!$login_result)) {
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Le t�l�chargement de la derni�re version � �chou�.</div>";
		die;
		} 
		$download = ftp_get($conn_id, $monmicro_file, $serveur_file, FTP_BINARY);
		if (!$download) {echo "Le t�l�chargement Ftp a �chou�!";}
		else {echo '<div id="valide"><img src="./images/valide.png" style="border-style: none" alt="img" /> Fichiers t�l�charg�s dans le repertoire download du serveur web.</div>';}
		ftp_quit($conn_id);
	}
	else
	{echo '<div id="valide"><img src="./images/valide.png" style="border-style: none" alt="img" />Votre version '.$vactu.' est � jour, pas de t�l�chargement n�cessaire.</div>';}
	
}
if($_POST['install'])
{
	if ($vactu>$vserv)
	{
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Installation impossible, vous possedez une version plus r�cente que le serveur.</div>";
	} elseif ($vactu==$vserv) {
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Installation impossible, votres version $vserv est la plus r�cente.</div>";
	} elseif(file_exists("./download/gestsup_$vserv.zip")) {
		echo "
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><u>Proc�dure manuel d'installation de mise � jour depuis votre serveur web:</u></b><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Faites une copie de sauvegarde de votre repertoire /gestup <br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Faites un dump de votre base de donn�es avant migration <img src=\"./images/question.png\" alt=\"question\" title=\"Allez sur http://localhost/phpmyadmin selectionner la base BSUP dans l'onglet EXPORTER faites EXECUTER\" /><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- D�zipper le fichier gestsup_x.x.zip depuis le repertoire /gestsup/download <br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Extraire l'int�gralit� du package � la racine de votre site sauf repertoire <b>upload</b> et le fichier <b>connect.php</b><br />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Passer le script ./gestsup/_SQL/update_x.x_to_x.x.sql <font color=\"red\"> <i>(!!! Attention vous devez mettre � jour version par version !!!)</i></font> <img src=\"./images/question.png\" alt=\"question\" title=\"Allez sur http://localhost/phpmyadmin selectionner la base BSUP dans l'onglet IMPORTER selectionner le fichier update_x.x_to_x.x.sql faites EXECUTER\" /><br />	
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Vider le cache de votre navigateur et lancer l'application <br />
			<br /><br />
			<form method=\"POST\" action=\"\">
				<div  class=\"buttons2\">
					<button name=\"install_update\" value=\"install_update\" type=\"submit\"  class=\"positive\">
						<img src=\"images/run.png\" alt=\"\"/>
						Lancez l'installation automatique
					</button>
				</div>
			</form>
		";
	} else {
		echo "<div id=\"erreur\"><img src=\"./images/critical.png\" border=\"0\" /> Vous devez d'abord t�l�charger la version $vserv.</div>";
	}
	
}
if(($_POST['install_update']) || ($_GET['install_update']==1))
{
	include("./core/install_update.php");
}
?>
<br /><br /><br /><br /><br /><br />
<form method="POST" action="">
	<div  class="buttons">
				<button name="check" value="check" type="submit"  class="positive">
					<img src="images/ok_min.png" alt=""/>
					V�rifier
				</button>
				
				<button name="download" value="download" type="submit" class="positive" >	
					<img src="images/download2.png" alt=""/>
					T�l�charger
				</button>

				<button name="install" value="install" type="submit" class="positive">
				 <img src="images/install.png" alt=""/>
					Installer
				</button>

				<button name="cancel" value="cancel" type="submit" class="negative" name="cancel" id="cancel">
					<img src="images/cross.png" alt=""/>
					Annuler
				</button>
				<br /><br /><br /><br />
	</div>
</form>