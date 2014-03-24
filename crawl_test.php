<?php

define( "CRAWL_LIMIT_PER_DOMAIN", 3 );
 
// Used to store the number of pages crawled per domain.
$domains = array();
// List of all our crawled URLs.
$urls = array();
$count = 0;
 

class Crawler {    

      // //  ********************  BEGIN - Original Area  ********************
    function crawl($url) {  
      global $domains, $urls; 
      
        $parse = parse_url( $url );
       
        /* This is where we add to the count of crawled URLs
        * and to our list of crawled URLs.	*/
        $thishost = $parse['host'];
        $domains[ $thishost ]++;
        $urls[] = $url;

       	print_r($urls);
       	echo "<br />Domains = " . count($domains) . "<br /><br />";
        $content = file_get_contents( $url );
        if ( $content === FALSE )
        {
          echo "Error.\n";
          return;
        }
        // * Maybe do something with the content here.
        // * Save it, parse it for data, etc.
        $content = stristr( $content, "body" );
        preg_match_all( '/http:\/\/[^ "\']+/', $content, $matches );
        
        echo 'Found ' . count( $matches[0] ) . " urls.\n\n";
        // print_r($matches);

        /* Recursive function below...  	*/
        foreach( $matches[0] as $crawled_url )
        {
          $parse = parse_url( $crawled_url );
       
           // 	Check that we haven't hit our limit for crawled pages per domain
           // * and that we haven't crawled that specific URL yet. 		
          // if ( count( $domains[ $parse['host'] ] ) < CRAWL_LIMIT_PER_DOMAIN )
          //     && !in_array( $crawled_url, $urls ) )
          // {
            sleep( 1 );
            // $count++;
            // if ($count > 100) return;
            crawl( $crawled_url );
          // }
        }

        
      // //  ********************  END - Original Area  ********************
    
	  }
}

$url = "http://spsu.edu";

$spider = new Crawler();
$spider->crawl($url);