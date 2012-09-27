	<div id="contenttext" style="">
	<h2>Step 3: Downloading Ties</h2>
	<span style="color:red"><?php if (isset($_GET['error_text'])) echo "Unfortunately there was an error while downloading your network. Please click the download button to try again."; ?></span>
	<h4>Next, the connections between your friends will be downloaded to capture the structure of your Facebook network.</h4>
	<p>Facebook allows applications to find the mutual friends between two users. Using this method programatically, NameGenWeb is able to reconstruct the connections between your friends in the form of a network.
	Click the button below to begin this process.</p>

	<p>Once again, the time that this will take is dependant on the size of your network. Very large networks may take a considerable amount of time.</p>	
<br />
	<iframe src="includes/phase3a.php?PHPSESSID=<?php echo $_GET['PHPSESSID']; ?>" frameborder="0" scrolling="no" width="100%" height="80px" style="border-top: 1px solid #ccc;overflow-x: hidden;overflow-y: hidden;"></iframe>
	<br /></div>
</div>