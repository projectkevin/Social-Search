<?php
//********TWITTER***********
/*$twitter_trends = file_get_contents("http://search.twitter.com/trends/daily.json");
$json = json_decode($twitter_trends);
echo 'Twitter: ';
foreach ($json->trends as $trend) {
	$nom = str_replace("#", "%23", $trend->name); 
    echo("<a href=\"/srch.php?scl=Twitter&q=$nom\">".$trend->name."</a>, ");
}
echo '<br />';*/
//************SCLSRCH************
echo 'SCLSRCH top trends. <br>Note, I am working hard to fix this list and make it more useful. Spambots have a grasp of this stuff and have more time than I do!<br>';
$con = mysql_connect("mysql519int.cp.blacknight.com","u1015635_srch","transf0rm");
if (!$con)
  {
  die();
  }
mysql_select_db("db1015635_srch", $con);
$result = mysql_query("SELECT query, COUNT(*) FROM srch GROUP BY query DESC LIMIT 0,10;");
while($row = mysql_fetch_array($result))
  {
	$nom = str_replace("#", "%23", $row); 
  echo '<a href="srch.php?scl=Twitter&q='.$nom['query'].'">'.$row['query'].'</a><br>';
}
mysql_close($con);

/********************Last entered items****************/
echo 'SCLSRCH last sought search terms!<br>';
$con = mysql_connect("mysql519int.cp.blacknight.com","u1015635_srch","transf0rm");
if (!$con)
  {
  die();
  }
mysql_select_db("db1015635_srch", $con);
$result = mysql_query("SELECT query, COUNT(*) FROM srch GROUP BY query ORDER BY query DESC LIMIT 10;");
while($row = mysql_fetch_array($result))
  {
	$nom = str_replace("#", "%23", $row); 
  echo '<a href="srch.php?scl=Twitter&q='.$nom['query'].'">'.$row['query'].'</a><br>';
}
mysql_close($con);
?>