<?php
/*
Author: Flox
FileName: stat.php
Description: Display Statistics
Version: 2.0
Last Update Date: 01/11/2012
*/

//initialize variables 
if(!isset($select)) $select = '';
if(!isset($selected1)) $selected1 = '';
if(!isset($selected2)) $selected2 = '';
if(!isset($libgraph)) $libgraph = '';
if(!isset($selected)) $selected= '';
if(!isset($find)) $find= '';
if(!isset($subcat)) $subcat= '%';
if(!isset($category)) $category= '';
if(!isset($result)) $result= '';
if(!isset($monthm)) $monthm= '';
if(!isset($container)) $container= '';
if(!isset($_POST['tech'])) $_POST['tech']='';
if(!isset($_POST['criticality'])) $_POST['criticality']='';
if(!isset($_POST['category'])) $_POST['category']='';
if(!isset($_POST['subcat'])) $_POST['subcat']= '';
if(!isset($_POST['year'])) $_POST['year'] = '';
if(!isset($_POST['month'])) $_POST['month'] = '';
?>

<h2 class="sec_head">Statistiques</h2>
<div id="catalogue">
	<?php
	////////////////////////////////////////////START LINE CHART/////////////////////////////////////////////////////////
	//default values 
	if ($_POST['tech']=="") $_POST['tech']="%";
	if ($_POST['criticality']=="") $_POST['criticality']="%";
	if ($_POST['year']=="") $_POST['year']=date('Y');
	if ($_POST['month']=="") $_POST['month']=date('n');
	
	$mois = array();
	$mois = array(1 => "1", 2=> "F�vrier", 3=> "Mars", 4=> "Avril", 5=> "Mai", 6=> "Juin", 7=> "Juillet", 8=> "Aout", 9=> "Septembre", 10=> "Octobre", 11=> "Novembre", 12=> "D�cembre");
	
	$jour= array();
	$jour = array(1 => "1", 2=> "2", 3=> "3", 4=> "4", 5=> "5", 6=> "6", 7=> "7", 8=> "8", 9=> "9", 10=> "10", 11=> "11", 12=> "12", 13=> "13", 14=> "14", 15=> "15", 16=> "16", 17=> "17", 18=> "18", 19=> "19", 20=> "20", 21=> "21", 22=> "22", 23=> "23", 24=> "24", 25=> "25", 26=> "26", 27=> "27", 28=> "28", 29=> "29", 30=> "30", 31=> "31");
	
	//count period
	$user_id=$_SESSION['user_id'];
	$req= mysql_query( "SELECT count(*) FROM `tincidents` WHERE technician LIKE '$_POST[tech]' and criticality like '$_POST[criticality]' and date_create not like '0000-00-00'  and date_create like '$_POST[year]-$_POST[month]-%' AND disable='0'");
	$res = mysql_fetch_array($req);
	$count=$res[0];
	
	//Table d�claration
	$values = array();
	$xnom = array();
	
	//query for year selection
	if (($_POST['month'] == '%') && ($_POST['year']!=='%'))
	{
		$values = array();
		$xnom = array();
		$libchart="Nombre de tickets ouvert en $_POST[year]";
		$sql= "SELECT month(date_create) as x,count(*) as y FROM `tincidents` WHERE technician LIKE '$_POST[tech]' and criticality like '$_POST[criticality]' and date_create not like '0000-00-00'  and date_create like '$_POST[year]-$_POST[month]-%' AND disable='0' group by x ";
		$result = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		// push data in table
		while($data = mysql_fetch_array($result)){array_push($values ,$data['y']); array_push($xnom ,$mois[$data['x']]);}
	}
	//query for month selection
	else if ($_POST['month']!='%')
	{
	$values = array();
	$xnom = array();
		//debug 01 name month problem
		if ($_POST['month']<10) 
		{ 
		$monthm= explode ("0", $_POST['month']);
		$monthm=$monthm[1];
		}
		else $monthm=10;
		$libchart="Nombre de tickets ouvert en $mois[$monthm] $_POST[year]";
		$sql= "SELECT day(date_create) as x,count(*) as y FROM `tincidents` WHERE technician LIKE '$_POST[tech]' and criticality like '$_POST[criticality]' and date_create not like '0000-00-00'  and date_create like '$_POST[year]-$_POST[month]-%' AND disable='0' group by date_create ";
		$result = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		// push data in table
		while($data = mysql_fetch_array($result)){
		array_push($values ,$data['y']);
		array_push($xnom ,$jour[$data['x']]);
		}
	}
	//query for all years selection
	else if ($_POST['year']=='%')
	{
	$values = array();
	$xnom = array();
		$libchart="Nombre de tickets ouvert sur toutes les ann�es";
		$sql= "SELECT year(date_create) as x,count(*) as y FROM `tincidents` WHERE technician LIKE '$_POST[tech]' and criticality like '$_POST[criticality]' and date_create not like '0000-00-00'  and date_create like '$_POST[year]-$_POST[month]-%' AND disable='0' group by year(date_create) ";
		$result = mysql_query($sql) or die('Erreur SQL !<br />'.$sql.'<br />'.mysql_error());
		// push data in table
		while($data = mysql_fetch_array($result)){array_push($values ,$data['y']); array_push($xnom ,$data['x']);}	
	}



	if ($res[0]!=0) 
	{
		$liby="Nombre de tickets";
		$container="container1";		
		include('./stat_line.php');
	}
	else { echo '<div id="erreur"><img src="./images/critical.png" style="border-style: none" alt="img" /> Aucune valeurs pour cette selection.</div>';}
	

	////// SELECT CRITERIA PART
	?>	
	<center>
		<form method="post" action="" name="date">
			<br />
			<select name="tech" onchange=submit()>
				<?php
				$query = mysql_query("SELECT * FROM tusers WHERE profile=0 and disable=0");				
				while ($row=mysql_fetch_array($query)) {
					if ($row['id'] == $_POST['tech']) $selected1="selected" ;
					if ($row['id'] == $_POST['tech']) $find="1" ;
					echo "<option value=\"$row[id]\" $selected1>$row[firstname]</option>"; 
					$selected1="";
				} 
				echo "<option value=\"%\" >Tous les techniciens</option>";
				if ($find!="1") echo "<option value=\"%\" selected>Tous les techniciens</option>";												
				?>
			</select> 
			<select name="criticality" onchange=submit()>
				<?php
				$query = mysql_query("SELECT * FROM tcriticality ORDER BY number");				
				while ($row=mysql_fetch_array($query)) {
					if ($row['id'] == $_POST['criticality']) $selected2="selected" ;
					if ($row['id'] == $_POST['criticality']) $find="1";
					echo "<option value=\"$row[id]\" $selected2>$row[name]</option>"; 
					$selected2="";
				} 
				echo "<option "; if ($_POST['criticality']=='%') echo "selected"; echo" value=\"%\" >Toutes les criticit�s</option>";										
				?>
			</select> 
			<select name="month" onchange=submit()>
				<option value="%" <?php if ($_POST['month'] == '%')echo "selected" ?>>Tous les mois</option>
				<option value="01"<?php if ($_POST['month'] == '1')echo "selected" ?>>Janvier</option>
				<option value="02"<?php if ($_POST['month'] == '2')echo "selected" ?>>F�vrier</option>
				<option value="03"<?php if ($_POST['month'] == '3')echo "selected" ?>>Mars</option>
				<option value="04"<?php if ($_POST['month'] == '4')echo "selected" ?>>Avril</option>
				<option value="05"<?php if ($_POST['month'] == '5')echo "selected" ?>>Mai</option>
				<option value="06"<?php if ($_POST['month'] == '6')echo "selected" ?>>Juin</option>
				<option value="07"<?php if ($_POST['month'] == '7')echo "selected" ?>>Juillet</option>
				<option value="08"<?php if ($_POST['month'] == '8')echo "selected" ?>>Aout</option>
				<option value="09"<?php if ($_POST['month'] == '9')echo "selected" ?>>Septembre</option>
				<option value="10"<?php if ($_POST['month'] == '10')echo "selected" ?>>Octobre</option>
				<option value="11"<?php if ($_POST['month'] == '11')echo "selected" ?>>Novembre</option>	
				<option value="12"<?php if ($_POST['month'] == '12')echo "selected" ?>>D�cembre</option>	
			</select>

			<select name="year" onchange=submit()>
				<?php
				$q1= mysql_query("SELECT distinct year(date_create) as year FROM `tincidents` WHERE date_create not like '0000-00-00'");
				while ($row=mysql_fetch_array($q1)) 
				{ 
					$selected=0;
					if ($_POST['year']==$row['year']) $selected="selected";  
					echo "test $_POST[year]==$row[year] $selected";
					echo "<option value=$row[year] $selected>$row[year]</option>";
				}
				?>
				<option value="%" <?php if ($_POST['year'] == '%')echo "selected" ?>>Toutes les ann�es</option>
			</select>
		</form>
	</center>
	<br />
	<br />
	<?php
	////////////////////////////////////////////END LINE CHART/////////////////////////////////////////////////////////
	echo "<br /><br />";
	////////////////////////////////////////////START 1 PIE CHART/////////////////////////////////////////////////////////
	$values = array();
	$xnom = array();
	$libchart="Tickets par technicien";
	//total
	$qtotal = mysql_query("SELECT count(*) FROM tincidents WHERE disable='0'");
	$rtotal=mysql_fetch_array($qtotal);

	$query = mysql_query("select tusers.firstname as Technicien, count(*) as Resolu FROM tincidents INNER JOIN tusers ON (tincidents.technician=tusers.id ) WHERE tusers.disable=0 group by tusers.firstname order by Resolu DESC");
	while ($row=mysql_fetch_array($query)) 
	{
		$r=($row[1]/$rtotal[0])*101;
		$r=round($r, 0);
		$name=substr($row[0],0,42);
		array_push($values, $r);
		array_push($xnom, $name);
	} 		
	$container='container2';
	include('./stat_pie.php');
	echo "<div id=\"$container\" style=\"min-width: 415px; height: 230px; margin: 0 auto; float:left; \"></div>";
	////////////////////////////////////////////END 1 PIE CHART/////////////////////////////////////////////////////////
	
	////////////////////////////////////////////START 3 PIE CHART/////////////////////////////////////////////////////////
	$values = array();
	$xnom = array();
	$qtotal = mysql_query("SELECT count(*) FROM tincidents WHERE disable='0'");
	$rtotal=mysql_fetch_array($qtotal);
	$libchart="Tickets par etat";
		$query = mysql_query("
		SELECT tstates.name as sta, COUNT(*) as nb
		FROM tincidents INNER JOIN tstates ON (tincidents.state=tstates.id)
		GROUP BY tstates.number 
		ORDER BY nb
		DESC
		");
	while ($row=mysql_fetch_array($query)) 
	{
		$r=($row[1]/$rtotal[0])*101;
		$name=substr($row[0],0,42);
		array_push($values, $r);
		array_push($xnom, $name);
	} 
	$container='container3';
	include('./stat_pie.php');
	echo "<div id=\"$container\" style=\"min-width: 430px; height: 230px;  float:right;\"></div>";
	////////////////////////////////////////////END 3 PIE CHART/////////////////////////////////////////////////////////
	echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
	////////////////////////////////////////////START 2 PIE CHART/////////////////////////////////////////////////////////
	?>
	<br />
	<br />
	<a name="cam2"></a>
	<center>
		<form method="post" action="#cam2" name="category">
			<select name="category" onchange=submit()>
				<?php
				$query = mysql_query("SELECT * FROM tcategory ORDER BY name");				
				while ($row=mysql_fetch_array($query)) {
					if ($row['id'] == $_POST['category']) $selected="selected" ;
					if ($row['id'] == $_POST['category']) $find="1" ;
					echo "<option value=\"$row[id]\" $selected>$row[name]</option>"; 
					$selected="";
				} 
				echo "<option value=\"%\" >Toutes les cat�gories</option>";	
				if ($find!="1") echo "<option value=\"%\" selected>Toutes les cat�gories</option>";					
				?>
			</select> 
		</form>
	</center>
	<br />
	<?php
	$values = array();
	$xnom = array();
	$libchart="Repartition des tickets par categorie";
	//total
	if ($_POST['category']=='') $_POST['category']='%';
	$qtotal = mysql_query("SELECT count(*) FROM tincidents WHERE category NOT LIKE '0' and category LIKE '$_POST[category]'");
	$rtotal=mysql_fetch_array($qtotal);
	if ($_POST['category']!="%")
	{
		$query = mysql_query("
		SELECT tsubcat.name as cat, COUNT(*) as nb
		FROM tincidents INNER JOIN tsubcat ON (tincidents.subcat=tsubcat.id)
		WHERE tincidents.category='$_POST[category]'
		GROUP BY tsubcat.name 
		ORDER BY nb
		DESC limit 0,10
		");
	}
	else 
	{
		$query = mysql_query("
			SELECT tcategory.name as cat, COUNT(*) as nb
			FROM tincidents INNER JOIN tcategory ON (tincidents.category=tcategory.id)
			GROUP BY tcategory.name 
			ORDER BY nb
			DESC limit 0,10");
	}
		
	while ($row=mysql_fetch_array($query)) 
	{
		$r=($row[1]/$rtotal[0])*101;
		$r=round($r, 1);
		$name=substr($row[0],0,35);
		$name=str_replace("'","\'",$name); 
		array_push($values, $r);
		array_push($xnom, $name);
	} 
	$container='container4';
	include('./stat_pie.php');
	echo "<div id=\"$container\" style=\"min-width: 200px; height: 300px; margin: 0 auto\"></div>";
	////////////////////////////////////////////END 2 PIE CHART/////////////////////////////////////////////////////////

	echo "<br /><br />";
	////////////////////////////////////////////START 4 HISTO CHART/////////////////////////////////////////////////////////
	$values = array();
	$xnom = array();
	$qtotal = mysql_query("SELECT count(*) FROM tincidents");
	$rtotal=mysql_fetch_array($qtotal);
	$libchart="Charge de travail par technicien";
	$query = mysql_query("
		SELECT tusers.firstname as Technicien, ROUND((SUM(tincidents.time_hope-tincidents.time))/60) as Charge
		FROM
		tincidents 
		INNER JOIN tusers 
		ON
		(tincidents.technician=tusers.id ) WHERE 
		tusers.disable='0' AND
		tincidents.disable='0' AND
		tincidents.time_hope-tincidents.time>0 AND
		(tincidents.state='1' OR tincidents.state='2' OR tincidents.state='6')
		GROUP BY tusers.firstname ORDER BY Charge DESC
	");
	while ($row=mysql_fetch_array($query)) 
	{
		$r=$row[1];
		$name=substr($row[0],0,42);
		array_push($values, $r);
		array_push($xnom, $name);
	} 
	$container="container5";
	include('./stat_histo.php');
	echo "<div id=\"$container\" style=\"min-width: 300px; height: 400px; margin: 0 auto\"></div>";
		////////////////////////////////////////////END 4 HISTO CHART/////////////////////////////////////////////////////////
	echo "<br /><br />";
	echo "<table border=\"0\">";
	echo "<td>";
	
	echo "<table  width=\"180\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">Demandes par techniciens</th></tr>";
	$query = mysql_query("select tusers.firstname as Technicien, count(*) as Resolu FROM tincidents INNER JOIN tusers ON (tincidents.technician=tusers.id ) WHERE tusers.disable='0' and tincidents.disable='0' group by tincidents.technician order by Resolu DESC");
	while ($row=mysql_fetch_array($query)) {echo "<tr><td>$row[0]</td><td>$row[1]</td></tr>";} 
	echo "</table>";	
	
	echo "<table  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">d�lais moyen de r�solution</th></tr>";
	$query = mysql_query("select tusers.firstname, AVG(TO_DAYS(date_res) - TO_DAYS(date_create)) as jour from tincidents INNER JOIN tusers ON (tincidents.technician=tusers.id )where tincidents.technician NOT LIKE '0' AND tincidents.date_res NOT LIKE '0000-00-00' AND tincidents.date_create NOT LIKE '0000-00-00' AND tusers.disable='0' group by tincidents.technician ORDER BY jour ASC");
	while ($row=mysql_fetch_array($query)) {echo "<tr><td>$row[0]</td><td>$row[1]j</td></tr>";} 
	echo "</table>";
	
	echo "</td>";
	echo "<td>";
	
	echo "<table  width=\"180\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">Demandes par criticit�</th></tr>";
	$query = mysql_query("select tcriticality.name, count(*) as number FROM tincidents INNER JOIN tcriticality ON (tincidents.criticality=tcriticality.id ) group by tincidents.criticality  order by tcriticality.number ASC");
	while ($row=mysql_fetch_array($query)) {echo "<tr><td>$row[0]</td><td>$row[1]</td></tr>";} 
	echo "</table>";
	
	echo "<table  width=\"180\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">Demandes par priorit�</th></tr>";
	$query = mysql_query("select tpriority.name, count(*) as number FROM tincidents INNER JOIN tpriority ON (tincidents.priority=tpriority.id ) group by tincidents.priority order by tpriority.number ASC");
	while ($row=mysql_fetch_array($query)) {echo "<tr><td>$row[0]</td><td>$row[1]</td></tr>";} 
	echo "</table>";
		
	
	echo "</td>";
	echo "<td>";
	
		echo "<table width=\"180\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">Top 10 demandeurs</th></tr>";
	$query = mysql_query("select tusers.firstname as Util, tusers.lastname, count(*) as demandes FROM tincidents INNER JOIN tusers ON (tincidents.user=tusers.id )  group by tincidents.user order by demandes DESC LIMIT 10");
	while ($row=mysql_fetch_array($query)) {echo "<tr><td>$row[0] $row[1]</td><td>$row[2]</td></tr>";} 
	echo "</table>";
	
	echo "</td>";
	echo "<td>";
	
			echo "<table width=\"180\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr><th colspan=\"2\">TOP 10 consommateur de temps</th></tr>";
	$query = mysql_query("
		select tusers.firstname as Util, tusers.lastname, sum(time) as temps 
		FROM tincidents 
		INNER JOIN tusers ON (tincidents.user=tusers.id )  
		WHERE tincidents.time NOT LIKE '0'
		AND
		tincidents.time NOT LIKE '0'
		group by tincidents.user
		order by sum(time) DESC limit 10");
	while ($row=mysql_fetch_array($query)) 
	{
		$tps=$row[2]/60;
		$tps=round($tps);
		echo "<tr><td>$row[0] $row[1]</td><td width=\"20\">$tps h</td></tr>";
	} 
	echo "</table>";
	echo "</td></table>";
	?>
</div>