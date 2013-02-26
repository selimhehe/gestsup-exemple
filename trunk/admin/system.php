<?php
/*
Author: Flox
Filename: system.php
Description: admin system
Version: 1.4
creation date: 12/01/2011
last update: 26/01/2013
*/

//Extraction des param�tres du phpinfo
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
<img src="./images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>�tat des param�tres PHP:</u></b>
<blockquote>
<?php
if ($phpinfo[$vphp]['file_uploads'][0]=="On") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b>: Activ�e<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> <b>file_uploads</b> d�sactiv�e, certaines fonctions sont indisponibles.<br />';
if ($phpinfo[$vphp]['memory_limit'][0]!="") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>M�moire Allou�e:</b> '.$phpinfo[$vphp]['memory_limit'][0].'<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> La m�moire allou�e est trop faible.<br />';
if ($phpinfo[$vphp]['upload_max_filesize'][0]!="2M") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Upload max:</b> '.$phpinfo[$vphp]['upload_max_filesize'][0].' <i>(Il est pr�conis� d\'avoir une valeur sup�rieur ou �gale � 10Mo)</i><br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Upload max: </b>'.$phpinfo[$vphp]['upload_max_filesize'][0].' <i> (Il est pr�conis� d\'avoir une valeur sup�rieur ou �gale � 10Mo, afin d\'attacher des pi�ces jointes volumineuses)</i>.<br />';
if ($phpinfo[$vphp]['max_execution_time'][0]>="60") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Max_execution_time:</b> '.$phpinfo[$vphp]['max_execution_time'][0].'s<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Max_execution_time: </b>'.$phpinfo[$vphp]['max_execution_time'][0].'s <i>(Il est pr�conis� d\'avoir une valeur sup�rieur ou �gale � 60s pour les mises � jours.)</i><br />';
if ($phpinfo['date']['date.timezone'][0]=="Europe/Paris") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>Fuseau Horaire:</b> '.$phpinfo['date']['date.timezone'][0].'<br />'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>Fuseau Horaire: </b>'.$phpinfo['date']['date.timezone'][0].' <i>(Il est pr�conis� de modifier la valeur date.timezone du fichier php.ini, et mettre "Europe/Paris" afin de ne pas avoir de probl�me d\'horloge.)</i><br />';
?>
</blockquote>
<br /><br />
<img src="./images/extension.gif" style="border-style: none" alt="img" />&nbsp;<b><u>�tat des Extensions PHP:</u></b>
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
	if ($mysql=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_mysql:</b> Activ�e'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_min.png" style="border-style: none" alt="img" /> <b>php_mysql</b> D�sactiv�, certaines fonctions sont indisponibles.';
	echo "<br />";
	if ($openssl=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_openssl:</b> Activ�e'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_openssl</b> D�sactiv�, si vous utilis� un serveur SMTP s�curis� les mails ne seront pas envoy�s.';
	echo "<br />";
	if ($ldap=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_ldap:</b> Activ�e'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_ldap</b> D�sactiv�, aucune synchronisation ni authentification via un serveur LDAP ne sera possible.';
	echo "<br />";
	if ($zip=="1") echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/ok_min.png" style="border-style: none" alt="img" /> <b>php_zip:</b> Activ�e'; else echo '&nbsp;&nbsp;&nbsp;&nbsp;<img src="./images/critical_yellow.png" style="border-style: none" alt="img" /> <b>php_zip</b> D�sactiv�, la fonction de mise � jour automatique ne sera pas possible.';

	?>
</blockquote>
<br />
<br />
<a href="./admin/phpinfos.php" target="_blank">Tous les param�tres PHP</a>