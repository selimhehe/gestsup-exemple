<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
﻿<?php
	include 'functions.php' ;
	
	$item = array();
	$showForm = true ;
	$currentPath = 'index.php?page=company';
	if(isset($_POST['type_groupe']) && $_POST['type_groupe'] == 'e'){
		$requiredFields = array('nom_groupe','code','diminutif','rue','code_postal','ville','gsm','tva','raison_social');
	}else{
		$requiredFields = array('nom_groupe','code','diminutif','rue','code_postal','ville','gsm',);
	}
	$returnMsgs = array(
		0=>'<div class="success">L\'enregistrement a été bien ajouté</div>',
		1=>'<div class="success">L\'enregistrement a été bien modifié</div>',
		2=>'<div class="success">L\'enregistrement a été bien supprimé</div>',
		3=>'<div class="errorMsg">Une erreur a été signalée</div>',
		4=>'<div class="warning">Veuillez remplir tous les champs obligatoires.</div>',
		5=>'<div class="info">Ce code a déjà été deja utilisé</div>'
	);
	// Vérifier que tous les champs obligatoires ne sont pas vide.
	function validSubmit ($requiredFields){
		$nullValues = array_keys($_POST, "");
		foreach($nullValues as $nullValue){
			if (in_array($nullValue, $requiredFields)) 
				return false;
		}
		return true;
	}
	
	if(isset($_POST['add'])){
		if (validSubmit ($requiredFields)) {
			if(isExistCode($_POST['code'])){
				if($_POST['type_groupe'] == 'p'){
					$_POST['raison_social'] = '';
					$_POST['service'] = '';
					$_POST['tva'] = '';
				}
				$query = "INSERT INTO `tcompany` (`type_groupe`,`nom_groupe`,`civilite`,`prenom`,`nom`,`email`, `code`, `responsible`, `raison_social`, `diminutif`, `rue`, `code_postal`, `ville`, `telephone`, `gsm`, `tva`, `compte_ban`, `service`)
				VALUES ('". $_POST['type_groupe'] ."','". $_POST['nom_groupe'] ."','". $_POST['civilite'] ."','". $_POST['prenom'] ."','". $_POST['nom'] ."','". $_POST['email'] ."', '". $_POST['code'] ."', '". $_POST['responsible'] ."', '". addslashes($_POST['raison_social']) ."', '". $_POST['diminutif'] ."', '". addslashes ($_POST['rue']) ." ', '". $_POST['code_postal'] ."', '". addslashes ($_POST['ville']) ."', '". $_POST['telephone'] ."', '". $_POST['gsm'] ."', '". $_POST['tva'] ."', '". $_POST['compte_ban'] ."', '". $_POST['service'] ."')";
				
				$rst = mysql_query($query);
				$showForm = false;
				$returnMsg = $rst > 0 ? $returnMsgs[0] : $returnMsgs[3];
			}else{
				$returnMsg = $returnMsgs[5];
			}
		}else{$returnMsg = $returnMsgs[4];}
	}elseif(isset($_POST['edit'])){
		if(isExistCode($_POST['code'], $_GET['id'])){
			if($_POST['type_groupe'] == 'p'){
				$_POST['raison_social'] = '';
				$_POST['service'] = '';
				$_POST['tva'] = '';
			}
			$query = "UPDATE `tcompany` SET `type_groupe` = '". $_POST['type_groupe'] ."', `nom_groupe` = '". $_POST['nom_groupe'] ."',`civilite` = '". $_POST['civilite'] ."',`prenom` = '". $_POST['prenom'] ."',`nom` = '". $_POST['nom'] ."',`email` = '". $_POST['email'] ."', `code` = '". $_POST['code'] ."',responsible =  '". $_POST['responsible'] ."',`raison_social`= '". addslashes ($_POST['raison_social']) ."',`diminutif`= '". $_POST['diminutif'] ."',`rue`= '". addslashes ($_POST['rue']) ."',`code_postal`= '". $_POST['code_postal'] ."',`ville`= '". addslashes ($_POST['ville']) ."',`telephone`= '". $_POST['telephone'] ."',`gsm`= '". $_POST['gsm'] ."',`tva`= '". $_POST['tva'] ."',`compte_ban`= '". $_POST['compte_ban'] ."',`service`= '". $_POST['service'] ."' WHERE `tcompany`.`id` =". $_GET['id'];
			$rst = mysql_query($query);
			$showForm = false;
			$returnMsg = $rst > 0 ? $returnMsgs[1] : $returnMsgs[3];
		}else{
			$returnMsg = $returnMsgs[5];
		}
	}elseif(isset($_GET['action']) && $_GET['action'] == 'delete'){
		$query = "delete From `tcompany` Where id = ".$_GET['id'];
		$rst = mysql_query($query);
		$returnMsg = $rst > 0 ? $returnMsgs[2] : $returnMsgs[3];
	}elseif(isset($_GET['action']) && $_GET['action'] == 'edit'){
		$query = mysql_query("SELECT * FROM `tcompany` Where id = ".$_GET['id']);
		$item = mysql_fetch_assoc($query);
	}
	
	$query = mysql_query("SELECT t.*, u.`firstname`, u.`lastname`, u.`mail`, u.`service`, u.`civility` FROM `tcompany` as t LEFT JOIN tusers as u ON t.responsible = u.id ORDER BY id");
	
	$query2 = mysql_query("SELECT * FROM `tusers` WHERE `profile` in (4, 0) ORDER BY firstname");
	
	if(isset($returnMsg) && $returnMsg != ''){ echo '<br />'. $returnMsg .'<br />'; }
