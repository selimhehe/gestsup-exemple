<?php
/*
Author: Flox
File name: serachengine.php 
Call: dashboard.php
Description: search engine in database incidents 
Version: 2.1
last update: 28/10/2012
*/

//Initialize Session variables
if(!isset($_SESSION['user_id'])) $_SESSION['user_id'] = '';
if(!isset($_GET['keywords'])) $_GET['keywords'] = '';

//case for down link number 
if($_GET['keywords']) {
$keywords=$_GET['keywords']; 
} else {
$keywords=$_POST['keyword']; 
}

//case when keywords contain '
$keywords = str_replace("'","\'",$keywords);

//case for multiple $keyrword
$keywords=explode(" ",$keywords);
$nkeyword= sizeof($keywords);

if ($nkeyword==2)
{
	$from = "
		FROM tincidents 
		WHERE
		(title LIKE '%$keywords[0]%' OR 
		description LIKE '%$keywords[0]%' OR 
		resolution LIKE '%$keywords[0]%' OR
		id = '$keywords[0]' OR
		user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[0]%' or lastname LIKE '%$keywords[0]%'))
		AND
		(title LIKE '%$keywords[1]%' OR 
		description LIKE '%$keywords[1]%' OR 
		resolution LIKE '%$keywords[1]%' OR
		id LIKE '$keywords[1]' OR
		user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[1]%' or lastname LIKE '%$keywords[1]%'))
		AND disable='0'
	"; 
}
else if ($nkeyword==3)
{
	$from = "
		FROM tincidents 
		WHERE
		(title LIKE '%$keywords[0]%' OR 
		description LIKE '%$keywords[0]%' OR 
		resolution LIKE '%$keywords[0]%' OR
		id = '$keywords[0]' OR
		user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[0]%' or lastname LIKE '%$keywords[0]%'))
		AND
		(title LIKE '%$keywords[1]%' OR 
		description LIKE '%$keywords[1]%' OR 
		resolution LIKE '%$keywords[1]%' OR
		id LIKE '$keywords[1]' OR
		user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[1]%' or lastname LIKE '%$keywords[1]%'))
		AND
		(title LIKE '%$keywords[2]%' OR 
		description LIKE '%$keywords[2]%' OR 
		resolution LIKE '%$keywords[2]%' OR
		id LIKE '$keywords[2]' OR
		user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[2]%' or lastname LIKE '%$keywords[2]%'))
		AND disable='0'
	"; 
}

else
{
	$from = "
		FROM tincidents, tsubcat 
		WHERE 
		tincidents.subcat=tsubcat.id AND
		(
		tincidents.title LIKE '%$keywords[0]%' OR 
		tincidents.description LIKE '%$keywords[0]%' OR 
		tincidents.resolution LIKE '%$keywords[0]%' OR
		tsubcat.name LIKE '$keywords[0]' OR
		tincidents.id = '$keywords[0]' OR
		tincidents.user LIKE (SELECT max(id) FROM tusers where firstname LIKE '%$keywords[0]%' or lastname LIKE '%$keywords[0]%')
		)
		AND disable='0'
	"; 
}
?>