<?php
	/**
	 * G�n�ration d'une chaine al�atoire.
	 */
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	/**
	 * V�rifier si le code est unique
	 */
	function isExistCode($c, $id = NULL){
		$sql = "SELECT count(*) as nbr FROM `tcompany` Where code = '". $c ."'";
		$sql .= !is_null($id) ? " And id <> ". $id : '';
		
		$data = mysql_query($sql);
		$row = mysql_fetch_assoc($data);
		return $row['nbr'] == 0 ? true : false ;
	}
	/**
	 * V�rifier et retourner si la valaeur passer en POST si la valeur passer en param�tre
	 * Utilise dans le cas d'un submit bloqu� par msg d'erreur. cette fonction permet de r�cup�rer les valeurs passer en POST
	 */
	function getFieldValue($item, $key, $defaultValue = ''){
		return isset($_POST[$key]) ? $_POST[$key]  : (isset($item[$key]) ? $item[$key] : $defaultValue );
	}