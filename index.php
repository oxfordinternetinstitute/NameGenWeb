<?php
/**
* NameGenWeb
*/
ini_set("session.use_cookies",0);
ini_set("session.use_only_cookies",0);
ini_set("session.use_trans_sid",1); # Forgot this one!
ini_set("session.cookie_domain","facebook.com");
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
header('Content-Type:text/html; charset=UTF-8');
error_reporting(E_ALL);

$starttime = microtime(true);
	
// Begin the session
session_start();

$session_id=session_id();

// Check we have a unique session id
if (empty($_GET["page"]) || $_GET["page"] == "phase1") {
	
	require ('config/db-config.php');

	if(mysql_num_rows(mysql_query("SELECT session_id FROM sessions WHERE session_id = '$session_id'")) > 0) {
		
		$new_session_id = $session_id;

		while (mysql_num_rows(mysql_query("SELECT session_id FROM sessions WHERE session_id = '$new_session_id'")) == 1) {
			session_regenerate_id();
			$new_session_id = session_id();
		}

	}

}

//  var_dump($_GET);
// 	var_dump($_POST);
//	var_dump($_SESSION);
// Load configuration files
require "core/functions.php";
require "config/facebook-config.php";
require "facebook-php-sdk/src/facebook.php";

//echo "Before: ".memory_get_usage()."<br>";
// Initialise Facebook

$facebook = new Facebook(array(
	'appId'  => $app_id,
	'secret' => $app_secret,
));
//echo "After: ".memory_get_usage()."<br>";

$auth_url = $facebook->getLoginUrl(array(
    'scope' => 'email,friends_about_me,friends_birthday,friends_education_history,friends_hometown,friends_interests,friends_likes,friends_location,friends_relationship_details,friends_relationships,friends_religion_politics,friends_website,friends_work_history',
    'redirect_uri' => $app_url, 
));

$access_token = $facebook->getAccessToken();
//echo "<br>Access token: ".$access_token;


?>
<?php
// Include general header
include "includes/header.php";

// Fetch current user 
$user = $facebook->getUser();

if ($user) {
	try {
    	// Proceed knowing we have a logged in user who's authenticated
    	$user_profile = (isset($_SESSION['user_profile'])) ? $_SESSION['user_profile'] : $facebook->api('/me?fields=email,name,id');
		$_SESSION['user_profile'] = $user_profile;
?>

<div id="title">
	<div style="float:left;padding-top:25px;width:700px;margin-left:70px;">
	   <img style="" align="left" src="images/namegen.png">  
	    <h1 style="margin-bottom:0px;margin-left:20px;margin-top:1.5em" id="maintitle"><b>NameGenWeb</b></h1>
	   <div style="margin-left:50px;font-size:16px;padding-top:5px;padding-bottom:15px;line-height:1.5;">
	   	Software designed to help you capture, analyse, and visualise your Facebook network.
	   </div>

<?	
	
	 	if ($user_profile) { 
			include "includes/loggedinheader.php"		
		
		?>

	</div>	
</div>
<div id="content" style="<?php if(isset($_GET["page"])) { echo "display:block"; } ?>">
	
	
<?php 
if (empty($_GET["page"])) {
	$page = "phase1";
} else {
	$page = $_GET["page"];	
}

// TODO: injection
include "includes/".$page.".php"; 

?>
			
</div>
<?php

		} // End if $user_profile

	
	} catch (FacebookApiException $e) { // End try. Error when first using the API. TODO: deal with this somehow.
			// echo("<script> top.location.href='" . $auth_url . "'</script>");
			echo "Please try again later. The Facebook platform seems to be encountering problems:".$e;
	}
	
} else { // No $user. user not logged in? 

	 echo("<script> top.location.href='" . $auth_url . "'</script>");
	
} 
// Include global footer
unset($facebook);
unset($user);
gc_collect_cycles();
include "includes/footer.php";

?>
