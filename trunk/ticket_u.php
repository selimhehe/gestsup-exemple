<?php
/*
Author: Florent Hauville
FileName: ticket.php
Description: page to edit user ticket
Version: 1.2
Last update: 09/05/2012
*/

// initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($category)) $category = ''; 
if(!isset($subcat)) $subcat = ''; 
if(!isset($title)) $title = ''; 
if(!isset($date_hope)) $date_hope = ''; 
if(!isset($description)) $description = ''; 
if(!isset($resolution)) $resolution = ''; 
if(!isset($priority)) $priority = '';
if(!isset($dateres)) $dateres = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($id_in)) $id_in = '';
if(!isset($save)) $save = '';
if(!isset($techread)) $teachread = '';
if(!isset($next)) $next = '';
if(!isset($previous)) $previous = '';
if(!isset($user)) $user = '';
if(!isset($globalrow['time'])) $globalrow['time'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['title'])) $_POST['title'] = '';
if(!isset($_POST['description'])) $_POST['description'] = '';
if(!isset($_POST['resolution'])) $_POST['resolution'] = '';
if(!isset($_POST['Submit'])) $_POST['Submit'] = '';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['modify'])) $_POST['modify'] = '';
if(!isset($_POST['quit'])) $_POST['quit'] = '';
if(!isset($_POST['date_create'])) $_POST['date_create'] = '';
if(!isset($_POST['date_hope'])) $_POST['date_hope'] = '';
if(!isset($_POST['priority'])) $_POST['priority'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_POST['time'])) $_POST['time'] = '';
if(!isset($_POST['state'])) $_POST['state'] = '';
if(!isset($_GET['print'])) $_GET['print'] = '';

//print demande
if ($_GET['print']=="1") echo '<body onload="window.print();"> </body>';

$globalquery = mysql_query("SELECT * FROM tincidents WHERE id LIKE '$_GET[id]'");
$globalrow=mysql_fetch_array($globalquery); 

$today = date("d/m/Y");
$today = "$today: ";

