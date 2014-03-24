<?php
// namespace Bc\BackgroundProcess;

$urls = array();
class BackgroundProcess
{
    private $command;
    private $pid;

    public function __construct($command)
    {
        $this->command = $command;
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
            // echo $href . "<br />",PHP_EOL;

            if ( !in_array($href, $urls) ) {
                $code = Get_Http_Code($url);
                $urls[$href] = $code;
                crawl_page($href, $depth - 1);
            }
        }
        // echo "URL:",$url,PHP_EOL,"CONTENT:",PHP_EOL,$dom->saveHTML(),PHP_EOL,PHP_EOL;
        foreach ($urls as $url => $value) {
         // $code = Get_Http_Code($url);
         echo "$url = [$value]<br />";// . Get_Http_Code($url);
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

    public function run($outputFile = '/dev')
    {
        $this->pid = shell_exec(sprintf(
            '%s > %s 2>&amp;1 &amp; echo $!',
            $this->command,
            $outputFile
        ));
    }

    public function isRunning() {
        try {
            $result = shell_exec(sprintf('ps %d', $this->pid));
            if(count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch(Exception $e) {}

        return false;
    }

    public function getPid() {
        return $this->pid;
    }
}

// <?php



$url = "http://spsu.edu/";
// crawl_page($url, 2);

$process = new BackgroundProcess(crawl_page($url, 2));
$process->run();

echo sprintf('Crunching numbers in process %d', $process->getPid());
while ($process->isRunning()) {
    echo '.';
    sleep(1);
}
echo "\nDone.\n"






