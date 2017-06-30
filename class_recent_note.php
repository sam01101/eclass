<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class Login/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token');

$ch = curl_init('http://eclass.chonghwakl.edu.my/eclass40/src/dialog_ajax.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "new_type=get_history_lesson_and_notes&access_assessment=1");


$result = curl_exec($ch);

if(empty($result)) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

//$result = trim($result); // remove spaces at the ends //
//$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//$result = preg_replace('/\s?,\s?/', ', ', $result); // enforce the 'word, word' format //
$result = preg_replace('/(\>)\s*(\<)/m', '$1$2', $result);

$regex = preg_match_all('/date_time">(.*)日(?:.*)nid=(.*)">(.*)<\/a><\/td>/U', $result, $data);



$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);
foreach($data[2] as $key => $id)
{
	$notes[$key]['id'] = $id;
    $notes[$key]['day'] = $data[1][$key];
    $notes[$key]['title'] = $data[3][$key];
}
$JSON -> count = count($notes);
$JSON -> notes = $notes;
//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;



/*
//var_dump($result);
if(count($data[1]) == 0) {
	JSON('err','Invalid Token!');
	exit();
}
//var_dump($day);

//var_dump($data[1]);
$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);
foreach($data[1] as $key => $day)
{
    $notes[$key]['day'] = $day;
    $notes[$key]['id'] = $data[2][$key];
    $notes[$key]['title'] = $data[3][$key];
}

$JSON -> count = count($notes);
$JSON -> notes = $notes;
//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;

//var_dump($note);




function JSON($key, $msg){
	$JSON = (object)array();
	$JSON -> $key = $msg;
	$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
	header("Content-type: application/json; charset=utf-8");
	echo $JSON;
}

*/
?>