<?php
	/**
	 * Génération d'une chaine aléatoire.
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
	 * Vérifier si le code est unique
	 */
	function isExistCode($c){
		$sql = "SELECT count(*) as nbr FROM `tcompany` Where code = '". $c ."'";
		$data = mysql_query($sql);
		$row = mysql_fetch_assoc($data);
		return $row['nbr'] == 0 ? true : false ;
	}
	/**
	 * Vérifier et retourner si la valaeur passer en POST si la valeur passer en paramètre
	 * Utilise dans le cas d'un submit bloqué par msg d'erreur. cette fonction permet de récupérer les valeurs passer en POST
	 */
	function getFieldValue($item, $key, $defaultValue = ''){
		return isset($_POST[$key]) ? $_POST[$key]  : (isset($item[$key]) ? $item[$key] : $defaultValue );
	}