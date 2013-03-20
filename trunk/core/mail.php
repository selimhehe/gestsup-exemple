<?php
/*
Author: Flox
Filename: /core/mail.php
Description: page to send mail
Parameters: ticket id
call: auto_mail, preview_mail
Version: 2.0
Last update: 26/01/2013
*/

// initialize variables 
if(!isset($_POST['usercopy'])) $_POST['usercopy'] = '';
if(!isset($_POST['usercopy2'])) $_POST['usercopy2'] = '';
if(!isset($_POST['usercopy3'])) $_POST['usercopy3'] = '';
if(!isset($_POST['usercopy4'])) $_POST['usercopy4'] = '';
if(!isset($fname11)) $fname11 = '';
if(!isset($fname21)) $fname21 = '';
if(!isset($fname31)) $fname31 = '';


//database queries to find values for create mail	
$globalquery = mysql_query("SELECT * FROM tincidents WHERE id LIKE '$_GET[id]'");
$globalrow=mysql_fetch_array($globalquery);
	
$userquery = mysql_query("SELECT * FROM tusers WHERE id LIKE '$globalrow[user]'");
$userrow=mysql_fetch_array($userquery);	
	
$techquery = mysql_query("SELECT * FROM tusers WHERE id LIKE '$globalrow[technician]'");
$techrow=mysql_fetch_array($techquery);
	
$creatorquery = mysql_query("SELECT * FROM tusers WHERE id LIKE '$_SESSION[user_id]'");
$creatorrow=mysql_fetch_array($creatorquery);
	
$querystate = mysql_query("SELECT name FROM tstates WHERE id LIKE '$globalrow[state]'");
$staterow=mysql_fetch_array($querystate);
	
$querycat = mysql_query("SELECT * FROM tcategory WHERE id LIKE '$globalrow[category]'");
$catrow=mysql_fetch_array($querycat);
	
$querysubcat = mysql_query("SELECT * FROM tsubcat WHERE id LIKE '$globalrow[subcat]'");
$subcatrow=mysql_fetch_array($querysubcat);
	
//text format
$description = str_replace("\n", "<br>", $globalrow['description']);
$resolution = str_replace("\n", "<br>", $globalrow['resolution']);
	
//Dates conversions
$date_create = date_cnv("$globalrow[date_create]");
$date_hope = date_cnv("$globalrow[date_hope]");
$date_res = date_cnv("$globalrow[date_res]");
	
//Mail object for states
$qobject = mysql_query("SELECT * FROM tstates WHERE id LIKE '$globalrow[state]'");
$robject=mysql_fetch_array($qobject);
$objet="$robject[mail_object] pour le ticket n°$_GET[id]: $globalrow[title]";

$destinataire="$userrow[mail]";
$emetteur="$creatorrow[mail]";

//interger link parameter
if ($rparameters['mail_link']==1)
{
$link=", ou suivez votre ticket sur ce lien: <a href=\"http://$_SERVER[SERVER_NAME]\">http://$_SERVER[SERVER_NAME]</a>";
} else $link=".";
	
