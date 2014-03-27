<?php
 
// define( "CRAWL_LIMIT_PER_DOMAIN", 2 );
// Used to store the number of pages crawled per domain.
// $domains = array();
// List of all our crawled URLs.
// $urls = array();
ob_implicit_flush(true);

class OurStuff {
		public $domain;
		public $urls = array();
		public $all_found = array();
		public $scanned = array();
		public $dirty = array();
		public $depth = 1;
		public $errors = array();
		public $total_scanned = 0;
		public $scan_num = 0;

		public function __construct() {
			$this->domain = "http://spsu.edu/";
			$this->urls = array();
			$this->all_found[] = "http://spsu.edu";
			$this->scanned = array();
			$this->dirty = array();
			$this->depth = 1;
			$this->errors = array();
			$this->total_scanned = 0;
			$this->scan_num = 0;
		}
	  }

class Crawler extends Thread {  
	public $url;
    public function __construct(&$stuff){
        $this->url = $stuff->domain;
    }

	function run( &$stuff ) {		
		$content = file_get_contents( $url );    
		if ( $content === FALSE ) {
			// echo "$url  ==>  Error.\n";
			$stuff->errors[] = $url;
			$this->crawl($stuff);
		}

		$parse = parse_url( $url );

		$host = $parse['host'];
		// $stuff->domains[ $host ]++;
		$stuff->urls[$host] = $url;

		$DOM = new DOMDocument;
		$DOM->loadHTML($content);

		$tmp1 = $this->getAllLinks($DOM);
		$stuff->total_scanned += count($tmp1);
		foreach ($tmp1 as $link) {
			if ( !in_array($link, $stuff->scanned) ) {
				$path = $this->Relative_2_Absolute($link,$stuff->domain);
				$code = $this->Get_Http_Code($path);
				// $temp_scanned[$path] = $code;
				// $temp_all_found[] = $path;
				$stuff->scanned[$path] = $code;
				$stuff->all_found[] = $path;
				// $stuff->total_scanned++;
			}
    		// var_dump($stuff);
    		// echo "$path = [$code]<br />";
    		// echo " ";
			// echo "$path<br />";
			if (!$printed) {
				echo $stuff->total_scanned . "<br />";
				$printed = true;
			}
		}
		// $printed = false;
		unset($path);
		unset($code);

		if (count($stuff->total_scanned) < 1500) { 
	    		// echo $stuff->total_scanned . "<br />";
	    		// echo "total urls = " . count($stuff->scanned) . "<br />";
	    		echo "done";
	    		return;
    		}
    		else {
    			//	$num = count($stuff->scan_num);
				// $tot = $stuff->total_scanned;
    			// echo "********  scan # = [$num]  ********<br />";
    			// echo "********  scanned = [$tot]  ********<br />";
				// $url = array_pop($stuff->all_found);
	    		if($scan_num < count($stuff->all_found)) {
	    			$scan = $stuff->scan_num;
	    			$url = $stuff->all_found[$scan];
	    			$stuff->scan_num += 1;
	    			// echo $stuff->scan_num . "<br />";
	    		}
	    		$this->run($stuff);
	    	}
	}
	function getAllLinks(DOMDocument $thisDOM) {
	  $array;
	  $aValues = $thisDOM->getElementsByTagName('a');
	  foreach ($aValues as $found_a) {
	  	//    echo $DOM->saveHtml($node), PHP_EOL;
	    $array[] = $found_a->getAttribute('href');        
	  }
	  return $array;
	}
	function getAllImages(DOMDocument $thisDOM) {
	  $imgs = $thisDOM->getElementsByTagName('img');
	  foreach($imgs as $img){
	      $array[] = $img->getAttribute('src');
	  }
	  return $array;
	}
	/* Thank you StackOverflow...
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

}



$stuff = new OurStuff();
// var_dump($stuff);
// echo "begin<br />" . array_pop($stuff->all_found);
// $stuff = new OurStuff;

// $spider = new Crawler();
// $spider->crawl($stuff);


class spiderCrawl extends Thread {
    public $url;
    public $data;

    public $stuff;
    public $spider;
     
    public function __construct($url){

	$this->stuff = new OurStuff();
	$this->spider = new Crawler();
        $this->url = $url;
    }
     
    public function run() {
        $response = file_get_contents($this->url);
        if ($response) {
            /* process response into useable data */
             
            $this->data = array($response);
        }
    }
}
 
$request = new Crawler($stuff);
 
if ($request->start()) {
     
    /* do some work */
     
    /* ensure we have data */
    $request->join();
     
    /* we can now manipulate the response */
    var_dump($request->data);
}