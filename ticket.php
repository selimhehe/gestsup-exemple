<?php
/*
Author: Flox
FileName: ticket.php
Description: page to edit ticket
Version: 1.6
Last update: 24/01/2012
*/

// initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($category)) $category = ''; 
if(!isset($subcat)) $subcat = ''; 
if(!isset($title)) $title = ''; 
if(!isset($date_hope)) $date_hope = ''; 
if(!isset($date_create)) $date_create = ''; 
if(!isset($dateres)) $dateres = ''; 
if(!isset($description)) $description = ''; 
if(!isset($resolution)) $resolution = ''; 
if(!isset($priority)) $priority = '';
if(!isset($dateres)) $dateres = '';
if(!isset($percentage)) $percentage = '';
if(!isset($id)) $id = '';
if(!isset($id_in)) $id_in = '';
if(!isset($save)) $save = '';
if(!isset($techread)) $teachread = '';
if(!isset($next)) $next = '';
if(!isset($previous)) $previous = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($user)) $user = '';
if(!isset($globalrow['time'])) $globalrow['time'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['upload'])) $_POST['upload'] = '';
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
if(!isset($_POST['date_res'])) $_POST['date_res'] = '';
if(!isset($_POST['priority'])) $_POST['priority'] = '';
if(!isset($_POST['criticality'])) $_POST['criticality'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_POST['time'])) $_POST['time'] = '';
if(!isset($_POST['time_hope'])) $_POST['time_hope'] = '';
if(!isset($_POST['state'])) $_POST['state'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_POST['technician'])) $_POST['technician'] = '';

// find current date & hour 
$hour = date("H:i");
$date = date("d/m/Y");
$today = "$date $hour";

//action print
if ($_GET['action']=="print") echo '<body onload="window.print();"> </body>';

//action delete
if ($_GET['action']=="delete") 
{
//disable ticket
$query = "UPDATE tincidents SET disable='1' WHERE id LIKE '$_GET[id]'";
$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());

//redirect
echo "<div id=\"erreur\"><img src=\"./images/delete_max.png\" border=\"0\" /> Ticket supprimé.</div>";
	// redirect
	echo "<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
			window.location='./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1'
			}
			setTimeout('redirect()',$rparameters[time_display_msg]);
			-->
			</SCRIPT>";
}

//Master query
$globalquery = mysql_query("SELECT * FROM tincidents WHERE id LIKE '$_GET[id]'");
$globalrow=mysql_fetch_array($globalquery); 

