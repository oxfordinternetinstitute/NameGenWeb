<?php
ini_set("session.use_cookies",0);
ini_set("session.use_only_cookies",0);
ini_set("session.use_trans_sid",1); # Forgot this one!
ini_set("session.cookie_domain","facebook.com");
session_start();
require "functions.php";
	
$format = (isset($_GET['format'])) ? $_GET['format'] : "graphml";
$include_ego = (isset($_GET['ego'])) ? TRUE : FALSE;
$anonymise = (isset($_GET['anon'])) ? TRUE : FALSE;		
$user_name = $_SESSION['user_profile']['name'];
$friend_list_array = $_SESSION['friend_list_array'];
$edge_list_array = $_SESSION['edge_list_array'];
$attribute_array = $_SESSION['attribute_array'];		


$file_name = $user_name."_".time();

$path = createGraphFile($friend_list_array, $edge_list_array, $attribute_array, $format, $file_name, $include_ego, $anonymise);
downloadPrompt($path);

// echo "<pre> path: $path \n format: $format \n username: $user_name \n file name: $file_name";
// echo "<pre>"; 
// var_dump($_SESSION['edge_list_array']);
// 

?>