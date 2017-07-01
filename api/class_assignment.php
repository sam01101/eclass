<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class Login/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token')

$ch = curl_init('http://eclass.chonghwakl.edu.my/eclass40/src/index_edit_ajax.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".@$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');

if(isset($_GET['redo']) && $_GET['redo'] == "true"){
	curl_setopt($ch, CURLOPT_POSTFIELDS,
            "ACTION=get_assessment_redo_list");
}else{
	curl_setopt($ch, CURLOPT_POSTFIELDS,
            "ACTION=get_assessment_list");
}


$result = curl_exec($ch);
$regex = preg_match_all('/assessment_id=(.*)"(?:.*)progress">(.*)<\/a>(?:.*)alert_deadline">(.*)<\/span>(?:.*)<td>(.*)<\/td>/U', $result, $data);

if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);
foreach($data[1] as $key => $id)
{
	$assignments[$key]['id'] = $id;
    $assignments[$key]['title'] = $data[2][$key];
    $assignments[$key]['date'] = $data[3][$key];
    $assignments[$key]['status'] = $data[4][$key];
}
$JSON -> count = count($assignments);
$JSON -> assignments = $assignments;
//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;


/*
if(count($data[1]) == 0) {
	JSON('err','Invalid Token!');
	exit();
}

$JSON = (object)array();
//$JSON = array_merge($data[1],$data[2],$data[3]);
foreach($data[1] as $key => $id)
{
	$assignments[$key]['id'] = $id;
    $assignments[$key]['title'] = $data[2][$key];
    $assignments[$key]['date'] = $data[3][$key];
    $assignments[$key]['status'] = $data[4][$key];
}
$JSON -> count = count($assignments);
$JSON -> assignments = $assignments;
//$JSON -> id = $data[2];
//$JSON -> title = $data[3];

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;







function JSON($key, $msg){
	$JSON = (object)array();
	$JSON -> $key = $msg;
	$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
	header("Content-type: application/json; charset=utf-8");
	echo $JSON;
}

*/
?>