$msg="
	<html>
		<head>
		</head>
		<body>
			<font face=\"Arial\">
				<table  width=\"820px\" cellspacing=\"0\" >
					<tr bgcolor=\"$rparameters[mail_color_title]\" >
					  <th><br /><font size=\"4px\" color=\"FFFFFF\"> &nbsp; $objet &nbsp;</font><br /><br /></th>
					</tr>
					<tr bgcolor=\"$rparameters[mail_color_bg]\" >
					  <td>
						<br /><br />
						$rparameters[mail_txt]<br />
						<br />
						<table  border=\"1\" bordercolor=\"0075A4\" cellspacing=\"0\" width=\800px\">
							<tr>
								<td><font color=\"$rparameters[mail_color_text]\"><b>Titre:</b></b> $globalrow[title]</font></td>
								<td><font color=\"$rparameters[mail_color_text]\"><b>Catégorie:</b></b> $catrow[1] - $subcatrow[2]</td>
							</tr>
							<tr>
								<td width=\"400px\"><font color=\"$rparameters[mail_color_text]\"><b>Demandeur:</b></b> $userrow[lastname] $userrow[firstname]</font></td>
								<td width=\"400px\"><font color=\"$rparameters[mail_color_text]\"><b>Technicien en charge:</b> $techrow[lastname] $techrow[firstname]</font></td>
							</tr>
							<tr>
								<td><font color=\"$rparameters[mail_color_text]\"><b>Etat:</b> $staterow[0]</font></td>
								<td><font color=\"$rparameters[mail_color_text]\"><b>Date de la demande:</b> $date_create</font></td>	
							</tr> 
							<tr>
								<td colspan=\"2\"><font color=\"$rparameters[mail_color_text]\"><b>Description:</b><br /> $description</font></td>
								
							</tr>
							<tr>
								<td colspan=\"2\"><font color=\"$rparameters[mail_color_text]\"><b>Résolution:</b><br /> $resolution</font></td>
							</tr>
							<tr>
								<td width=\"400px\"><font color=\"$rparameters[mail_color_text]\"><b>Date estimée de résolution:</b></b> $date_hope</font></td>
								<td width=\"400px\"><font color=\"$rparameters[mail_color_text]\"><b>Date de résolution:</b> $date_res</font></td>
							</tr>
						</table>
						<br /><br /><br /><br />
						<hr />
						Pour toutes informations complémentaires sur votre ticket, vous pouvez joindre $techrow[firstname] $techrow[lastname] au $techrow[phone]
						$link
						<hr />
					  </td>
					</tr>
				</table>
			</font>
		</body>
	</html>"."\r\n";

if ($send==1)
{
	require("components/PHPMailer_v5.1/class.phpmailer.php"); 
	$mail = new PHPmailer();
	$mail->CharSet = 'UTF-8'; //UTF-8 possible if characters problems
	$mail->IsSendmail();
//	$mail->Host = "$rparameters[mail_smtp]";
//	$mail->SMTPAuth = $rparameters['mail_auth'];
//	if ($rparameters['mail_secure']=='465') $mail->SMTPSecure = 'ssl';
//	if ($rparameters['mail_secure']=='587') $mail->SMTPSecure = 'tls';
//	if ($rparameters['mail_secure']=='465') $mail->Port = 465;
//	if ($rparameters['mail_secure']=='587') $mail->Port = 587;
	$mail->Username = "$rparameters[mail_username]";
	$mail->Password = "$rparameters[mail_password]";
	$mail->IsHTML(true); // Envoi en html
	 
	// add picture
	// $mail->AddEmbeddedImage("chemin_image", "non_image", "cid_image");
	 
	$mail->From = "demande.me";
	$mail->FromName = "$rparameters[mail_from]";
	$mail->AddAddress("$userrow[mail]");
	$mail->AddReplyTo("$techrow[mail]");
	$mail->AddCC("$rparameters[mail_cc]");
	if ($_POST['usercopy']!='') $mail->AddCC("$_POST[usercopy]");
	if ($_POST['usercopy2']!='') $mail->AddCC("$_POST[usercopy2]");
	if ($_POST['usercopy3']!='') $mail->AddCC("$_POST[usercopy3]");
	if ($_POST['usercopy4']!='') $mail->AddCC("$_POST[usercopy4]");
	if ($globalrow['img1']!='')$mail->AddAttachment("./upload/$_GET[id]/$globalrow[img1]");
	if ($globalrow['img2']!='')$mail->AddAttachment("./upload/$_GET[id]/$globalrow[img2]");
	if ($globalrow['img3']!='')$mail->AddAttachment("./upload/$_GET[id]/$globalrow[img3]");
	$mail->Subject = "$objet";
	$mail->Body = "$msg";
	if (!$mail->Send()){
	echo '<div id="erreur"><img src="./images/access.png" alt="erreur" style="border-style: none" alt="img" />';
	echo $mail->ErrorInfo;
	echo '</div>';
	}
	else {
	echo "<center><div id=\"valide\"><img src=\"./images/mail.png\" border=\"0\" /> Message envoyé.</div></center>";
				echo "
				<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php?page=dashboard&techid=$globalrow[technician]&state=1'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
				</SCRIPT>
				";
	}
	//$mail->SmtpClose();
}


///////Functions

// Date conversion
function date_cnv ($date) 
{return substr($date,8,2) . "/" . substr($date,5,2) . "/" . substr($date,0,4);}
?>