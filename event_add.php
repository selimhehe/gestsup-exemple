<?php
/*
Author: Flox
FileName: event_add.php
Description: add user
Version: 1.1
Last Update: 24/01/2013
*/

// initialize variables 
if(!isset($_GET['technician'])) $_GET['technician'] = ''; 
if(!isset($_GET['planning'])) $_GET['planning'] = ''; 
if(!isset($date)) $date = ''; 
if(!isset($hour)) $hour = ''; 
if(!isset($_GET['id'])) $_GET['id'] = ''; 
if(!isset($_POST['Valider'])) $_POST['Valider'] = ''; 
if(!isset($_POST['Modifier'])) $_POST['Modifier'] = ''; 
?>

<link href="style.css" rel="stylesheet" type="text/css" />
<div id="content">
	<?php
	//db modify
	require "./connect.php";
	if($_POST['Valider'])
	{
		if ($_GET['planning']!=1)
		{
			if ($_POST['direct']) $date=$_POST['direct']; else  $date="$_POST[date] $_POST[hour]";
			$requete = "INSERT INTO tevents (technician,incident,date_start,type) VALUES ('$_GET[technician]','$_GET[id]','$date','1')";
			$execution = mysql_query($requete);
		} else {
			$requete = "INSERT INTO tevents (technician,incident,date_start,date_end,type) VALUES ('$_GET[technician]','$_GET[id]','$_POST[date] $_POST[hour]','$_POST[date_fin] $_POST[hour_fin]','2')";
			$execution = mysql_query($requete);
		}
		
		echo"
		<script type=\"text/javascript\" language=\"javascript\">
		window.close();
		</script>
		";
	}
	//calc dates
	$date = date("Y-m-d H:i");
	
	$day= date('Y-m-d',strtotime("+1 day ", strtotime($date)));
	$afterday= date('Y-m-d',strtotime("+2 day ", strtotime($date)));
	$week= date('Y-m-d',strtotime("+7 day ", strtotime($date)));
	$month= date('Y-m-d',strtotime("+1 month ", strtotime($date)));
	$year= date('Y-m-d',strtotime("+1 year ", strtotime($date)));

	if ($_GET['id']!="")
	{
		if ($_GET['planning']!=1)
		{
			echo "
			<h2> Ajouter un rappel</h2>
			<br />
			<form name=\"form\" enctype=\"multipart/form-data\" method=\"post\" action=\"\" id=\"thisform\">
				<label>Date:</label> 
				<input style=\"display: inline;\" class=\"textfield\" type=\"text\" name=\"date\"  value=\"\" ><img src=\"./images/calendar.png\" value=\"Calendrier\" onClick=\"window.open('components/mycalendar/mycalendar.php?form=form&elem=date','Calendrier','width=400,height=400')\">
				<br />
				<label>Heure:</label> 
				<select class=\"textfield\" id=\"hour\" name=\"hour\" >
					<option value=\"00:00:00\">00h00</option>
					<option value=\"01:00:00\">01h00</option>
					<option value=\"02:00:00\">02h00</option>
					<option value=\"04:00:00\">03h00</option>
					<option value=\"05:00:00\">05h00</option>
					<option value=\"06:00:00\">06h00</option>
					<option value=\"07:00:00\">07h00</option>
					<option selected value=\"08:00:00\">08h00</option>
					<option value=\"09:00:00\">09h00</option>
					<option value=\"10:00:00\">10h00</option>
					<option value=\"11:00:00\">11h00</option>
					<option value=\"12:00:00\">12h00</option>
					<option value=\"13:00:00\">13h00</option>
					<option value=\"14:00:00\">14h00</option>
					<option value=\"15:00:00\">15h00</option>
					<option value=\"16:00:00\">16h00</option>
					<option value=\"17:00:00\">17h00</option>
					<option value=\"18:00:00\">18h00</option>
					<option value=\"19:00:00\">19h00</option>
					<option value=\"20:00:00\">20h00</option>
					<option value=\"21:00:00\">21h00</option>
					<option value=\"22:00:00\">22h00</option>
					<option value=\"23:00:00\">23h00</option>
				</select>
				<br />
				<br />
				----------------------OU--------------------<br /><br />
			<input type=\"radio\" name=\"direct\" value=\"$day 08:00:00\"> Demain <br />
			<input type=\"radio\" name=\"direct\" value=\"$afterday 08:00:00\"> Après demain <br />
			<input type=\"radio\" name=\"direct\" value=\"$week 08:00:00\"> Dans une semaine <br />
			<input type=\"radio\" name=\"direct\" value=\"$month 08:00:00\"> Dans un mois <br />
			<input type=\"radio\" name=\"direct\" value=\"$year 08:00:00\"> Dans un ans<br />
			";
		} else {
			echo "
			<h2> Planifier une intervention</h2>
			<br />
			<form name=\"form\" enctype=\"multipart/form-data\" method=\"post\" action=\"\" id=\"thisform\">
				<label>Date Début:</label> 
				<input style=\"display: inline;\" class=\"textfield\" type=\"text\" name=\"date\"  value=\"\" ><img src=\"./images/calendar.png\" value=\"Calendrier\" onClick=\"window.open('components/mycalendar/mycalendar.php?form=form&elem=date','Calendrier','width=400,height=400')\">
				<br />
				<label>Heure Début:</label> 
				<select class=\"textfield\" id=\"hour\" name=\"hour\" >
					<option value=\"00:00:00\">00h00</option>
					<option value=\"01:00:00\">01h00</option>
					<option value=\"02:00:00\">02h00</option>
					<option value=\"04:00:00\">03h00</option>
					<option value=\"05:00:00\">05h00</option>
					<option value=\"06:00:00\">06h00</option>
					<option value=\"07:00:00\">07h00</option>
					<option selected value=\"08:00:00\">08h00</option>
					<option value=\"09:00:00\">09h00</option>
					<option value=\"10:00:00\">10h00</option>
					<option value=\"11:00:00\">11h00</option>
					<option value=\"12:00:00\">12h00</option>
					<option value=\"13:00:00\">13h00</option>
					<option value=\"14:00:00\">14h00</option>
					<option value=\"15:00:00\">15h00</option>
					<option value=\"16:00:00\">16h00</option>
					<option value=\"17:00:00\">17h00</option>
					<option value=\"18:00:00\">18h00</option>
					<option value=\"19:00:00\">19h00</option>
					<option value=\"20:00:00\">20h00</option>
					<option value=\"21:00:00\">21h00</option>
					<option value=\"22:00:00\">22h00</option>
					<option value=\"23:00:00\">23h00</option>
				</select>
				<br />
				<br />
				<label>Date de fin:</label> 
				<input style=\"display: inline;\" class=\"textfield\" type=\"text\" name=\"date_fin\"  value=\"\" ><img src=\"./images/calendar.png\" value=\"Calendrier\" onClick=\"window.open('components/mycalendar/mycalendar.php?form=form&elem=date_fin','Calendrier','width=400,height=400')\">
				<br />
				<label>Heure de fin:</label> 
				<select class=\"textfield\" id=\"hour_fin\" name=\"hour_fin\" >
					<option value=\"00:00:00\">00h00</option>
					<option value=\"01:00:00\">01h00</option>
					<option value=\"02:00:00\">02h00</option>
					<option value=\"04:00:00\">03h00</option>
					<option value=\"05:00:00\">05h00</option>
					<option value=\"06:00:00\">06h00</option>
					<option value=\"07:00:00\">07h00</option>
					<option selected value=\"08:00:00\">08h00</option>
					<option value=\"09:00:00\">09h00</option>
					<option value=\"10:00:00\">10h00</option>
					<option value=\"11:00:00\">11h00</option>
					<option value=\"12:00:00\">12h00</option>
					<option value=\"13:00:00\">13h00</option>
					<option value=\"14:00:00\">14h00</option>
					<option value=\"15:00:00\">15h00</option>
					<option value=\"16:00:00\">16h00</option>
					<option value=\"17:00:00\">17h00</option>
					<option value=\"18:00:00\">18h00</option>
					<option value=\"19:00:00\">19h00</option>
					<option value=\"20:00:00\">20h00</option>
					<option value=\"21:00:00\">21h00</option>
					<option value=\"22:00:00\">22h00</option>
					<option value=\"23:00:00\">23h00</option>
				</select>
				<br />
				<br />
				";
		}
			echo "
			<br />
			
							<div  class=\"buttons2\">
				<br />
				<button name=\"Valider\" value=\"Valider\" type=\"submit\"  class=\"positive\">
					<img src=\"images/apply2.png\" alt=\"\"/>
					Valider
				</button>

				<button name=\"cancel\" value=\"cancel\" onclick='window.close()'  type=\"submit\" class=\"negative\">
					<img src=\"images/cross.png\" alt=\"\"/>
					Fermer
				</button>
				<br /><br /><br />
			</div>
		</form>
		<br />
		";
	}
	?>
</div>
