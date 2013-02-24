<?php
/*
Author: Flox
FileName: ldap.php
Description: page to synchronize users from LDAP to GestSup
Version: 1.0
Creation date: 15/10/2012
Last update: 27/11/2012
*/

// initialize variables
if(!isset($ldap_query)) $ldap_query = '';
if(!isset($find)) $find = '';
if(!isset($_GET['action'])) $_GET['action'] = '';
if(!isset($_POST['test_ldap'])) $_POST['test_ldap'] = '';
if(!isset($_GET['ldap'])) $_GET['ldap'] = '';
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';
if(!isset($dcgen)) $dcgen = '';
if(!isset($find2_login)) $find2_login= '';
if(!isset($update)) $update= '';

//LDAP connection parameters
$user=$rparameters['ldap_user']; 
$password=$rparameters['ldap_password']; 
$hostname=$rparameters['ldap_server'];
$domain=$rparameters['ldap_domain'];
	
//Generate DC Chain from domain parameter
$dcpart=explode(".",$domain);
$i=0;
while($i<count($dcpart)) {
	$dcgen="$dcgen,dc=$dcpart[$i]";
	$i++;
}
	
////LDAP URL for users emplacement
$ldap_url="$rparameters[ldap_url]$dcgen";

if(($_GET['action']=='simul') || ($_GET['action']=='run') || $_POST['test_ldap'] || ($_GET['ldap']=='1') || ($ldap_auth==1))
{
	// LDAP connect
	$ldap = @ldap_connect("$hostname.$domain") or die("Impossible de se connecter au serveur LDAP.");
	ldap_set_option($ldap, LDAP_OPT_NETWORK_TIMEOUT, 1);
	$ldapbind = @ldap_bind($ldap, "$user@$domain", $password);

	// Check ldap authentication
	if ($ldapbind) {$ldap_connection="ok_min";} else {$ldap_connection="access_min";}
	
	if ($ldapbind) 
	{
		if(($_GET['action']=='simul') || ($_GET['action']=='run')) 
		{
				echo "<h2 class=\"sec_head\">Synchronisation depuis l'Annuaire LDAP vers GestSup</h2><br />";
				
				// LDAP Query
				$query = @ldap_search($ldap, $ldap_url, "(&(objectClass=user)(cn=*))");
				// Put all data to $data
				$data = @ldap_get_entries($ldap, $query);
				//count LDAP number of users
				$cnt_ldap = @ldap_count_entries($ldap, $query);
				//count GESTSUP number of users
				$q=mysql_query("SELECT count(*) FROM tusers WHERE disable='0'"); 
				$cnt_gestsup=mysql_fetch_array($q);
				
				echo "<b><u>Vérification des Annuaires</u></b><br />";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;Nombre d'utilisateurs trouvés dans l'annuaire LDAP: $cnt_ldap<br />";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;Nombre d'utilisateurs actif trouvés dans GestSup: $cnt_gestsup[0]<br /><br />";
				echo "<b><u>Modifications à apporter dans GestSup:</u></b><br /><br />";
				//Initialize counter
				$cnt_maj=0;
				$cnt_create=0;
				$cnt_disable=0;
				$cnt_enable=0;
				
				// For each LDAP user 
				for ($i=0; $i < $cnt_ldap; $i++) 
				{
					//Initialize variable for empty data
					if(!isset($data[$i]['givenname'][0])) $data[$i]['givenname'][0] = '';
					if(!isset($data[$i]['sn'][0])) $data[$i]['sn'][0] = '';
					if(!isset($data[$i]['telephonenumber'][0])) $data[$i]['telephonenumber'][0] = '';
					if(!isset($data[$i]['streetaddress'][0])) $data[$i]['streetaddress'][0] = '';
					if(!isset($data[$i]['postalcode'][0])) $data[$i]['postalcode'][0] = '';
					if(!isset($data[$i]['l'][0])) $data[$i]['l'][0] = '';
					if(!isset($data[$i]['mail'][0])) $data[$i]['mail'][0] = '';
					if(!isset($data[$i]['company'][0])) $data[$i]['company'][0] = '';
					if(!isset($data[$i]['facsimiletelephonenumber'][0])) $data[$i]['facsimiletelephonenumber'][0] = '';
					if(!isset($data[$i]['userAccountControl'][0])) $data[$i]['userAccountControl'][0] = '';
					
					//Get user data
					$givenname=$data[$i]['givenname'][0];
					$sn=$data[$i]['sn'][0];
					$samaccountname=$data[$i]['samaccountname'][0];  
					$mail=$data[$i]['mail'][0];
					$telephonenumber=$data[$i]['telephonenumber'][0];  
					$streetaddress=$data[$i]['streetaddress'][0];  
					$postalcode=$data[$i]['postalcode'][0]; 
					$l=$data[$i]['l'][0]; 
					$company=$data[$i]['company'][0]; 
					$fax=$data[$i]['facsimiletelephonenumber'][0]; 
					$UAC=$data[$i]['useraccountcontrol'][0]; 
					
					//Display all data for debug (userAccountControl=512) 514=desactivé
					//var_dump($data);
					
					////Check if account not exist in GestSup user database
					//1st Check login
					$find_login=0;
					$q = mysql_query("SELECT * FROM `tusers`");
					while ($row=mysql_fetch_array($q))
					{
						if($samaccountname==$row['login']) {
						$find_login=$row['login'];
						$g_disable=$row['disable'];
						$g_mail=$row['mail'];
						$g_telephonenumber=$row['phone'];
						$g_streetaddress=$row['address1'];
						$g_postalcode=$row['zip'];
						$g_l=$row['city'];
						$g_company=$row['company'];
						$g_fax=$row['fax'];
						}
					}
					if ($find_login!='')
					{	
						////Update exist account
						if (($UAC=='514') && ($g_disable==0))
						{
							//Disable GestSup account
							$cnt_disable=$cnt_disable+1;
							if($_GET['action']=='run') {
								echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/disable.png\" alt=\"disable\" /><font color=\"red\"> Utilisateur <b>$givenname $sn</b> ($samaccountname), désactivé.</font><br />";
								$query= "UPDATE tusers SET disable='1' WHERE login='$find_login'";		
								$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
							} else {
								echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/disable.png\" alt=\"disable\" /><font color=\"red\"> Désactivation de l'utilisateur <b>$givenname $sn</b> ($samaccountname). <font size=\"1\" >Raison: Utilisateur désactivé dans l'annuaire LDAP.</font></font><br />";
							}
						} else {
							//Enable gestsup account if LDAP user is re-activate
							if(($g_disable=='1') && ($UAC!='514'))
							{
								$cnt_enable=$cnt_enable+1;
								if($_GET['action']=='run') {
								echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/enable.png\" alt=\"enable\" /><font color=\"green\"> Utilisateur <b>$givenname $sn</b> ($samaccountname), activé.</font><br />";
								$query= "UPDATE tusers SET disable='0' WHERE login='$samaccountname'";
								$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
								} else {
									echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/enable.png\" alt=\"enable\" /><font color=\"green\"> Activation de l'utilisateur <b>$givenname $sn</b> ($samaccountname).</font><br />";
								}
							//Update GestSup account if LDAP have informations and not GestSup
							} else if($UAC!='514'){
								//Compare data 
								$update=0;
								if(($g_mail=='') && ($mail!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET mail='$mail' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_telephonenumber=='') && ($telephonenumber!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET phone='$telephonenumber' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_streetaddress=='') && ($streetaddress!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET address1='$streetaddress' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_postalcode=='') && ($postalcode!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET zip='$postalcode' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_l=='') && ($l!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET city='$l' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_company=='') && ($company!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET company='$company' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								if(($g_fax=='') && ($fax!='')) 
								{
									$update=1;
									if($_GET['action']=='run') {
									$query= "UPDATE tusers SET fax='$fax' WHERE login='$samaccountname'";
									$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
									}
								}
								
								if($update==1)
								{
									$cnt_maj=$cnt_maj+1;
									if($_GET['action']=='run') {
										echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/update.png\" alt=\"update\" /><font color=\"orange\"> Utilisateur <b>$givenname $sn</b> ($samaccountname), mis à jour.</font><br />";
									} else {
										echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/update.png\" alt=\"update\" /><font color=\"orange\"> Mise à jour de l'utilisateur <b>$givenname $sn</b> ($samaccountname).</font><br />";
									}
								}
							}
						}

					} else {
						//Create GestSup account
						$cnt_create=$cnt_create+1;
						if($_GET['action']=='run') {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/addsmall.png\" alt=\"add\" /><font color=\"green\"> Utilisateur <b>$givenname $sn</b> ($samaccountname) à été crée.</font><br />";
							$query= "INSERT INTO tusers (login,firstname,lastname,profile,mail,phone,address1,zip,city,company,fax) VALUES ('$samaccountname','$givenname','$sn','2','$mail','$telephonenumber','$streetaddress','$postalcode','$l','$company','$fax')";
							$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
						} else {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/addsmall.png\" alt=\"add\" /><font color=\"green\"> Création de l'utilisateur <b>$givenname $sn</b> ($samaccountname).</font><br />";
						}
					}
				}
				//For each Gestsup USER (find user not present in LDAP for disable in GestSup)
				$q = mysql_query("SELECT * FROM `tusers`");
				while ($row=mysql_fetch_array($q))	
				{
					$find2_login='';
					for ($i=0; $i < $cnt_ldap; $i++) 
					{
						$samaccountname=$data[$i]['samaccountname'][0];
						if ($samaccountname==$row['login']) $find2_login=$row['login'];
					}
					if (($find2_login=='') && ($row['disable']=='0') && ($row['login']!='') && $row['login']!=' ')
					{
						$cnt_disable=$cnt_disable+1;
						if($_GET['action']=='run')
						{
							echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/disable.png\" alt=\"disable\" /><font color=\"red\"> Utilisateur <b>$row[firstname] $row[lastname]</b> ($row[login]), désactivé.</font><br />";
							$query= "UPDATE tusers SET disable='1' WHERE login='$row[login]'";
							
							$exec = mysql_query($query) or die('Erreur SQL !<br />'.mysql_error());
						} else {
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/disable.png\" alt=\"disable\" /><font color=\"red\"> Désactivation de l'utilisateur <b>$row[firstname] $row[lastname]</b> ($row[login]). <font size=\"1\" >Raison: Utilisateur non présent dans l'annuaire LDAP.</font></font><br />";
						}
					}
						
				}
				
				if (($cnt_create=='0') && ($cnt_disable=='0') && ($cnt_maj=='0') && ($cnt_enable=='0')) echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"./images/valide_min.png\" alt=\"valid\" /><font color=\"green\"> Aucune modification à apporter, les annuaires sont à jour.</font><br />";
				echo'
				<br />
				&nbsp;&nbsp;&nbsp;&nbsp;Nombre de d\'utilisateurs à créer dans GestSup: '.$cnt_create.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;Nombre de d\'utilisateurs à mettre à jour dans GestSup: '.$cnt_maj.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;Nombre de d\'utilisateurs à désactiver dans GestSup: '.$cnt_disable.' <br />
				&nbsp;&nbsp;&nbsp;&nbsp;Nombre de d\'utilisateurs à activer dans GestSup: '.$cnt_enable.' <br />
				<br />
				<b><u>Information de Synchronisation:</u></b><br />
				&nbsp;&nbsp;&nbsp;&nbsp;La jointure inter-annuaires est réalisée sur le login, les comptes existant dans GestSup qui possèdent un login doivent être existant dans l\'annuaire LDAP.<br />


				';
		}
		if(($_GET['action']=='simul') || ($_GET['action']=='run') || ($_GET['ldap']=='1')) 
		{
			echo'
				<br />
				<div  class="buttons2">
					<form action="index.php?page=admin&amp;subpage=user&amp;ldap=1&amp;action=simul" method="post" >
						<button name="sync" id="sync" type="submit" class="positive">
							<img src="images/calc2.png" alt=""/>
							Simuler
						</button>
					</form>
	
					<form action="index.php?page=admin&amp;subpage=user&amp;ldap=1&amp;action=run" method="post" >
						<button name="sync" id="sync" type="submit" class="regular">
							<img src="images/run.png" alt=""/>
							Synchroniser
						</button>
					</form>
				</div>
				
				<br /><br /><br />
		';
		}
	} else if($_GET['subpage']=='user'){echo '<div id="erreur"><img src="./images/critical.png" alt="erreur" style="border-style: none" alt="img" /> La connection LDAP n\'est pas disponible, verifier vos paramètres de connection.</div>';}

} 
?>