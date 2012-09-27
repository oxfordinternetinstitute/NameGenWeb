<?php

ini_set("memory_limit","512M"); // needed for extra large networks
ini_set("session.use_cookies",0);
ini_set("session.use_only_cookies",0);
ini_set("session.use_trans_sid",1); # Forgot this one!
ini_set("session.cookie_domain","facebook.com");
header('Content-Type:text/html; charset=UTF-8');
error_reporting(E_ALL);

// Begin the session
session_start();


$session_id=session_id();

require "../config/facebook-config.php";
require "../core/functions.php";
require "../facebook-php-sdk/src/facebook.php";

// Initialise Facebook
$facebook = new Facebook(array(
	'appId'  => $app_id,
	'secret' => $app_secret,
));

?>
<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<html>
	<head> 
	<link rel="stylesheet" type="text/css" href="../css/style.css">
	<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
	<style type="text/css">
	body {
		background:#fff;
		margin:0;
		margin-top:10px;
		overflow:hidden;
	}
	</style>
	</head>
  <body scroll="no">


<?php	

if (isset($_GET['download'])) {
	
?>	

	<div id="tohide" style="width:97%;">
		<div style="margin-left:auto;margin-right:auto;width:440px">
	<img src="../images/ajax-loader.gif" alt="loading..." style="float:left">	
	<div id="progressbarcontainer">
		<div id="progressbar" style="">10%</div>
	</div>
		</div>
	</div>	

<? } else { ?>
	<div id="tohide" style="width:100%">	
	<form action="phase3a.php" method="GET">
		<input type="hidden" name="phase" value="3" />		
	     <button type="submit" value="Submit" name="download" id="" class="mainformbutton">Download</button>
	</form>	
	</div>	
<? }?>
		

<?

if (isset($_GET['download'])) {

// Get access token
$access_token = $facebook->getAccessToken();

// Get My UID
$uid = $facebook->getUser();

// Retrieve session variables
$friend_count = count($_SESSION['friend_list_array']);
$friend_list_array = $_SESSION['friend_list_array'];

// Scale Sleep time depending on network size

$sleep_time = ($friend_count > 350) ? 1.5 : 1;

// Define multiquery loops and variables, as-per NodeXL Facebook importer. 	
$outer_max_num = 125;
$inner_max_num = 500;
$outer_iter_interval = ceil($friend_count/$outer_max_num);
$inner_iter_interval = ceil($friend_count/$inner_max_num);
$outer_index_start = 0;
$outer_index_end = 0;
$inner_index_start = 0;
$inner_index_end = 0;
$num_of_queries = 0;
$edge_list_array = array();

// Begin outer loop
for ($i = 0; $i < $outer_iter_interval; $i++) {

	// Calculate start and end index
	$outer_index_start = $i * $outer_max_num;
	$outer_index_end = ($i + 1) * $outer_max_num;
	$outer_index_end = $outer_index_end >= $friend_count ? $friend_count - 1 : $outer_index_end;	// ternary operator - neat.

	// Begin inner loop
	for ($j = 0; $j< $inner_iter_interval; $j++) {

		// Calculate start and end index
		$inner_index_start = $j * $inner_max_num;
		$inner_index_end = ($j + 1) * $inner_max_num;
		$inner_index_end = $inner_index_end >= $friend_count ? $friend_count - 1 : $inner_index_end;
		
		// Structure query
		$query = urlencode("select uid1, uid2 from friend where uid1 in (select uid2 from friend where uid1=$uid and uid2>=".$friend_list_array[$outer_index_start]['uid']." and uid2<=".$friend_list_array[$outer_index_end]['uid'].") and uid2 in (select uid1 from friend where uid2=$uid and uid1>=".$friend_list_array[$inner_index_start]['uid']." and uid1<=".$friend_list_array[$outer_index_end]['uid'].")");
		$fql_query_url = "https://graph.facebook.com/fql?q=$query&access_token=$access_token";
		try { // Run the query.

			$fqlresult = makeCall($fql_query_url);
			sleep($sleep_time); // Hack for CURL timeouts
	
			// Insert the mutual friends into the edge array
			foreach ($fqlresult->data as $key => $value) {
				$r1 = $value->uid1;
				$r2 = $value->uid2;
				$sp=",";

			
				if (strcmp($r1, $r2) > 0) {
					$edge_list_array[$r1 . $sp . $r2] = 1;
				
				} else {
					$edge_list_array[$r2 . $sp . $r1] = 1;															
				}

			}
	
			// Progress bar. TODO: make this with with asynchronous ajax query to another script.
			$current_percent = round((($i+1) / $outer_iter_interval) * 100);
?>

<script type="text/javascript">
	$width = $('#progressbarcontainer').width();
	$percentage = <?php echo $current_percent; ?>*($width/100);
	$('#progressbar').animate({width:$percentage},"slow");
	$text = <?php echo $current_percent; ?>+"%";
	$('#progressbar').html($text);
</script>


<?PHP
			@ob_flush();
			flush();
			unset($fqlresult);
			gc_collect_cycles();
			
		} catch (FacebookApiException $e) {
	    	//TODO: exception handling on MQ. Handling should be to retry query after delay.	
	     	echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
?>
 <script> parent.location.href='../index.php?page=phase3&PHPSESSID=<?php echo $_GET['PHPSESSID']?>&error_text=1; '</script>
<?php
		}
	}	// End inner loop

	$num_of_queries++;
	
} // End outer loop

?>

<script> parent.location.href='../index.php?page=phase4&PHPSESSID=<?php echo $_GET['PHPSESSID']; ?>'</script>

<?

// Set new session data
$_SESSION['edge_list_array'] = $edge_list_array;
$_SESSION['friend_list_array'] = $friend_list_array;


} // End some condition
?>
</body>
</html>