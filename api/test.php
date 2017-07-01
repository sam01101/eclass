<?php
$testb = array(); 
$testb[] = "hello";
$testb[] = "hello1";
$testb[] = "hello2";
echo json_encode($testb);

?>
<?php
$compressed = gzcompress('Compress me', 9);
echo $compressed;
echo html_entity_decode("&#x2714;");
?>