<?php
include_once __DIR__ . "/../../../../wp-config.php";

function get_API_key() {
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$password=DB_PASSWORD;
$db = new PDO($dsn, $user, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT option_value 
	FROM `wp_options` 
	WHERE option_name = 'CCast_API_Key_settings'";

$result = $db->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$pieces = explode('"',$row['option_value']);
return $pieces[3];
}

function get_URL_key() {
$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST;
$user = DB_USER;
$password=DB_PASSWORD;
$db = new PDO($dsn, $user, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT option_value 
	FROM `wp_options` 
	WHERE option_name = 'CCast_URL_settings'";

$result = $db->query($sql);
$row = $result->fetch(PDO::FETCH_ASSOC);
$url_is = explode('"',$row['option_value']);
return $url_is[3];
}
?>
