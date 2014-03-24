<?php

define( "CRAWL_LIMIT_PER_DOMAIN", 2 );
 
// Used to store the number of pages crawled per domain.
$domains = array();
// List of all our crawled URLs.
$urls = array();
$all_found = array();
 

class Crawler {    

  //     // //  ********************  BEGIN - Original Area  ********************
    // function crawl( $url ) {  
    // {
    //   global $domains, $urls; 
      
    //     // $parse = parse_url( $url );
       
    //     // * This is where we add to the count of crawled URLs
    //     // * and to our list of crawled URLs.
    //     // $domains[ $parse['host'] ]++;
    //     // $urls[] = $url;
       
    //     // $content = file_get_contents( $url );
    //     // if ( $content === FALSE )
    //     // {
    //     //   echo "Error.\n";
    //     //   return;
    //     // }
    //     // * Maybe do something with the content here.
    //     // * Save it, parse it for data, etc.
    //     // $content = stristr( $content, "body" );
    //     // preg_match_all( '/http:\/\/[^ "\']+/', $content, $matches );
        
    //     // echo 'Found ' . count( $matches[0] ) . " urls.\n\n";

    //     // * Recursive function below...  
    //     // foreach( $matches[0] as $crawled_url )
    //     // {
    //     //   $parse = parse_url( $crawled_url );
       
    //     //    Check that we haven't hit our limit for crawled pages per domain
    //     //    * and that we haven't crawled that specific URL yet. 
    //     //   if ( count( $domains[ $parse['host'] ] ) < CRAWL_LIMIT_PER_DOMAIN
    //     //       && !in_array( $crawled_url, $urls ) )
    //     //   {
    //     //     sleep( 1 );
    //     //     crawl( $crawled_url );
    //     //   }
    //     // }
  //     // //  ********************  END - Original Area  ********************
    
  // }

  function crawl( $a )
  {
    global $domains, $urls, $all_found;

    echo "Crawling $url... <br />";

    // //   ********************  BEGIN -- New Area  ********************  
    
    $url = array_pop($a);    
    $parse = parse_url( $url );
   
    /* This is where we add to the count of crawled URLs
     * and to our list of crawled URLs. */
    $host = $parse['host'];
    echo $host . "<br />";

    $domains[ $host ]++;
    $urls[$host] = $url;
    
    foreach ($urls as $u) {
      echo "$u <br />";      
    }

    $content = file_get_contents( $url );    
    if ( $content === FALSE )
    {
      echo "$url  ==>  Error.\n";
      array_pop($all_found);
      $this->crawl($all_found);
    }
    $DOM = new DOMDocument;
    $DOM->loadHTML($content);

    $tmp1 = $this->getAllElements($DOM);
    foreach ($tmp1 as $link) {
      if ( !in_array($link, $all_found) ) {
        $all_found[] = $link;
      }
    }

    echo 'all_found = ' . count($all_found) . '<br />';
    echo 'Found = ' . count($found) . '<br />';

    // foreach ($found as $value) {
    //     $path[] = $this->Relative_2_Absolute($value,$url);
    // }
    $onelink = array_pop($all_found);
    $currentdomain = $domain[count($domain)-1];
    $path = $this->Relative_2_Absolute($onelink,$domain[$currentdomain]);
    // }
    
    // foreach( $path as $crawled_url ) {
      // $parse = parse_url($crawled_url);
      // $code = $this->Get_Http_Code($crawled_url);
      // echo "$crawled_url  =  $code <br />";
    // }

      $code = $this->Get_Http_Code($path);
      $urls[$path] = $code;
      echo "$path  =  $code <br />";

    // $code = $this->Get_Http_Code($onelink);
    // $urls[$onelink] = $code;
    // echo "$onelink  =  $code <br />";

    $this->crawl($all_found);
    // //   ********************  END   -- New Area  ********************  
  }

  function getAllElements(DOMDocument $thisDOM) {
      $array;
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

  function absolute_array(array $ar, $url)     {
    //    $cnt = count($ar);
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

}

$all_found[] = "http://spsu.edu/";

$spider = new Crawler();
$spider->crawl($all_found);
