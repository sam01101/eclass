<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Class_List/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/index20_aj_eclass.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);
//$result = preg_replace('/(\>)\s*(\<)/m', '$1$2', $result);
//$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//$result = str_replace("\n", '', $result);
//var_dump($result);
//preg_match_all('/indextabclasslist">(.*)<\/a>/U', $result, $class_list);
//preg_match_all("/fe_eclass\('(.*)'\)/U", $result, $class_id);

//$regex = preg_match_all('/fe_eclass\(\'(.*)\'\)(?:.*)indextabclasslist">(.*)<\/a>(?:.*)icon_econtent.gif(?:.*)>(.*) \n<(?:.*)icon_assessment.gif(?:.*)>(.*) \n<(?:.*)icon_forum_off.gif(?:.*)>(.*) \n<(?:.*)icon_annou_off.gif(?:.*)>(.*) \n</Us', $result, $data);
$regex = preg_match_all('/fe_eclass\(\'(.*)\'\)(?:.*)indextabclasslist">(.*)<\/a>/Us', $result, $data);

//var_dump($data);

if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();
foreach($data[1] as $key => $id)
{
	$class[$key]['id'] = $id;
    $class[$key]['name'] = htmlspecialchars_decode($data[2][$key]);
    //$class[$key]['c'] = $data[3][$key];
    //$class[$key]['w'] = $data[4][$key];
    //$class[$key]['b'] = $data[5][$key];
    //class[$key]['a'] = $data[6][$key];
}
$JSON -> count = count($class);
$JSON -> classroom = $class;
$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;


?>