?>
<form name="myForm" method="post" action="" id="myForm">

<?php if($showForm && isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')){ ?>
<?php
	
?>
<center>
	<table align="center" width="700" style="margin-bottom:0;">
		<tr>
			 <th colspan="2" >Gestion des groupes</th>
		<tr>
		<tr>
			 <td colspan="2" >
				<br />
				<?php $type = getFieldValue($item, 'type_groupe', 'e'); $type = $type != '' ? $type : 'e'; ?>
				<label>Vous êtes un : </label>
				<input type="radio" <?php if($type == 'e'){ ?>checked="checked"<?php } ?> name="type_groupe" id="type_groupe_e" value="e" onclick="getTypeGroupeForm(this)" /> <label for="type_groupe_e">Entreprise</label>
				<input type="radio" <?php if($type == 'p'){ ?>checked="checked"<?php } ?> name="type_groupe" value="p" id="type_groupe_p" onclick="getTypeGroupeForm(this)" /> <label for="type_groupe_p">Groupe</label>
				<br /><br />
			 </td>
		<tr>
		<tr>
			<td width="200"><label for="code"><span class="required">*</span>Responsable:</label></td>
			<td>
				<select name="responsible">
					<?php while ($row2 = mysql_fetch_array($query2)) { ?>
					<option value="<?php echo $row2['id']; ?>" <?php if(getFieldValue($item, 'responsible') == $row2['id']){ ?> selected="selected"<?php } ?>>
						<?php echo $row2['civility'].' '.$row2['firstname'].' '.$row2['lastname']; ?>
					</option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td width="200"><label for="nom_groupe"><span class="required">*</span>Nom du groupe:</label></td>
			<td><input name="nom_groupe" id="nom_groupe" type="text" class="required"  value="<?php echo getFieldValue($item, 'nom_groupe') ; ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="code"><span class="required">*</span>Code:</label></td>
			<td><input name="code" id="code" type="text" class="required"  value="<?php echo getFieldValue($item, 'code', generateRandomString(6)); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200">
				<label for="nom">Contact:</label>
			</td>
			<td>
				<select name="civilite">
					<option <?php if(getFieldValue($item, 'civilite') == 'M.'){ ?>checked="checked"<?php } ?> value="M.">M.</option>
					<option <?php if(getFieldValue($item, 'civilite') == 'Mme'){ ?>checked="checked"<?php } ?> value="Mme">Mme</option>
					<option <?php if(getFieldValue($item, 'civilite') == 'Mlle'){ ?>checked="checked"<?php } ?> value="Mlle">Mlle</option>
				</select>
				<input name="prenom" id="prenom" type="text" value="<?php echo getFieldValue($item, 'prenom'); ?>" size="20" placeholder="Prénom" />
				<input name="nom" id="nom" type="text" value="<?php echo getFieldValue($item, 'nom') ; ?>" size="20" placeholder="Nom" />
			</td>
		</tr>
		<tr>
			<td width="200"><label for="email"><span class="required email">*</span>Email du contact:</label></td>
			<td><input name="email" id="email" type="text" class="required"  value="<?php echo getFieldValue($item, 'email'); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="compte_ban">Compte bancaire:</label></td>
			<td><input name="compte_ban" id="compte_ban" type="text" value="<?php echo getFieldValue($item, 'compte_ban'); ; ?>" size="20" /></td>
		</tr>
	</table>
	<table id="GroupeFields" align="center" width="700" style="margin-bottom:0;margin-top:-1px;<?php if($type != 'e'){ ?>display:none;<?php } ?>">
		<tr>
			<td width="200"><label for="raison_social"><span class="required">*</span>Raison social :</label></td>
			<td><input name="raison_social" id="raison_social" type="text" class="required"  value="<?php echo getFieldValue($item, 'raison_social'); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="service">Service:</label></td>
			<td><input name="service" id="service" type="text" value="<?php echo getFieldValue($item, 'service'); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="tva">N° de TVA:</label></td>
			<td><input name="tva" id="tva" type="text" class="required"  value="<?php echo getFieldValue($item, 'tva'); ?>" size="20" /></td>
		</tr>
	</table>
	<table align="center" width="700" style="margin-bottom:0;margin-top:-1px;">
		<tr>
			<td width="200"><label for="diminutif"><span class="required">*</span>Diminutif:</label></td>
			<td><input name="diminutif" id="diminutif" type="text" class="required"  value="<?php echo getFieldValue($item, 'diminutif'); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="rue"><span class="required">*</span>Adresse:</label></td>
			<td>
				<input name="rue" id="rue" type="text" class="required"  value="<?php echo getFieldValue($item, 'rue'); ?>" size="20" placeholder="Rue" />
				<input name="code_postal" id="code_postal" type="text" class="required"  value="<?php echo getFieldValue($item, 'code_postal'); ?>" size="10" placeholder="Code postal" />
				<input name="ville" id="ville" type="text" class="required"  value="<?php echo getFieldValue($item, 'ville'); ?>" size="20" placeholder="Ville" />
			</td>
		</tr>
		<tr>
			<td width="200"><label for="telephone">Telephone:</label></td>
			<td><input name="telephone" id="telephone" type="text" value="<?php echo getFieldValue($item, 'telephone'); ?>" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="gsm"><span class="required">*</span>GSM:</label></td>
			<td><input name="gsm" id="gsm" type="text" class="required"  value="<?php echo getFieldValue($item, 'gsm');; ?>" size="20" /></td>
		</tr>
	</table>
	
	<div  class="buttons2">
	<br />
	<?php if($_GET['action'] == 'add'){  ?>
	<button name="add" value="Ajouter" type="submit"  class="positive" id="Modifier"> 
		<img src="images/apply2.png" alt=""/> Ajouter
	</button>
	<?php }elseif($_GET['action'] == 'edit'){  ?>
	<button name="edit" value="Modifier" type="submit"  class="positive" id="Modifier"> 
		<img src="images/apply2.png" alt=""/> Modifier
	</button>
	<?php } ?>
	<button name="cancel" value="cancel" type="reset" class="negative">
		<img src="images/cross.png" alt=""/> Annuler
	</button>
	<br /><br /><br />
	</div>
</center>
<?php }else{ ?>
	<br /><br >
	<center><a href="<?php echo $currentPath ; ?>&action=add" class="positive"><img src="images/apply2.png" alt=""/> Ajouter</a></center>
	<br /><br >
<?php } ?>
<center>
	<table>
		<tr>
			<th>ACTION</th>
			<th>responsable</th>
			<th>code</th>
			<th>Nom du groupe</th>
			<th>Raison social</th>
			<th>Contact</th>
<!--			<th>service</th>-->
			<th>Diminutif</th>
			<th>Adresse</th>
			<th>GSM</th>
<!--			<th>TVA</th>-->
<!--			<th>Compte bancaire</th>-->
			<th>id</th>
		<tr>
		<?php while ($row = mysql_fetch_array($query)) { ?>
		<tr>
			<td>
				<a title="Editer" href="<?php echo $currentPath ; ?>&action=edit<?php echo '&id='.$row['id'] ; ?>"><img src="./images/edit.png" border="0" /></a>
				<a title="Supprimer" href="<?php echo $currentPath ; ?>&action=delete<?php echo '&id='.$row['id'] ; ?>"><img src="./images/delete.png" border="0" /></a>
			</td>
			<td><?php echo $row['civility'].' '.$row['firstname'].' '.$row['lastname']; ?></td>
			<td><?php echo $row['code']; ?></td>
			<td><?php echo $row['nom_groupe']; ?></td>
			<td><?php echo $row['raison_social']; ?></td>
			<td><?php echo '<strong>'.$row['civilite'].' '.$row['prenom'].' '.$row['nom'].'</strong> <br />'.$row['email']; ?></td>
			
			
			<td><?php echo $row['diminutif']; ?></td>
			<td><?php echo $row['rue'].' - <em>'.$row['code_postal'].'</em> '.$row['ville']; ?></td>
			<td><?php echo ' '.$row['gsm']; ?></td>			
			<td><?php echo $row['id']; ?></td>
		</tr>
		<?php } ?>
	</table>
<center>
</form>
<script language="javascript">
	$(document).ready(function() {
		$("#myForm").validate();
	});
	
	function getTypeGroupeForm(obj){
		if($(obj).val() == 'e'){
			$("#GroupeFields").show();
		}else if($(obj).val() == 'p'){
			$("#GroupeFields").hide();
		}
	}
</script>
