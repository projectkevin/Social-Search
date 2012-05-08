<?php
/*
Primary search file that will take in a string from user input on index.php and then push the data out to some selected service, as per index.php
The system will then retrieve the data as an object and output it to the results page.
*/

//include the generic theme header for the page.
include_once('header.php');

//***************************************FLICKR CONSTRUCT CLASS******************************
//This class builds the object for flickr images as standard methods do not work with the php_serial format flickr uses.
class Flickr { 
	//set API keys required to retrieve data
	private $apiKey=''; 
	private $secret='';
 	//required function to create the object on the system to carry the data.
	public function __construct() {} 
 	//main function to retrieve the object from the flickr service using the query user inputed.
	public function search($query) { 
		$search='http://flickr.com/services/rest/?method=flickr.photos.search&api_key=&tags=' . $query . '&per_page=30&sort=relevance&format=php_serial'; 
		$result = file_get_contents($search); 
		$result = unserialize($result); 
		//return the value (object) to the function requesting this class.
		return $result; 
	}
}

//************************SQL**************************
//Simple mySQL methods to connect to the local database to perform local "trends". 
//$connect = mysql_connect("","","");
//if it does not connect, kill the connection to avoid corrupting the database
/*	if (!$connect) {
  		die('Could not connect: ' . mysql_error());
  	}*/
//connect to the database table using the database address/user/pass contained in the string above, then insert the query into the DB
/*	mysql_select_db("db1015635_srch", $connect);
	mysql_query("INSERT INTO `srch` (`query`) VALUES ('$_GET[q]')"); 
	mysql_close($connect);*/
//end (note that this is not a function on it's own but rather a global event)

//***********************GLOBAL*******************************
//Strings and manipulators used to pass the query into their respective functions

//get the query from the search bar and give it to the 'q' variable
$q=$_GET['q'];
//get the search section required by the user and give it to the 'sel' variable
$sel=$_GET['scl'];
//replace the 'hash' key (a commonly used item on twitter, see documentation) to ensure system stability. Using this character could crash the search function as it's not standard UTF characters
$q = str_replace("#", "%23", $q);
//series of individual "if" statements to pass the query to relevant function based on what system they selected!
if ($sel=="Flickr"){
	flick($q); 
}

if ($sel=="reddit"){
	reddit($q); 
}
if ($sel=="Twitter"){
	twit($q);
}

if ($sel=="LastFM"){
	last($q);
}
if ($sel=="vu"){
	vu($q);
}

if ($sel=="vid"){
	vid($q);
}

if ($sel=="blogs"){
	blogs($q);
}

if ($sel=="digg"){
	digg($q);
}

//end global items

//***************************************FLICKR**********************************
function flick($q){
	//display a headed message to show users what system they're looking at!
	echo '<div class="header"><h1><a href="http://flickr.com" target="new" class="header_link">FLICKR</a> Image Results for: <em>' . $q . '</em></h1><div>';
	//replace the hash-key and empty spaces to ensure the system will work.
	$q = str_replace(" ", "", $q);
	$q = str_replace("#", "%23", $q);
	//create a new flickr object based on the class (as constructed above)
	$Flickr = new Flickr; 
	//pass the data from flickr into the data variable and output it using a 'foreach' loop, only looping when it finds a new instance of an image return.
	$data = $Flickr->search($q); 
	foreach($data['photos']['photo'] as $photo) { 
		echo '<a href="http://www.flickr.com/photos/' . $photo["owner"] . '/' . $photo["id"] . '/" target="new"><img class="flickr_thumb" border="0" src="http://farm' . $photo["farm"] . '.static.flickr.com/' . $photo["server"] . '/' . $photo["id"] . '_' . $photo["secret"] . '.jpg" width="100" /></a>';
	}
}

