<?php

/*  
 * Not Used
    //      $imgs = $DOM->Get_ElementsByTagName('img');
    //      foreach($imgs as $img){
    //      $src = $img->getAttribute('src');
    //      if(strpos($src, 'http://sitename.com/path/') !== 0){
    //          $img->setAttribute('src', "http://sitename.com/path/$src");
    //      }
    //}
 */


/* var_dump()...
 * This removes warnings for special characters that haven't been converted to HTML
 * Example:
 *  <a href="/script.php?foo=bar&hello=world">link</a>
 * Should be
 *  <a href="/script.php?foo=bar&amp;hello=world">link</a>
 */
var_dump(libxml_use_internal_errors(true));

    // global $scanned_a;
	// global $scanned_src;
	// global $scanned
	
class Crawl
{    
    // return array();
    function Get_a_Elements(DOMDocument $thisDOM) {
        foreach ($thisDOM->Get_ElementsByTagName('a') as $found_a) {
        //    echo $DOM->saveHtml($node), PHP_EOL;
            $array[] = $found_a->getAttribute('href');        
        }
        return $array;
    }

    function Get_src_Elements(DOMDocument $thisDOM) {
        $imgs = $thisDOM->Get_ElementsByTagName('img');
        foreach($imgs as $img){
            $src[] = $img->getAttribute('src');
        }
        return $src;
    }
	
	/*
	 * Thank you StackOverflow...
	 * http://stackoverflow.com/questions/1243418/php-how-to-resolve-a-relative-url
	 * This function would have taken a week to write.
	 * */
	function Relative_2_Absolute($rel, $base)
    {
        /* return if already absolute URL */
        if (parse_url($rel, PHP_URL_SCHEME) != '') return $rel;

        /* queries and anchors */
        if ($rel[0]=='#' || $rel[0]=='?') return $base.$rel;

        /* parse base URL and convert to local variables:
         $scheme, $host, $path */
        extract(parse_url($base));
		
		$scheme = "http";
        /* remove non-directory element from path */
        $path = preg_replace('#/[^/]*$#', '', $path);

        /* destroy path if relative url points to root */
        if ($rel[0] == '/') $path = '';

        /* dirty absolute URL */
        $abs = "$abs$path/$rel";

        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

        /* absolute URL is ready! */
        return $scheme.'://'.$abs;
    }

    function Print_Array(array $ar)
    {
    //    $cnt = count($ar);
        foreach($ar as $a)
        {
			$path = $this->Relative_2_Absolute($a, "http://spsu.edu");
        	$code = $this->Get_Http_Code($path);
			// if($code != '200') {
				echo "$path = $code <br />";
			// }
			// else {
				// echo "$path = Good <br />";
			// }
// 			
			// switch ($code) {
			    // case 0:
			        // echo "i equals 0";
			        // break;
			    // case 200:
			        // echo "i equals 1";
			        // break;
			    // case 404:
			        // echo "i equals 2";
			        // break;
			// }
        }
    }
	
	function Get_Http_Code($url) {
	
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
	    $data = curl_exec($ch);
	    $headers = curl_getinfo($ch);
	    curl_close($ch);
	
	    return $headers['http_code'];
	}
    
    function Get_Elements($url) {
        
        $html_source = file_get_contents($url);
        $DOM = new DOMDocument;
        $DOM->loadHTML($html_source);
        
        $tmp1 = $this->Get_a_Elements($DOM);
		
        $tmp2 = $this->Get_src_Elements($DOM);
        
        echo '<h1>'.$url.'<br />';
        echo '\'a\' Items: '.count($tmp1).'<br />';
        echo '\'src\' Items: '.count($tmp2).'<br />';
        $tot = count($tmp1) + count($tmp2);
        echo 'Total Items: '.$tot.'</h1>';
        
        echo '<h3>----------  Begin \'a\'  ----------</h3>';
        $this->Print_Array($tmp1);
        echo '<h3>----------  Begin \'src\'  ----------</h3>';
        $this->Print_Array($tmp2);
    }
}

// $url = "http://spsu.edu/";
$url = "http://www.jetsquared.org/waggle/";


//$html_source = file_get_contents($url);
//$DOM = new DOMDocument;
//$DOM->loadHTML($html_source);

$crawl = new Crawl();

$crawl->Get_Elements($url);



/*
 * Updated - 3/16 @ 12:15 AM
 * 
 * Now this will take in an array and print out the links from the new page.
 * It's stopping when it finds an input that is not an array.
 * 
 * 
// This takes in the entire array of $a_elements from the current page.
foreach ($a_elements as $this_array)
{
    
    $newDOM = new DOMDocument;
    if(strpos($this_array, $url) !== 0){
        $test = $url."/".$this_array;
    }
    $test_contents = file_get_contents($test);
    $newDOM->loadHTML($test_contents);
    $new_array = $crawl->Get_a_Elements($newDOM);
    
    echo '<br /><br /><h1>New Items: '.count($new_array).'</h1><br /><br />';
    Print_Array($new_array);
}
 */


