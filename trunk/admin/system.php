<?php
/*
Author: Flox
Filename: system.php
Description: admin system
Version: 1.4
creation date: 12/01/2011
last update: 26/01/2013
*/

//Extraction des paramètres du phpinfo
ob_start();
phpinfo();
$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
    foreach($matches as $match)
        if(strlen($match[1]))
            $phpinfo[$match[1]] = array();
        elseif(isset($match[3])){
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
			}
        else
            {
			$ak=array_keys($phpinfo);
            $phpinfo[end($ak)][] = $match[2];
		}

// case for old version php, php info tab is PHP CORE 			
if (isset($phpinfo['Core'])!='') $vphp='Core'; else $vphp='PHP Core';
			
// initialize variables 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = '';
if(!isset($phpinfo[$vphp]['file_uploads'][0])) $phpinfo[$vphp]['file_uploads'][0] = '';
if(!isset($phpinfo[$vphp]['memory_limit'][0])) $phpinfo[$vphp]['memory_limit'][0] = '';
if(!isset($phpinfo[$vphp]['upload_max_filesize'][0])) $phpinfo[$vphp]['upload_max_filesize'][0] = '';
if(!isset($phpinfo[$vphp]['max_execution_time'][0])) $phpinfo[$vphp]['max_execution_time'][0] = '';
if(!isset($phpinfo['date']['date.timezone'][0])) $phpinfo['date']['date.timezone'][0] = '';
if(!isset($i)) $i = '';
if(!isset($openssl)) $openssl = '';
if(!isset($mysql)) $mysql = '';
if(!isset($ldap)) $ldap = '';
if(!isset($zip)) $zip = '';
?>

<?php
// MySQL basedir 
$query = mysql_query("show variables");
while ($row=mysql_fetch_array($query)) {
if ($row[0]=="version") $mysql=$row[1];
}

// Check OS
$OS=$phpinfo['phpinfo']['System'];
$OS= explode(" ",$OS);
$OS=$OS[0];
?>
<img src="./images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>Versions:</u></b><br />
	<blockquote>
		&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/<?php echo $OS; ?>.png" style="border-style: none" alt="img" /> <?php echo "<b> {$phpinfo['phpinfo']['System']}</b><br />\n"; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/apache.png" style="border-style: none" alt="img" /> <?php $apache=$phpinfo['apache2handler']['Apache Version']; $apache=preg_split('[ ]', $apache); $apache=preg_split('[/]', $apache[0]); echo "<b>Apache $apache[1] </b><br />\n"; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/mysql_min.png" style="border-style: none" alt="img" /> <?php echo "<b>Mysql $mysql</b><br />\n"; ?>
		&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/php.png" style="border-style: none" alt="img" /> <b>PHP <?php echo phpversion(); ?></b>
	</blockquote>
	<br /><br />
<img src="./images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>État des paramètres PHP:</u></b>
<blockquote>
<?php
if ($phpinfo[$vphp]['file_uploads'][0]=="On") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b>: Activée<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b> désactivée, certaines fonctions sont indisponibles.<br />';
if ($phpinfo[$vphp]['memory_limit'][0]!="") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Mémoire Allouée:</b> '.$phpinfo[$vphp]['memory_limit'][0].'<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> La mémoire allouée est trop faible.<br />';
if ($phpinfo[$vphp]['upload_max_filesize'][0]!="2M") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Upload max:</b> '.$phpinfo[$vphp]['upload_max_filesize'][0].' <i>(Il est préconisé d\'avoir une valeur supérieur ou égale à 10Mo)</i><br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Upload max: </b>'.$phpinfo[$vphp]['upload_max_filesize'][0].' <i> (Il est préconisé d\'avoir une valeur supérieur ou égale à 10Mo, afin d\'attacher des pièces jointes volumineuses)</i>.<br />';
if ($phpinfo[$vphp]['max_execution_time'][0]>="60") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Max_execution_time:</b> '.$phpinfo[$vphp]['max_execution_time'][0].'s<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Max_execution_time: </b>'.$phpinfo[$vphp]['max_execution_time'][0].'s <i>(Il est préconisé d\'avoir une valeur supérieur ou égale à 60s pour les mises à jours.)</i><br />';
if ($phpinfo['date']['date.timezone'][0]=="Europe/Paris") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Fuseau Horaire:</b> '.$phpinfo['date']['date.timezone'][0].'<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Fuseau Horaire: </b>'.$phpinfo['date']['date.timezone'][0].' <i>(Il est préconisé de modifier la valeur date.timezone du fichier php.ini, et mettre "Europe/Paris" afin de ne pas avoir de problème d\'horloge.)</i><br />';
?>
</blockquote>
<br /><br />
<img src="./images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>État des Extensions PHP:</u></b>
<blockquote>
	<?php
	$textension = get_loaded_extensions();
	$nblignes = count($textension);
	if(!isset($textension[$i])) $textension[$i] = '';
	for ($i;$i<$nblignes;$i++)
	{
	if ($textension[$i]=='mysql') $mysql="1";
	if ($textension[$i]=='openssl') $openssl="1";
	if ($textension[$i]=='ldap') $ldap="1";
	if ($textension[$i]=='zip') $zip="1";
	}
	if ($mysql=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_mysql:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> <b>php_mysql</b> Désactivé, certaines fonctions sont indisponibles.';
	echo "<br />";
	if ($openssl=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_openssl:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_openssl</b> Désactivé, si vous utilisé un serveur SMTP sécurisé les mails ne seront pas envoyés.';
	echo "<br />";
	if ($ldap=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_ldap:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_ldap</b> Désactivé, aucune synchronisation ni authentification via un serveur LDAP ne sera possible.';
	echo "<br />";
	if ($zip=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_zip:</b> Activée'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_zip</b> Désactivé, la fonction de mise à jour automatique ne sera pas possible.';

	?>
</blockquote>
<br />
<br />
<a href="./admin/phpinfos.php" target="_blank">Tous les paramètres PHP</a>