//Database inputs if submit
if($_POST['modify']||$_POST['quit']||$_POST['mail']||$save=="1") 
{
	// special char in sql query
	$_POST['description']  = str_replace('\\','\\\\',$_POST['description']);
	$_POST['resolution']  = str_replace("\\","\\\\",$_POST['resolution']);
	$_POST['title']  = str_replace("\\","\\\\",$_POST['title']);	
	$_POST['description'] = str_replace("'","\'",$_POST['description']); 
	$_POST['resolution']  = str_replace("'","\'",$_POST['resolution']); 
	$_POST['title']  = str_replace("'","\'",$_POST['title']); 
	$_POST['title']  = str_replace(chr(34),"\'",$_POST['title']);  
	
	//insert resolution date if state is res
	if ($_POST['state']=='3') $dateres=date("Y-m-d");
	
	//unread ticket case when creator is not techncian
	if($_POST['technician']!=$globalrow['technician']) $techread=0; else $techread=1;

	$query = "UPDATE tincidents SET user='$_POST[user]', technician='$_POST[technician]', title='$_POST[title]', description='$_POST[description]', resolution='$_POST[resolution]', date_create='$_POST[date_create]', date_hope='$_POST[date_hope]', date_res='$dateres', priority='$_POST[priority]', state='$_POST[state]', time='$_POST[time]', category='$_POST[category]', subcat='$_POST[subcat]', techread='$techread' WHERE id LIKE '$_GET[id]' and disable='0'";
	$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
	
	//uploading files
	include "./core/upload.php";
	
	// redirect
	if ($_POST['quit'])
	{
		//select redirect page
		if ($state=="1") {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1";} 
		else if ($state=="2") {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=2";}
		else {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1";}
			
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
	// send mail
	if($_POST['mail'])
	{
	// redirect
	$www = "./index.php?page=mail&id=$_GET[id]&state=$_POST[state]";
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$www.'");
	// -->
	</script>';
	}
	echo "<div id=\"valide\"><img src=\"./images/save.png\" border=\"0\" /> Ticket sauvegardé.</div>";
	// redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
			window.location='./index.php?page=ticket&id=$_GET[id]'
			}
			setTimeout('redirect()',$rparameters[time_display_msg]);
			-->
			</SCRIPT>";
}  
//unread ticket technician
if (($globalrow['techread']=="0")&&($globalrow['technician']==$_SESSION['user_id'])) 
{
	$query = "UPDATE tincidents SET techread='1' WHERE id='$_GET[id]'";
	$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
}
//find previous and next ticket
$query = mysql_query("SELECT MIN(id) FROM tincidents WHERE id > '$_GET[id]' AND id IN (SELECT id FROM tincidents WHERE technician='$techid' AND state='$state' AND id not like '$_GET[id]')");
$next = mysql_fetch_array($query);
$query = mysql_query("SELECT MAX(id) FROM tincidents WHERE id < '$_GET[id]' AND id IN (SELECT id FROM tincidents WHERE technician='$techid' AND state='$state' AND id not like '$_GET[id]')");
$previous = mysql_fetch_array($query);
?>

<div id="catalogue">
	<form name="form" enctype="multipart/form-data" method="post" action="" id="thisform">
		<h2 class="sec_head"><img src="./images/ticket-icon.png" /> Ticket n°<?php echo $_GET['id'];?> &nbsp;&nbsp;
		<a target="_blank" href="./index.php?page=ticket_u&id=<?php echo $_GET['id']; ?>&print=1"><img align="right" style="margin:0px 10px 0px 0px;" align="left" style="border-style: none" alt="img" src="./images/print.png" title="Imprimer" /></a>
		</h2>
		<br />
		<label  for="user">Demandeur:</label>
		   	<?php
			if ($_POST['user'])	{$user=$_POST['user'];}	else {$user=$globalrow['user'];}
			if ($user!='')
			{
				$query = mysql_query("SELECT * FROM tusers WHERE disable='0' and id LIKE $user");
				$row = mysql_fetch_array($query);
				echo "$row[firstname] $row[lastname]";
			} else {
			echo "Aucun";
			}
			?>
		<br />
		<br />
		<label for="technician">Technicien:</label>
		   	<?php
			$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$globalrow[technician]' ");
			$row=mysql_fetch_array($querytech);
			echo "$row[firstname] $row[lastname]"; 
			?>
		<br />
		<br />
		<label for="category">Catégorie:</label>
		
		<?php
			$query= mysql_query("SELECT * FROM `tcategory` where id=$globalrow[category]");
			$row=mysql_fetch_array($query);
			echo "$row[name] - ";
			$query= mysql_query("SELECT * FROM `tsubcat` WHERE id=$globalrow[subcat]");
			$row=mysql_fetch_array($query);
			echo $row['name'];
	    ?>
		<br /><br />
		<label for="title">Titre:</label>
		<?php echo $globalrow['title']; ?>
		<br />
		<br />
		<label for="description">Descritption:</label>
		<br />
		<br />
		<textarea readonly="readonly" class="textfield" id="description" name="description" cols="100" rows="2" ><?php  echo $globalrow['description']; ?></textarea>
		<br />
		<?php include "./attachement.php";?>
		<br /><br />
		<label for="resolution">Résolution:</label>
		<br />
		<br />
		<textarea readonly="readonly" class="textfield" id="description" name="description" cols="100" rows="2" ><?php echo $globalrow['resolution']; ?></textarea>
		<br />
		<br />
		<label for="date_create">Date de la demande:</label>
		<?php echo $globalrow['date_create']; ?>
		<br />
		<br />
		<label for="date_hope">Date de résolution estimé:</label>
		<?php echo $globalrow['date_hope']; ?>
		<br />
		<br />
		<label for="time">Temps passé:</label>
		<?php echo "$globalrow[time] min";?>
		<br />
		<br />
		<label for="priority">Priorité:</label>
		   	<?php
				$query = mysql_query("SELECT * FROM `tpriority` WHERE number LIKE '$globalrow[priority]'");
				$row=mysql_fetch_array($query);
				echo $row['name'];
			?>
		<br />
		<br />
		<label for="priority">Criticité:</label>
		   	<?php
				$query = mysql_query("SELECT * FROM `tcriticality` WHERE id LIKE '$globalrow[criticality]'");
				$row=mysql_fetch_array($query);
				echo $row['name'];
			?>
		<br />
		<br />
		<label for="state">Etat:</label>
		   	<?php
				$query = mysql_query("SELECT * FROM `tstates` WHERE id LIKE '$globalrow[state]'");
				$row=mysql_fetch_array($query);
				echo "$row[name]";
			?>
		<br /><br />
	</form>
</div>

<!-- Script allow autogrow textareas -->
<script>
$('textarea').ata();
</script>

<script type="text/javascript">
        // jQuery en action
        jQuery.noConflict();
        jQuery('#jquery').addClass('jquery');

        // Prototype en action
        $('prototype').addClassName('prototype');
</script>

<SCRIPT language="Javascript">
function insertAtCaret(areaId,text) {
	var txtarea = document.getElementById(areaId);
	var scrollPos = txtarea.scrollTop;
	var strPos = 0;
	var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
		"ff" : (document.selection ? "ie" : false ) );
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		strPos = range.text.length;
	}
	else if (br == "ff") strPos = txtarea.selectionStart;
	
	var front = (txtarea.value).substring(0,strPos);  
	var back = (txtarea.value).substring(strPos,txtarea.value.length); 
	txtarea.value=front+text+back;
	strPos = strPos + text.length;
	if (br == "ie") { 
		txtarea.focus();
		var range = document.selection.createRange();
		range.moveStart ('character', -txtarea.value.length);
		range.moveStart ('character', strPos);
		range.moveEnd ('character', 0);
		range.select();
	}
	else if (br == "ff") {
		txtarea.selectionStart = strPos;
		txtarea.selectionEnd = strPos;
		txtarea.focus();
	}
	txtarea.scrollTop = scrollPos;
}
</SCRIPT>