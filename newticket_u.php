<?php
/*
Author: Flox
FileName: newticket_u.php
Description: page to create ticket for users
Version: 1.1
Last update: 27/11/2012
*/

// initialize variables 
if(!isset($userreg)) $userreg = ''; 
if(!isset($title)) $title = ''; 
if(!isset($date_hope)) $date_hope = ''; 
if(!isset($description)) $description = ''; 
if(!isset($resolution)) $resolution = ''; 
if(!isset($priority)) $priority = '';
if(!isset($dateres)) $dateres = '';
if(!isset($subcat)) $subcat = '';
if(!isset($id)) $id = '';
if(!isset($id_in)) $id_in = '';
if(!isset($today)) $today = '';
if(!isset($globalrow['time'])) $globalrow['time'] = '';
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['description'])) $_POST['description'] = '';
if(!isset($_POST['resolution'])) $_POST['resolution'] = '';
if(!isset($_POST['save'])) $_POST['save'] = '';
if(!isset($_POST['quit'])) $_POST['quit'] = '';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['user'])) $_POST['user'] = '';
if(!isset($_POST['time'])) $_POST['time'] = '';
if(!isset($_POST['priority'])) $_POST['priority'] = '';
if(!isset($_POST['state'])) $_POST['state'] = '';
if(!isset($_POST['technician'])) $_POST['technician'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '';
if(!isset($state)) $state = '';

//date 
$today=date("Y-m-d");

// find incident number  
$query = mysql_query("SELECT MAX(id) FROM tincidents");
$row=mysql_fetch_array($query);
$number =$row[0]+1;

//Database inputs if submit
if($_POST['save']||$_POST['mail']||$_POST['quit']) 
{
	// special char in sql query
	$_POST['description']  = str_replace('\\','\\\\',$_POST['description']);
	$_POST['resolution']  = str_replace("\\","\\\\",$_POST['resolution']);
	$_POST['title']  = str_replace("\\","\\\\",$_POST['title']);
	$_POST['description'] = str_replace("'","\'",$_POST['description']); 
	$_POST['resolution']  = str_replace("'","\'",$_POST['resolution']); 
	$_POST['title']  = str_replace("'","\'",$_POST['title']);	
	
	//default values
	if($_POST['priority']=='') $_POST['priority']="0";
	
	// if technician is selected pass state to 1
	if ($_POST['technician']!="") $state=1; else $state=5;
	
	//insert resolution date if state is res
	if ($_POST['state']=='3') $dateres=date("Y-m-d");
	$query= "INSERT INTO tincidents (user,title,description,resolution,date_create,date_hope,date_res,priority,state,creator,time,category,subcat,techread,technician,criticality) 
	VALUES
	('$uid','$_POST[title]','$_POST[description]','','$today','','','$_POST[priority]','$state','$uid','0','$_POST[category]','$_POST[subcat]','0','$_POST[technician]','4')";
	$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
	
	//files upload  
	include "./core/upload.php";
	
	//send mail to admin if it's enable in parameters
	if ($rparameters['mail_newticket']==1)
	{
		//find username
		$userquery = mysql_query("SELECT * FROM tusers WHERE id='$uid'");
		$userrow=mysql_fetch_array($userquery);	
		
		//Mail parameters
		//if ($userrow['mail']!='') $from=$userrow['mail']; else $from=$rparameters['mail_cc'];
		//$to=$rparameters['mail_newticket_address'];
		$object="Une nouvelle demande à été déclarée par $userrow[lastname] $userrow[firstname]: $_POST[title]";
		$message = "
		La demande n°$number à été déclarée par l'utilisateur $userrow[lastname] $userrow[firstname].<br />
		<br />
		<u>Objet:</u><br />
		$_POST[title]<br />		
		<br />	
		<u>Description:</u><br />
		$_POST[description]<br />
		<br />
		Pour plus d'informations vous pouvez consulter le ticket sur <a href=\"http://$_SERVER[SERVER_NAME]/index.php?page=ticket&id=$number\">http://$_SERVER[SERVER_NAME]/index.php?page=ticket&id=$number</a>. ";
	//	require('./core/message.php');
       require("components/PHPMailer_v5.1/class.phpmailer.php");
       
      $reqAllEmailsAdminAndTechnicien = "SELECT mail FROM tusers WHERE profile in (4,0)";
      $intervenantsquery = mysql_query($reqAllEmailsAdminAndTechnicien);
        $mail = new PHPmailer();
        $mail->CharSet = 'utf-8'; //UTF-8 possible if characters problems
        $mail->IsSendmail();
        $mail->IsHTML(true); // Envoi en html
        $mail->From = "$rparameters[mail_from]";
        $mail->FromName = "$rparameters[mail_from]";
	      $mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = $object;
        $bodyMSG = $message;
        $mail->Body =$bodyMSG;
         while ($emailAddress = mysql_fetch_array($intervenantsquery))
        {
          $mail->AddAddress($emailAddress[mail]);
	        $mail->Send();
        }


        $mail->ClearAddresses();
     
    // Send mail au responsable
    if($_SESSION['profile'] == '1'){
      $queryResponsable = "SELECT * FROM tusers as u, tcompany as c WHERE u.id = c.responsible and u.group_id ='$userrow[group_id]'";
   //   echo $queryResponsable;
      $responsablequery = mysql_query($queryResponsable);
		  $resposanblerow=mysql_fetch_array($responsablequery);
      //   echo "resp row : ".$resposanblerow;
      if($resposanblerow != "")
      {
//         echo $queryResponsable;
//         echo "<br />";
//         echo $resposanblerow;
        $email_to_send = $resposanblerow['mail'];
     //   echo "email : ".$email_to_send;
        $mail2 = new PHPmailer();
        $mail2->CharSet = 'utf-8'; //UTF-8 possible if characters problems
       // $mail->IsMail();
        $mail2->IsSendmail();
        $mail2->IsHTML(true); // Envoi en html

        $mail2->From = "$rparameters[mail_from]";
        $mail2->FromName = "$rparameters[mail_from]";

        $mail2->AddAddress($email_to_send);
	      $mail2->AddReplyTo("$rparameters[mail_from]");
        $mail2->Subject = $object;
        $bodyMSG = $message;
        $mail2->Body = $bodyMSG;
        $mail2->Send();
        $mail2->ClearAddresses();
      }

    }
    


	} 
	
	if ($_POST['quit'])
	{
		echo "<div id=\"valide\"><img src=\"./images/save.png\" border=\"0\" /> Ticket n°$number envoyé.</div>";
			
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
}
?>
<div id="catalogue">
	<form name="thisform" enctype="multipart/form-data" method="post" action="" id="thisform">
		<h2 class="sec_head"><img src="./images/create-ticket-icon.png" /> Ouverture du ticket n°<?php echo $number; ?></h2>
		<br />
		<label for="user">Demandeur:</label>
		<?php echo "$reqfname[lastname] $reqfname[firstname]"; 

		//Display phone number if exist
		$query = mysql_query("SELECT * FROM `tusers` WHERE id LIKE '$_POST[user]' or  id LIKE '$userreg'");
		$row=mysql_fetch_array($query);
		if ($row['phone']!="") echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/tel.png\" border=\"0\" /> <b>$row[phone]</b>&nbsp;";
		if ($row['mail']!="") echo '<img title="L\'adresse mail est bien renseignée.('.$row['mail'].')" src="./images/mail_min.png" style="border-style: none" alt="img" /> '.$row['mail'].'';
		
		//other demands for this user 
		if($_POST['user'])
		{
			$qn = mysql_query("SELECT count(*) FROM `tincidents` WHERE user LIKE '$_POST[user]' and (state='1' OR state='2')"); 
			while ($rn=mysql_fetch_array($qn))
			$rnn=$rn[0];
			if ($rnn!=0) echo "&nbsp;&nbsp;<i>(";
			$c=0;
			$q = mysql_query("SELECT * FROM `tincidents` WHERE user LIKE '$_POST[user]' and (state='1' OR state='2')"); 
			while (($r=mysql_fetch_array($q)) && ($c<3)) {	
				$c=$c+1;
				echo "<a title=\"$r[title]\" href=\"./index.php?page=ticket&amp;id=$r[id]\">$r[id]</a>";
				if ($c<$rnn) echo ",";
				if ($c==3) echo "...";
			}  
			if ($rnn!=0) echo ")</i>";
		}
		?>
		<br />
		<?php 
		if ($_SESSION['profile_id']==0 || $_SESSION['profile_id']==4)
		{
			echo "<label for=\"technician\">Technicien:</label>
			<select class=\"textfield\" id=\"technician\" name=\"technician\">";
			if ($_POST['technician'])
			{
			$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and profile LIKE '0' and id='$_POST[technician]'");
			$row=mysql_fetch_array($query);
			echo "<option selected value=\"$row[id]\">$row[lastname] $row[firstname]</option>";
			} else {
			echo "<option selected value=\"\">Aucun</option>";
			}
				$query = mysql_query("SELECT * FROM `tusers` WHERE disable='0' and profile LIKE '0'");
				while ($row=mysql_fetch_array($query)) {echo "<option value=\"$row[id]\">$row[lastname] $row[firstname]</option>";} 
				
			echo"
			</select>";
		}
		?>
		<br />
		<label for="category">Catégorie:</label>
		<select class="textfield" id="category" name="category" onchange="submit();">
		   	<?php
			//case for url parameter $category
			if ($_POST['category']) 
			{
				$query= mysql_query("SELECT * FROM `tcategory` where id like '$_POST[category]' ");
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
			//case for url parameter $subcat
			if ($subcat=="1") 
			{
				$query= mysql_query("SELECT * FROM tsubcat where id = (select max(id) from tsubcat)");
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
		<br />
		<label for="title">Titre:</label>
		<input class="textfield" name="title" id="title" type="text" size="70" value="<?php echo $title; ?>"/>
		<br /><br />
		<label for="description">Description:</label>
		<br />
		<textarea class="textfield" id="description" name="description" cols="100" rows="2"  ><?php echo $description; ?></textarea>
		<?php include "./attachement.php"; ?>
		<br /><br />
		<?php 
		if ($_SESSION['profile_id']==3) {echo '
		<label for="priority">Priorité:</label>
		<select class="textfield" id="priority" name="priority" >';
		   
			$query = mysql_query("SELECT * FROM `tpriority` order by number ASC");
			while ($row=mysql_fetch_array($query)) {
				echo '<option value="'.$row['number'].'">'.$row['name'].'</option>'; 
				if ($_POST['priority']==$row['number']) echo '<option value="'.$row['number'].'">'.$row['name'].'</option>'; 
			}
			$q= mysql_query("SELECT * FROM `tpriority` where number=(select max(number) from tpriority)");
			$row=mysql_fetch_array($q);
			 echo '<option selected value="'.$row['number'].'">'.$row['name'].'</option>
		
		</select>
		<br />';
		}
		?>	
<div  class="buttons2">
<br /><br />
			
			<button name="quit" value="Enregistrer et Fermer" type="submit" class="positive" id="quit">	
				<img src="images/apply2.png" alt=""/>
				Envoyer
			</button>
<br /><br /><br />
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