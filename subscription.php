<?php
/*
Author: Flox
FileName: index.php
Description: main page include sub-pages
Version: 1.3
Creation date: 07/03/2010
Last update: 06/11/2012
*/

//starting session
session_start();

//initialize variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_SESSION['profile_id'])) $_SESSION['profile_id'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['keywords'])) $_GET['keywords'] = '';
if(!isset($_POST['keyword'])) $_POST['keyword'] = '';

//connexion script with database parameters
require "connect.php";

//Load parameters table
$qparameters = mysql_query("SELECT * FROM `tparameters`");
$rparameters= mysql_fetch_array($qparameters);

//Load variables
$daydate=date('Y-m-d');
$msg = "";
//if user is connected
if ($_SESSION['user_id'])
{
     echo "
				<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
				</SCRIPT>
				";
}

if(isset($_GET['code']) || isset($_POST['code'])){
  if (isset($_GET['code']))
  $code = (isset($_GET['code'])) ? $_GET['code'] : '';

  if (isset($_POST['code']))
  $code = (isset($_POST['code'])) ? $_POST['code'] : '';

  $query = mysql_query("SELECT code FROM tcompany where code='$code'");

	$r = mysql_fetch_array($query);
  if($r['0']=='')
		{
			echo "<div id='erreur'><img src='./images/access.png' alt='erreur' style='border-style: none' alt='img' /> Merci de v√©rifier votre code d'inscription.</div>";
      $www = "./codesubscription.php";
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

if (isset($_POST['submit'])){
 
  $password = $_POST['password'];
  $salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
	$_POST['password']=md5($salt . md5($_POST['password'])); // store in md5, md5 password + salt

  $email = $_POST['email'];

  $query = mysql_query("SELECT mail FROM tusers where mail='$email'");
  $r = mysql_fetch_array($query);
  if($r['0']=='')
	{
	  $requete = "INSERT INTO tusers (profile, code, civility, firstname,lastname,password,salt,mail,phone,mobil,company,numero_rue, address1,zip,city,login,service, code_tva, note, disable) VALUES (2, '$_POST[code]','$_POST[civility]', '$_POST[firstname]','$_POST[lastname]','$_POST[password]','$salt','$_POST[email]','$_POST[fixe]','$_POST[mobile]','$_POST[company]','$_POST[rue]','$_POST[address1]','$_POST[zip]','$_POST[vile]','$_POST[email]','$_POST[service]','$_POST[tva]','$_POST[note]', '1')";
		
    $execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());

    if($execution){

        $reqAllEmailsAdminAndTechnicien = mysql_query("select mail from tusers where profile in (4,0)");
        $allEmailsAdminAndTechnicien = mysql_fetch_array($reqAllEmailsAdminAndTechnicien);

        
        require("components/PHPMailer_v5.1/class.phpmailer.php");
        $mail = new PHPmailer();
        $mail->CharSet = 'UTF-8'; //UTF-8 possible if characters problems
        $mail->IsSMTP();
        $mail->Host = "$rparameters[mail_smtp]";
        $mail->SMTPAuth = $rparameters['mail_auth'];
        if ($rparameters['mail_secure']=='465') $mail->SMTPSecure = 'ssl';
        if ($rparameters['mail_secure']=='587') $mail->SMTPSecure = 'tls';
        if ($rparameters['mail_secure']=='465') $mail->Port = 465;
        if ($rparameters['mail_secure']=='587') $mail->Port = 587;
        $mail->Username = "$rparameters[mail_username]";
        $mail->Password = "$rparameters[mail_password]";


        $mail->IsHTML(true); // Envoi en html

        $mail->From = "$rparameters[mail_from]";
        $mail->FromName = "$rparameters[mail_from]";

        $mail->AddAddress("sahli28@gmail.cm");
	      $mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = "Nouvelle entrée dans le système.";
        $bodyMSG = "Bonjour , <br /><br />
         Nous vous remercions pour votre nouvelle demande dans le système.<br />
         Celle-ci sera prise en compte dans les prochaines heures.<br /><br />
         Voila votre nom d'utilisateur et mot de passe pour accéder à la plateforme <br />
         <b>Login </b> : $_POST[email]<br />
         <b>Mot de passe </b> : $password <br />";
        $mail->Body = "$bodyMSG";
        if (!$mail->Send()){
          $msg = '<div id="erreur"><img src="./images/access.png" alt="erreur" style="border-style: none" alt="img" />';
          $msg = $mail->ErrorInfo;
          $msg = '</div>';
        }
    	else {

        $mail->AddAddress("sahli28@gmail.cm");
	      $mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = "Nouvelle entrée dans le système.";
        $bodyMSG = "Bonjour , <br /><br />
         L’utilisateur : $_POST[email] a déposé une nouvelle demande dans le système.<br /><br />
         Vous pouvez valider ou réfuser son compte sur le lien suivant : <a href=\"http://$_SERVER[SERVER_NAME]/index.php?page=admin&subpage=user&profileid=ND\">http://$_SERVER[SERVER_NAME]/index.php?page=admin&subpage=user&profileid=ND</a> <br />";        
        
        $mail->Body = "$bodyMSG";
        $mail->Send();

      echo "<center><div id=\"valide\"><img src=\"./images/mail.png\" border=\"0\" /> Message envoyé.</div></center>";
				echo "
				<SCRIPT LANGUAGE='JavaScript'>
				<!--
				function redirect()
				{
				window.location='./index.php'
				}
				setTimeout('redirect()',$rparameters[time_display_msg]);
				-->
				</SCRIPT>
				";

          $msg = "message envoyé";
        }
        

    } else {
      $msg = "Un problème est survenue lors de la création de compte";
    }
    
	} else {
	  $msg = "Adresse email existe déjà";
	}  
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GestSup | Gestion de Support</title>
<link rel="shortcut icon" type="image/ico" href="images/favicon.ico" />
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="./components/lightbox2.05/css/lightbox.css" rel="stylesheet" type="text/css" media="screen" />


<script type="text/javascript" src="js/jquery.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jquery.ata.js" charset="utf-8"></script>
<script type="text/javascript" src="js/jqModal.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>

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
			</div>
			<div class="clear"></div>

		</div>
		<div id="content">
      
		<center>

<h2 class="sec_head" style="margin-top:0px">Inscription</h2>
<?php
 if($msg != ""){
   echo "<div class='error'>$msg</div>";
 }
?>
<div id="catalogue" style="width:530px;">
  
	<form id="myForm" name="myForm" method="post"  action="">
		<table width="100%">
			<tr>
				 <th colspan="2" ><img alt="user" src="./images/user.png" sytle="border-style:none;"/>   Inscription utilisateur</th>
			<tr>
			<tr>
				<td width="200"><label for="mail"><span class="required validate-email">*</span>E-mail:</label></td>
				<td><input name="email" id="email" type="text" class="required email"  value="<?php echo $_POST['email']; ?>" size="20" /></td>
			</tr>
       <tr>
				<td width="200"><label for="password"><span class="required">*</span>Mot de passe :</label></td>
				<td><input name="password" id="password" type="password" class="required"  value="<?php echo $password; ?>" size="20" /></td>
			</tr>
        <tr>
				<td width="220"><label for="confirm_password"><span class="required">*</span>Confirmation mot de passe :</label></td>
				<td><input name="confirm_password" id="confirm_password" type="password" class="required"  value="<?php echo $_POST['confirm_password']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="civilite"><span class="required">*</span>Civilité:</label></td>
				<td>
          <select name="civilite">
            <option>M.</option>
            <option>Mme</option>
            <option>Mlle</option>
          </select>
					
					
				</td>
			</tr>
			<tr>
				<td><label for="firstname"><span class="required validate" required>*</span>Nom:</label></td>
				<td><input name="firstname" id="firstname" type="text" class="required"  value="<?php echo $_POST['firstname']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="lastname"><span class="required">*</span>Prénom:</label></td>
				<td><input name="lastname" id="lastname" type="text" class="required"  value="<?php echo $_POST['lastname']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="fixe">Tél. fixe:</label></td>
				<td><input name="fixe" id="fixe" type="text"  value="<?php echo $_POST['fixe']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="mobile"><span class="required">*</span>Tél. mobile:</label></td>
				<td><input name="mobile" id="mobile" type="text" class="required"  value="<?php echo $_POST['mobile']; ?>" size="20" /></td>
			</tr>
			<tr>
				<td><label for="ville"><span class="required">*</span>Ville:</label></td>
				<td><input name="ville" id="ville" type="text" class="required"  value="<?php echo $_POST['ville']; ?>" size="20" /></td>
			</tr>
        <tr>
				<td><label for="rue">N° rue :</label></td>
				<td><input name="rue" id="rue" type="text" size="20" value="<?php echo $_POST['rue']; ?>" /></td>
			</tr>
        <tr>
				<td><label for="Adresse">Adresse :</label></td>
				<td><input name="address1" id="address1" type="text" size="20" value="<?php echo $_POST['address1']; ?>" /></td>
			</tr>
			<tr>
				<td><label for="cp">Code postal :</label></td>
				<td><input name="zip" id="zip" type="text"  size="20" value="<?php echo $_POST['zip']; ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="note">Note:</label></td>
				<td><input name="note" id="note" type="text"  size="20" value="<?php echo $_POST['note']; ?>" /></td>
			</tr>
			<tr>
				<td><label for="tva">N° TVA :</label></td>
				<td><input name="tva" id="tva" type="text"   size="20" value="<?php echo $_POST['tva']; ?>" /></td>
			</tr>

        <tr>
				<td><label for="societe">Société :</label></td>
				<td><input name="company" id="company" type="text" size="20" value="<?php echo $_POST['company']; ?>" /></td>
			</tr>

        <tr>
				<td><label for="service">Service :</label></td>
				<td><input name="service" id="service" type="text" size="20" value="<?php echo $_POST['service']; ?>" /></td>
			</tr>

		</table>

    <div class="buttons1">
      <input type="hidden" class="textbox" id="code" name="code" value="<?php echo $_GET['code']; ?>" />
										<button name="submit" value="Enregistrer" type="submit"  class="positive"  id="submit">
											<img src="images/apply2.png" alt=""/>
											Valider
										</button>
									</div>
                  <div class="newUser"><a href="index.php">Retour</a></div>
	</form>
</div>
</center>
		
			<?php
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

  <script language="javascript">
    jQuery.extend(jQuery.validator.messages, {
    required: "Ce champ est obligatoire",
		equalTo: "Please enter the same password as above",
    minlength: "Min 5 caractères",
    email: "Adresse email n'est pas valide"
    });

	$(document).ready(function() {
    //  $("#myForm").validate();
      $("#myForm").validate({
        rules: {
          password: {
				required: true,
				minlength: 5
			},
          confirm_password: {
				required: true,
				minlength: 5,
				equalTo: "#password"
			}
        },
        messages: {
			
			password: {
				required: "Ce champ est obligatoire",
				minlength: "Min 5 caractères",
				equalTo: "Vérifier votre mot de passe"
			},
			confirm_password: {
				required: "Ce champ est obligatoire",
				minlength: "Min 5 caractères",
				equalTo: "Vérifier votre mot de passe"
			}
			
		}
      });
    });
  </script>
</body>
</html>