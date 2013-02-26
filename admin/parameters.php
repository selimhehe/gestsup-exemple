<?php
/*
Author: Flox
File name: parameters.php
Description: admin parameters
Version: 1.4
creation date: 12/01/2011
last update: 17/01/2013
*/

// initialize variables 
if(!isset($extensionFichier)) $extensionFichier = '';
if(!isset($id_)) $id_ = '';
if(!isset($logo)) $logo = '';
if(!isset($filename)) $filename = '';
if(!isset($mail_auto)) $mail_auto = '';
if(!isset($user_advanced)) $user_advanced= '';
if(!isset($mail_auth)) $mail_auth= '';
if(!isset($mail_secure)) $mail_secure= '';
if(!isset($nomorigine)) $nomorigine = '';
if(!isset($action)) $action = '';
if(!isset($_POST['Valider'])) $_POST['Valider'] = '';
if(!isset($_POST['mail_username'])) $_POST['mail_username'] = '';
if(!isset($_POST['mail_password'])) $_POST['mail_password'] = '';
if(!isset($_POST['mail_secure'])) $_POST['mail_secure'] = '';
if(!isset($_POST['user_advanced'])) $_POST['user_advanced'] = '';
if(!isset($_POST['mail_auth'])) $_POST['mail_auth']= '';
if(!isset($_POST['mail_auto'])) $_POST['mail_auto']= '';
if(!isset($_POST['mail_newticket'])) $_POST['mail_newticket']= '';
if(!isset($_POST['mail_newticket_address'])) $_POST['mail_newticket_address']= '';
if(!isset($_POST['ldap'])) $_POST['ldap']= '';
if(!isset($_POST['ldap_auth'])) $_POST['ldap_auth']= '';
if(!isset($_POST['ldap_server'])) $_POST['ldap_server']= '';
if(!isset($_POST['ldap_domain'])) $_POST['ldap_domain']= '';
if(!isset($_POST['ldap_url'])) $_POST['ldap_url']= '';
if(!isset($_POST['ldap_user'])) $_POST['ldap_user']= '';
if(!isset($_POST['ldap_password'])) $_POST['ldap_password']= '';
if(!isset($_POST['test_ldap'])) $_POST['test_ldap']= '';
if(!isset($_POST['planning'])) $_POST['planning']= '';
if(!isset($_GET['action'])) $_GET['action']= '';
if(!isset($_FILES['logo']['name'])) $_FILES['logo']['name'] = '';

//delete logo file
if($_GET['action']=="deletelogo")
{
	$requete = "UPDATE tparameters SET logo=''";
	$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
		$www = "./index.php?page=admin&subpage=parameters";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>'; 
}
	
if($_POST['Valider'])
{
	//upload logo file
	if($_FILES['logo']['name'])
	{
		$filename = $_FILES['logo']['name'];
		$repertoireDestination = "./upload/logo/";
		if (move_uploaded_file($_FILES['logo']['tmp_name'], $repertoireDestination.$_FILES['logo']['name'])   ) 
		{
		} else {
		echo "Erreur de transfert v�rifier le chemin ".$repertoireDestination;
		}
	}
	else $filename="$rparameters[logo]";
	
	$requete = "UPDATE tparameters SET 
	company='$_POST[company]',
	maxline='$_POST[maxline]',
	mail_smtp='$_POST[mail_smtp]',
	mail_secure='$_POST[mail_secure]',
	mail_username='$_POST[mail_username]',
	mail_password='$_POST[mail_password]',
	mail_txt='$_POST[mail_txt]',
	mail_cc='$_POST[mail_cc]',
	mail_from='$_POST[mail_from]',
	mail_color_title='$_POST[mail_color_title]',
	mail_color_bg='$_POST[mail_color_bg]',
	mail_color_text='$_POST[mail_color_text]',
	mail_link='$_POST[mail_link]',
	logo='$filename',
	lign_yellow='$_POST[lign_yellow]',
	lign_orange='$_POST[lign_orange]',
	time_display_msg='$_POST[time_display_msg]',
	auto_refresh='$_POST[auto_refresh]',
	user_advanced='$_POST[user_advanced]',
	mail_auth='$_POST[mail_auth]',
	mail_auto='$_POST[mail_auto]',
	mail_newticket='$_POST[mail_newticket]',
	mail_newticket_address='$_POST[mail_newticket_address]',
	ldap='$_POST[ldap]',
	ldap_auth='$_POST[ldap_auth]',
	ldap_server='$_POST[ldap_server]',
	ldap_user='$_POST[ldap_user]',
	ldap_password='$_POST[ldap_password]',
	ldap_domain='$_POST[ldap_domain]',
	ldap_url='$_POST[ldap_url]',
	planning='$_POST[planning]'
	";
	
	$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	//web redirect
		$www = "./index.php?page=admin&subpage=parameters";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>'; 
}
?>

