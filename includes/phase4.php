<script language="javascript">

function warnPrompt()
{
var agree=confirm("WARNING: The visualiser is experimental and has known issues with large networks and slow hardware. We do not recommend visualisation of networks over 400 nodes. For best results, please use Google Chrome.");
if (agree)

w2=window.open("core/runjava.php?PHPSESSID=<?php echo $_GET['PHPSESSID']; ?>", "vis","location=0,status=0,scrollbars=0,resizable=0,width=1400px,height=800px");

}
</script>

<div id="contenttext" style="">
	<h2>Step 4: Explore your Network</h2>
<div id="downloaddiv"><h4>Download Your Data</h4>
<p>Exporting your data allows you to conduct more advanced analysis in packages such as NodeXL, Gephi, or Ucinet. At present we offer <strong>4</strong> output formats: JSON, GraphML, Ucinet 'dl', and Guess GDF. Use the drop down box below to select your preferred format, and then click download to proceed.</p>

<form action="core/generatefile.php" method="GET" target="downloadframe">
	<fieldset style="width:45%;height:100px;float:left">
		<legend>Download Format</legend>

<p><strong>Please note:</strong> because of file format limitations, only GraphML is suitable for exporting node attribute data at this time.</p>

<label for="format">File type: </label>
<select style="color: #000000;width: 150px; border:1px solid #ccc;paddding:5px;" name="format">
		<option value="graphml">GraphML</option>
		<option value="guess">Guess (.gdf)</option>
		<option value="ucinet">UCINET (.dl)</option>
		<option value="json">JSON</option>
</select></fieldset>
<fieldset style="width:45%;height:100px;float:right">
	<legend>Download Options: </legend><p>
<input id="ego" type="checkbox" value="ego" name="ego"/>
<label for="ego">Include Ego</label>
<br />
<input id="anon" type="checkbox" value="anon" name="anon"/>
<label for="anon">Anonymise Network</label>
</p>
</fieldset><div style="width:100%;clear:both;"><br /></div>
<input type="submit" value="Download" name="download" class="mainformbutton" />
</form>
<h4>Web-Based Visualiser</h4>
<p>Alternatively you may use our experimental JavaScript visualiser to explore your network from your web browser.</p>
<p><input type="button" class="mainformbutton" value="Launch Visualiser" onClick="javascript: warnPrompt();"></p>
<iframe style="display:none" id="downloadframe"></iframe>
<br />
<div style="width:100%;clear:both; border-top:1px solid #ccc;padding-top:10px;text-align:right">

<br /><br />
</div>


</div>
</div>
<?
@ob_flush();
flush();
gc_collect_cycles();

// Get session data
$friend_list_array = $_SESSION['friend_list_array'];
$edge_list_array = $_SESSION['edge_list_array'];
//$json_network = $_SESSION['json_network'];
$user_id = $_SESSION['user_profile']['id'];
$user_name = $_SESSION['user_profile']['name'];
$user_email = $_SESSION['user_profile']['email'];
$email_permission = $_SESSION['email_permission'];
$session_id = session_id();
$_SESSION['attribute_array'] = array("uid","name","sex","first_name","middle_name","last_name","locale","about_me","hometown_location","birthday_date","political","relationship_status","religion","likes_count","friend_count","mutual_friend_count","pic_big");

// connect to db
require("config/db-config.php");

// write ego to db
if(mysql_num_rows(mysql_query("SELECT facebook_id FROM egos WHERE facebook_id = '$user_id'")) > 0){

// if ego exists, just add another session and update email pref.
	mysql_query("UPDATE egos SET email_permission = $email_permission WHERE facebook_id = '$user_id'");
	mysql_query("INSERT INTO sessions (session_id, ego_fid) VALUES ('$session_id', '$user_id')");		

} else { 
	
	// else create ego and the session
	mysql_query("INSERT INTO egos (facebook_id, name, email, email_permission) VALUES ('$user_id', '$user_name', '$user_email', $email_permission)");		
	mysql_query("INSERT INTO sessions (session_id, ego_fid) VALUES ('$session_id', '$user_id')");		

}


