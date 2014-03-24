<?php
// session_start();
// if (isset($_SESSION['login'])) {
    // if ($_SESSION['login']==FALSE) { 
        // header("Location: logout.php"); } }
// else { header("Location: logout.php"); }


/*
 * var_dump()...
 * This removes warnings for special characters that haven't been converted to HTML
 * Example:
 *      <a href="/script.php?foo=bar&hello=world">link</a>
 *   Should be
 *      <a href="/script.php?foo=bar&amp;hello=world">link</a>
 */
var_dump(libxml_use_internal_errors(true));

    global $found;
    global $scanned;
    global $dirty;
    global $main_list;

class Crawl
{    
    
    // return array();
    function getAllElements(DOMDocument $thisDOM) {
        $array = new array();
        $aVals = $thisDOM->getElementsByTagName('a');
        foreach ($aVals as $found_a) {
        //    echo $DOM->saveHtml($node), PHP_EOL;
            $array[] = $found_a->getAttribute('href');        
        }
        $imgs = $thisDOM->getElementsByTagName('img');
        foreach($imgs as $img){
            $array[] = $img->getAttribute('src');
        }
        return $array;
    }

    // Not Used
    // function Get_src_Elements(DOMDocument $thisDOM) {
    //     $imgs = $thisDOM->getElementsByTagName('img');
    //     foreach($imgs as $img){
    //         $src[] = $img->getAttribute('src');
    //     }
    //     return $src;
    // }
	
	/*
	 * Thank you StackOverflow...
	 * http://stackoverflow.com/questions/1243418/php-how-to-resolve-a-relative-url
	 * This function would have taken a week to write.
	 * */
	function Relative_2_Absolute($rel, $base) {
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
        $abs = "$host$path/$rel";

        /* replace '//' or '/./' or '/foo/../' with '/' */
        $re = array('#(/\.?/)#', '#/(?!\.\.)[^/]+/\.\./#');
        for($n=1; $n>0; $abs=preg_replace($re, '/', $abs, -1, $n)) {}

        /* absolute URL is ready! */
        return $scheme.'://'.$abs;
    }

    function absolute_array(array $ar, $url) {
        foreach($ar as $a)
        {
			$path = $this->Relative_2_Absolute($a, $url);    //"http://spsu.edu/");
        	$code = $this->Get_Http_Code($path);
            // echo "$path  -->  $code<br />";
        }
        return $code;
    }
	
    function Check_Value_Exists($arr) {
        return array_key_exists('first', $arr);
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
    
    function getElements($url) {
        
        $html_source = file_get_contents($url);
        $DOM = new DOMDocument;
        $DOM->loadHTML($html_source);
        
        $tmp1 = $this->getAllElements($DOM);

        foreach ($tmp1 as $value) {
            $path[] = $this->Relative_2_Absolute($value,$url);
        }
        unset($value);

        $subDomain;
        print_r($path);
        foreach ($path as $value) {
            $code = $this->Get_Http_Code($value);
            // switch ($code) {
            //     case '200':
            //         $subDomain[200] = $
            //         break;
                
            //     default:
            //         # code...
            //         break;
            // }
            // $subDomain[] = array($value => $code);
        }
        // $tmp2 = $this->Get_src_Elements($DOM);
        // $tmp3 = $this->absolute_array($tmp1,$url);
        // $tmp4 = $this->absolute_array($tmp2,$url);
        
        
        $found[$url] = $subDomain;  // = array("$url" => $tmp1);
        // $found[$url] = $subDomain = array("$url" => $tmp2);
        
        // print_r($found);

        echo '<h1>'.$url.'<br />';
        echo '\'a\' Items: '.count($tmp1).'<br />';
        echo '\'src\' Items: '.count($tmp2).'<br />';
        $tot = count($tmp1) + count($tmp2);
        echo 'Total Items: '.$tot.'</h1>';
        
        echo '<h3>----------  Begin \'a\'  ----------</h3>';
        $this->absolute_array($tmp1, $url);
        echo '<h3>----------  Begin \'src\'  ----------</h3>';
        $this->absolute_array($tmp2, $url);
    }
}

$url = "http://spsu.edu/";
// $url = "http://www.jetsquared.org/waggle/";

//$html_source = file_get_contents($url);
//$DOM = new DOMDocument;
//$DOM->loadHTML($html_source);

$crawl = new Crawl();
$crawl->getElements($url);


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
    $new_array = $crawl->getAllElements($newDOM);
    
    echo '<br /><br /><h1>New Items: '.count($new_array).'</h1><br /><br />';
    absolute_array($new_array);
}
 */


