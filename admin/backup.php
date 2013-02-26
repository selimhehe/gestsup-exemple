<?php
/*
Author: Flox
File name: backup.php
Description: save and restore page
Version: 1.1
creation date: 12/04/2012
last update: 14/10/2012
*/
// initialize variables 
if(!isset($action)) $action = '';
if(!isset($command)) $command = '';
if(!isset($_FILES['restore']['name'])) $_FILES['restore']['name'] = '';
if(!isset($_FILES['logo']['name'])) $_FILES['logo']['name'] = '';
if(!isset($_POST['upload'])) $_POST['upload'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';

if ($_GET['action']=="backup")
{
	//// Create Temporary directory
	mkdir("./tmp");
	
	////Dump database
		set_time_limit(90);
		$date = date("Y-m-d");
		$file = "./_SQL/backup-bsup-v$rparameters[version]-$date.sql";
		//Find MySQL BaseDir
		$query = mysql_query("show variables");
		while ($row=mysql_fetch_array($query)) {
		if ($row[0]=="basedir") $basedir=$row[1];
		}
		$command .= "$basedir\bin\mysqldump.exe -u root --opt bsup -h localhost > $file";
		system($command);
		
	////files copy	
		function copy_dir ($dir2copy,$dir_paste) {
				// Verify directory
				if (is_dir($dir2copy)) {
						// Open Directory
						if ($dh = opendir($dir2copy)) {     
								// list directory
								while (($file = readdir($dh)) !== false) {
										// if pastdir don't exist create
										if (!is_dir($dir_paste)) mkdir ($dir_paste, 0777);
										// if it's directory recursive
										if(is_dir($dir2copy.$file) && $file != '..'  && $file != '.') copy_dir ( $dir2copy.$file.'/' , $dir_paste.$file.'/' );     
										// if file copy
										elseif($file != '..'  && $file != '.') copy ( $dir2copy.$file , $dir_paste.$file );
								}
						// close directory
						closedir($dh);
						}
				}       
		}
		// copy all directory to backup
		$dir2copy = './_SQL/';
		$dir_paste = './tmp/_SQL/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './admin/';
		$dir_paste = './tmp/admin/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './components/';
		$dir_paste = './tmp/components/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './core/';
		$dir_paste = './tmp/core/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './download/';
		$dir_paste = './tmp/download/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './images/';
		$dir_paste = './tmp/images/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './install/';
		$dir_paste = './tmp/install/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './js/';
		$dir_paste = './tmp/js/';
		copy_dir ($dir2copy,$dir_paste);
		
		$dir2copy = './upload/';
		$dir_paste = './tmp/upload/';
		copy_dir ($dir2copy,$dir_paste);
		
		// copy all root files to backup
		$dh = opendir("./") ;
		// list directory
		while (($file = readdir($dh)) !== false) 
		{  
			if((!is_dir($file)) && ($file!="my-archive.zip"))
			{
				// file copy
				if (!copy($file, "./tmp/$file")) {echo "failed to copy $file...\n";}
			}
		}
		// close directory
		closedir($dh);
		
	//// Zip backup folder
		// increase script timeout value
		ini_set("max_execution_time", 300);
		// create object
		$zip = new ZipArchive();
		// open archive
		if ($zip->open("./backup/backup-bsup-v$rparameters[version]-$date.zip", ZIPARCHIVE::CREATE) !== TRUE) {
		die ("Could not open archive");
		}
		// initialize an iterator
		// pass it the directory to be processed
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator("./tmp"));
		// iterate over the directory
		// add each file found to the archive
		foreach ($iterator as $key=>$value) {
		$zip->addFile(realpath($key), $key) or die ("ERROR: Could not add file: $key");
		}
		// close and save archive
		$zip->close();
		
	//// remove tmp directory
		function deltree($dossier)
		{
			if(($dir=opendir($dossier))===false)
				return;
	 
			while($name=readdir($dir)){
				if($name==='.' or $name==='..')
					continue;
				$full_name=$dossier.'/'.$name;
	 
				if(is_dir($full_name))
					deltree($full_name);
				else unlink($full_name);
				}
	 
			closedir($dir);
	 
			@rmdir($dossier);
		}
		deltree("./tmp/");
		
	//// Download file
		$file = "./backup/backup-bsup-v$rparameters[version]-$date.zip";
		header("Content-type: application/zip");
		header("Content-Disposition: attachment; filename=$file");
		header("Pragma: no-cache");
		header("Expires: 0");
		readfile("$file");
		exit;
}
if($_POST['upload'])
{
/*
	////upload restore file
		if($_FILES["zip_file"]["name"]) {
			$filename = $_FILES["zip_file"]["name"];
			$source = $_FILES["zip_file"]["tmp_name"];
			$type = $_FILES["zip_file"]["type"];
		 
			$name = explode(".", $filename);
			$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
			foreach($accepted_types as $mime_type) 
			{
				if($mime_type == $type) {
					$okay = true;
					break;
				} 
			}

		 
			$target_path = "./backup/".$filename;  
			if(move_uploaded_file($source, $target_path)) 
			{
				echo "ready to unzip <br />";
				//unzip file
				
				
				
		
				
		
function unzip($target_path)
      {
                $zip = new ZipArchive;
                $res = $zip->open($package);
                if ($res === TRUE) {
                        $zip->extractTo(dirname(__FILE__));
                        $zip->close();
                        //echo "Package extracted";
                        return true;
                } else {
                        //echo "Package failed to extract";
                        return false;
                }
    } 

				
				
				
				
				
				
				
				
			$message = "Upload sucefuly <br />.";
			} else {	
				$message = "There was a problem with the upload. Please try again.";
			}
		}
		echo $message;
	///// Check versions
	
	////Import SQL data
*/
}
?>

<a href="./index.php?page=admin&subpage=backup&action=backup">
	<img title="Sauvegarde la base de données et les fichiers de Gestup " src="./images/download.png" style="border-style: none" alt="img" />
	&nbsp;Sauvegarder <i>(Télécharge une archive des fichiers et de la base de donnée)&nbsp; <img title="Disponible sur un serveur Windows uniquement" src="./images/windows.png" style="border-style: none" alt="img" /></i>
</a>
<br /><br />
<!--
<form enctype="multipart/form-data" method="post" action="">
	<img title="Crée un copie de la base de donnée sur le serveur web" src="./images/backup.png" style="border-style: none" alt="img" />
	&nbsp;Restaurer 
	<input type="file" name="zip_file" /> 
	<input name="upload" id="upload" type="submit" value="Upload" />
</form>
--->

<br /><br />
<a target="_blank" href="/phpmyadmin/index.php?db=bsup">
	<img src="./images/phpmyadmin.png" style="border-style: none" alt="img" />
	&nbsp;Administrer la base de donnée <i>(PHPMyAdmin) </i>
</a>