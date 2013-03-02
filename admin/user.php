<?php
/*
Author: Flox
File name: user.php
Description: admin user
Version: 1.3
Call: admin.php
creation date: 12/01/2011
last update: 25/12/2012
*/

// initialize variables 
if(!isset($_SERVER['QUERY_URI'])) $_SERVER['QUERY_URI'] = '';
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($_POST['Ajouter'])) $_POST['Ajouter'] = '';
if(!isset($_POST['cancel'])) $_POST['cancel'] = '';
if(!isset($_POST['addview'])) $_POST['addview'] = '';
if(!isset($_POST['profil'])) $_POST['profil'] = '';
if(!isset($_POST['name'])) $_POST['name'] = '';
if(!isset($_POST['company'])) $_POST['company'] = '';
if(!isset($_POST['address1'])) $_POST['address1'] = '';
if(!isset($_POST['address2'])) $_POST['address2'] = '';
if(!isset($_POST['zip'])) $_POST['zip'] = '';
if(!isset($_POST['city'])) $_POST['city'] = '';
if(!isset($_POST['custom1'])) $_POST['custom1'] = '';
if(!isset($_POST['custom2'])) $_POST['custom2'] = '';
if(!isset($_POST['password'])) $_POST['password'] = '';
if(!isset($_POST['category'])) $_POST['category'] = '%';
if(!isset($_POST['subcat'])) $_POST['subcat'] = '';
if(!isset($_POST['firstname'])) $_POST['firstname'] = '';
if(!isset($_POST['lastname'])) $_POST['lastname'] = '';
if(!isset($_POST['viewname'])) $_POST['viewname'] = '';
if(!isset($password)) $password = '';
if(!isset($addeview)) $addview = '';
if(!isset($category)) $category = '%';
if(!isset($_POST['chgpwd'])) $_POST['chgpwd'] = '';
if(!isset($_GET['id'])) $_GET['id'] = '';
if(!isset($_GET['deleteview'])) $_GET['deleteview'] = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_GET['ldap'])) $_GET['ldap'] = '';

//Special char rename
$_POST['firstname'] = str_replace("'","\'",$_POST['firstname']); 
$_POST['lastname'] = str_replace("'","\'",$_POST['lastname']);

// Si une modification est demandé alors on met a jour la table tusers puis on redirige l'utilisateur vers le listing des utilisateurs
if($_POST['Modifier'])
{
	//no update already crytped password if no change
	$q = mysql_query("SELECT * FROM `tusers` where id LIKE '$_GET[id]'"); 
	$r = mysql_fetch_array($q);
	if($_POST['password']!=$r['password']) {
	$salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
	$_POST['password']=md5($salt . md5($_POST['password'])); // store in md5, md5 password + salt
	} else {
	$salt=$r['salt'];
	}
	
	$requete = "UPDATE tusers SET 
  group_id='$_POST[group_id]',
	firstname='$_POST[firstname]',
	lastname='$_POST[lastname]',
	password='$_POST[password]',
	salt='$salt',
	phone='$_POST[phone]',
  mobil='$_POST[mobil]',
	profile='$_POST[profile]',
	code='$_POST[code]',
	fax='$_POST[fax]',
	company='$_POST[company]',
	address1='$_POST[address1]',
	address2='$_POST[address2]',
	zip='$_POST[zip]',
	city='$_POST[city]',
	custom1='$_POST[custom1]',
	custom2='$_POST[custom2]',
	chgpwd='$_POST[chgpwd]' WHERE id LIKE '$_GET[id]'";

  //echo "reqette : ".$requete;
  //die;
	
	$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	
	if($_POST['viewname']){
		$query = "INSERT INTO tviews (uid,name,category,subcat) VALUES ('$_GET[id]','$_POST[viewname]', '$_POST[category]', '$_POST[subcat]')";
		$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
	}
	//redirect
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$_SERVER['QUERY_URI'].'");
	// -->
	</script>';
}

