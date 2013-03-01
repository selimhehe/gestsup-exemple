<?php
/*
Author: Flox
File name: planning.php
Description: display planning
Version: 1.0
Creation date: 28/12/2012
Last update: 24/01/2013
*/

// initialize variables 
if(!isset($_GET['view'])) $_GET['view'] = '';
if(!isset($mon_color)) $mon_color = '';
if(!isset($tue_color)) $tue_color = '';
if(!isset($wed_color)) $wed_color = '';
if(!isset($thu_color)) $thu_color = '';
if(!isset($fri_color)) $fri_color = '';
if(!isset($sat_color)) $sat_color = '';
if(!isset($sun_color)) $sun_color = '';
if(!isset($cursor)) $cursor = '';
if(!isset($previous)) $previous = '';
if(!isset($next)) $next = '';

if(!isset($_GET['next'])) $_GET['next'] = '';
if(!isset($_GET['previous'])) $_GET['previous'] = '';
if(!isset($_GET['cursor'])) $_GET['cursor'] = '';
if(!isset($_GET['delete'])) $_GET['delete'] = '';

//default settings
if ($_GET['view']=='') $_GET['view']="week";
if ($next=='') $next=0;
if ($previous=='') $previous=0;


//calc dates
$cursor=$_GET['cursor']+$_GET['next']-+$_GET['previous'];
$current = date("Y-m-d H:i");
$week = date("W")-1 + $cursor;
$year = date("Y");

$monday=strtotime('First Monday January '.$year.' +'.($week-1).' Week');
$tuesday=strtotime('First Tuesday January '.$year.' +'.($week-1).' Week');
$wednesday=strtotime('First Wednesday January '.$year.' +'.($week).' Week');
$thursday=strtotime('First Thursday January '.$year.' +'.($week).' Week');
$friday=strtotime('First Friday January '.$year.' +'.($week).' Week');
$saturday=strtotime('First Saturday January '.$year.' +'.($week).' Week');
$sunday=strtotime('First Sunday January '.$year.' +'.($week).' Week');

