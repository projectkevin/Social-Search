<html> 
<head> 
<title>SCLSRCH.com The Social Search Engine</title> 
<link rel="stylesheet" href="style.css" type="text/css" />
</head> 
 
<body> 
 
<div class="container"> 
	<div id="sidenav"> 
    	<a href="index.php"><img src="logo.png" width="200" height="70" border="0" /></a> 
    </div> 
<div id="sidesearch">
		<form action="srch.php" method="get"> 
			<?php echo '<input type="text" name="q" class="box" value="' . $_GET['q'] . '">'; ?>
			<button class="btn" title="Submit Search">Search</button><br />
			<!--radio buttons to allow users to choose what service to search... twitter is selected by default-->
				<input type="radio" name="scl" value="Twitter" checked><img src="twitter.png" width="16" border="0" /></input>
				<input type="radio" name="scl" value="reddit"><img src="reddit.png" width="16" border="0" /></input>
				<input type="radio" name="scl" value="digg"><img src="digglogo.png" width="16" border="0" /></input><br>
				<input type="radio" name="scl" value="Flickr"><img src="flickr.png" width="16" border="0" /></input>
				<input type="radio" name="scl" value="vid"><img src="youtube.png" width="16" border="0" /></input>
				<input type="radio" name="scl" value="LastFM"><img src="last.png" width="16" border="0" /></input>
	</form>
	<!-- nav --> 
		<ul id="nav1"> 
		  <li id="nav-news"><a href="/"><strong>Home</strong></a></li> 
		  <li id="nav-features"><a href="/blog"><strong>Blog</strong></a></li> 
		 <li id="nav-pile"><a href="/contact"><strong>Contact</strong></a></li> 
		  <li id="nav-photos"><a href="/about"><strong>About</strong></a></li> 
		</ul> 
</div>

		<!-- /nav -->
    <div id="content">