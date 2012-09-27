<div id="contenttext" style="">
	<h2>Step 1: Help us with our Research</h2>
	<h4>This application is part of a research study.</h4>
	<p>This tool is provided as part of a research project on how people understand their personal network. We may wish to contact you for feedback about this application and your network. If you do not want to be part of our study, you may still download and visualise your network.</p>
	<p>Please note: we will not do any research on your network without contacting you.</p>
<br />
<div style="width:700px;margin-left:auto;margin-right:auto">
<form method="GET" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
<?php
// connect to db
require("config/db-config.php");

$user_id = $_SESSION['user_profile']['id'];
$sql = "SELECT * FROM egos WHERE facebook_id = '$user_id' AND email_permission = 1";
$query = mysql_query($sql);

if (!$query) {
	 // echo "Query failed: ".mysql_error()."<br>";
} else {
	
 	if(mysql_num_rows($query)>0) {
		$email_permission = "checked";
	}
	
}

?>	
	
	<input id="email_permission" type="checkbox" name="email_permission" <?php echo $email_permission; ?>/>	
	<label for="email_permission"><strong>I hereby give consent to be contacted via email at a later date about my experiences using this program.</strong></label>
	
</div>	
	<br /><br />
<div style="border-top: 1px solid #ccc;padding-top:10px">		
<button type="submit" value="Submit" name="" id="" class="mainformbutton">Continue</button>
</div>
<input type="hidden" name="page" value="phase2" />
</form>
		

<br />
</div>