<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<html>
	<head> 
	<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
  <body style="height:100%">	

<div id="container">

<div id="title">
	<div style="float:left;padding-top:25px;width:700px;margin-left:70px;">
	   <img style="" align="left" src="images/namegen.png">  
	    <h1 style="margin-bottom:0px;margin-left:20px;margin-top:1.5em" id="maintitle"><b>NameGenWeb</b></h1>
	   <div style="margin-left:50px;font-size:16px;padding-top:5px;padding-bottom:15px;line-height:1.5;">
	   	Software designed to help you capture, analyse, and visualise your Facebook network.
	   </div>

	
	<span style="padding-right:5px;">
		<?php 
		$url = htmlspecialchars($_SERVER['HTTP_REFERER']); 
		?>
	  <a class="topbutton" role="button" href="<?php echo $url; ?>">Return to Application</a>   
	</span>
	

	</div>	
</div>
<div id="content" style="display:block">
	
	
	<div id="contenttext" style="padding:35px">
	<h2>Why Study Personal Networks on Facebook?</h2>
<p>The network of friends, family, neighbours, schoolmates and others is a highly meaningful set of friendships. Showing their relationships to each other can help you understand how networks work and maybe help you learn a little about how your social world fits together. Different people have different clusters in their networks, as well as different brokers that link everyone together. If you have a significant other, chances are they will be tied to several groups. If you have your high school friends on Facebook, chances are they will be tightly linked together as many of them are friends of friends.</p> 

<div style="width:700px;margin-left:auto;margin-right:auto;height:350px;">

	<div style="float:left;width:50%"><img src="images/groups.png" style="width:100%"/></div>
	<div style="float:right;width:50%"><img src="images/groups2.png" style="width:100%"/></div>
	<div style="clear:both"></div>	
</div> 

	<p>We have been exploring Facebook personal networks at the Oxford Internet Institute for several years. Here are some of the publications from this work:</p>
	<ul>     
	<li>Hogan, B. (2010). Visualizing and Interpreting Facebook Networks. In D. Hansen, M. A. Smith, & B. Shneiderman (Eds.), Analyzing Social Media Networks with NodeXL (pp. 165-180). Burlington, MA: Morgan Kaufmann.</li>

	<li>Brooks, B., Welser, H. T., Hogan, B., & Titsworth, S. (2011). Socioeconomic Status Updates: Family SES and emergent social capital in college student Facebook networks. Information, Communication & Society, 14(4), 529-549.</li>

	<li>Hogan, B. Melville, J. (DRAFT) Revealing the Audience: Articulating The Collapsed Contexts of Facebook through Social Network Visualisation. Submitted January 18, 2012. </li>

	<li>Hogan, B. Brooks, B. Ellison, N., Lampe, C. Vitak, J. (DRAFT). Assessing structural correlates to social capital in Facebook personal networks. Submitted March 2012. </li>
</ul>

	<p>NameGenWeb is hosted at the <a href="http://www.oii.ox.ac.uk" target="_top">Oxford Internet Institute</a>. Funding for this version of the program has been provided by the Teaching Excellence Award through the University of Oxford. Current codebase is maintained by Joshua R. Melville. Project Coordinator is Dr Bernie Hogan. </p> 

	<h4>Pending Features</h4>
	<p>NameGenWeb is under construction. At present we currently only render a plain blue node network. We have plans for the following features. You can request other features by emailing us. </p>
	<ul>
	<li>Color coded nodes based on different communities clusters. </li>
	<li>Export network diagram to post to your wall.</li>
	</ul>
	<br />
	</div>
</div>			
</div>

</div> 

</div>

 
</body>
</html>