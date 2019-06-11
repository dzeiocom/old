<?php 
ini_set('display_errors', 'On');

$tablee = 'links';
require_once 'meekrodb.2.3.class.php'; //Import meekrodb 2.3

DB::$user = '___';
DB::$host = '___.mysql.db';
DB::$password = '____';
DB::$dbName = '___';
DB::$encoding = 'utf8';
	
$shouldbetrue = false;

if(isset($_GET['l']) && is_numeric($_GET['l'])) {
	$result = DB::queryFirstRow("SELECT url, count FROM ".$tablee." WHERE id=%s LIMIT 1", $_GET['l']);
	DB::update($tablee, array(
  		'count' => $result['count']+1,
	), "id=%s", $_GET['l']);
	if(isset($result["url"])) header("Location : ".$result["url"]);
	else echo 'Link not found! <a href="#!" onclick="window.history.back();">Back</a>';
} elseif(isset($_GET['create'])) {
	if(count($_GET) > 1) {
		foreach ($_GET as $key => $value) {
			if($key != 'create') $_GET['create'] .= '&'.$key.'='.$value;
		}
	}
	$tables = DB::tableList();
	foreach ($tables as $table) if($table == $tablee) $shouldbetrue = true;
	if(!$shouldbetrue) {
		DB::query('
			CREATE TABLE '.$tablee.' (
  				id       	INT 			AUTO_INCREMENT PRIMARY KEY,
  				url			VARCHAR(256) 	UNIQUE NOT NULL,
  				count		INT(100)		NOT NULL DEFAULT \'0\'
			);
		');
	}
	$row = DB::queryFirstRow("SELECT id, url FROM ".$tablee." WHERE url=%s LIMIT 1", $_GET['create']);
	if(isset($row)) {
		echo "Already Existing Link : ";
	} else {
		DB::insert($tablee, array(
    		'url'       => $_GET['create']
		));
		
	}
	$url = 'https://s.delta-wings.net/?l='.DB::queryFirstRow("SELECT id, url FROM ".$tablee." WHERE url=%s LIMIT 1", $_GET['create'])['id'];
	echo '<a target="_blank" href="'.$url.'">'.$url.'</a>';
} elseif (isset($_GET['get'])) {
	$row = DB::queryFirstRow("SELECT id, url, count FROM ".$tablee." WHERE id=%s LIMIT 1", $_GET['get']);
	echo 'ID = <a target="_blank" href="/?l='.$row['id'].'">'.$row['id'].'</a>';
	echo '<br/>URL = <a target="_blank" href="'.$row['url'].'">'.$row['url'].'</a>';
	echo '<br/>Click Count = '.$row['count'];
	echo '<br/><a href="/">Return</a>';
} else {
	echo '<p>Made in less than 2 hours by Aviortheking! <a href="https://s.delta-wings.net/?l=1">Redirect to Website</a></p><br/><input class="creator"><button class="creatorButton">Create Link!</button><br><br><input class="getdatas"><button class="getDatasButton">Get Datas!</button><script>document.getElementsByClassName("creatorButton")[0].onclick = function(){window.location = "/?create="+document.getElementsByClassName("creator")[0].value};document.getElementsByClassName("getDatasButton")[0].onclick = function(){window.location = "/?get="+document.getElementsByClassName("getdatas")[0].value};</script>';
}
