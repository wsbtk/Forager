<?php

function check_url($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);

    return $headers['http_code'];
}
// You need to have CURL installed for this to work. Now you can check for broken links using:

function check_url2($url) {
   $headers = @get_headers( $url);
   $headers = (is_array($headers)) ? implode( "\n ", $headers) : $headers;

   return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
}

$url = "http://spsu.edu/james";
$check_url_status = check_url($url);
if ($check_url_status == '200')
   echo "Link Works";
else
   echo "Broken Link:  " . $check_url_status;