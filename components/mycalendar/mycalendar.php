<?php
  // Section de configuration

  $bgcolor="ffffff" ;        // Couleur de fond
  $daybgcolor="aaaaaa" ;     // Couleur des jours de la semaine
  $dombgcolor="#FFDEAB" ;     // Couleur du jour s�lectionn�
  $dayholcolor="aaaaaa" ;     // Couleur des WE

  // Mois
  $month[0] = "Janvier" ;
  $month[1] = "Février" ;
  $month[2] = "Mars" ;
  $month[3] = "Avril" ;
  $month[4] = "Mai" ;
  $month[5] = "Juin" ;
  $month[6] = "Juillet" ;
  $month[7] = "Août" ;
  $month[8] = "Septembre" ;
  $month[9] = "Octobre" ;
  $month[10] = "Novembre" ;
  $month[11] = "Décembre" ;

  // Premi�re lettre des jours de la semaine
  $day[0] = "D" ;
  $day[1] = "L" ;
  $day[2] = "M" ;
  $day[3] = "M" ;
  $day[4] = "J" ;
  $day[5] = "V" ;
  $day[6] = "S" ;

  $error01 = "Erreur : date invalide"

?>
<html>
<head>
<br />
<link rel="stylesheet" href="stylepopup.css" type="text/css" />
<script language='JavaScript'>
 window.resizeTo(200,325) ;
 function modifier (jour)
 {
  window.location.href = "mycalendar.php?form=<?php echo $_GET['form'];?>&elem=<?php echo $_GET['elem'];?>&mois=" + document.forms["MyCalendar"].elements['month'].options[document.forms["MyCalendar"].elements['month'].selectedIndex].value + "&jour=" + jour +"&annee=" + document.forms["MyCalendar"].elements['year'].options[document.forms["MyCalendar"].elements['year'].selectedIndex].value

 }
<?php
  if (!isset($_GET['jour']))
       $_GET['jour'] = date("j") ;

  if (!isset($_GET['mois']))
       $_GET['mois'] = date("m") ;

  if (!isset($_GET['annee']))
       $_GET['annee'] = date("Y") ;

    // nombre de jours par mois
  $nbjmonth[0] = 31 ;
  $nbjmonth[1] = ($_GET['annee']%4==0?($_GET['annee']%100==0?($_GET['annee']%400?29:28):29):28) ;
  $nbjmonth[2] = 31 ;
  $nbjmonth[3] = 30 ;
  $nbjmonth[4] = 31 ;
  $nbjmonth[5] = 30 ;
  $nbjmonth[6] = 31;
  $nbjmonth[7] = 31 ;
  $nbjmonth[8] = 30 ;
  $nbjmonth[9] = 31 ;
  $nbjmonth[10] = 30 ;
  $nbjmonth[11] = 31 ;

  if(!checkdate($_GET['mois'],$_GET['jour'],$_GET['annee']))
  {
   echo "alert('$error01')\n" ;
   $_GET['jour'] = date("j") ;
   $_GET['mois'] = date("m") ;
   $_GET['annee'] = date("Y") ;
  }

  // Calcul du jour julien et du num�ro du jour
  $HR = 0;
  $GGG = 1;
  if( $_GET['annee'] < 1582 ) $GGG = 0;
  if( $_GET['annee'] <= 1582 && $_GET['mois'] < 10 ) $GGG = 0;
  if( $_GET['annee'] <= 1582 && $_GET['mois'] == 10 && 1 < 5 ) $GGG = 0;
  $JD = -1 * floor(7 * (floor(($_GET['mois'] + 9) / 12) + $_GET['annee']) / 4);
  $S = 1;
  if (($_GET['mois'] - 9)<0) $S=-1;
  $A = abs($_GET['mois'] - 9);
  $J1 = floor($_GET['mois'] + $S * floor($A / 7));
  $J1 = -1 * floor((floor($J1 / 100) + 1) * 3 / 4);
  $JD = $JD + floor(275 * $_GET['mois'] / 9) + 1 + ($GGG * $J1);
  $JD = $JD + 1721027 + 2 * $GGG + 367 * $_GET['annee'] - 0.5;



  /*$tmp = ((int)(($_GET['mois']>2?$_GET['annee']:$_GET['annee']-1)/100)) ;
  $jj = (int)((((int)(365.25*($_GET['mois']>2?$_GET['annee']:$_GET['annee']-1))) + ((int)(30.6001*($_GET['mois']>2?$_GET['mois']+1:$_GET['mois']+13))) + $_GET['jour'] + 1720994.5 + ($_GET['annee'] > 1582 && $_GET['mois'] > 10 && $_GET['jour'] > 15?2-$tmp+((int)($tmp/4)):0))) ;
  $jj = (int)(($jj) % 7)*/
  $jj = (($JD+.5)%7) ;
