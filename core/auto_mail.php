<?php
/*
Author: Flox
FileName: /core/auto_mail.php
Description: page to send automail
Call: ticket.php, newticket.php
Version: 1.1
Last update: 25/01/2013
*/

// initialize variables 
if(!isset($send)) $send = ''; 

//Check if open mail have already sent
$query = mysql_query("SELECT * FROM tmails WHERE incident='$_GET[id]'");
$row = mysql_fetch_array($query);

//case for send open mail
if (($_POST['state']=='1' || $_POST['state']=='2'))
{
		if ($row[0]=='')
		{
			// auto send open notification mail
			$send=1;
			include('./core/mail.php');
			//Insert mail table
			$query= "INSERT INTO tmails (incident,open,close) VALUES ('$_GET[id]','1','0')";
			$exec = mysql_query($query);
		} else {
			//open mail already sent
		}
}
//case for close close mail
if ($_POST['state']=='3')
{
	if ($row['open']=='1')
	{
		//Check if is the first close mail
		if ($row['close']=='0')
		{
			$send=1;
			// auto send close notification mail
			include('./core/mail.php');
			//Update mail table
			$query= "UPDATE tmails SET close='1' WHERE incident='$_GET[id]'";
			$exec = mysql_query($query);
		} else {
			//close mail already sent
		}
	} else {
		//close not sent because no open mail was sent
	}
}	
?>