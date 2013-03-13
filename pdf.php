<?php
session_start();
if ($_SESSION['user_id']){
	require "connect.php";	
	$qparameters = mysql_query("SELECT * FROM `tparameters`"); 
	$rparameters= mysql_fetch_array($qparameters);
	
	
	
	// ##### PDF
		
		$html .= '<table>';
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
		$html .= '<table border="1" style="border-collapse: collapse;width:100%;">';
		$html .= '<tr>
					<td>
						<br /><center><h3 background="color:#186185;">Liste des demandes</h3></center><br /><br />
					</td>
				</tr>
				<tr>
					<td style="padding:16px 20px 20px;">
							<br /><strong>Entrprise :</strong> XXXXXXXXXX
							<br /><strong>Contact :</strong> M. XXXXXXX XXXXXX
							<br /><strong>Email :</strong> XXXXXXX@XXXXXX.com
							<br /><strong>Tél. :</strong> 123456789 <br />
							<br />
					</td>
				</tr>
				<tr>
					<td>
						<table border="1" style="border-collapse: collapse;width:680px;">
							<tr>
								<td>Ticket</td>
								<td>Demandeur</td>
								<td>Technicien</td>
								<td>Date de la demande</td>
								<td>Temps passé</td>
								<td>Statut</td>
								<td>Prix</td>
							</tr>
							<tr>
								<td>1</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>23</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>35</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>37</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>50</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>99</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td colspan="6" align="right"><strong>Total : </strong></td>
								<td>2300€30</td>
							</tr>
						</table>
					</td>
				</tr>';
		$html .= '</table>';
		
		$html .= '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
		
		
		
		
		if(1){
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