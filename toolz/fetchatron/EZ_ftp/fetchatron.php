<?php
// fetchatron.php

$source = 'E:\www\datas\logs\errors.log';
$target = 'E:\www\scripts-fu\fetchatron\ftp.txt';
$pattern = '%(?:.*)"(.*?/media)/(.*)/(.*?)"%';
$replace = "\r\nlcd $1/$2 \r\n cd /$2 \r\nget $3";

print PHP_EOL.'source : '.$source;
print PHP_EOL.'target : '.$target;
print PHP_EOL.'-------------------';


$handleTg = fopen($target, "w+");
if ($handleTg) {
    
    
    $handleSrc = fopen($source, "r");
    if ($handleSrc) {
        $q = 0;
        while (($buffer = fgets($handleSrc, 4096)) !== false) {            
            $matches = array();
            preg_match_all (
                $pattern,
                $buffer,
                $matches,
                PREG_PATTERN_ORDER
            );
            foreach ($matches[0] as $match) {
                print PHP_EOL.'['.(str_pad(++$q,3,' ', 0)).'] '.$match;
                $b = fwrite(
                    $handleTg, 
                    preg_replace(
                        $pattern,
                        $replace,
                        $match
                    )
                );
            }
        }
        if (!feof($handleSrc)) {
            echo "Erreur: fgets() a échoué\n";
        }
        fclose($handleSrc);
    }
    
    
    
    $b = fwrite($handleTg, "\r\n");
	
    fclose($handleTg);
}
print PHP_EOL.'Done'.PHP_EOL;