<form enctype="multipart/form-data" method="post" action="">
	<b>Soci�t�:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Nom de l'entreprise: <input name="company" type="" value="<?php echo $rparameters['company']; ?>" size="20" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Logo:
	<?php
	if ($rparameters['logo']!="")
	{
		echo "<img src=\"./upload/logo/$rparameters[logo]\" />";
		echo '&nbsp;&nbsp;<a href="./index.php?page=admin&subpage=parameters&action=deletelogo"><img style="margin:0px 10px 0px 0px; border-style: none;" alt="img" src="./images/delete_max.png" title="Supprimer le logo" /></a>';	
	} else {
		echo "<input type=\"file\" id=\"logo\"  name=\"logo\" /> (35px X 35px)";
	}
	?>
	<br /><br />
	<b>Affichage:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Lignes par page <i>(T�ches)</i>: <input name="maxline" type="" value="<?php echo $rparameters['maxline']; ?>" size="1" />  <img src="./images/info.png" alt="info" title="Si cette valeur est trop grande cela peut ralentir l'application" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Ticket ancien <i>(T�ches)</i>: <input name="lign_yellow" type="" value="<?php echo $rparameters['lign_yellow']; ?>" size="1" /> jours  <img src="./images/question.png" alt="question" title="D�termine, la couleur jaune des tickets dans les t�ches" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Ticket tr�s ancien <i>(T�ches)</i>: <input name="lign_orange" type="" value="<?php echo $rparameters['lign_orange']; ?>" size="1" /> jours <img src="./images/question.png" alt="question" title="D�termine, la couleur orange des tickets dans les t�ches" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Temps d'affichage des messages d'actions : <input name="time_display_msg" type="" value="<?php echo $rparameters['time_display_msg']; ?>" size="1" /> ms<br />
	&nbsp;&nbsp;&nbsp;&nbsp;Actualisation automatique <i>(T�ches)</i>: <input name="auto_refresh" type="" value="<?php echo $rparameters['auto_refresh']; ?>" size="1" /> s <img src="./images/info.png" alt="info" title="Si la valeur est � 0, alors l'actualisation automatique est d�sactiv�e. Attention, cette fonction peut faire clignoter l'�cran selon les navigateurs." /><br />
	<br />
	<b>Utilisateurs:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Utiliser les propri�t�s utilisateur avanc�s: <input type="checkbox" <?php if ($rparameters['user_advanced']==1) echo "checked"; ?> name="user_advanced" value="1"> <img  src="./images/question.png" alt="question" title="Ajoute des champs supl�mentaire aux propri�t�s utilisateurs, Soci�t�, FAX, Adresses... " /><br />
	<br />
	<b>Messages:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Serveur SMTP: <input name="mail_smtp" type="" value="<?php echo $rparameters['mail_smtp']; ?>" size="20" /> <img src="./images/question.png" alt="question" title="Adresse IP ou Nom de votre serveur de messagerie (Exemple: 192.168.0.1 ou SRVMSG ou smtp.free.fr ou auth.smtp.1and1.fr) " /><br />
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;Serveur SMTP Authentifi�: <input type="checkbox" <?php if ($rparameters['mail_auth']==1) echo "checked"; ?> name="mail_auth" value="1"> <img src="./images/question.png" alt="question" title="Cochez cette case si votre serveur de messagerie n�cessite un identifiant et mot de passe pour envoyer des messages." /><br />
	<?php if ($rparameters['mail_auth']=='1') {
		echo "
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Utilisateur: <input name=\"mail_username\" type=\"\" value=\"$rparameters[mail_username]\" size=\"30\" />
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mot de passe: <input name=\"mail_password\" type=\"password\" value=\"$rparameters[mail_password]\" size=\"30\" />
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Serveur SMTP s�curis�: 
		<select class=\"textfield\" id=\"mail_secure\" name=\"mail_secure\" >
			<option ";if ($rparameters['mail_secure']==0) echo "selected "; echo" value=\"0\">D�sactiv�</option>
			<option ";if ($rparameters['mail_secure']==465) echo "selected "; echo" value=\"465\">SSL (port: 465)</option>
			<option ";if ($rparameters['mail_secure']==587) echo "selected "; echo"value=\"587\">TLS (port: 587)</option>
		</select>
		";if ($rparameters['mail_secure']!=0) {echo"<i><font color=\"red\">(Attention l'extension php_openssl doit �tre activ�e)</font></i>";} else {echo"<img src=\"./images/question.png\" alt=\"question\" title=\"Si votre serveur de messagerie est s�curis� alors selectionner le protocole SSL ou TLS (Exemple: Gmail utilise TLS).\" />";} echo "
		<br />
		";
	}
	?>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;Envoi de mail automatique � l'utilisateur lors de l'ouverture ou fermeture d'un ticket par un technicien: <input type="checkbox" <?php if ($rparameters['mail_auto']==1) echo "checked"; ?> name="mail_auto" value="1"><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Envoi de mail automatique � l'administrateur lors de l'ouverture d'un ticket par un utilisateur: <input type="checkbox" <?php if ($rparameters['mail_newticket']==1) echo "checked"; ?> name="mail_newticket" value="1"><br />
	<?php if ($rparameters['mail_newticket']=='1') 
	{
		echo "
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Adresse Mail: <input name=\"mail_newticket_address\" type=\"\" value=\"$rparameters[mail_newticket_address]\" size=\"30\" />
		<br />
		";
	}
	?>
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;Texte d�but du mail: <input name="mail_txt" type="" value="<?php echo $rparameters['mail_txt']; ?>" size="80" /> <img src="./images/info.png" alt="info" title="Vous pouvez utiliser du code HTML (Exemple: <br />, <b></b>...)" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Adresse en copie: <input name="mail_cc" type="" value="<?php echo $rparameters['mail_cc']; ?>" size="30" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Intitul� de l'�metteur: <input name="mail_from" type="" value="<?php echo $rparameters['mail_from']; ?>" size="30" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Int�grer un lien vers GestSup: <input type="checkbox" <?php if ($rparameters['mail_link']==1) echo "checked"; ?> name="mail_link" value="1"><br />
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;Couleur du titre: #<input  style="background-color: <?php echo "#$rparameters[mail_color_title]"; ?>;" name="mail_color_title" type="" value="<?php echo $rparameters['mail_color_title']; ?>" size="6" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Couleur du fond: #<input  style="background-color: <?php echo "#$rparameters[mail_color_bg]"; ?>;" name="mail_color_bg" type="" value="<?php echo $rparameters['mail_color_bg']; ?>" size="6" /><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Couleur du texte: #<input  style="background-color: <?php echo "#$rparameters[mail_color_text]"; ?>;" name="mail_color_text" type="" value="<?php echo $rparameters['mail_color_text']; ?>" size="6" /><br />
	<br />
	<b>Connection LDAP:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Activer la fonction LDAP: <input type="checkbox" <?php if ($rparameters['ldap']==1) echo "checked"; ?> name="ldap" value="1"> <img src="./images/question.png" alt="question" title="Active la liaison entre GestSup et l'annuaire utilisateurs de l'entreprise (Exemple: Active Directory pour Windows Server)" /><br />
	<?php if ($rparameters['ldap']=='1') 
	{
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Activer l'authentification GestSup avec LDAP: <input type=\"checkbox\""; if ($rparameters['ldap_auth']==1) echo "checked"; echo " name=\"ldap_auth\" value=\"1\"> <img src=\"./images/question.png\" alt=\"question\" title=\"Active l'authentification des utilisateurs dans GesStup, avec les identifiants pr�sents dans l'annuaire LDAP. Cela ne d�sactive pas l'authentification avec la base utilisateurs de GestSup. \" /><br />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Serveur LDAP: <input name=\"ldap_server\" type=\"\" value=\"$rparameters[ldap_server]\" size=\"20\" /> <img src=\"./images/question.png\" alt=\"question\" title=\"Nom Netbios du serveur d'annuaire, sans suffixe DNS (Exemple: SVRAD). \" /><br />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Domaine: <input name=\"ldap_domain\" type=\"\" value=\"$rparameters[ldap_domain]\" size=\"20\" /> <img src=\"./images/question.png\" alt=\"question\" title=\"Nom du domaine FQDN (Exemple: exemple.local). \" /><br />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Emplacement des utilisateurs: <input name=\"ldap_url\" type=\"\" value=\"$rparameters[ldap_url]\" size=\"20\" /> <img src=\"./images/question.png\" alt=\"question\" title=\"Emplacement dans l'annuaire des utilisateurs. Par d�faut pour active directory cn=users, si vous utiliser des unit�s d'organisation alors ou=ouname2,ou=ouname1... \" /><br />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Utilisateur: <input name=\"ldap_user\" type=\"\" value=\"$rparameters[ldap_user]\" size=\"20\" /> <img src=\"./images/question.png\" alt=\"question\" title=\"Utilisateur pr�sent dans l'annuaire LDAP\" /><br />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Mot de passe: <input name=\"ldap_password\" type=\"password\" value=\"$rparameters[ldap_password]\" size=\"20\" /><br />";

		//check LDAP parameters
		if($_POST['test_ldap']) {
		include('./core/ldap.php');
		echo "<br />&nbsp;&nbsp;&nbsp;&nbsp;Etat de la liaison LDAP: <img src=\"./images/$ldap_connection.png \" alt=\"Etat LDAP\" /><br />";
		}
		echo'
		<br />
		<div class="buttons3">
			<button name="test_ldap" value="test_ldap" type="submit"  class="regular">
				<img src="images/connect.png" alt=""/>
				Test de connection LDAP
			</button>
		</div>';
		
		
	}
	?>
	<br />
	<b>Planning:</b><br />
	&nbsp;&nbsp;&nbsp;&nbsp;Activer la fonction Planning: <input type="checkbox" <?php if ($rparameters['planning']==1) echo "checked"; ?> name="planning" value="1"> <img src="./images/question.png" alt="question" title="Active la gestion de planning, nouvel onglet et gestion dans les tickets" /><br />
	
	<br /><br /><br /><br /><br />
	<div  class="buttons1">
		<br />
		<button name="Valider" value="Valider" type="submit"  class="positive">
			<img src="images/apply2.png" alt=""/>
			Valider
		</button>
		<br /><br />
	</div>
</form>