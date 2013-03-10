<?php
/*
Author: Flox
Filename: preview_mail.php
Description: page to preview mail
Version: 1.2
Last update: 05/10/2012
*/

// initialize variables 
if(!isset($send)) $send = ''; 
if(!isset($_POST['mail'])) $_POST['mail'] = '';
if(!isset($_POST['return'])) $_POST['return'] = '';

if ($_POST['mail'])
{
	$send=1;
	include('./core/mail.php');
}
elseif ($_POST['return'])
{
	$send=0;
	echo "
	<SCRIPT LANGUAGE='JavaScript'>
	<!--
	function redirect()
	{
	window.location='./index.php?page=ticket&id=$_GET[id]'
	}
	setTimeout('redirect()',0);
	-->
	</SCRIPT>
	";
}
else
{
	$send=0;
	include('./core/mail.php');	
	//Display preview message
	echo "<form name=\"mail\" method=\"post\" action=\"\">";
		echo "<h2 class=\"sec_head\">Paramètres du Message</h2>";
		echo "<div id=catalogue><b>Emetteur:</b> $emetteur<br  /></div>"; 
		echo "<div id=catalogue><b>Destinataire:</b> $destinataire<br /></div>";
		echo "<div id=catalogue><b>Copie:</b> $rparameters[mail_cc], 
					<select class=\"textfield\" id=\"usercopy\" name=\"usercopy\" >
					";
						$quser = mysql_query("SELECT * FROM `tusers`  WHERE mail NOT LIKE '' ORDER BY lastname ASC, firstname ASC");
						while ($row=mysql_fetch_array($quser)) {echo "<option value=\"$row[mail]\">$row[lastname] $row[firstname] </option>";}
						if ($creatorrow['mail']!=$techrow['mail']) echo "<option selected value=\"$techrow[mail]\">$techrow[lastname] $techrow[firstname]</option>"; else echo "<option selected value=\"\"></option>"; 
						echo "
					</select>,
					<select class=\"textfield\" id=\"usercopy2\" name=\"usercopy2\" >
						";
						$quser = mysql_query("SELECT * FROM `tusers` WHERE mail NOT LIKE '' ORDER BY lastname ASC, firstname ASC");
						while ($row=mysql_fetch_array($quser)) {echo "<option value=\"$row[mail]\">$row[lastname] $row[firstname]</option>";}
						echo "
						<option selected value=\"\"></option>
					</select>,
					<select class=\"textfield\" id=\"usercopy3\" name=\"usercopy3\" >
						";
						$quser = mysql_query("SELECT * FROM `tusers` WHERE mail NOT LIKE '' ORDER BY lastname ASC, firstname ASC");
						while ($row=mysql_fetch_array($quser)) {echo "<option value=\"$row[mail]\">$row[lastname] $row[firstname]</option>";}
						echo "
						<option selected value=\"\"></option>
					</select>,
					<select class=\"textfield\" id=\"usercopy4\" name=\"usercopy4\" >
						";
						$quser = mysql_query("SELECT * FROM `tusers` WHERE mail NOT LIKE '' ORDER BY lastname ASC, firstname ASC");
						while ($row=mysql_fetch_array($quser)) {echo "<option value=\"$row[mail]\">$row[lastname] $row[firstname]</option>";}
						echo "
						<option selected value=\"\"></option>
					</select>
					<br />
			   </div>";
		echo "<div id=catalogue><b>Objet:</b> $objet<br /></div>";
		echo "<div id=catalogue><b>Message:</b><br /> $msg</div>";
		
		if (($globalrow['img1']!='')||($globalrow['img2']!='')||($globalrow['img3']!=''))
		{
			echo "<div id=catalogue><b>Pièce jointe:</b><br /><br />";
			if ($globalrow['img1']!='')
			{	
				$ext = explode('.', $globalrow['img1']);
				$ext=$ext[1];
				echo "
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a  target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img1]\" style=\"text-decoration:none\"> <img border=\"0\" src=\"./images/icon_file/$ext.png\" /> </a>&nbsp;
					<a target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img1]\" >$globalrow[img1]</a>
				";
			}
			if ($globalrow['img2']!='')
			{	
				$ext = explode('.', $globalrow['img2']);
				$ext=$ext[1];
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a  target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img2]\" style=\"text-decoration:none\"> <img border=\"0\" src=\"./images/icon_file/$ext.png\" /> </a>&nbsp;
					<a target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img2]\" >$globalrow[img2]</a>
					";
			}
			if ($globalrow['img3']!='')
			{	
				$ext = explode('.', $globalrow['img3']);
				$ext=$ext[1];
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<a  target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img3]\" style=\"text-decoration:none\"> <img border=\"0\" src=\"./images/icon_file/$ext.png\" /> </a>&nbsp;
					<a target=\"about_blank\" href=\"./upload/$_GET[id]/$globalrow[img3]\" >$globalrow[img3]</a>
				";
			}
			echo "</div>";
		}
		echo '
			<div  class="buttons2">
			<br /><br />
				<button name="mail" value="Mail" type="submit" class="regular" name="mail" >
				 <img src="images/mail_icn.png" alt=""/>
					Envoyer le Message
				</button>

				<button name="return" value="return" type="submit" class="negative" name="cancel">
					<img src="images/cross.png" alt=""/>
					Annuler
				</button>
			</div>
			<br /><br /><br />
		</form>
		';
		
		
}
	
?>