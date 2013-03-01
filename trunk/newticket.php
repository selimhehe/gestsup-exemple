<?php
/*
Author: Flox
FileName: newticket.php
Description: page to create ticket
Version: 1.5
Last update: 15/01/2013
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
if(!isset($id)) $id = '';
if(!isset($id_in)) $id_in = '';
if(!isset($globalrow['time'])) $globalrow['time'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['upload'])) $_POST['upload'] = '';
if(!isset($_POST['description'])) $_POST['description'] = '';
if(!isset($_POST['resolution'])) $_POST['resolution'] = '';
if(!isset($_POST['save'])) $_POST['save'] = '';
if(!isset($_POST['quit'])) $_POST['quit'] = '';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['time'])) $_POST['time'] = '';
if(!isset($_POST['time_hope'])) $_POST['time_hope'] = '';
if(!isset($_POST['priority'])) $_POST['priority'] = '';
if(!isset($_POST['criticality'])) $_POST['criticality'] = '';
if(!isset($_POST['state'])) $_POST['state'] = '';
if(!isset($_POST['title'])) $_POST['title'] = '';
if(!isset($_POST['date_hope'])) $_POST['date_hope'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_GET['category'])) $_GET['category'] = '';
if(!isset($_GET['subcat'])) $_GET['subcat'] = '';

//Default values
if(!isset($_POST['technician'])) $_POST['technician'] = $_SESSION['user_id'];


// find incident number  
$query = mysql_query("SELECT MAX(id) FROM tincidents");
$row=mysql_fetch_array($query);
$number =$row[0]+1;

// find current date & hour 
$hour = date("H:i");
$date = date("d/m/Y");
$today = "$date $hour: ";

//Database inputs if submit
if($_POST['save']||$_POST['mail']||$_POST['quit']||$_POST['upload']) 
{
	// special char in sql query
	$_POST['description']  = str_replace('\\','\\\\',$_POST['description']);
	$_POST['resolution']  = str_replace("\\","\\\\",$_POST['resolution']);
	$_POST['title']  = str_replace("\\","\\\\",$_POST['title']);
	$_POST['description'] = str_replace("'","\'",$_POST['description']); 
	$_POST['resolution']  = str_replace("'","\'",$_POST['resolution']); 
	$_POST['title']  = str_replace("'","\'",$_POST['title']);	
	
	//insert resolution date if state is res
	if ($_POST['state']=='3') $dateres=date("Y-m-d");
	
	//unread ticket case when creator is not techncian
	if($_POST['technician']!= $_SESSION['user_id']) $techread=0; else $techread=1;
	
	$query= "INSERT INTO tincidents (user,technician,title,description,resolution,date_create,date_hope,date_res,priority,criticality,state,creator,time,time_hope,category,subcat,techread) VALUES ('$_POST[user]','$_POST[technician]','$_POST[title]','$_POST[description]','$_POST[resolution]','$_POST[date_create]','$_POST[date_hope]','$dateres','$_POST[priority]','$_POST[criticality]','$_POST[state]','$_SESSION[user_id]','$_POST[time]','$_POST[time_hope]','$_POST[category]','$_POST[subcat]','$techread')";
	$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
	
	// files upload  
	include "./core/upload.php";
	
	//auto send mail
	if(($rparameters['mail_auto']==1)&&($_POST['upload']=='')){include('./core/auto_mail.php');}
	
	// send mail
	if($_POST['mail'])
	{
		// redirect
		$www = "./index.php?page=preview_mail&id=$number";
		echo '<script language="Javascript">
		<!--
		document.location.replace("'.$www.'");
		// -->
		</script>';
	}
	else if ($_POST['quit'])
	{
		echo "<div id=\"valide\"><img src=\"./images/save.png\" border=\"0\" /> Ticket sauvegardé.</div>";
		 // redirect
		$www = "./index.php?page=dashboard&techid=$_SESSION[user_id]&state=1";
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
	else if ($_POST['save']||$_POST['upload']!='')
	{
		echo "<div id=\"valide\"><img src=\"./images/save.png\" border=\"0\" /> Ticket sauvegardé.</div>";
		 // redirect
		$www = "./index.php?page=ticket&id=$number";
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
<div id="catalogue">
	<form name="thisform" enctype="multipart/form-data" method="post" action="" id="thisform">
		<h2 class="sec_head"><img src="./images/create-ticket-icon.png" />
			Ouverture du ticket n°<?php echo $number; ?>
			<img align="right" title="Créer ce ticket à partir d'un modèle d'incident" src="./images/template.png" style="border-style: none" alt="img" value='useradd' onClick="window.open('./newticket_template.php?id=<?php echo $number; ?>&uid=<?php echo $_SESSION['user_id']; ?>','useradd','width=400,height=130')" />
		</h2>
		<br />
		<label for="user">Demandeur:</label>
		<select class="textfield" id="user" name="user" onchange="submit();">
		   	<?php
			//case for url parameter $user
			if ($userreg)
			{
				$query= mysql_query("SELECT * FROM `tusers` where id like '$userreg' ");
				$row=mysql_fetch_array($query);
				echo "<option value=$row[id] selected>$row[lastname] $row[firstname] </option>";
			}
			$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' ORDER BY lastname ASC, firstname ASC");
			while ($row=mysql_fetch_array($query)) echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>"; 
			$query = mysql_query("SELECT * FROM tusers WHERE id LIKE '$_POST[user]'");
			$row = mysql_fetch_array($query);
			if ($userreg=="") {echo "<option selected value=\"$_POST[user]\">$row[lastname] $row[firstname] </option>";}
			?>
		</select>
		<img title="Ajouter un utilisateur" src="./images/plus.png" style="border-style: none" alt="img" value='useradd' onClick="window.open('./newticket_useradd.php','useradd','width=420,height=230')" />
		<img title="Modifier un utilisateur" src="./images/edit.png" style="border-style: none" alt="img" value='useredit' onClick="window.open('./newticket_useradd.php?id=<?php echo $_POST['user']; ?>','useredit','width=420,height=230')" />
		<input title="Actualiser la liste" border="0" src="./images/actualiser.png" type="image" value="submit" align="absmiddle" /> 
		<?php
		//Display phone number if exist
		$post_user=$_POST['user'];
		$query = mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$post_user' or  id LIKE '$userreg'");
		$row=mysql_fetch_array($query);
		if ($row['phone']!="") echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/tel.png\" border=\"0\" /> <b>$row[phone]</b>&nbsp;";
		if ($row['mail']!="") echo '<img title="L\'adresse mail est bien renseignée.('.$row['mail'].')" src="./images/mail_min.png" style="border-style: none" alt="img" />';
		
		//other demands for this user 
		if($_POST['user'])
		{
			$qn = mysql_query("SELECT count(*) FROM `tincidents` WHERE user LIKE '$_POST[user]' and (state='1' OR state='2') and disable=0"); 
			while ($rn=mysql_fetch_array($qn))
			$rnn=$rn[0];
			if ($rnn!=0) echo "&nbsp;&nbsp;<i>(";
			$c=0;
			$q = mysql_query("SELECT * FROM `tincidents` WHERE user LIKE '$_POST[user]' and (state='1' OR state='2') and disable=0"); 
			while (($r=mysql_fetch_array($q)) && ($c<7)) {	
				$c=$c+1;
				echo "<a title=\"$r[title]\" href=\"./index.php?page=ticket&amp;id=$r[id]\">$r[id]</a>";
				if ($c<$rnn) echo ",";
				if ($c==7) echo "...";
			}  
			if ($rnn!=0) echo ")</i>";
		}
		?>
		<br />
		<label for="technician">Technicien:</label>
		<select class="textfield" id="technician" name="technician" onchange="submit();">
		   	<?php
			if ($_POST['technician']!='')
			{
				$querytech = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and id LIKE '$_POST[technician]' ");
				$row=mysql_fetch_array($querytech);
				echo "<option value=\"$row[id]\" selected >$row[lastname] $row[firstname]</option>"; 
				echo "<option value=\"\">Aucun</option>";
			
			} else {
				echo "<option value=\"\" Selected>Aucun</option>";
			}
			
			$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and profile LIKE '0' and id!='$_SESSION[user_id]' ORDER BY lastname ASC, firstname ASC");
			while ($row=mysql_fetch_array($query)) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>";} 
			?>
		</select>
		<br />
		<label for="category">Catégorie:</label>
		<select class="textfield" id="category" name="category" onchange="submit();">
		   	<?php
			//case for url parameter $category for reload list
			if ($_GET['category']) 
			{
				$query= mysql_query("SELECT * FROM `tcategory` where id like '$_GET[category]' ");
				$row=mysql_fetch_array($query);
				echo '<option value='.$row['id'].'selected>'.$row['name'].'</option>';
			}
			else {echo '<option value='.$row['id'].'selected>'.$row['name'].'</option>';}
			
			$query= mysql_query("SELECT * FROM `tcategory` order by name ");
			while ($row=mysql_fetch_array($query)) 
			{
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
				if ($_POST['category']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
			}
			?>
		</select>
		<select class="textfield" id="subcat" name="subcat" onchange="submit();">
		   	<?php
			if ($_GET['subcat']) 
			{
				$query= mysql_query("SELECT * FROM tsubcat WHERE id='$_POST[subcat]' ");
				$row=mysql_fetch_array($query);
				echo '<option value='.$row['id'].'selected>'.$row['name'].'</option>';
			}
			$query= mysql_query("SELECT * FROM `tsubcat` WHERE cat LIKE '$_POST[category]' order by name ASC");
			while ($row=mysql_fetch_array($query)) 
			{
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
				if ($_POST['subcat']==$row['id']) echo '<option value="'.$row['id'].'" selected>'.$row['name'].'</option>';
			} 
			?>
		</select>
		<img title="Ajouter une categorie" src="./images/plus.png" style="border-style: none" alt="img" value='useradd' onClick="window.open('./edit_categories.php?cat=<?php echo $_POST['category']; ?>','useradd','width=400,height=200')" />
		<img title="Modifier une categorie" src="./images/edit.png" style="border-style: none" alt="img" value='useredit' onClick="window.open('./edit_categories.php?cat=<?php echo $_POST['category']; ?>&amp;subcat=<?php echo $_POST['subcat']; ?>','useredit','width=400,height=200')" />
		<input title="Actualiser la liste" border="0" src="./images/actualiser.png" type="image" value="submit" align="absmiddle" /> 
		<br />
		<label for="title">Titre:</label>
		<input class="textfield" name="title" id="title" type="text" size="50" value="<?php echo $_POST['title']; ?>"/>
		<br /><br />
		<label for="description">Description:</label>
		<br />
		<textarea class="textfield" id="description" name="description" cols="100" rows="2"  ><?php echo $_POST['description']; ?></textarea>
		<?php include "./attachement.php"; ?>
		<br /><br />
		<label for="resolution">Résolution:</label>
		<br />&nbsp;&nbsp;<img title="Insérer la date et l'heure du jour" src="./images/date.png" onclick="insertAtCaret('resolution','<?php echo $today; ?>');" />
		<textarea class="textfield" id="resolution" name="resolution" cols="100" rows="2"><?php echo $_POST['resolution']; ?></textarea>
		<br />
		<label for="date_create">Date de la demande:</label>
		<input style="display:inline;" class="textfield" type='text' name='date_create'  value="<?php echo date("Y-m-d");?>" />
		<img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=thisform&amp;elem=date_create','Calendrier','width=400,height=400')" />
		<br />
		<label for="date_hope">Date de résolution estimée:</label>
		<input style="display: inline;" class="textfield" type='text' name='date_hope'  value="<?php echo $_POST['date_hope']; ?>" />
		<img src="./images/calendar.png" value='Calendrier' onClick="window.open('components/mycalendar/mycalendar.php?form=thisform&amp;elem=date_hope','Calendrier','width=400,height=400')" />
		<br />
		<label for="time">Temps passé:</label>
		<select class="textfield" id="time" name="time" > 
			<?php
			$query = mysql_query("SELECT * FROM `ttime` order by min ASC");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
				if ($_POST['time']==$row['min']) echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>'; 
			}
			?>
		</select>
		<br />
		<label for="time">Temps estimé:</label>
		<select class="textfield" id="time_hope" name="time_hope" > 
			<?php
			$query = mysql_query("SELECT * FROM `ttime` order by min ASC");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['min'].'">'.$row['name'].'</option>';
				if ($_POST['time_hope']==$row['min']) echo '<option selected value="'.$row['min'].'">'.$row['name'].'</option>'; 
			}
			?>
		</select>
		<br />
		<label for="priority">Priorité:</label>
		<select class="textfield" id="priority" name="priority" >
		   	<?php
			$query = mysql_query("SELECT * FROM `tpriority` order by number ASC");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['number'].'">'.$row['name'].'</option>'; 
				if ($_POST['priority']==$row['number']) echo '<option value="'.$row['number'].'">'.$row['name'].'</option>'; 
			}
			$q= mysql_query("SELECT * FROM `tpriority` where number=(select max(number) from tpriority)");
			$row=mysql_fetch_array($q);
			 echo '<option selected value="'.$row['number'].'">'.$row['name'].'</option>'; 
			?>		
		</select>
		<br />
		<label for="criticality">Criticité:</label>
		<select class="textfield" id="criticality" name="criticality" >
		   	<?php
			$query = mysql_query("SELECT * FROM `tcriticality` order by number ASC");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
				if ($_POST['priority']==$row['id']) echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
			}
			$q= mysql_query("SELECT * FROM `tcriticality` where number=(select max(number) from tcriticality)");
			$row=mysql_fetch_array($q);
			 echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>'; 
			?>		
		</select>
		<br />
		<label for="state">Etat:</label>
		<select class="textfield" id="state" name="state"  >
		   	<?php
			$query = mysql_query("SELECT * FROM `tstates` ORDER BY number");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['id'].'">'.$row['name'].'</option>'; 
				if ($_POST['state']==$row['id']) {
					echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';
				} elseif ($row['id']==1) {
					echo '<option selected value="'.$row['id'].'">'.$row['name'].'</option>';
				}
			}
			
			?>
		</select>
		<br />
<div  class="buttons">
<br /><br />
			<button name="save" value="Enregistrer" type="submit"  class="positive"  id="save">
				<img src="images/apply2.png" alt=""/>
				Enregistrer
			</button>
			
			<button name="quit" value="Enregistrer et Fermer" type="submit" class="positive" id="quit">	
				<img src="images/apply2.png" alt=""/>
				Enregistrer et Fermer
			</button>

			<button name="mail" value="Mail" type="submit" class="regular" name="mail" id="mail">
			 <img src="images/mail_icn.png" alt=""/>
				Envoyer un mail
			</button>

			<button name="cancel" value="cancel" type="submit" class="negative" name="cancel" id="cancel">
				<img src="images/cross.png" alt=""/>
				Annuler
			</button>
			<br /><br /><br /><br />
</div>

	</form>
</div>

<!-- Script allow autogrow textareas -->
<script type="text/javascript">
$('textarea').ata();
</script>

<script type="text/javascript">
        // jQuery en action
        jQuery.noConflict();
        jQuery('#jquery').addClass('jquery');

        // Prototype en action
        $('prototype').addClassName('prototype');
</script>

<SCRIPT type="text/javascript">
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