//*********************************TWITTER******************************
function twit($q){
	//replace null character input to enure the system works, regardless of whether it outputs anything or not
	if($_GET['q']==''){$q = '';}
	//display a headed message to show users what system they're looking at
	echo '<div class="header"<h1><a href="http://twitter.com" target="new" class="header_link">TWITTER</a> Tweet Results for: <em>' . $q . '</em></h1>&nbsp;&nbsp;&nbsp;&nbsp; <input type="button" value="Reload Page" onClick="window.location.href=window.location.href">
</div>';
echo '<div class="alternative">Not find what you want? Try searching news with <a href="srch.php?scl=reddit&q='.$q.'">reddit</a>...</div><div class="clear"></div>';
	//replace string characters to ensure system integrity
	$q = str_replace(" ", "%20", $q);
	$q = str_replace("#", "%23", $q); 
	//construct the address using the string to pull the .atom data
	$search = "http://search.twitter.com/search.atom?q=".$q."";
	//instanciate an instance of the PHP5 function (cURL)
	$tw = curl_init();
	curl_setopt($tw, CURLOPT_URL, $search);
	curl_setopt($tw, CURLOPT_RETURNTRANSFER, TRUE);
	$twi = curl_exec($tw);
	//take the cURL request and turn it into an XML element to loop through results and output as they appear in the object
	$search_res = new SimpleXMLElement($twi);
	foreach ($search_res->entry as $twit1) {
		$description = $twit1->content;
		echo "<div class='user'><a href=\"",$twit1->author->uri,"\" target=\"new\"><img border=\"0\" width=\"48\" class=\"twitter_thumb\" src=\"",$twit1->link[1]->attributes()->href,"\" title=\"", $twit1->author->name, "\" /></a>\n";
		echo "<div class='text'>".$description."<div class='description'>From: <a href=\"",$twit1->author->uri,"\">",$twit1->author->name,"</a> at ",$twit1->published,"</div></div><div class='clear'></div></div>";
	}
	//close the cURL instance
	curl_close($tw);
}

//*********************************LAST.FM*******************************
function last($q){
	//construct the address to pull data from
	$target_url='http://ws.audioscrobbler.com/2.0/?method=tag.gettopartists&api_key=&tag=' . $q . '';
	//display a headed message to show users what system they're looking at
	echo '<div class="header"><h1><a href="http://last.fm" target="new" class="header_link">LAST.FM</a> Music Results for: <em>' . $q . '</em></h1><h2>These results are links to related artists to your search...</h2><div>';
	//ensure system integrity by only polling the request if a result is returned
	if ($xml = @simplexml_load_file($target_url)){
		foreach($xml->topartists->artist as $tracks){
			echo '<div class="last"><a href="'.$tracks->url.'" target="new"><img src="'.$tracks->image.'" border="0" class="lastimg">'.$tracks->name.'</a><div class="lasttag">Link:<a href="http://" '.$tracks->url.'" target="new">'.$tracks->url.'</a></div></div><div class="clear"></div>';
		}
	}
	//if results are empty, give the user an error message
	else { echo "Oops! An error occured. <em>".$q."</em> does not exist in the last.fm records. Please have another go!"; }
}

//*******************************VIDEO*************************************
function vid($q){
	//display a headed message to show users what system they're looking at
	echo '<div class="clear"></div><div class="header"><h1>Video results for: <em>' . $q . '</em></h1><div>';
	echo '<div class="alternative">Looking for pictures? Try searching with <a href="srch.php?scl=Flickr&q='.$q.'">flickr</a>..</div><div class="clear"></div>';
	//replace characters that may inhibit the functions of the system
	$q = str_replace(" ", "", $q);
	$q = str_replace("#", "%23", $q);
	//construct the XML data to be returned
	$target_url = 'http://api.vodpod.com/v2/search.xml?api_key=&limit=20&sort=date&query=' . $q . '';
	//instanciate a PHP5 cURL request
	$video = curl_init();
	curl_setopt($video, CURLOPT_URL, $target_url);
	curl_setopt($video, CURLOPT_RETURNTRANSFER, TRUE);
	$return = curl_exec($video);
	//if a result is returned from cURL, turn it into an XML element to be looped through and output data
	if ($search_res = new SimpleXMLElement($return)){
		foreach ($search_res->video as $vid){
			echo '<div class="vid"><a href="'.$vid->url.'" target="new"><img src="'.$vid->thumbnail.'" class="vidpic" /> '.$vid->title.'</a><div class="viddesc">Video viewed '.$vid->total_views.' times!</div></div><div class="clear"></div>';
		}
	}
	else { echo "Oops! <em>".$q."</em> doesn't exist in our records! Please try again :)";}
}

