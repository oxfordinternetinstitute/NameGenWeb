<!DOCTYPE html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<html>
	<head> 
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
	<script>
$(document).ready(function(){
	var contentwidth = $('#changelogtext').width();
	var contentheight = $('#changelogtext').height();

	

	
	$('#changelog').hover(function(){
		var pos = $('#changelog').offset();  
		var left = pos.left-contentwidth+85 + "px";
	    var top = 	pos.top-contentheight-36 + "px";
		$('#changelogtext').css( { 
	                position: 'absolute',
	                zIndex: 5000,
	                left: left, 
	                top: top
	        } );
		$('#changelogtext').fadeToggle("fast", "linear");});
});	
	
	</script>
	</head>
  <body>
	<div id="fb-root"></div>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '<?php echo $app_id; ?>', // App ID
	      channelUrl : 'channel.html', // Channel File
	      status     : true, // check login status
	      cookie     : false, // enable cookies to allow the server to access the session
	      xfbml      : true  // parse XFBML
	    });

	    FB.Canvas.setAutoGrow(100);
		FB.Canvas.scrollTo(0,0);
	  };

	  // Load the SDK Asynchronously
	  (function(d){
	     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement('script'); js.id = id; js.async = true;
	     js.src = "//connect.facebook.net/en_US/all.js";
	     ref.parentNode.insertBefore(js, ref);
	   }(document));

	</script>

  <a id="top" name="top" style="display:block;height:1px;width:1px;"></a>	
		<script type="text/javascript">
			    function toggleContent(val) {
			            jQuery("#content").slideDown(350);
						$('.getstarted').fadeOut(500);
			    }
		
		</script>

<div id="container">
