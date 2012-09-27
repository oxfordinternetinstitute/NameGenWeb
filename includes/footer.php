<?php

$endtime = microtime(true);

$totaltime = $endtime - $starttime;
?>
</div> 
<div id="footer">
<div style='padding:10px;'>Rendered in <?php echo round($totaltime,2); ?>s | NameGenWeb Version 2.1 (<a href="#" id="changelog">Changelog</a>) | <a href="privacy.php">Privacy Policy</a></div>
	
</div>
<div id="changelogtext">
<?php include('changelog.txt'); ?>		
</div>
<?php 

	
	// 	echo "<textarea style=\"width:100%;height:600px\">";
	// 	print_r($friend_count);
	// 	print_r($json_network);
	// 	var_dump($_SESSION);
	// 	echo "</textarea>"; 


 ?>
</body>
</html>