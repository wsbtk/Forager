<?php
// $urls = array();
error_reporting(E_ALL);
class AsyncWebRequest extends Thread {

    public $url;
    public $urls;
    public $data;
    public $depth;

    public function __construct($url)	{	//, $depth) {
        $this->url = $url;
        // $this->depth = $depth;
    }

    public function run() {
        if (($url = $this->url)) {
            /*
             * If a large amount of data is being requested, you might want to
             * fsockopen and read using usleep in between reads
             */
            $this->data = file_get_contents($url);
            // crawl_page($this->url,$this->depth);
        } else
            printf("Thread #%lu was not provided a URL\n", $this->getThreadId());
	}

	public function crawl_page($url, $depth = 1) {
	    static $seen = array();
	    if (isset($seen[$url]) || $depth === 0) {
	        return;
	    }

	    $seen[$url] = true;

	    $dom = new DOMDocument('1.0');
	    @$dom->loadHTMLFile($url);

	    $anchors = $dom->getElementsByTagName('a');
	    foreach ($anchors as $element) {
	        $href = $element->getAttribute('href');
	        if (0 !== strpos($href, 'http')) {
	            $path = '/' . ltrim($href, '/');
	            if (extension_loaded('http')) {
	                $href = http_build_url($url, array('path' => $path));
	            } else {
	                $parts = parse_url($url);
	                $href = $parts['scheme'] . '://';
	                if (isset($parts['user']) && isset($parts['pass'])) {
	                    $href .= $parts['user'] . ':' . $parts['pass'] . '@';
	                }
	                $href .= $parts['host'];
	                if (isset($parts['port'])) {
	                    $href .= ':' . $parts['port'];
	                }
	                $href .= $path;
	            }
	        }
	        echo $href . "<br />",PHP_EOL;

	      	if ( !in_array($href, $urls) ) {
	    		$code = Get_Http_Code($url);
	    		$urls[$href] = $code;
	        	crawl_page($href, $depth - 1);
		    }
	    }
	    // echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
	    // foreach ($urls as $url => $value) {
	    // 	// $code = Get_Http_Code($url);
	    // 	echo "$url = [$value]<br />";// . Get_Http_Code($url);
	    // }
	}

	public function Get_Http_Code($url) {  
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


$url = "http://spsu.edu/";

$t = microtime(true);
$g = new AsyncWebRequest(sprintf("http://www.google.com/?q=%s", rand() * 10));
if ( $g->start() ) {
    printf("Request took %f seconds to start ", microtime(true) - $t);
    while ($g->isRunning()) {
        echo ".";
        usleep(100);
    }
    if ($g->join()) {
        printf(" and %f seconds to finish receiving %d bytes\n", microtime(true) - $t, strlen($g->data));
    } else
        printf(" and %f seconds to finish, request failed\n", microtime(true) - $t);
}

