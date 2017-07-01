<?php
include('functions.php');
getCheck('token','file');

$file = 'http://eclass.chonghwakl.edu.my/home/download_attachment.php?target_e='.$_GET['file'];
download($file);


function HandleHeaderLine( $curl, $header_line ) {
    header($header_line); // or do whatever
    return strlen($header_line);
}


function download($url) {
    set_time_limit(0);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, "HandleHeaderLine");
    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".@$_GET['token']);
    $r = curl_exec($ch);
    curl_close($ch);
    //echo $r;
    //preg_match('/^Location: (.*)$/mi', $result, $location);
//var_dump($r);
/*
    header('Expires: 0'); // no cache
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
    header('Cache-Control: private', false);
    header('Content-Type: application/force-download');
    header('Content-Disposition: attachment; filename="' . basename($url) . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($r)); // provide file size
    header('Connection: close');
    echo $r;
*/
}
?>