?>
</script>
</head>
<?php
  echo "<body  onUnLoad=''>\n" ;

  echo "<center><form name='MyCalendar'>\n" ;
  echo "<table width='170' cellspacing='0' cellspading='0' border='0'><tr>\n" ;

  // Affichage de la s�lection du mois et de l'ann�e
  echo "<td><select name='month' onChange=\"modifier($_GET[jour])\">\n" ;

  for ($i=0;$i<12;$i++)
  {
   echo "<option value='".($i+1)."'".($_GET['mois']==($i+1)?" selected":"").">".$month[$i]."</option>\n" ;
  }

  echo "</select></td>\n" ;

  echo "<td align='right'><select name='year' onChange=\"modifier($_GET[jour])\">\n" ;

  $y = date("Y") ;
  for ($i=$y-10;$i<$y+10;$i++)
  {
   echo "<option value='$i'".($_GET['annee']==($i)?" selected":"").">$i</option>\n" ;
  }

  echo "</select></td></tr><tr><td colspan='2'>&nbsp;</td></tr>\n" ;

  echo "<tr><td colspan='2'><table width='100%' cellspacing='0' cellspading='0' border='0'>\n" ;
  echo "<tr>\n" ;

  // Affichage des jours
  for($i=0;$i<7;$i++)
  {
   echo "<td width='14%' bgcolor='#$daybgcolor'><font id='general'>".$day[$i]."</font></td>" ;
  }

  echo "</tr>\n<tr><td colspan='7'> </td></tr>\n<tr>\n" ;

  // Premi�re ligne des jours
  $j = $jj ;//date ("w", mktime (0,0,0,$_GET['mois'],1,$_GET['annee'])) ;
  $dom = 1 ;
  for ($i=0;$i<7;$i++)
  {
   if ($j<=$i)
   {
        echo "<td".($dom==$_GET['jour']?" bgcolor='#$dombgcolor'":"")."><a href='javascript:modifier($dom)'><font id='general'>".$dom++."</font></a></td>\n" ;
   }
   else
       echo "<td>&nbsp;</td>\n" ;
  }

  echo "</tr>\n" ;
  // Le reste
  for ($i=0;$i<5;$i++)
  {
   echo "<tr>\n" ;
   for ($j=0;$j<7;$j++)   
   {    
	$j_inac = ($j==0 || $j==6) ;
	
	if($dom < $nbjmonth[($_GET['mois']-1)])
         echo "<td".($dom==$_GET['jour']?" bgcolor='#$dombgcolor'":($j_inac ?" bgcolor='#$dayholcolor'":""))."><a href='javascript:modifier($dom)'><font id='general'>".$dom++."</font></a></td>\n" ;
    else if (checkdate($_GET['mois'],$dom,$_GET['annee']))
         echo "<td".($dom==$_GET['jour']?" bgcolor='#$dombgcolor'":($j_inac ?" bgcolor='#$dayholcolor'":""))."><a href='javascript:modifier($dom)'><font id='general'>".$dom++."</font></a></td>\n" ;
    else
         echo "<td>&nbsp;</td>\n" ;

   }
   echo "</tr>\n" ;
  }

  echo "\n<tr><td colspan='10' align='center'><input type='button' onclick='window.opener.document.forms[\"$_GET[form]\"].elements[\"$_GET[elem]\"].value=\"$_GET[annee]-$_GET[mois]-$_GET[jour]\";window.close()' value='Valider'>&nbsp;&nbsp;<input onclick='window.close()' type='button' value='Annuler'></td></tr></table>\n" ;

  echo "\n</tr></table>\n" ;

  echo "</td></tr></table>" ;
  echo "</form></center>" ;

  echo "</body>\n" ;
?>
</html>
