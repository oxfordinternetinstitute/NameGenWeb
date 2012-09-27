<?php
error_reporting(E_ALL);
ini_set("session.use_cookies",0);
ini_set("session.use_only_cookies",0);
ini_set("session.use_trans_sid",1); # Forgot this one!
ini_set("session.cookie_domain","facebook.com");
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
session_start();
require "functions.php";

?>
<html>
<head>
<title>Please Wait...</title>
</head>
<style type="text/css">

body {
	background:#fff;
	color:#000;
	width:100%;
	height:100%;
	font-family:"lucida grande",tahoma,verdana,arial,sans-serif;
}

#center { margin-left:auto;margin-right:auto;height:100%;width:50% }
#main { position:absolute;top:30%;width:50%;text-align:center}
</style>
<body>
<center>	
<div id="center">
<div id="main"><img src="../images/ajax-loader.gif" /><br />
<p>Please wait while your graph is partitioned and rendered. This process make take some time to complete. You will be redirected automatically once the graph is ready.</p>
<?
$format = "graphml";
$user_name = $_SESSION['user_profile']['name'];
$friend_list_array = $_SESSION['friend_list_array'];
$edge_list_array = $_SESSION['edge_list_array'];
$attribute_array = $_SESSION['attribute_array'];		

$file_name = $user_name."_".time();

$input_file = createGraphFile($friend_list_array, $edge_list_array, $attribute_array, $format, $file_name, TRUE);

$namegen_location = $_SERVER['DOCUMENT_ROOT']."/namegenweb/";
$app_location = $_SERVER['DOCUMENT_ROOT']."/namegenweb/gephi-toolkit/GenerateGraph/dist/";

$input_file = "output/".$file_name.".graphml";
$output_file = "output/".$file_name.".gexf";

$command = 'java -jar '.$app_location.'GenerateGraph.jar "'.$namegen_location.$input_file.'" "'.$namegen_location.$output_file.'" &';

//echo "<pre> $command \n $namegen_location \n $app_location ";
ob_flush();
flush();


exec($command, $output);
ob_flush();
flush();


while (true) {

	
	if (file_exists($namegen_location.$output_file)) {
		
		echo "<script> parent.location.href='../visualiser/index.php?".$file_name.".gexf'</script>";
	//	echo "Rendering complete. Please wait to be redirected, or <a href='visualise.php?".$file_name.".gexf'>click here</a>.";
		break(1);
	}
	//var_dump($output);
	sleep(0.5);
	ob_flush();
 	flush();

}


?>

?>


</div><!-- end #main -->
</div><!-- end #center -->
</center>
</body>
</html>