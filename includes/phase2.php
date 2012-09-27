<div id="contenttext" style="">
	<h2>Step 2: Selecting Attributes</h2>
	
<?
if (isset($_GET['download'])) {
	
		$friend_count = count($_SESSION['friend_list']['data']);
?>
<h4>First, select which information about your network you are interested in.</h4> 
<p>An attribute is a piece of information about someone in your Facebook network. A person's gender, likes, or relationship status can all be thought of as attributes. 
The form below allows you to select which attributes you may download from the <strong><? echo $friend_count; ?></strong> friends available to NameGenWeb.

<p><strong>Default attributes</strong> are usually always available about any member of Facebook, whereas <strong>Extended attributes</strong> may or may not be available depending on the privacy settings of your friends.</p>
<p>Please be aware that if you have a large number of friends, selecting more attributes may significantly increase the time it takes to download your network. If you encounter difficulties, please return to this page and try again with fewer attributes selected.</p>
<p></p><br /><br /><br />
<div style="width:150px;height:200px;margin-left:auto;margin-right:auto;text-align:center;">
<img src='images/ajax-loader.gif' alt="Loading..." />
<br />
</div>
<?	
@ob_flush();
flush();	
	$attribute_string ="uid, name";
	$friend_list_array = array();
	$i = 0;
	
	
	foreach ($_GET as $key => $value) {
		
		if (($key!="download") && ($key!="page") && ($key!="PHPSESSID")) {
		$attribute_string.=",".$key;
		}
	}
	
	$friend_list = $_SESSION['friend_list']['data'];
	$chunk = array_chunk($friend_list, 300);

foreach ($chunk as $chunk_key => $chunk_array) {

	$bottom = $chunk[$chunk_key][0]['id'];
	$count = count($chunk[$chunk_key])-1;
	$top = $chunk[$chunk_key][$count]['id'];
	//echo "bottom: $bottom, top: $top, count: $count <br>";	
	
	$query = "SELECT $attribute_string FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me() AND uid2>=$bottom AND uid2<=$top)";
	
	$param = array(       
     	'method' => 'fql.query',       
     	'query' => $query,       
     	'callback' => '');

	try { // Run the query.

		$fqlresult = $facebook->api($param);
		
		
		foreach ($fqlresult as $key => $value) {
			
			$friend_list_array[$i] = $value;
			
			$i++;		
		}
		
		// Facebook returns "false" for some profiles (blocked, deactivated etc.). Remove these.
        $true_friend_list_array = array();
        for($i=0; $i<count($friend_list_array); $i++) {
            if($friend_list_array[$i]!==false) $true_friend_list_array[] = $friend_list_array[$i];
        }
		$_SESSION['friend_list_array'] = $true_friend_list_array;		
	
	unset($fqlresult);
	} catch (FacebookApiException $e) {
		sleep(5);	
		    $fqlresult = $facebook->api($param);
			
			
			foreach ($fqlresult as $key => $value) {
				
				$friend_list_array[$i] = $value;
				
				$i++;		
				
			}

	}
// sleep(1.5);
} //end chunk foreach

?>

<script> window.location.href='index.php?page=phase3&PHPSESSID=<?php echo $_GET['PHPSESSID']; ?>'</script>

<?
	
} else { // Form not submitted.
	
	$_SESSION['friend_list'] = $facebook->api('/me/friends');
	$friend_count = count($_SESSION['friend_list']['data']);
	$_SESSION['email_permission'] = (isset($_GET['email_permission'])) ? 1 : 0;
	
?>	
	<h4>First, select which information about your network you are interested in.</h4> 
	<p>An attribute is a piece of information about someone in your Facebook network. A person's gender, likes, or relationship status can all be thought of as attributes. 
	The form below allows you to select which attributes you may download from the <strong><? echo $friend_count; ?></strong> friends available to NameGenWeb.

	<p><strong>Default attributes</strong> are usually always available about any member of Facebook, whereas <strong>Extended attributes</strong> may or may not be available depending on the privacy settings of your friends.</p>
	<p>Please be aware that if you have a large number of friends, selecting more attributes may significantly increase the time it takes to download your network. If you encounter difficulties, please return to this page and try again with fewer attributes selected.</p>
	<p></p>

	<form action="index.php?page=phase2" id="attribute_form" method="GET">
	<div style="width:600px;margin-left:auto;margin-right:auto;margin-bottom:20px;">
	<div style="clear:both;"> </div>
	<div style="width:50%px;float:left">
		<div style="margin-left:50px;padding-bottom:50px">
	<h4>Default Attributes:</h4>

		<input id="id" type="checkbox" name="id" checked disabled/>	
		<label for="id">Facebook User ID</label><br />

		<input id="name" type="checkbox" name="name" checked disabled/>	
		<label for="name">Full Name</label><br />

		<input id="first_name" type="checkbox" name="first_name" />	
		<label for="first_name">First Name</label><br />

		<input id="middle_name" type="checkbox" name="middle_name" />	
		<label for="middle_name">Middle Name</label><br />

		<input id="last_name" type="checkbox" name="last_name" />	
		<label for="last_name">Last Name</label><br />

		<input id="sex" type="checkbox" name="sex" />	
		<label for="sex">Gender</label><br />

		<input id="pic_big" type="checkbox" name="pic_big" />	
		<label for="pic_big">Profile Picture URL</label><br />

		<input id="locale" type="checkbox" name="locale" />	
		<label for="locale">Locale</label><br />

		<input id="mutual_friend_count" type="checkbox" name="mutual_friend_count" />	
		<label for="mutual_friend_count">Mutual Friend Count</label><br />
		</div>
	</div>
	<div style="width:50%;float:right">
		 <div style="margin-left:50px;padding-bottom:50px">
	<h4>Extended Attributes:</h4>


		<input id="about_me" type="checkbox" name="about_me" />	
		<label for="about_me">Biography</label><br />

		<input id="hometown_location" type="checkbox" name="hometown_location" />	
		<label for="hometown_location">Hometown</label><br />

		<input id="birthday_date" type="checkbox" name="birthday_date" />	
		<label for="birthday_date">Birthday</label><br />

		<input id="political" type="checkbox" name="political" />	
		<label for="political">Political Beliefs</label><br />

		<input id="relationship_status" type="checkbox" name="relationship_status" />	
		<label for="relationship_status">Relationship Status</label><br />

		<input id="religion" type="checkbox" name="religion" />	
		<label for="religion">Religious Beliefs</label><br />

		<input id="likes_count" type="checkbox" name="likes_count" />	
		<label for="likes_count">Likes Count</label><br />

		<input id="friend_count" type="checkbox" name="friend_count" />	
		<label for="friend_count">Friend Count</label><br />
		</div>
	</div>	

	</div>
	<div style="width:100%;clear:both; border-top:1px solid #ccc;padding-top:10px">
	    <button type="submit" value="Submit" name="download" id="download" class="mainformbutton">Download</button>		
		<input type="hidden" name="page" value="phase2" />
	</div>

	</form>
<? } ?>	
	<br />
	</div>
	</div>			
	</div>

	</div>
	
