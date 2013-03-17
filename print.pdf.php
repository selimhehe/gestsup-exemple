<?php
session_start();
if ($_SESSION['user_id']){
	require "connect.php";	
	$qparameters = mysql_query("SELECT * FROM `tparameters`"); 
	$rparameters= mysql_fetch_array($qparameters);
	
	$etendu = isset($_GET['etendu']) && $_GET['etendu'] == 1 ? true : false ;
	$showPdf = true;
	
	// ##### PDF
	$profile = isset($_SESSION['pdf']['profile']) ? $_SESSION['pdf']['profile'] : 'usery' ;
	//echo'<pre>'; print_r($_SESSION['pdf']); echo '<pre>'; 
		
	$html = '<table>';
	$html .= '<tr>
				<td>
					<img style="border-style:none" alt="logo" src="./upload/logo/'.  (isset($rparameters['logo']) ? $rparameters['logo'] : '') .'" />
				</td>
				<td>
					<h2>Gestion de Support '. $rparameters['company'] .'</h2>
				</td>
			</tr>';
	$html .= '</table>';
	$html .= '<hr /><br /><br />';
	
	/////////////////////////////
	// Groupes
	
	$SQL = "Select * From tcompany";
	$SQL .= " where id in (Select group_id From tusers)";
	//if(isset($_SESSION['pdf']['userid']) && $_SESSION['pdf']['userid'] != ''){
		//$SQL .= " And responsible = ".$_SESSION['pdf']['userid'];
	//}
	if(isset($_SESSION['pdf']['groupe']) && $_SESSION['pdf']['groupe'] != ''){
		$SQL .= " And id = ".$_SESSION['pdf']['groupe'];
	}
	if(isset($_SESSION['pdf']['res']) && $_SESSION['pdf']['res'] != ''){
		$SQL .= " And responsible = ".$_SESSION['pdf']['res'];
	}
	//echo '<br /><br /> SQL1 ==>' .  $SQL ;
	$gRows = mysql_query($SQL);
	
	while( $gRow = mysql_fetch_assoc( $gRows ) ){
	/////////////////////////////
		$gHtml = '';
		$gHtml .= '<table width="100%" border="1" cellspacing="0" cellpadding="4" style="border-collapse:collapse; font-size:12px;font-family:arial;background:#F8F8F8">';
		$gHtml .= '<tr>
				<td width="120" style="background:#EDEDED;">'. $gRow['nom_groupe'] .'</td>
				<td colspan="7" style="background:#EDEDED;">'. utf8_encode( $gRow['rue'] .' - '. $gRow['code_postal'] .' '. $gRow['ville'] .' - '. $gRow['civilite'] .' '. $gRow['prenom'] .' '. $gRow['nom']) .'</td>
			</tr>';
		
		// Demandeurs
		$SQL2 = 'Select * From tusers';
		$SQL2 .= ' Where group_id ='.$gRow['id'].' And id in (Select user From tincidents)';
		if(isset($_SESSION['pdf']['demandeure']) && $_SESSION['pdf']['demandeure'] != ''){
			$SQL2 .= " And id = ".$_SESSION['pdf']['demandeure'];
		}
		//echo '<br /><br /> SQL2 ==>' .  $SQL2 ;
		$uRows = mysql_query($SQL2);
		$num_uRows = mysql_num_rows($uRows);
		$uHtml = '';
		$num_tRows = 0 ;
		$sum_num_tRows = 0;
		while( $uRow = mysql_fetch_assoc( $uRows ) ){
			// Tickets
			$SQL3 = 'Select i.*, p.name as pname, c.name as cname, c.color as ccolor, s.name as sname, s.description, s.mail_object 
			From tincidents as i
			LEFT JOIN tpriority as p ON i.priority = p.id
			LEFT JOIN tcriticality as c ON i.criticality = c.id
			LEFT JOIN tstates as s ON i.state = s.id
			Where i.user ='.$uRow['id'];
			
			if(isset($_SESSION['pdf']['techid']) && $_SESSION['pdf']['techid'] != ''){
				$SQL3 .= " AND i.".$profile." LIKE '". $_SESSION['pdf']['techid'] ."'";
				// 
			}
			$SQL3 .="
			AND	i.technician LIKE '".$_SESSION['pdf']['technician']."'
			AND	i.disable='0'
			AND	(i.category LIKE '".$_SESSION['pdf']['category'] ."')
			AND	i.subcat LIKE '". $_SESSION['pdf']['subcat'] ."'
			AND	i.id LIKE '". $_SESSION['pdf']['ticket'] ."'
			AND	i.date_create LIKE '".$_SESSION['pdf']['date']."'
			AND	i.state LIKE '".$_SESSION['pdf']['fstate']."'
			AND	i.priority LIKE '".$_SESSION['pdf']['priority']."'
			AND	i.criticality LIKE '".$_SESSION['pdf']['criticality']."'
			AND	i.title LIKE '%".$_SESSION['pdf']['title']."%'"; 
			
			if( isset($_SESSION['pdf']['date_1']) && isset($_SESSION['pdf']['date_2']) && $_SESSION['pdf']['date_1'] != '' && $_SESSION['pdf']['date_2'] != ''){
				$SQL3 .=" AND i.date_create BETWEEN '".$_SESSION['pdf']['date_1']."' AND '".$_SESSION['pdf']['date_2']."'";
			}elseif( isset($_SESSION['pdf']['date_1']) && isset($_SESSION['pdf']['date_2']) && $_SESSION['pdf']['date_1'] != '' && $_SESSION['pdf']['date_2'] == ''){
				$SQL3 .=" AND i.date_create >= '".$_SESSION['pdf']['date_1']."'";
			}elseif( isset($_SESSION['pdf']['date_1']) && isset($_SESSION['pdf']['date_2']) && $_SESSION['pdf']['date_1'] == '' && $_SESSION['pdf']['date_2'] != ''){
				$SQL3 .=" AND i.date_create <= '".$_SESSION['pdf']['date_2']."'";
			}
			
			if(isset($_SESSION['profile_id']) && $_SESSION['profile_id'] == 3){
				$SQL3 .= " AND	i.user in (Select u.id 
				From tusers as u 
				LEFT JOIN tcompany as c on c.id = u.group_id 
				Where c.responsible = ". $_SESSION['user_id'] .")" ;
			}else{
				$SQL3 .= " AND	i.user LIKE '".$_SESSION['pdf']['userid']."'";
			}
			
			//echo '<br /><br /> SQL3 ==>' . $SQL3 ;
			$tRows = mysql_query($SQL3);
			$num_tRows = mysql_num_rows($tRows);
			$first = true;
			$tHtml = '';
			while( $tRow = mysql_fetch_assoc( $tRows ) ){
				
					$rowspan = $etendu ? $num_tRows * 4 : $num_tRows * 2 ; 
					
					$tHtml .= '<tr>';
					if($first){
						$tHtml .= '<td style="border-top:3px solid #222;" rowspan="'.$rowspan.'"><strong>'. $uRow['firstname'].' '.$uRow['lastname'].'</strong> <em>('. $num_tRows .')</em></td>'; 
						$first = false; 
					}
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;" width="100px"><b>Date demande</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Titre</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Priorité</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Criticité</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Etat</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Date de résolution estimée</b></td>';
					$tHtml .= '<td style="border-top:3px solid #222;color:#014782;"><b>Temps passé</b></td>';
					$tHtml .= '</tr>';
					
					$tHtml .= '<tr>';
					$tHtml .= '<td width="100px">'. utf8_encode( $tRow['date_create'] ) .'</td>';
					$tHtml .= '<td>'. utf8_encode( $tRow['title'] ) .'</td>';
					$tHtml .= '<td>'. utf8_encode( $tRow['pname'] ) .'</td>';
					$tHtml .= '<td><span style="color:'. utf8_encode( $tRow['ccolor'] ) .'" >'. utf8_encode( $tRow['cname'] ) .'</span></td>';
					$tHtml .= '<td>'. utf8_encode( $tRow['sname'] ) .'</td>';
					$tHtml .= '<td>'. utf8_encode( $tRow['date_hope'] ) .'</td>';
					$tHtml .= '<td>'. utf8_encode( $tRow['time'] ) .'</td>';
					$tHtml .= '</tr>';
					if($etendu){
					$tHtml .= '<tr>';
					$tHtml .= '<td> Note > </td>';
					$tHtml .= '<td colspan="6">'. utf8_encode( $tRow['description'] ) .'</td>';
					$tHtml .= '</tr>';
					//$tHtml .= '<tr><td colspan="7" style="background:#FFF;"> </td></tr>';
					$tHtml .= '<tr>';
					$tHtml .= '<td> Résolution > </td>';
					$tHtml .= '<td colspan="6">'. utf8_encode( $tRow['resolution'] ) .'</td>';
					$tHtml .= '</tr>';
					//$tHtml .= '<tr><td colspan="7" style="background:#FFF;"> </td></tr>';
					}
			}
			$uHtml .= $num_tRows > 0 ? $tHtml : '';
			$sum_num_tRows += $num_tRows;
		}
		$gHtml .= $uHtml;
		$gHtml .= '</table>';
		$gHtml .= '<br />';
		/////////////////////////
		$html .= $num_uRows > 0 && $sum_num_tRows > 0 ? $gHtml : '';
		}
		
		//////////////////////////
		
		if($showPdf){ // 0 => Mode Dev ; 1 => Mode PDF 
			include("components/mpdf/mpdf.php");
			$mpdf=new mPDF('utf-8', 'A4'); 
			
			
			$mpdf->defaultheaderfontsize = 10;	/* in pts */
			$mpdf->defaultheaderfontstyle = B;	/* blank, B, I, or BI */
			$mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

			$mpdf->defaultfooterfontsize = 12;	/* in pts */
			$mpdf->defaultfooterfontstyle = B;	/* blank, B, I, or BI */
			$mpdf->defaultfooterline = 1; 	/* 1 to include line below header/above footer */


			$mpdf->SetHeader('Gestion de Support '. $rparameters['company'].'||{DATE j/m/Y}');
			$mpdf->SetFooter('{PAGENO}');	/* defines footer for Odd and Even Pages - placed at Outer margin */
			
			$mpdf->WriteHTML($html);
			$mpdf->Output();
			exit;
		}else{
			echo $html ;
		}

	
}else{

	echo '<script language="Javascript">
	<!--
		history.back();
	// -->
	</script>';

}