//*******************************context********************************
function vu($q){
//uberVU api: 995bcvbu9gxqaqf9sjzv88hj
		echo '<div class="header"><h1>Social media comment results for: <em>' . $q . '</em></h1><h2>These results are links to related sites & comments to your search...</h2><div>';
		$q = str_replace(" ", "", $q);
		$q = str_replace("#", "%23", $q);
		$target_url = "http://api.contextvoice.com/1.2/resources/search/?s=hotness&format=json&apikey=&q=" . $q . "";
		$data = file_get_contents($target_url);
		$decode = json_decode($data);
		foreach ($decode->results as $return) {
			echo '<a href="' . $return->url . '" target="new">' . $return->title . '</a><br>' . $return->content . '<br><b>Reaction count: '. $return->reactions_count . '</b><p>';
		}
}

//*************************DIGG*************************************
function digg($q){
	//api key: 4b9482bacdb5ebf4ac96e97f03d882ea API SECRET: 63452c4c0064eee8baa7097245871d2e
	echo '<div class="header"><h1><a href="http://digg.com" target="new">Digg</a> social news stories for: <em>' . $q . '</em></h1><div>';
	echo '<div class="alternative">Not find what you want? Try searching with <a href="srch.php?scl=reddit&q='.$q.'">reddit</a></div><div class="clear"></div>';
	$q = str_replace(" ", "", $q);
	$q = str_replace("#", "%23", $q);
	$target_url = 'http://services.digg.com/1.0/endpoint?method=search.stories&appkey=&sort=promote_date-desc&count=20&type=json&query='.$q.'';
	ini_set('user_agent', 'SCLSRCH/1.0');
	$story = file_get_contents($target_url);
	$decode = json_decode($story);
	foreach($decode->stories as $digg){
		echo '<div class="digg"><div class="digg_count">'.$digg->diggs.'<a href="'.$digg->short_url.'" target="new"></div>'.$digg->title.'</a><div class="diggdesc">'.$digg->description.'<br><strong>Category:</strong> '.$digg->container->name.'->'.$digg->topic->name.'</div></div><div class="clear"></div>';
	}
}

//***************************REDDIT****************************

function reddit($q){
	//replace null character input to enure the system works, regardless of whether it outputs anything or not
	if($_GET['q']==''){$q = '';}
	//display a headed message to show users what system they're looking at
	echo '<div class="header"><h1><a href="http://reddit.com" target="new" class="header_link">Reddit</a> Results for: <em>' . $q . '</em></h1></div>';
	echo '<div class="alternative">Not find what you want? Try searching with <a href="srch.php?scl=digg&q='.$q.'">digg</a>!</div><div class="clear"></div>';
	//replace string characters to ensure system integrity
	$q = str_replace(" ", "%20", $q);
	//construct the address using the string to pull the .atom data
	$search = 'http://www.reddit.com/search.json?q='.$q.'';
	//instanciate an instance of the PHP5 function (cURL)
	$data = file_get_contents($search);
		$decode = json_decode($data);
		foreach ($decode->data->children as $return) {
			echo '<div class="digg"><div class="digg_count">'.$return->data->score.'</div><a href="'.$return->data->url.'" target="new">'.$return->data->title.'</a><div class="diggdesc"><a href="http://reddit.com/'.$return->data->permalink.'" target="new" class="redditcomments"><font color="#404040">'.$return->data->num_comments.' Comments</font></a>. Submitted by '.$return->data->name.' in <font color="#505050">'.$return->data->subreddit.'</font></div></div><div class="clear"></div>';
			}
}


//include the generic footer code to be displayed on the page.
include_once('footer.php');
?>