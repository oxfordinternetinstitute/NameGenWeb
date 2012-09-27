<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<html>
	<head> 
	<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
  <body>	

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
	<h2>Privacy Policy</h2>

	<p>NameGenWeb is a research application developed at the Oxford Internet Institute, University of Oxford by Joshua R. Melville and Bernie Hogan based on earlier code from Bernie Hogan and Arber Ceni. This application is designed principally as a research and teaching utility for accessing a particular social network, classically referred to as the 'personal network'. The principal motivation is to help individuals make sense of the connections within their own network, and to familiarize themselves with network analysis as an analytical technique.</p> 

	<h4>What we keep:</h4> 
	<p>We keep all data that we use to generate the network files that can be downloaded in a variety of formats (GraphML, GDF, DL). This data is then deleted after a short caching period, and no later than 90 days. </p>

	<h4>What we use:</h4> 
	<p>This is part of a research study on personal networks and Facebook use. If you consent to participation, we collect aggregate statistics on your network, and store in a separate table that is linked only through a confidential identifier.</p> 

	<h4>How you can be exempted from analysis: </h4>
	<p>Easy - do nothing. No networks that are collected using this tool are analyzed for any purpose without the explicit permission of the network owner. If you have consented to being contacted about the use of this tool, we reserve the right to create and store summary statistics of your network. Any further analysis of your data will be done at a later stage using an explicit consent request emailed to you using the email address provided by Facebook.</p>  

	<h4>How you can be exempted from storage:</h4> 
	<p>The network data that you download through NameGenWeb will be temporarily cached in a database and available for redownload at a later time. You can delete this data at any time. We reserve the right to delete this data at any time. Deletion of this data does not preclude you from re-downloading your network.</p>  

	<h4>Who do we share data with:</h4>
	<p>No one outside of the principal research team, including Bernie Hogan, Joshua R. Melville and Adham Tamer (our in-house server administrator) will have access to any data downloaded through this tool without either:</p>
	<ul style="list-style-type:lower-alpha"> 
	<li>Being brought in to research this data with the core team, or</li>
	<li>Receiving explicit permission from you, the "owner" of the network.</li> 
	</ul>
	<p>We are academic researchers. We have no interest in marketing this data, selling it, or releasing it for other researchers outside of our core team. That said, we will make the source code for specific network capture and analysis techniques available upon request.</p> 

	<p>We will never publish data collected here in such a way that identifies specific individuals in a Facebook network, either through the display of names, icons or Facebook user ids. Any network visualization we publish will:</p>
	<ul style="list-style-type:lower-alpha">
	<li>be done with the explicit consent of a specific individual from a specific study, and not from general NameGenWeb use, or</li>
	<li>be displayed using artificial icons for illustrative purposes. This does not preclude you from displaying your own network with names and icons.</li>   
	</ul>	
		
	<h4>Contact:</h4> 
	<p>You can contact us with questions and comments: bernie.hogan@oii.ox.ac.uk</p>
	<p>We are located at the Oxford Internet Institute, University of Oxford, 1 St. Giles, Oxford, UK. OX1 3JS.</p>
	<br />	<br />	<br />
	</div>
</div>			
</div>

</div> 	
</div>

 
</body>
</html>