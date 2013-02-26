<?php
	$currentPath = 'index.php?page=company';
	$query = mysql_query("SELECT * FROM `tcompany` ORDER BY id");
	
	if(isset($_POST['add'])){
		echo '<pre>' ; print_r($_POST) ; echo '</pre>' ;
		if($_POST['code'] != '' && $_POST['raison_social'] != '' && $_POST['diminutif'] != '' && $_POST['rue'] != '' && $_POST['code_postal'] != '' && $_POST['ville'] != ''){
			
			$query = "INSERT INTO `bsup`.`tcompany` (`code`, `raison_social`, `diminutif`, `rue`, `code_postal`, `ville`, `telephone`, `gsm`, `tva`, `compte_ban`) 
			VALUES ('". $_POST['code'] ."', '". $_POST['raison_social'] ."', '". $_POST['diminutif'] ."', '". $_POST['rue'] ." ', '". $_POST['code_postal'] ."', '". $_POST['ville'] ."', '". $_POST['telephone'] ."', '". $_POST['gsm'] ."', '". $_POST['tva'] ."', '". $_POST['compte_ban'] ."')";
			
			$rst = mysql_query($query);
			$returnMsg = $rst > 1 ? 'OK' : 'KO';
		}else{
			$returnMsg = 'Veuillez remplir tous les champs obligatoires.';
		}
	}
?>
<form name="myForm" method="post" action="" id="myForm">

<?php if(isset($_GET['action']) && $_GET['action'] == 'add'){ ?>
<center>
	<table>
		<tr>
			 <th colspan="2" >Inscription utilisateur</th>
		<tr>
		<tr>
			<td width="200"><label for="code"><span class="required">*</span>Code:</label></td>
			<td><input name="code" id="code" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="raison_social"><span class="required">*</span>Raison social :</label></td>
			<td><input name="raison_social" id="raison_social" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="diminutif"><span class="required">*</span>Diminutif:</label></td>
			<td><input name="diminutif" id="diminutif" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="rue"><span class="required">*</span>Rue:</label></td>
			<td><input name="rue" id="rue" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="code_postal"><span class="required">*</span>Code postal:</label></td>
			<td><input name="code_postal" id="code_postal" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="ville"><span class="required">*</span>Ville:</label></td>
			<td><input name="ville" id="ville" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="telephone"><span class="required">*</span>Telephone:</label></td>
			<td><input name="telephone" id="telephone" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="gsm"><span class="required">*</span>GSM:</label></td>
			<td><input name="gsm" id="gsm" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="tva"><span class="required">*</span>tva:</label></td>
			<td><input name="tva" id="tva" type="text" class="required"  value="" size="20" /></td>
		</tr>
		<tr>
			<td width="200"><label for="compte_ban"><span class="required">*</span>Compte bancaire:</label></td>
			<td><input name="compte_ban" id="compte_ban" type="text" class="required"  value="" size="20" /></td>
		</tr>
	</table>
	<div  class="buttons2">
	<br />
	<button name="add" value="Ajouter" type="submit"  class="positive" id="Modifier"> 
		<img src="images/apply2.png" alt=""/> Ajouter
	</button>
	<button name="cancel" value="cancel" type="reset" class="negative">
		<img src="images/cross.png" alt=""/> Annuler
	</button>
	<br /><br /><br />
	</div>
</center>
<?php }else{ ?>
<br /><br >
<center><a href="<?php echo $currentPath ; ?>&action=add"><img src="images/apply2.png" alt=""/> Ajouter</a></center>
<br /><br >
<?php } ?>
<center>
	<table>
		<tr>
			<th>ACTION</th>
			<th>code</th>
			<th>Raison social</th>
			<th>Diminutif</th>
			<th>Rue</th>
			<th>Code postal</th>
			<th>Ville</th>
			<th>Telephone</th>
			<th>GSM</th>
			<th>TVA</th>
			<th>Compte bancaire</th>
			<th>id</th>
		<tr>
		<?php while ($row = mysql_fetch_array($query)) { ?>
		<tr>
			<td>
				<a title="Editer" href="<?php echo $currentPath ; ?>&action=edit<?php echo '&id='.$row['id'] ; ?>"><img src="./images/edit.png\" border="0" /></a>
				<a title="Supprimer" href="<?php echo $currentPath ; ?>&action=delete<?php echo '&id='.$row['id'] ; ?>"><img src="./images/delete.png\" border="0" /></a>
			</td>
			<td><?php echo $row['code']; ?></td>
			<td><?php echo $row['raison_social']; ?></td>
			<td><?php echo $row['diminutif']; ?></td>
			<td><?php echo $row['rue']; ?></td>
			<td><?php echo $row['code_postal']; ?></td>
			<td><?php echo $row['ville']; ?></td>
			<td><?php echo $row['telephone']; ?></td>
			<td><?php echo $row['gsm']; ?></td>
			<td><?php echo $row['tva']; ?></td>
			<td><?php echo $row['compte_ban']; ?></td>
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
  </script>
