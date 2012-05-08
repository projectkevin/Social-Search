<?php
//***************CALL HEADER*************
include_once('header.php');
echo '<div class="rank">Below are the top 10 trending topics on SCLSRCH, ordered by how many people have been searching various topics on the site. So, the top trend is the most searched for item on the site. Go to the <a href="index.php">homepage</a> to compare with the twitter trends!<p><div class="clear"></div><ol>';
//*******SQL FUNCTION************
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("srch", $con);

$result = mysql_query("SELECT query, COUNT(*) FROM social GROUP BY query DESC LIMIT 0,10;");
while($row = mysql_fetch_array($result))
  {
	$nom = str_replace("#", "%23", $row); 
  echo '<div class="ranklink"><li><a href="srch.php?scl=Twitter&q='.$nom['query'].'">'.$row['query'].'</a></li></div>';
}
mysql_close($con);
echo '</ol></div>';
//**************END SQL FUNCTION, CALL FOOTER***************
include_once('footer.php');
?>