// Si un ajout est demandé alors alors on fait un insert dans la table tusers puis on redirige l'utilisateur vers le listing des utilisateurs
if($_POST['Ajouter']){
	//crypt password md5 + salt
	$salt = substr(md5(uniqid(rand(), true)), 0, 5); // Generate a random key
	$_POST['password']=md5($salt . md5($_POST['password'])); // store in md5, md5 password + salt
	
	$requete = "INSERT INTO tusers (firstname,lastname,password,salt,mail,phone,mobil,fax,company,address1,address2,zip,city,custom1,custom2,profile,code,chgpwd) VALUES ('$_POST[firstname]','$_POST[lastname]','$_POST[password]','$salt','$_POST[mail]','$_POST[phone]','$_POST[mobil]','$_POST[fax]','$_POST[company]','$_POST[address1]','$_POST[address2]','$_POST[zip]','$_POST[city]','$_POST[custom1]','$_POST[custom2]','$_POST[profile]','$_POST[code]','$_POST[chgpwd]')";
	$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	//redirection vers la page d'accueil
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}


if($_POST['cancel']){
	//redirection vers la page d'accueil
	$www = "./index.php?page=dashboard&techid=$uid&state=%";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
// View Part
if ($_GET['deleteview']=="1")
{
$query = "DELETE FROM tviews WHERE id = '$_GET[viewid]'";
$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
	//redirection vers la page d'accueil
	$www = "./index.php?page=admin&subpage=user&action=edit&id=$_GET[id]";
	echo '<script language="Javascript">
	<!--
	document.location.replace("'.$www.'");
	// -->
	</script>';
}


//Si on demande l'action edition alors on affiche le formulaire  d'edition sinon on affiche la table
if ($_GET['action']=='edit')
{
	//On récupére les données en fonction de l'id utilisateur récupéré
	$requser1 = mysql_query("SELECT * FROM `tusers` where id LIKE '$_GET[id]'"); 
	$user1 = mysql_fetch_array($requser1);
	//On affiche le formulaire d'edition
	echo '
	<form id="1" name="form" method="post"  action="">
		<table >
			<tr>
				 <th colspan="2" ><img alt="user" src="./images/user.png" sytle="border-style:none;"/>   Fiche utilisateur</th>
			<tr>
			<tr>
				<td><b>Prénom:</b></td><td><input name="firstname" type="" value="'.$user1['firstname'].'" size="20" /></td>
			</tr>
			<tr>
				<td><b>Nom:</b></td><td><input name="lastname" type="" value="'.$user1['lastname'].'" size="20" /></td>
			</tr>
			<tr>
				<td><b>Code:</b></td><td><input name="code" type="" value="'; if($user1['code']) echo "$user1[code]"; else echo ""; echo'" size="20" /></td>
			</tr>
			<tr>
				<td><b>Mot de passe:</b></td><td><input name="password" type="password" value="';if($user1['password']=="") echo ""; else echo "$user1[password]"; echo'" size="20" /></td>
			</tr>
			<tr>
				<td><b>Adresse mail:</b></td><td><input name="mail" type="" value="'.$user1['mail'].'" size="40" /></td>
			</tr>
			<tr>
				<td><b>Téléphone:</b></td><td><input name="phone" type="" value="'.$user1['phone'].'" size="20" /></td>
			</tr>
      <tr>
				<td><b>Tél portable :</b></td><td><input name="mobil" type="" value="'.$user1['mobil'].'" size="20" /></td>
			</tr>
			<tr>
				<td><b>Fax:</b></td><td><input name="fax" type="" value="'.$user1['fax'].'" size="20" /></td>
			</tr>';
			// Display advanced user informations
			if ($rparameters['user_advanced']!='0')
			{
			echo '
				<tr>
					<td><b>Société:</b></td><td><input name="company" type="" value="'.$user1['company'].'" size="20" /></td>
				</tr>
				<tr>
					<td><b>Adresse 1:</b></td><td><input name="address1" type="" value="'.$user1['address1'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Adresse 2:</b></td><td><input name="address2" type="" value="'.$user1['address2'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Ville:</b></td><td><input name="city" type="" value="'.$user1['city'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Code postal:</b></td><td><input name="zip" type="" value="'.$user1['zip'].'" size="20" /></td>
				<tr>
				<tr>
					<td><b>Champ Personalisé 1:</b></td><td><input name="custom1" type="" value="'.$user1['custom1'].'" size="30" /></td>
				<tr>
				<tr>
					<td><b>Champ Personalisé 2:</b></td><td><input name="custom2" type="" value="'.$user1['custom2'].'" size="30" /></td>
				<tr>';
			}
			echo'
			<br />
			';
			// Display profile list
			if ($rright['admin_user_profile']!='0')
			{
				echo '
				<tr>
					<td><b>Profile:</b></td>
					<td>
						<input type="radio" name="profile" value="4" '; if ($user1['profile']=='4')echo "checked"; echo '> Administrateur <i>(Tous)</i> <br />
						<input type="radio" name="profile" value="0" '; if ($user1['profile']=='0')echo "checked"; echo '> Technicien <i>(création, visualisation, administration)</i> <br />
						<input type="radio" name="profile" value="3" '; if ($user1['profile']=='3')echo "checked"; echo '> Superviseur <i>(création, visualisation, accés aux statistiques)</i> <br />
						<input type="radio" name="profile" value="1" '; if ($user1['profile']=='1')echo "checked"; echo '> Utilisateur avec pouvoir <i>(création, visualisation)</i> <br />
						<input type="radio" name="profile" value="2" '; if ($user1['profile']=='2')echo "checked"; echo '> Utilisateur <i>(visualisation)</i> 
					</td>
				</tr>
				<tr>
					<td><b>Forcer le changement<br /> du mot de passe:</b></td>
					<td>
						<input type="radio" disable="disable" name="chgpwd" value="0" '; if ($user1['chgpwd']=='0')echo "checked"; echo '> Non<br />
						<input type="radio" name="chgpwd" value="1" '; if ($user1['chgpwd']=='1')echo "checked"; echo '> Oui<br />
					</td>
				</tr>';
        $query2 = mysql_query("SELECT * FROM `tcompany` order by nom");
       echo '<tr>
					<td><b>Groupe :</b></td>
					<td><select name="group_id">';
					 while ($row2 = mysql_fetch_array($query2)) {
            echo '<option value="'.$row2[id].'"';
            if(isset($user1['group_id']) && $user1['group_id'] == $row2['id']){ echo  'selected="selected"'; }
            echo ">";
            echo $row2['nom'];
            echo '</option>';
           }
					  echo '</select></td></tr>';

			}
			else
			{
			echo '<input type="hidden" name="profile" value="'.$user1['profile'].'" '; if ($user1['profile']=='2')echo "checked"; echo '>';
			}
			//Display personal view
			if ($rright['admin_user_view']!='0')
			{
				echo '
				<tr>
					<td><b>Vues personnelles</b><br><i>(associe des catégories ‡ l\'utilisateur)</i></td>
					<td>';
					// Check if connected user have view
					$query = mysql_query("SELECT * FROM `tviews` WHERE uid='$_GET[id]'");
					$row=mysql_fetch_array($query);
					if ($row[0]!='')
					{
						//Display Actives views
						$query = mysql_query("SELECT * FROM `tviews` WHERE uid='$_GET[id]' ORDER BY uid");
						while ($row=mysql_fetch_array($query))
						{
							$cname= mysql_query("SELECT name FROM `tcategory` WHERE id='$row[category]'"); 
							$cname= mysql_fetch_array($cname);
							
							if ($row['subcat']!=0)
							{
								$sname= mysql_query("SELECT name FROM `tsubcat` WHERE id='$row[subcat]'"); 
								$sname= mysql_fetch_array($sname);
								$sname=", $sname[0]";
							} else {$sname='';}
							echo "$row[name]:  $cname[name]$sname 
							<a title=\"Supprimer cette Vue\" href=\"index.php?page=admin&subpage=user&action=edit&id=$_GET[id]&viewid=$row[id]&deleteview=1\"><img alt=\"delete\" src=\"./images/delete.png\" style=\"border-style: none\" /></a>
							<br />";
						}
					}
					// Diplay add view form
					echo '
						Catégorie:
						<select name="category" onchange="submit()" style="width:100px" >
							<option value="%"></option>';
							$query = mysql_query("SELECT * FROM tcategory ORDER BY name");
							while ($row=mysql_fetch_array($query)) 
							{
								echo "<option value=\"$row[id]\">$row[name]</option>";
								if ($_POST['category']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
							} 
							echo '
						</select>
						Sous-Catégorie:
						<select name="subcat" onchange="submit()" style="width:90px">
							<option value="%"></option>';
							if($_POST['category']!='%')
							{$query = mysql_query("SELECT * FROM tsubcat WHERE cat LIKE $_POST[category] ORDER BY name");}
							else
							{$query = mysql_query("SELECT * FROM tsubcat ORDER BY name");}
							while ($row=mysql_fetch_array($query))
							{
								echo "<option value=\"$row[id]\">$row[name]</option>";
								if ($_POST['subcat']==$row['id']) echo "<option selected value=\"$row[id]\">$row[name]</option>";
							} 
							echo '
						</select>
						Nom: <input name="viewname" type="" value="'.$_POST['name'].'" size="20" />
					</td>
					</tr>';
			} echo'	
		</table>';
			
		echo '
		</table>
		<div  class="buttons2">
			<br />
			<button name="Modifier" value="Modifier" type="submit"  class="positive" id="Modifier"> 
				<img src="images/apply2.png" alt=""/>
				Modifier
			</button>
			<button name="cancel" value="cancel" type="submit" class="negative">
				<img src="images/cross.png" alt=""/>
				Annuler
			</button>
			<br /><br /><br />
		</div>
	</form>
	';
	
}
else if ($_GET['action']=="add")
{
	echo "<h2 class=\"sec_head\">Ajout d'un utilisateur</h2>";
	//On récupére les données en fonction de l'id utilisateur récupéré
	$requser = mysql_query("SELECT * FROM `tusers` where id LIKE '$_GET[id]'"); 
	$user = mysql_fetch_array($requser);
	//On affiche le formulaire d'edition
	echo '
	<form method="post" action="">
	<table>
		<tr>
			<td><b>Prénom:</b></td><td><input name="firstname" type="" value="'.$user['firstname'].'" size="10" /></td>
		</tr>
		<tr>
			<td><b>Nom:</b></td><td><input name="lastname" type="" value="'.$user['lastname'].'" size="10" /></td>
		</tr>
		<tr>
			<td><b>Code:</b></td><td><input name="code" type="" value="'.$user['code'].'" size="10" /></td>
		</tr>
		<tr>
			<td><b>Mot de passe:</b></td><td><input name="password" type="password" value="'.$user['password'].'" size="10" /></td>
		</tr>
		<tr>
			<td><b>Adresse mail:</b></td><td><input name="mail" type="" value="'.$user['mail'].'" size="40" /></td>
		</tr>
		<tr>
			<td><b>Téléphone:</b></td><td><input name="phone" type="" value="'.$user['phone'].'" size="10" /></td>
		</tr>
     <tr>
				<td><b>Tél portable :</b></td><td><input name="mobil" type="" value="'.$user['mobil'].'" size="20" /></td>
		</tr>
		<tr>
			<td><b>Fax:</b></td><td><input name="fax" type="" value="'.$user['fax'].'" size="20" /></td>
		</tr>';
		// Display advanced user informations
		if ($rparameters['user_advanced']!='0')
		{
			echo '
				<tr>
					<td><b>Société:</b></td><td><input name="company" type="" value="'.$user['company'].'" size="20" /></td>
				</tr>
				<tr>
					<td><b>Adresse 1:</b></td><td><input name="address1" type="" value="'.$user['address1'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Adresse 2:</b></td><td><input name="address2" type="" value="'.$user['address2'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Ville:</b></td><td><input name="city" type="" value="'.$user['city'].'" size="30" /></td>
				</tr>
				<tr>
					<td><b>Code postal:</b></td><td><input name="zip" type="" value="'.$user['zip'].'" size="20" /></td>
				<tr>
				<tr>
					<td><b>Champ Personalisé 1:</b></td><td><input name="custom1" type="" value="'.$user['custom1'].'" size="30" /></td>
				<tr>
				<tr>
					<td><b>Champ Personalisé 2:</b></td><td><input name="custom2" type="" value="'.$user['custom2'].'" size="30" /></td>
				<tr>';
		}
			echo'
		
		<br />
		<tr>
			<td><b>Profile:</b></td>
			<td>
				Technicien <i>(création, visualisation, administration) <input type="radio" name="profile" value="0" /><br />
				Superviseur <i>(création, visualisation) <input type="radio" name="profile" value="3" /><br />
				Utilisateur avec pouvoir <i>(création, visualisation)</i> <input type="radio" name="profile" value="1" /><br />
				Utilisateur <i>(visualisation)</i> <input type="radio" name="profile" value="2" checked />
			</td>
		</tr>
		<tr>
				<td><b>Forcer le changement<br /> du mot de passe:</b></td>
				<td>
					<input type="radio" disable="disable" name="chgpwd" value="0" checked> Non<br />
					<input type="radio" name="chgpwd" value="1" > Oui<br />
				</td>
		</tr>
	</table>
	<div  class="buttons2">
		<br />
			<button name="Ajouter" value="Ajouter" type="submit"  class="positive">
				<img src="images/apply2.png" alt=""/>
				Ajouter
			</button>
			<button name="cancel" value="cancel" type="submit" class="negative">
				<img src="images/cross.png" alt=""/>
				Annuler
		</button>
		<br /><br /><br />
		</div>
	</form>
	';
}
else if ($_GET['action']=="delete")
{
$requete = "DELETE FROM tusers WHERE id = '$_GET[id]'";
$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	//redirection vers la page d'accueil
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
else if ($_GET['action']=="disable")
{
$requete = "UPDATE tusers set disable=1 WHERE id = '$_GET[id]'";
$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
	//redirection vers la page d'accueil
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
else if ($_GET['action']=="enable")
{
$requete = "UPDATE tusers set disable=0 WHERE id = '$_GET[id]'";
$execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());

        if($_GET[profileid]=='ND'){

        $requser = mysql_query("SELECT * FROM `tusers` where id LIKE '$_GET[id]'");
        $user_to_send_email = mysql_fetch_array($requser);

        require("components/PHPMailer_v5.1/class.phpmailer.php");
        $mail = new PHPmailer();
        $mail->CharSet = 'UTF-8'; //UTF-8 possible if characters problems
        $mail->IsSendmail();

        $mail->IsHTML(true); // Envoi en html

        $mail->From = "$rparameters[mail_from]";
        $mail->FromName = "$rparameters[mail_from]";

        $mail->AddAddress($user_to_send_email[mail]);
	      $mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = "Validation compte";
        $bodyMSG = "Bonjour , <br /><br />
         Votre compte a été validé <br /><br />
         Vous pouvez utiliser votre email et votre mot de passe pour se connecter à notre systéme. <br /><br />
         Bien à vous
         ";
        $mail->Body = "$bodyMSG";
        $mail->Send();
        $mail->ClearAddresses();
        }

	//home page redirection
	$www = "./index.php?page=admin&subpage=user";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';
}
else if ($_GET['ldap']=="1")
{
	include('./core/ldap.php');
}
else if($_GET['action']=="reject"){

  $requete = "DELETE FROM tusers WHERE id = '$_GET[id]'";
  $execution = mysql_query($requete) or die('Erreur SQL !<br /><br />'.mysql_error());
  $requser = mysql_query("SELECT * FROM `tusers` where id LIKE '$_GET[id]'");
  $user_to_send_email = mysql_fetch_array($requser);
   require("components/PHPMailer_v5.1/class.phpmailer.php");
        $mail = new PHPmailer();
        $mail->CharSet = 'UTF-8'; //UTF-8 possible if characters problems
       // $mail->IsMail();

        $mail->IsHTML(true); // Envoi en html

        $mail->From = "$rparameters[mail_from]";
        $mail->FromName = "$rparameters[mail_from]";

        $mail->AddAddress($user_to_send_email[mail]);
	      $mail->AddReplyTo("$rparameters[mail_from]");
        $mail->Subject = "Compte rejeter";
        $bodyMSG = "Bonjour , <br /><br />
         Votre compte n’a pas été validé par le système, nous reprenons contact avec vous dès que possible. <br /><br />
         Nous restons à votre disposition pour tout renseignement complémentaire.  <br /><br />
         Bien à vous
         ";
        $mail->Body = "$bodyMSG";
        $mail->Send();
        $mail->ClearAddresses();


  $www = "./index.php?page=admin&subpage=user&profileid=ND";
			echo '<script language="Javascript">
			<!--
			document.location.replace("'.$www.'");
			// -->
			</script>';

}
// Else display users
else
{
	
	//Display Buttons
		if($rparameters['ldap']==1)	echo"<div  class=\"buttons2\">"; else echo"<div  class=\"buttons1\">";
		echo'<br />
		<form name="add" method="post" action="index.php?page=admin&subpage=user&action=add"  id="thisform">';
		echo '
				<button name="adduser" value="Ajouter" type="submit" class="positive" id="adduser">
					<img src="images/apply2.png" alt=""/>
					Ajouter
				</button>
			</form>
		
		';
		if($rparameters['ldap']==0)	echo"</div>";
			if($rparameters['ldap']==1)
			{
				echo '
				<form name="sync" method="post" action="index.php?page=admin&amp;subpage=user&amp;ldap=1" id="thisform">
						<button name="sync" value="Synchronisation LDAP" type="submit" class="regular" id="sync">
							<img src="images/sync.png" alt=""/>
							Synchronisation LDAP
						</button>
					<br /><br />
				</form>
				</div>
				
				';
			}
	echo'
	
	<br />
	';
	//Count user 
	$q = mysql_query("SELECT COUNT(*) FROM tusers where disable='0'");
	$r = mysql_fetch_array($q);
	$q1 = mysql_query("SELECT COUNT(*) FROM tusers where disable='1'");
	$r2 = mysql_fetch_array($q1);
	echo "<u>Nombre d'utilisateurs Activé:</u> $r[0] <i>($r2[0] Désactivés)</i><br /><br />";
					//Display user table
					echo "<center>";
					echo "<table  >";
					echo "
						<tr  >
							<th class=\"th\" >Actions</th>
							<th class=\"th\">Nom Prénom</th>
							<th class=\"th\" >Mot de passe</th>
							<th class=\"th\">Adresse Mail</th>
							<th class=\"th\">Téléphone</th>
              <th class=\"th\">Tél portable</th>
              <th class=\"th\">Code</th>
							<th class=\"th\">Profile</th>
						</tr>
						";
				if($_GET[profileid] == 'ND'){
          $query = mysql_query("SELECT * FROM `tusers` WHERE profile LIKE '2' AND disable = 1 ORDER BY lastname");
        } else {
          $query = mysql_query("SELECT * FROM `tusers` WHERE profile LIKE '$_GET[profileid]' ORDER BY lastname");
        }

				while ($row=mysql_fetch_array($query)) 
				{
					//find profile name
					$q = mysql_query("select name FROM tprofiles where level='$row[profile]'");
					$r = mysql_fetch_array($q) ;
					
					echo "<tr class=\"blue\">
							<td width=\"75px\">
								<center>
									<a title=\"Editer\" href=\"./index.php?page=admin&amp;subpage=user&amp;profileid=$_GET[profileid]&amp;action=edit&amp;id=$row[id]\"><img src=\"./images/edit.png\" border=\"0\" /></a>";
									if($row['id']!=$uid) echo "<a title=\"Supprimer\" href=\"./index.php?page=admin&amp;subpage=user&amp;id=$row[id]&amp;action=delete\"><img src=\"./images/delete.png\" border=\"0\" /></a>";                 
									if ($row['disable']!=1){echo "<a title=\"Activer, cliquez pour désactiver\" href=\"./index.php?page=admin&amp;subpage=user&amp;id=$row[id]&amp;action=disable\"><img src=\"./images/valide_min.png\" border=\"0\" /></a>";}
									else
									{echo "<a title=\"Désactiver cliquez pour Activer\" href=\"./index.php?page=admin&amp;profileid=$_GET[profileid]&amp;subpage=user&amp;id=$row[id]&amp;action=enable\"><img src=\"./images/access_min.png\" border=\"0\" /></a>";}
                   if($_GET[profileid]=='ND') echo "<a title=\"Rejeter\" href=\"./index.php?page=admin&amp;subpage=user&amp;id=$row[id]&amp;action=reject\"><img src=\"./images/ico-validate.png\" border=\"0\" /></a>";
					echo "
								</center>
							</td>
							<td >$row[lastname] $row[firstname] </td>
							<td width=\"100px\" >*******</td>
							<td >$row[mail]</td>
							<td >$row[phone]</td>
              <td >$row[mobil]</td>
              <td >$row[code]</td>
							<td >$r[name]</td>
						</tr>
					";
				}
				echo "</table>";
				echo "</center>";
}
?>