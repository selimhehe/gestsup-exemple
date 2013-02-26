<?php
/*
Author: Flox
FileName: install_update.php
Description: install update 
Version: 1.0
Creation date: 06/11/2012
Last update: 12/11/2012
*/

//initialize variables 
if(!isset($step)) $step= '1';
if(!isset($command)) $command= '';
if(!isset($error)) $error= '';
if(!isset($_POST['continue1'])) $_POST['continue1']= '';
if(!isset($_POST['continue2'])) $_POST['continue2']= '';
if(!isset($_POST['continue3'])) $_POST['continue3']= '';

//POST
if ($_POST['continue1']) $step=2;
if ($_POST['continue2']) $step="2.5";
if ($_POST['continue3']) $step=3;

//date
$date = date("Y-m-d");

echo "<b>Lancement de la migration de GestSup version $vactu vers GestSup version $vserv:</b><br /><br />";

//display backup warning
if ($step==1)
{
echo "
	<script type=\"text/javascript\"> 
		$().ready(function() { 
			$('#dialog').jqm(); 
				$('#dialog').jqmShow(); 
				return false; 
			}); 
	</script> 
	<div class=\"jqmWindow\" id=\"dialog\"> 
			<div id=\"warning\"><img src=\"./images/warning.png\" alt=\"\" /> Il est recommandé de faire ses propres sauvegardes avant lancer la mise à jour (Base de donnée et Fichiers)</div>
			<br />
			<form method=\"POST\" action=\"index.php?page=admin&subpage=update&install_update=1&step=2\">
				<div  class= \"buttons2\">
					<button name=\"continue1\" value=\"continue1\" type=\"submit\"  class=\"positive\">
						<img src=\"images/ok_min.png\" alt=\"\"/>
						J'ai fait mes propres sauvegardes
					</button>
					<button name=\"cancel\" value=\"cancel\" type=\"submit\" class=\"jqmClose\"  id=\"jqmCloseBtn\  name=\"cancel\" \">
						<img src=\"images/cross.png\" alt=\"\"/>
						Annuler
					</button>
					<br /><br /><br /><br />
				</div>
			</form>
	</div>
";
}
//restart web server
if ($step==2)
{
echo "
	<script type=\"text/javascript\"> 
		$().ready(function() { 
			$('#dialog').jqm(); 
				$('#dialog').jqmShow(); 
				return false; 
			}); 
	</script> 
	<div class=\"jqmWindow\" id=\"dialog\"> 
			<div id=\"warning\"><img src=\"./images/warning.png\" alt=\"\" /> Un redémarrage des services web du serveur est préconisé, afin de libérer tous les fichiers actuellement en cours d'accès. <i>(Cette procédure sera eventuellement à relancer)</i></div>
			<br />
			<form method=\"POST\" action=\"index.php?page=admin&subpage=update&install_update=1&step=2\">
				<div  class= \"buttons2\">
					<button name=\"continue2\" value=\"continue2\" type=\"submit\"  class=\"positive\">
						<img src=\"images/ok_min.png\" alt=\"\"/>
						Mes services ont été redémarrés
					</button>
					<button name=\"cancel\" value=\"cancel\" type=\"submit\" class=\"jqmClose\"  id=\"jqmCloseBtn\  name=\"cancel\" \">
						<img src=\"images/cross.png\" alt=\"\"/>
						Annuler
					</button>
					<br /><br /><br /><br />
				</div>
			</form>
	</div>
";

}
//time avert
if ($step=="2.5")
{
echo "
	<script type=\"text/javascript\"> 
		$().ready(function() { 
			$('#dialog').jqm(); 
				$('#dialog').jqmShow(); 
				return false; 
			}); 
	</script> 
	<div class=\"jqmWindow\" id=\"dialog\"> 
			<div id=\"warning\"><img src=\"./images/warning.png\" alt=\"\" /> Attention cette procédure peut prendre du temps en fonction de votre base actuelle.</div>
			<br />
			<form method=\"POST\" action=\"index.php?page=admin&subpage=update&install_update=1&step=2\">
				<div  class= \"buttons2\">
					<button name=\"continue3\" value=\"continue3\" type=\"submit\"  class=\"positive\">
						<img src=\"images/ok_min.png\" alt=\"\"/>
						Lancer la migration
					</button>
					<button name=\"cancel\" value=\"cancel\" type=\"submit\" class=\"jqmClose\"  id=\"jqmCloseBtn\  name=\"cancel\" \">
						<img src=\"images/cross.png\" alt=\"\"/>
						Annuler
					</button>
					<br /><br /><br /><br />
				</div>
			</form>
	</div>
";

}
//backup SQL Data
if ($step==3)
{
	echo "&nbsp;&nbsp;&nbsp;- Redémarrage des services web: <img src=\"images/ok_min.png\" alt=\"\"/><br />";
	require('./core/mysqldump.php');
	$file = "./_SQL/backup-bsup-$rparameters[version]-$date.sql";
	dumpMySQL("$serveur", "$user", "$password", "bsup", 3, "$file");

	if(file_exists($file)) 
	{
		echo "&nbsp;&nbsp;&nbsp;- Sauvegarde de la base de données: <img src=\"images/ok_min.png\" alt=\"\"/><br />";
		$step=4;
	} else {
		echo "&nbsp;&nbsp;&nbsp;- Sauvegarde de la base de données: <img src=\"./images/critical_min.png\" border=\"0\" /><br />";
		$error=1;
	}
}
//backup files
if ($step==4)
{
	ini_set("memory_limit","200M");
	function Zip($source, $destination)
	{
		if (!extension_loaded('zip') || !file_exists($source)) {
			return false;
		}

		$zip = new ZipArchive();
		if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
			return false;
		}

		$source = str_replace('\\', '/', realpath($source));

		if (is_dir($source) === true)
		{
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

			foreach ($files as $file)
			{
				$file = str_replace('\\', '/', $file);

				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
					continue;

				$file = realpath($file);

				if (is_dir($file) === true)
				{
					$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				}
				else if (is_file($file) === true)
				{
					$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		}
		else if (is_file($source) === true)
		{
			$zip->addFromString(basename($source), file_get_contents($source));
		}

		return $zip->close();
	}
	Zip('./', './backup/backup-bsup-'.$rparameters['version'].'-'.$date.'.zip');
	copy('./connect.php', './backup/connect.php');
	
	//check backup
	if(file_exists('./backup/backup-bsup-'.$rparameters['version'].'-'.$date.'.zip')) 
	{
		echo "&nbsp;&nbsp;&nbsp;- Sauvegarde des fichiers: <img src=\"./images/ok_min.png\" border=\"0\" /><br />";
		$step=5;
	} else {
		echo "&nbsp;&nbsp;&nbsp;- Sauvegarde des fichiers: <img src=\"./images/critical_min.png\" border=\"0\" /><br />";
		$error=1;
	}

}
//extract last version
if ($step==5)
{	
	if(file_exists('./download/tmp')) {} else {mkdir("./download/tmp");}
	$zip = new ZipArchive;
    $res = $zip->open('./download/gestsup_'.$vserv.'.zip');
    if ($res === TRUE) {
        $zip->extractTo('./download/tmp/');
        $zip->close();
	}
	//check
	if(file_exists('./download/tmp/index.php'))
	{
		echo "&nbsp;&nbsp;&nbsp;- Extraction de la nouvelle version: <img src=\"images/ok_min.png\" alt=\"\"/><br />";
	    $step=6;
    } else {
		echo "&nbsp;&nbsp;&nbsp;- Extraction de la nouvelle version: <img src=\"./images/critical_min.png\" border=\"0\" /> open=$res <br />";
		$error=1;
	}
}
//install SQL update
if ($step==6)
{
	require('./connect.php');
	//check for multiple sql update case to update from old version.
	$subactu=explode(".",$vactu);
	$subserv=explode(".",$vserv);
	$subserv="$subserv[0]$subserv[1]";
	$subactu="$subactu[0]$subactu[1]";
	$nbscript=$subserv-$subactu;
	$i=$nbscript;
	while ($i>0)
	{
		$i--;
		$srv=$subserv-$i;
		$srv=number_format($srv / 10,1,".","");
		$act=$subserv-$i-1;
		$act=number_format($act / 10,1,".","");
		$sqlfile='update_'.$act.'_to_'.$srv.'.sql';
		
		$sql_file=file_get_contents('./download/tmp/_SQL/'.$sqlfile.'');
		$sql_file=explode(";", $sql_file);
		foreach ($sql_file as $value) {
			mysql_query($value);
		}
	}
	//check
	$qvactu = mysql_query("SELECT * FROM `tparameters`");
	$rvactu = mysql_fetch_array($qvactu);
	$vactu="$rvactu[version]";
	if ($vactu==$vserv) {
		echo "&nbsp;&nbsp;&nbsp;- Mise à jour de la base de donnée: <img src=\"images/ok_min.png\" alt=\"\"/><br />";
	$step=7;
	} else {
		echo "&nbsp;&nbsp;&nbsp;- Mise à jour de la base de données: <img src=\"./images/critical_min.png\" border=\"0\" /><br />"; $error=1;
	}
	
}
//copy lastest files.
if ($step==7)
{
	function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		@mkdir($dst); 
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
	}  
	recurse_copy("./download/tmp/","./");
	copy('./backup/connect.php', './connect.php');
	//exclude connect.php file
	$step=8;
}
//clean temporary folder.
if ($step==8)
{
	echo "&nbsp;&nbsp;&nbsp;- Mise à jour des fichiers: <img src=\"images/ok_min.png\" alt=\"\"/> <br />";
	unlink("./download/gestsup_$vserv.zip");
	function rrmdir($dir) {
	   if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object) {
		   if ($object != "." && $object != "..") {
			 if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
		   }
		 }
		 reset($objects);
		 rmdir($dir);
	   }
	 }
	$dir="./download/tmp/";
	rrmdir($dir);
	$step=9;
}
if ($step==9)
{
echo "&nbsp;&nbsp;&nbsp;- Supression des fichiers temporaires: <img src=\"images/ok_min.png\" alt=\"\"/>";
echo "<br /><br />";
echo '<div id="valide"><img alt="logo" src="./images/valide.png" style="border-style: none" alt="img" /> L\'installation c\'est correctement déroulée. <br /> Afin de finaliser la procédure, déconnéctez vous, videz le cache de votre navigateur, et re-lancer l\'application.</div>';
} elseif ($error==1) {
echo "<br /><br />";
echo '<div id="erreur"><img src="./images/critical.png" alt="erreur" style="border-style: none" alt="img" /> Une erreur est survenue pendant la migration, il est recommandé de restaurer votre base de donnée et vos fichiers, puis de lancer la procédure manuellement.</div>';

}
?>