//Database inputs if submit
if($_POST['modify']||$_POST['quit']||$_POST['mail']||$_POST['upload']||$save=="1") 
{
	// special char in sql query
	$_POST['description']  = str_replace('\\','\\\\',$_POST['description']);
	$_POST['resolution']  = str_replace("\\","\\\\",$_POST['resolution']);
	$_POST['title']  = str_replace("\\","\\\\",$_POST['title']);	
	$_POST['description'] = str_replace("'","\'",$_POST['description']); 
	$_POST['resolution']  = str_replace("'","\'",$_POST['resolution']); 
	$_POST['title']  = str_replace("'","\'",$_POST['title']); 
	$_POST['title']  = str_replace(chr(34),"\'",$_POST['title']);  
	
	// Include technician transfert 
	if ($_POST['technician']!=$globalrow['technician'] && $_POST['technician']!='')
	{
	$q1= mysql_query("SELECT * FROM tusers where id = $globalrow[technician]");
	$r1=mysql_fetch_array($q1); 
	$q2 = mysql_query("SELECT * FROM tusers where id = $_POST[technician]");
	$r2=mysql_fetch_array($q2);
	if ($globalrow['technician']=='0')
		{
		$_POST['resolution']="$today: Attribution de l\'incident à $r2[firstname] $r2[lastname].
$_POST[resolution]";
		} else {
		$_POST['resolution']="$_POST[resolution]
$today: Transfert de l\'incident de $r1[firstname] $r1[lastname] à $r2[firstname] $r2[lastname]. ";
		}
	}
	//AUTO modify state from 5 to 1 if technician change
	if ($_POST['technician']!=''&& $globalrow['state']=='5') $_POST['state']='1';
	
	//insert resolution date if state is res
	if ($_POST['state']=='3' && $globalrow['date_res']=='0000-00-00') $dateres=date("Y-m-d"); else $dateres=$_POST['date_res'];
	
	//unread ticket case when creator is not techncian 
	if($_POST['technician']!=$globalrow['technician']) $techread=0; else $techread=1;
	
	//unread ticket case when it's an unassigned ticket.
	if($globalrow['technician']=='') $techread=1;
	
	//Update ticket
	$query = "UPDATE tincidents SET 
	user='$_POST[user]',
	technician='$_POST[technician]',
	title='$_POST[title]',
	description='$_POST[description]',
	resolution='$_POST[resolution]',
	date_create='$_POST[date_create]',
	date_hope='$_POST[date_hope]',
	date_res='$dateres',
	priority='$_POST[priority]',
	criticality='$_POST[criticality]',
	state='$_POST[state]',
	time='$_POST[time]',
	time_hope='$_POST[time_hope]',
	category='$_POST[category]',
	subcat='$_POST[subcat]',
	techread='$techread'
	WHERE
	id LIKE '$_GET[id]'";
	$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
	
	//uploading files
	include "./core/upload.php";
	
	//auto send mail
	if($rparameters['mail_auto']==1&&($_POST['upload']=='')){include('./core/auto_mail.php');}
	
	// redirect
	if ($_POST['quit'])
	{
		//select redirect page
		if ($_GET['state']=="1") {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1";} 
		else if ($_GET['state']=="2") {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=2";}
		else {$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1";}
			
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
	echo "<div id=\"valide\"><img src=\"./images/save.png\" border=\"0\" /> Ticket sauvegardé.</div>";
	
	// send mail
	if($_POST['mail'])
	{
		// redirect
		$www = "./index.php?page=preview_mail&id=$_GET[id]";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
	

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
if($_POST['cancel']) 
{
echo "<div id=\"erreur\"><img src=\"./images/delete_max.png\" border=\"0\" /> Annulation pas de modification.</div>";
echo "<SCRIPT LANGUAGE='JavaScript'>
			<!--
			function redirect()
			{
			window.location='./index.php?page=dashboard&techid=$_GET[techid]&state=$_GET[state]'
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

//percentage calc of ticket resolution
if ($globalrow['time_hope']!=0)
{
	$percentage=($globalrow['time']*100)/$globalrow['time_hope'];
	$percentage=round($percentage);
	if (($globalrow['time']!='1') && ($globalrow['time_hope']!='1') && ($globalrow['time_hope']>=$globalrow['time'])) $percentage="<font size=\"2\"><i>($percentage%)</i></font>"; else $percentage='';
}
?>

<div id="catalogue">
	<form name="form" enctype="multipart/form-data" method="post" action="" id="thisform">
		<h2 class="sec_head"><img src="./images/ticket-icon.png" /> Edition du ticket n°<?php echo "$_GET[id]   $percentage"; ?> &nbsp;&nbsp;
		<?php 
		//Display clock if alarm 
		$query = mysql_query("SELECT * FROM tevents WHERE incident='$_GET[id]' and disable='0'");
		$alarm = mysql_fetch_array($query);
		if($alarm) echo '<img title="Alame activée le '.$alarm['date_start'].'" src="./images/clock2.png" />';
		
		if($previous[0]!='') echo"		<a href=\"./index.php?page=ticket&amp;id=$previous[0]&amp;state=$state&amp;techid=$techid\"><img border=\"0\" title=\"Ticket précédent\" src=\"./images/left.png\" /></a>&nbsp;"; 
		if($next[0]!='') echo"	<a href=\"./index.php?page=ticket&amp;id=$next[0]&amp;state=$state&amp;techid=$techid \"><img border=\"0\" title=\"Ticket suivant\" src=\"./images/right.png\" /></a>";
		if ($rright['ticket_delete']!=0) echo '<a href="./index.php?page=ticket&id='.$_GET['id'].'&action=delete"><img align="right" style="margin:2px 10px 0px 0px; border-style: none;" alt="img" src="./images/delete_max.png" title="Supprimer ce ticket" /></a>';

		?>
		<a target="_blank" href="./index.php?page=ticket&id=<?php echo $_GET['id']; ?>&action=print"><img align="right" style="margin:2px 10px 0px 0px; border-style: none;" alt="img" src="./images/print.png" title="Imprimer" /></a>
		<?php if (($rright['planning']!=0) && ($rparameters['planning']==1))  echo "<img align=\"right\" style=\"margin:2px 10px 0px 0px; border-style: none;\"  alt=\"img\" src=\"./images/planning.png\" title=\"Planifier une intervention pour ce ticket\" onClick=\"window.open('./event_add.php?id=$_GET[id]&technician=$_SESSION[user_id]&planning=1','useradd','width=400,height=300')\"/></a>"; ?>
		<img align="right" style="margin:2px 10px 0px 0px; border-style: none;"  alt="img" src="./images/event.png" title="Créer un rappel pour ce ticket" onClick="window.open('./event_add.php?id=<?php echo $_GET['id']; ?>&technician=<?php echo $_SESSION['user_id']; ?>','useradd','width=350,height=300')"/></a>
		</h2>
		<br />
		<label  for="user">Demandeur:</label>
		<select class="textfield" id="user" name="user" onchange="submit();">
		   	<?php
			$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' ORDER BY lastname ASC, firstname ASC");
			while ($row=mysql_fetch_array($query)) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>";}
			//user selection
			if ($_POST['user'])	{$user=$_POST['user'];}	else {$user=$globalrow['user'];}
			$query = mysql_query("SELECT * FROM tusers WHERE id LIKE $user");
			$row = mysql_fetch_array($query);
			echo "<option selected value=\"$user\">$row[lastname] $row[firstname]</option>";
			?>
		</select>
		<img title="Ajouter un utilisateur" src="./images/plus.png" style="border-style: none" alt="img" value='useradd' onClick="window.open('./newticket_useradd.php','useradd','width=420,height=230')" />
		<img title="Modifier un utilisateur" src="./images/edit.png" style="border-style: none" alt="img" value='useredit' onClick="window.open('./newticket_useradd.php?id=<?php echo $globalrow['user']; ?>','useredit','width=420,height=230')" />
		<input title="Actualiser la liste" border="0" src="./images/actualiser.png" type="image" value="submit" align="absmiddle" /> 
	<?php
		//Display phone number if exist
		if ($_POST['user']) 
		{
			$query = mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$_POST[user]'"); 
		}
		else
		{
			$query = mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$globalrow[user]'"); 
		}
		$row=mysql_fetch_array($query);
		if ($row['phone']!="") echo "&nbsp;&nbsp;<img src=\"./images/tel.png\" border=\"0\" /> <b>$row[phone]</b>";
		if ($row['mail']!="") echo "&nbsp;<img title=\"$row[mail]\" src=\"./images/mail_min.png\" border=\"0\" />";
		
		//other demands for this user 
		if($_POST['user']) $umodif=$_POST['user']; else  $umodif=$globalrow['user'];
		$qn = mysql_query("SELECT count(*) FROM `tincidents` WHERE user LIKE '$umodif' and (state='1' OR state='2') and id NOT LIKE $_GET[id] and disable=0"); 
		while ($rn=mysql_fetch_array($qn))
		$rnn=$rn[0];
		if ($rnn!=0) echo "&nbsp;&nbsp;<i>(";
		$c=0;
		$q = mysql_query("SELECT * FROM `tincidents` WHERE user LIKE '$umodif' and (state='1' OR state='2') and id NOT LIKE $_GET[id] and disable=0"); 
		while (($r=mysql_fetch_array($q)) && ($c<7)) {	
			$c=$c+1;
			echo "<a title=\"$r[title]\" href=\"./index.php?page=ticket&amp;id=$r[id]\">$r[id]</a>";
			if ($c<$rnn) echo ",";
			if ($c==7) echo "...";
		}  
		if ($rnn!=0) echo ")</i>";
		?>
		<br />
		<label for="technician">Technicien:</label>
		<select class="textfield" id="technician" name="technician" >
		   	<?php
			if ($_POST['technician'])
			{
				echo "coucou";
				$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$_POST[technician]' ");
			} else {
				$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$globalrow[technician]' ");		
			}
			$row=mysql_fetch_array($querytech);
			echo "<option value=\"$row[id]\" selected >$row[lastname] $row[firstname]</option>"; 
			$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and profile LIKE '0' and id!='$globalrow[technician]' ORDER BY lastname ASC, firstname ASC") ;
			while ($row=mysql_fetch_array($query)) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname] </option>";} 
			?>
		</select>
		<?php
		if (($globalrow['creator']!=$globalrow['technician']) && ($globalrow['creator']!="0") )
		{
			// select creator name
			$query = mysql_query("SELECT firstname FROM `tusers` WHERE id LIKE '$globalrow[creator]'");
			$row=mysql_fetch_array($query);
			echo "<img src=\"./images/admin.png\" border=\"0\" /> Ouvert par $row[0]";
		}
		?>
		<br />
		<label for="category">Catégorie:</label>
		<select class="textfield" id="category" name="category" onchange="submit();">
		<?php
			$query= mysql_query("SELECT * FROM `tcategory` order by name ");
			while ($row=mysql_fetch_array($query)) 
			{
				echo "<option value=\"$row[id]\">$row[name]</option>";
				if ($_POST['category'])
				{
					if ($_POST['category']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>";
				}
				else
				{
					if ($globalrow['category']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>";
				}
			}
			if ($globalrow['category']==0 && $_POST['category']==0) echo "<option value=\"\" selected></option>";
		?>
		</select>
		<select class="textfield" id="subcat" name="subcat" >
		<?php
			if ($_POST['category'])
			{$query= mysql_query("SELECT * FROM `tsubcat` WHERE cat LIKE '$_POST[category]' order by name ASC");}
			else
			{$query= mysql_query("SELECT * FROM `tsubcat` WHERE cat LIKE '$globalrow[category]' order by name ASC");}
			
			while ($row=mysql_fetch_array($query)) 
			{
				echo "<option value=\"$row[id]\">$row[name]</option>";
				if ($_POST['subcat'])
				{
					if ($_POST['subcat']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>";
				}
				else
				{
					if ($globalrow['subcat']==$row['id']) echo "<option value=\"$row[id]\" selected>$row[name]</option>";
				}
			} 
			if ($globalrow['subcat']==0 && $_POST['subcat']==0) echo "<option value=\"\" selected></option>";
		?>
		</select>
		<img title="Ajouter une categorie" src="./images/plus.png" style="border-style: none" alt="img" value='useradd' onClick="window.open('./edit_categories.php?cat=<?php echo $globalrow['category']; ?>','useradd','width=400,height=200')" />
		<img title="Modifier une categorie" src="./images/edit.png" style="border-style: none" alt="img" value='useredit' onClick="window.open('./edit_categories.php?cat=<?php echo $_POST['category']; ?>&subcat=<?php echo $_POST['subcat']; ?>','useredit','width=400,height=200')" />
		<input title="Actualiser la liste" border="0" src="./images/actualiser.png" type="image" value="submit" align="absmiddle" /> 
		<br />
		<label for="title">Titre:</label>
		<input class="textfield" name="title" id="title" type="text" size="50"  value="<?php if ($_POST['title']) echo $_POST['title']; else echo $globalrow['title']; ?>" />
		<br />
		<label for="description">Descritption:</label>
		<br />
		<textarea class="textfield" id="description" name="description" cols="100" rows="2" ><?php if ($_POST['description']) echo $_POST['description']; else echo $globalrow['description']; ?></textarea>
		<?php include "./attachement.php";?>
		<br /><br />
		<label for="resolution">Résolution:</label>
		<br />
		&nbsp;&nbsp;<img title="Insérer la date et l'heure du jour" src="./images/date.png" onclick="insertAtCaret('resolution','<?php if ($uid!=$globalrow['technician'])  echo "$today $reqfname[firstname]:"; else echo "$today: ";?>');" /><br />
		<textarea class="textfield" id="resolution" name="resolution" cols="100" rows="2" ><?php if ($_POST['resolution']) echo $_POST['resolution']; else echo $globalrow['resolution']; ?></textarea>
		<br />
		<label for="date_create">Date de la demande:</label>
		<input style="display: inline;" class="textfield" type='text' name='date_create' value="<?php if ($_POST['date_create']) echo $_POST['date_create']; else echo $globalrow['date_create']; ?>" ><img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=form&elem=date_create','Calendrier','width=400,height=400')">
		<br />
		<label for="date_hope">Date de résolution estimée:</label>
		<input style="display: inline;" class="textfield" type='text' name='date_hope'  value="<?php  if ($_POST['date_hope']) echo $_POST['date_hope']; else echo $globalrow['date_hope']; ?>" ><img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=form&elem=date_hope','Calendrier','width=400,height=400')">
		<?php
			//display warning if hope date is passed
			$date_hope=$globalrow['date_hope'];
			$querydiff=mysql_query("SELECT DATEDIFF(NOW(), '$date_hope') "); 
			$resultdiff=mysql_fetch_array($querydiff);
			if ($resultdiff[0]>0 && ($globalrow['state']!="3" && $globalrow['state']!="4")) echo "<img border=\"0\" title=\"Date de résolution dépassée de $resultdiff[0] jours de retard\" src=\"./images/warning_min.png\" />";
			
		?>
		<br />
		<?php
			if ($globalrow['date_res']!="0000-00-00")
			{
				echo "
					<label for=\"date_res\">Date de résolution :</label>
					<input style=\"display: inline;\" class=\"textfield\" type='text' name='date_res'  value=".$globalrow['date_res']."  >
					<br />
				";
			}
		?>
		<label for="time">Temps passé:</label>
		<select class="textfield" id="time" name="time" >
		<?php
			$query = mysql_query("SELECT * FROM `ttime` order by min ASC");
			while ($row=mysql_fetch_array($query)) 
			{
				echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
				if (($_POST['time']==$row['min'])||($globalrow['time']==$row['min'])) echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>'; 
			}
		?>
		</select>
		<br />
		<label for="time">Temps estimé:</label>
		<select class="textfield" id="time_hope" name="time_hope" >
		<?php
			$query = mysql_query("SELECT * FROM `ttime` order by min ASC");
			while ($row=mysql_fetch_array($query)) 
			{
				echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
				if (($_POST['time_hope']==$row['min'])||($globalrow['time_hope']==$row['min'])) echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>'; 
			}
		?>
		</select>
		<?php
			//display error if time hope < time pass
		
			if (($globalrow['time_hope']<$globalrow['time']) && $globalrow['state']!='3') echo "<img border=\"0\" title=\"La durée estimée est inférieur à la durée dèja passé.\" src=\"./images/critical_min.png\" />";
			
		?>
		<br />
		<label for="priority">Priorité:</label>
		<select class="textfield" id="priority" name="priority" >
		   	<?php
			if ($_POST['priority'])
			{
				$query = mysql_query("SELECT * FROM `tpriority` WHERE number LIKE '$_POST[priority]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$_POST[priority]\" selected >$row[name]</option>";
			}
			else
			{
				$query = mysql_query("SELECT * FROM `tpriority` WHERE number LIKE '$globalrow[priority]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$globalrow[priority]\" selected >$row[name]</option>";
			}			
			$query = mysql_query("SELECT * FROM `tpriority`");
			while ($row=mysql_fetch_array($query)) echo "<option value=\"$row[number]\">$row[name]</option>"; 
			?>			
		</select>
		<br />
		<label for="priority">Criticité:</label>
		<select class="textfield" id="criticality" name="criticality" >
		   	<?php
			if ($_POST['criticality'])
			{
				$query = mysql_query("SELECT * FROM `tcriticality` WHERE id LIKE '$_POST[criticality]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$_POST[criticality]\" selected >$row[name]</option>";
			}
			else
			{
				$query = mysql_query("SELECT * FROM `tcriticality` WHERE id LIKE '$globalrow[criticality]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$globalrow[criticality]\" selected >$row[name]</option>";
			}			
			$query = mysql_query("SELECT * FROM `tcriticality` ORDER BY number");
			while ($row=mysql_fetch_array($query)) echo "<option value=\"$row[id]\">$row[name]</option>"; 
			?>			
		</select>
		<?php
		//Display criticality picture
		$query = mysql_query("SELECT * FROM `tcriticality` WHERE id LIKE '$globalrow[criticality]'");
		$row=mysql_fetch_array($query);
		echo "<img style=\"border-style: none\" alt=\"img\" src=\"./images/critical_$row[color].png\" />";
		?>
		<br />
		<label for="state">État:</label>
		<select class="textfield" id="state"  name="state" >
		   	<?php
			if ($_POST['state'])
			{
				$query = mysql_query("SELECT * FROM `tstates` WHERE id LIKE '$_POST[state]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$_POST[state]\" selected >$row[name]</option>";
			}
			else
			{
				$query = mysql_query("SELECT * FROM `tstates` WHERE id LIKE '$globalrow[state]'");
				$row=mysql_fetch_array($query);
				echo "<option value=\"$globalrow[state]\" selected >$row[name]</option>";
			}			
			$query = mysql_query("SELECT * FROM `tstates` ORDER BY number");
			while ($row=mysql_fetch_array($query)) echo "<option value=\"$row[id]\">$row[name]</option>"; 
			?>
		</select>
		<img style="border-style: none" alt="img" src="./images/<?php echo $globalrow['state']; ?>.png" />
		<br /><br /><br />
		<div  class="buttons">
			<button name="modify" value="Enregistrer" type="submit"  class="positive"  id="modify">
				<img src="images/apply2.png" alt=""/>
				Enregistrer
			</button>
			
			<button name="quit" value="Enregistrer et Fermer" type="submit" class="positive" id="quit">	
				<img src="images/apply2.png" alt=""/>
				Enregistrer et Fermer
			</button>

			<button value="Mail" type="submit" class="regular" name="mail" id="mail">
			 <img src="images/mail_icn.png" alt=""/>
				Envoyer un mail
			</button>

			<button value="cancel" type="submit" class="negative" name="cancel" id="cancel">
				<img src="images/cross.png" alt=""/>
				Annuler
			</button>
		</div>
<br /><br /><br />

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