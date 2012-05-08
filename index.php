<html>
<head>
	<title>SCLSRCH.com The Social Search Engine</title>
	<!--stylesheet included in document contains all style rules for this page and the search results page-->
	<link rel="stylesheet" href="style.css" type="text/css" />
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-24992024-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<!--begin body-->
<body>
<!--outer class wrapper defines width constraints for form-->
<div id="wrapper">
	<!--fieldset defines a set of classes in CSS to style the form-->
	<fieldset class="search">
		<!--logo class is a background containing the logo image-->
	<div id="logo">&nbsp;</div>
	<div id="logodesc">&nbsp;<strong>Social Search</strong> - Social media search engine...</div>
	<!--content class contains the form data itself, including styles for the input box-->
	<div id="contents">
		<form action="srch.php" method="get"> 
			<input type="text" name="q" class="box"><button class="btn" title="Submit Search">Search</button>
			<!--radio buttons to allow users to choose what service to search... twitter is selected by default-->
		<input type="radio" name="scl" value="Twitter" alt="flickr" checked><img src="twitter.png" border="0" /></input>
		<input type="radio" name="scl" value="digg"><img src="digglogo.png" border="0" alt="Digg social news stories" /></input>
	<input type="radio" name="scl" value="reddit"><img src="reddit.png" border="0" alt="Reddit social news" width="32" height="32" /></input>
	<input type="radio" name="scl" value="Flickr"><img src="flickr.png" border="0" alt="Flickr" /></input>
	<input type="radio" name="scl" value="vid"><img src="youtube.png" border="0" alt="Video" width="32" height="32" /></input>
	<input type="radio" name="scl" value="LastFM"><img src="last.png" border="0" alt="LastFM" /></input>
	</div>
</form><br />		
	<div class="mainfooter"> &copy; 2010 SCLSRCH.com, all rights reserved. Developed by <a href="http://iamkev.in" target="new">Kevin</a>.</div><div class="clear"></div> </fieldset> 
	</body>
</html>