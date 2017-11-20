    <form method="get">
	<input type="text" name="url" placeholder="Enter URL">
	<input type="submit" name="submit">
    </form>

<?php

echo "<h4><u>Server IP Address: </u></h4>" ;
echo $_SERVER['REMOTE_ADDR']."<br>";//server IP Address

function timer()//timer() function for calculating load time
{   static $first;
    if (is_null($first))
    {
        $first = microtime(true);
    }
    else
    {
        $other = round((microtime(true) - $first), 4);
        $first = null;
        return $other;
    }
}
timer();

echo "<h4><u>Page Loading time in Second :</u></h4>" . timer() . ' seconds.<br>  <br>';


function file_get_contents_curl($url)
{
    $var = curl_init();
	
    curl_setopt($var, CURLOPT_HEADER, 0);
    curl_setopt($var, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($var, CURLOPT_URL, $url);
    curl_setopt($var, CURLOPT_FOLLOWLOCATION, 1);
   
   $data = curl_exec($var);
   
   curl_close($var);
    
	return $data;
}



if(isset($_GET['submit']))
{
	$url=$_GET['url'];
	$html = file_get_contents_curl($url);


$wap = new DOMdocument();
@$wap->loadHTML($html);
$duc = $wap->getElementsByTagName('title');

$title = $duc->item(0)->nodeValue;
$metas = $wap->getElementsByTagName('meta');

for ($i = 0; $i < $metas->length; $i++)
{
    $meta = $metas->item($i);
    if($meta->getAttribute('name') == 'description')
        $description = $meta->getAttribute('content');
    if($meta->getAttribute('name') == 'keywords')
        $keywords = $meta->getAttribute('content');
}

//HTTP ststus
$var = curl_init($url);
curl_setopt($var,CURLOPT_RETURNTRANSFER,1);
curl_setopt($var,CURLOPT_TIMEOUT,10);
$output = curl_exec($var);
$httpcode = curl_getinfo($var, CURLINFO_HTTP_CODE);
curl_close($var);

//printing HTTP Status
echo "<h4><u>HTTP Status of URL: </u></h4>".$httpcode;




//printing the title
echo "<h4><u>Title Tag of URL: </u></h4>$title". '<br/><br/>';  

//printing keywords
echo "<h4><u>Keywords of URL: </u></h4>$keywords"."<br><br>"; 
//printing description
echo "<h4><u>Description of URL: </u></h4>$description". '<br/><br/>'; 

}


?>