// // write alter attribute data
// foreach ($friend_list_array as $key => $value) {
// 	
// 	$facebook_id			= (isset($friend_list_array[$key]['uid'])) ? mysql_real_escape_string($friend_list_array[$key]['uid']) : Null;
// 	$friend_name 			= (isset($friend_list_array[$key]['name'])) ? mysql_real_escape_string($friend_list_array[$key]['name']) : Null;
// 	$gender					= (isset($friend_list_array[$key]['sex'])) ? mysql_real_escape_string($friend_list_array[$key]['sex']) : Null;
// 	$first_name				= (isset($friend_list_array[$key]['first_name'])) ? mysql_real_escape_string($friend_list_array[$key]['first_name']) : Null;
// 	$middle_name			= (isset($friend_list_array[$key]['middle_name'])) ? mysql_real_escape_string($friend_list_array[$key]['middle_name']) : Null;
// 	$last_name				= (isset($friend_list_array[$key]['last_name'])) ? mysql_real_escape_string($friend_list_array[$key]['last_name']) : Null;
// 	$locale					= (isset($friend_list_array[$key]['locale'])) ? mysql_real_escape_string($friend_list_array[$key]['locale']) : Null;
// 	$bio					= (isset($friend_list_array[$key]['about_me'])) ? mysql_real_escape_string($friend_list_array[$key]['about_me']) : Null;
// 	$hometown				= (isset($friend_list_array[$key]['hometown_location']['name'])) ? mysql_real_escape_string($friend_list_array[$key]['hometown_location']['name']) : Null;
// 	$birthday				= (isset($friend_list_array[$key]['birthday_date'])) ? mysql_real_escape_string($friend_list_array[$key]['birthday_date']) : Null;
// 	$political_beliefs		= (isset($friend_list_array[$key]['political'])) ? mysql_real_escape_string($friend_list_array[$key]['political']) : Null;
// 	$relationship_status	= (isset($friend_list_array[$key]['relationship_status'])) ? mysql_real_escape_string($friend_list_array[$key]['relationship_status']) : Null;
// 	$religious_beliefs		= (isset($friend_list_array[$key]['religion'])) ? mysql_real_escape_string($friend_list_array[$key]['religion']) : Null;
// 	$likes_count			= (isset($friend_list_array[$key]['likes_count'])) ? mysql_real_escape_string($friend_list_array[$key]['likes_count']) : Null;
// 	$friend_count			= (isset($friend_list_array[$key]['friend_count'])) ? mysql_real_escape_string($friend_list_array[$key]['friend_count']) : Null;
// 	$mutual_friend_count	= (isset($friend_list_array[$key]['mutual_friend_count'])) ? mysql_real_escape_string($friend_list_array[$key]['mutual_friend_count']) : Null;
// 	$picture_url			= (isset($friend_list_array[$key]['pic_big'])) ? mysql_real_escape_string($friend_list_array[$key]['pic_big']) : Null;			
// 
// 
// 
// 
// 	if (mysql_query("INSERT INTO edge_attributes (session_id, facebook_id, name, sex, first_name, middle_name, last_name, locale, about_me, hometown, birthday_date, political_beliefs, relationship_status, religious_beliefs, likes_count, friend_count, mutual_friend_count, picture_url) VALUES ('$session_id', '$facebook_id', '$friend_name','$gender', '$first_name', '$middle_name', '$last_name', '$locale', '$bio', '$hometown', '$birthday', '$political_beliefs', '$relationship_status', '$religious_beliefs', '$likes_count', '$friend_count', '$mutual_friend_count', '$picture_url')")) {
// 	
// 	} else {
// 		// echo "Query failed: ".mysql_error()."<br>";
// 	}
// }
// 
// 
// //write edges
// foreach ($edge_list_array as $key => $value) {
// 	
// 	$uids = explode(",", $key);	 
// 	mysql_query("INSERT INTO edges (session_id, uid1, uid2) VALUES ('$session_id', '$uids[0]','$uids[1]')");
// 	
// }

?>