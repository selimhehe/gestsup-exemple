<?php
/*
Author: Flox
filename: event.php
Description: display popup event
Version: 1.1
Creation date: 20/07/2011
last update: 26/01/2013
*/

// initialize variables 
if(!isset($_POST['disable'])) $_POST['disable'] = ''; 

$q = mysql_query("SELECT * FROM tincidents WHERE id LIKE '$eventincident'");
$r=mysql_fetch_array($q); 

$qu = mysql_query("SELECT * FROM tusers WHERE id=$r[user]");
$ru=mysql_fetch_array($qu); 

if($_POST['disable'])
{
	$query = "UPDATE tevents SET disable='1' where id like '$eventid'";
	$exec = mysql_query($query) or die('Erreur SQL !<br /><br />'.mysql_error());
	echo"
	<script type=\"text/javascript\" language=\"javascript\">
	$().ready(function() { jqmCloseBtn}
	</script>
	";
}
echo "
<img  src=\"./images/event.png\" />
&nbsp;<b><font size=\"3\"><a href=\"./index.php?page=ticket&id=$eventincident\">Rappel ticket $eventincident: $r[title]</a></font></b>
<br /><br />
<u>Planifié à:</u> $eventtime
<br /><br />
<u>Demandeur:</u> $ru[firstname] $ru[lastname]
<br /><br />
<u>Description:</u> 
$r[description]<br />
<br /><br />


<form method=\"POST\" action=\"\" id=\"disable\"  >
<div  class=\"buttons2\">
	<button name=\"disable\" value=\"disable\" type=\"submit\" class=\"positive\">
		<img src=\"images/apply2.png\" alt=\"\"/>
		Acrediter l'alarme
	</button>

</form>
<button name=\"cancel\" value=\"cancel\" class=\"jqmClose\"  id=\"jqmCloseBtn\ class=\"negative\">
		<img src=\"images/cross.png\" alt=\"\"/>
		Fermer
</button>
</div>
";
?>



	