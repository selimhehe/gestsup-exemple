<?php
/*
Author: Florent Hauville
File name: admin.php
Description: admin parent page
Version: 1.2
creation date: 12/01/2011
last update: 13/10/2012
*/

// initialize variables 
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';

//default settings
if ($_GET['subpage']=='') $_GET['subpage']='user';

?>

<h2 class="sec_head">Administration</h2>
<div id="catalogue">
	<div id="downmenu">
		<ul>
			<?php
			echo '
			<li '; if ($_GET['subpage']=="user") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=user">Utilisateurs</a></li>
			<li '; if ($_GET['subpage']=="profile") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=profile">Profiles</a></li>
			<li '; if ($_GET['subpage']=="list") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=list">Listes</a></li>
			<li '; if ($_GET['subpage']=="parameters") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=parameters">Paramètres</a></li>
			<li '; if ($_GET['subpage']=="backup") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=backup">Sauvegardes</a></li>
			<li '; if ($_GET['subpage']=="update") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=update">Mise à jour</a></li>
			<li '; if ($_GET['subpage']=="system") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=system">État du système</a></li>
			<li '; if ($_GET['subpage']=="infos") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=infos">Informations</a></li>
			';
			?>
		</ul>
	</div>
	<br />
	<?php include "./admin/$_GET[subpage].php"; ?>
</div>