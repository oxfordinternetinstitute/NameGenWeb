<?php

if (isset($_GET['page']))	{
	
	if($_GET['page'] == "phase1") {
		
	?>	
		<a class="infolink" href="learnmore.php">Learn More</a>.
				
		
<?	} else { ?>
	
	<span style="padding-right:5px;" class="getstarted">
	  <a class="topbutton" role="button" href="index.php?page=phase1">Start Again</a>   
	</span>
	<span style="padding-right:5px;" class="getstarted">
	  or </span> <a class="infolink" role="button" href="learnmore.php">Learn More</a>.   
	
	
<?	} ?>
	
<? } else { ?>
	
	<span style="padding-right:5px;" class="getstarted">
	  <a class="topbutton" role="button" onClick="toggleContent('1')">Get Started
	   </a>   
	</span>
	<span style="text-decoration:none;display:inline-block;font-size:14px;padding-top:5px" class="getstarted">
	  or </span> <a class="infolink" href="learnmore.php">Learn More</a>.

	
<? } ?>
