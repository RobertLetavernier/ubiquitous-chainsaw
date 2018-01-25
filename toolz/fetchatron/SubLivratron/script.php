<?php
// fetchatron.php

$source = '_sources.txt';
$target = '_out';
$syssep = '\\'; // system separator

print PHP_EOL.'source : '.$source;
print PHP_EOL.'target : '.$target;
print PHP_EOL.'-------------------';
print PHP_EOL;

function r_copy($src,$dst) {
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                r_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

$handle = fopen($source, "r");
$line = 0;
if ($handle) {
    while (($buffer = stream_get_line($handle, 4096, "\r\n")) !== false) {
        echo PHP_EOL . '[' . (++$line) . '] ' . $buffer;
        $path = $target . $syssep . str_replace(':', '', $buffer);
        @mkdir(pathinfo($path, PATHINFO_DIRNAME), '0777', true);
        if (file_exists($buffer)) {
            if (is_file($buffer)) {
                copy($buffer, $path);
            } elseif (is_dir($buffer)) {
                r_copy($buffer,$path);
            }
        } elseif (substr($path, -1) == $syssep) {
            mkdir($path);
        }
    }    
    fclose($handle);
}
print PHP_EOL.'Done'.PHP_EOL;