$frday = array ('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
$frmonth = array ('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre');


//Delete events
if($_GET['delete']!='')
{
//disable ticket
$query = "DELETE FROM tevents WHERE incident=$_GET[delete]";
$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
}



//Display Head
echo "<h2 class=\"sec_head\"> "; 

if ($_GET['view']=='day') echo 'Planning du '.$frday[date('w')].' '.date("d/m/Y"); 
if ($_GET['view']=='week') echo 'Planning du '.date("d/m/Y", strtotime('First Monday January '.$year.' +'.($week-1).' Week')).' au '.date("d/m/Y", strtotime('First Monday January '.$year.' +'.$week.' Week -1 day')); 

echo "</h2>";
echo'
<br />
<div id="downmenu">
		<ul>
			<li '; if ($_GET['view']=="week") echo "class=\"active\""; echo '><a href="./index.php?page=planning&view=week">Semaine</a></li>
		</ul>
		</div>
		&nbsp;&nbsp;
		<a href="./index.php?page=planning&view=week&cursor='.$cursor.'&previous=1"><img alt="img" src="./images/left.png" title="Semaine précédente" /></a>
		<a href="./index.php?page=planning&view=week&cursor='.$cursor.'&next=1"><img alt="img" src="./images/right.png" title="Semaine suivante" /></a>

';

////////////////////////////////////////////////////////////WEEK VIEW//////////////////////////////////////////////////////////////////
if ($_GET['view']=='week') 
{
	$period='Semaine '.date("W"); 
	$date=date("Y-m-d");
	//find day for display green on currrent day
	if(date("D")=='Mon' && date("j")==date("d", $monday)) $mon_color='bgcolor="#CEF6CE"';
	if(date("D")=='Tue' && date("j")==date("d", $tuesday)) $tue_color='bgcolor="#CEF6CE"';
	if(date("D")=='Wed' && date("j")==date("d", $wednesday)) $wed_color='bgcolor="#CEF6CE"';
	if(date("D")=='Thu' && date("j")==date("d", $thursday)) $thu_color='bgcolor="#CEF6CE"';
	if(date("D")=='Fri' && date("j")==date("d", $friday)) $fri_color='bgcolor="#CEF6CE"';

	$sat_color='bgcolor="#F2F5A9"';
	$sun_color='bgcolor="#F2F5A9"';
	
	echo"<table>";
	echo '<th colspan="8">Semaine '.date("W", strtotime('First Monday January '.$year.' +'.($week-1).' Week')).' </th>';
	//Display first Line
	echo '<tr>
			<td></td>
			<td '.$mon_color.' align="center">
				<b>
				'.$frday[date("w", $monday)].'
				'.date("d", $monday).'
				'.$frmonth[date("m", $monday)-1].' 
				</b>
			</td>
			<td '.$tue_color.' align="center">
				<b>
				'.$frday[date("w", $tuesday)].'
				'.date("d", $tuesday).'
				'.$frmonth[date("m", $tuesday)-1].' 
				</b>
			</td>
			<td '.$wed_color.' align="center">
				<b>
				'.$frday[date("w", $wednesday)].'
				'.date("d", $wednesday).'
				'.$frmonth[date("m", $wednesday)-1].' 
				</b>
			</td>
			<td '.$thu_color.' align="center">
				<b>
				'.$frday[date("w", $thursday)].'
				'.date("d", $thursday).'
				'.$frmonth[date("m", $thursday)-1].' 
				</b>
			</td>
			<td '.$fri_color.' align="center">
				<b>
				'.$frday[date("w", $friday)].'
				'.date("d", $friday).'
				'.$frmonth[date("m", $friday)-1].' 
				</b>
			</td>
			<td '.$sat_color.' align="center">
				<b>
				'.$frday[date("w", $saturday)].'
				'.date("d", $saturday).'
				'.$frmonth[date("m", $saturday)-1].' 
				</b>
			</td>
			<td '.$sun_color.' align="center">
				<b>
				'.$frday[date("w", $sunday)].'
				'.date("d", $sunday).'
				'.$frmonth[date("m", $sunday)-1].' 
				</b>
			</td align="center">
	</tr>';
	//Display each time line
	for ($i = 7; $i <= 19; $i++) 
	{ 
		echo '
		<tr>
			<td><b>'.$i.'h</b></td>
			<td '.$mon_color.'>';
				// find Monday date
				$date=date("Y-m-d", strtotime('First Monday January '.$year.' +'.($week-1).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';
				}
			echo '
			</td>
			<td '.$tue_color.' >';
				// find Tuesday date
				$date=date("Y-m-d", strtotime('First Tuesday January '.$year.' +'.($week-1).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';

				}
			echo '
			</td>
			<td '.$wed_color.'>';
				// find Wednesday date
				$date=date("Y-m-d", strtotime('First Wednesday January '.$year.' +'.($week).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';

				}
			echo '
			</td>
			<td '.$thu_color.'>';
				// find Tursday date
				$date=date("Y-m-d", strtotime('First Thursday January '.$year.' +'.($week).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';

				}
			echo '
			</td>
			<td '.$fri_color.'>';
				// find Friday date
				$date=date("Y-m-d", strtotime('First Friday January '.$year.' +'.($week).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';

				}
			echo '
			</td>
			<td '.$sat_color.'>';
				// find Saturday date
				$date=date("Y-m-d", strtotime('First Saturday January '.$year.' +'.($week).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00')) ");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';
				}
			echo '
			</td>
			<td '.$sun_color.'>';
				// find Sunday date
				$date=date("Y-m-d", strtotime('First Synday January '.$year.' +'.($week).' Week'));
				$query= mysql_query("SELECT * FROM tevents WHERE technician=$_SESSION[user_id] AND (date_start='$date $i:00' OR date_end='$date $i:00' OR (date_start<'$date $i:00' AND date_end>'$date $i:00'))");
				$row = mysql_fetch_array($query);
				if ($row['incident']!='')
				{
					if ($row['type']==1) $type='<img src="./images/clock2.png" border="0" />'; else $type='<img src="./images/planning.png" border="0" />';
					$query= mysql_query( "SELECT * FROM `tincidents` WHERE id=$row[incident] ");
					$row = mysql_fetch_array($query);
					echo '<a title="Voir le ticket '.$row['id'].'" href="./index.php?page=ticket&id='.$row['id'].'">'.$type.' '.$row['title'].'</a>';
					echo '<a title="Supprimer cet évenement" href="./index.php?page=planning&view='.$_GET['view'].'&cursor='.$_GET['cursor'].'&next='.$_GET['next'].'&delete='.$row['id'].'"><img src="./images/delete.png" /></a>';

				}
			echo '
			</td>
		</tr>';
	}
		
	echo "</table>";
} 
?>