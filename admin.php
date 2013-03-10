<?php
/*
Author: Flox
File name: admin.php
Description: admin parent page
Version: 1.3
creation date: 12/01/2011
last update: 10/12/2012
*/

// initialize variables 
if(!isset($_GET['subpage'])) $_GET['subpage'] = '';
if(!isset($_GET['profileid'])) $_GET['profileid'] = '';

//default settings
if ($_GET['subpage']=='') $_GET['subpage']='user';
if ($_GET['subpage']=='user')
if ($_GET['profileid']=='') if ($_GET['subpage']=='user') $_GET['profileid'] = '%';
if ($_GET['subpage']=='profile' && $_GET['profileid']=='') $_GET['profileid']=0;
?>

<h2 class="sec_head">Administration</h2>
<div id="catalogue">
	<?php 	if(!(isset($_SESSION['profile_id']) && $_SESSION['profile_id'] == 3)){ ?>
	<div id="downmenu">
		<ul>
			<?php
			echo '
			<li '; if ($_GET['subpage']=="parameters") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=parameters">Paramètres</a></li>
			<li '; if ($_GET['subpage']=="user") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=user">Utilisateurs</a></li>
			<li '; if ($_GET['subpage']=="profile") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=profile">Profiles</a></li>
			<li '; if ($_GET['subpage']=="list") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=list">Listes</a></li>
			<li '; if ($_GET['subpage']=="backup") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=backup">Sauvegardes</a></li>
			<li '; if ($_GET['subpage']=="update") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=update">Mise à jour</a></li>
			<li '; if ($_GET['subpage']=="system") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=system">Système</a></li>
			<li '; if ($_GET['subpage']=="workflow") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=workflow">Workflow</a></li>
			<li '; if ($_GET['subpage']=="infos") echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage=infos">Infos</a></li>
			';
			?>
		</ul>
		<ul>
			<?php
			if ($_GET['subpage']=='user' || $_GET['subpage']=='profile'){
				if ($_GET['subpage']=='user') {
					echo '<li '; if ($_GET['profileid']=='%') echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage='.$_GET['subpage'].'&amp;profileid=%">Tous</a></li>';
				}
				//Display profile table
				$query = mysql_query("select * FROM tprofiles ORDER BY level");
				while ($row=mysql_fetch_array($query)) {
					echo '<li '; if ($_GET['profileid']==$row['level']) echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage='.$_GET['subpage'].'&amp;profileid='.$row['level'].'">'.$row['name'].'</a></li>';
				}
        if ($_GET['subpage']=='user') {
          echo '<li '; if ($_GET['profileid']=='ND') echo "class=\"active\""; echo '><a href="./index.php?page=admin&amp;subpage='.$_GET['subpage'].'&amp;profileid=ND">Nouveaux inscrits</a></li>';

          }
			}
			?>
		</ul>
	</div>
	<br />
	<br />
	<?php } ?>

	<?php include "./admin/$_GET[subpage].php"; ?>
</div>