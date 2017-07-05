<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////Announcement//////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token','type');

if($_GET['type'] == "public") $type = 0;
else if($_GET['type'] == "group") $type = 1;
else exit();

if(isset($_GET['year']) && is_int($_GET['year'])){
	$year = $_GET['year'];
}else{
	$year = date("Y");
}

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/moreannouncement.php?type='. $type);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
$result = curl_exec($ch);


$regex = preg_match_all('/\'tabletext\'>(.*) (?:.*)tabletext\'>(.*)<a(?:.*)fe_view_announcement\((.*)\)(?:.*)indexnewslist \'>(.*) <\/a>(?:.*)tabletext\'>(.*)<\/td>/U', $result, $data);

if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();
foreach($data[1] as $key => $date)
{
	$annyear = substr($date, 0, 4);
	if($annyear != $year) break;
	$attach = false;
	if($data[2][$key] != "&nbsp;") $attach = true;
	$announcements[$key]['date'] = $date;
    $announcements[$key]['attachment'] = $attach;
    $announcements[$key]['id'] = $data[3][$key];
    $announcements[$key]['title'] = $data[4][$key];
    $announcements[$key]['by'] = str_replace('<br>', '@', htmlspecialchars_decode($data[5][$key]));
}
$JSON -> count = count($announcements);
$JSON -> announcements = $announcements;
$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;


/*
preg_match_all("/(\d{4})-(\d{2})-(\d{2})/U", $result, $date_list);
preg_match_all("/announcements='indexnewslist '>(.*)<\/a>/U", $result, $news_list);
preg_match_all("/<\/td><td width='30%' valign='top' nowrap bgcolor='#FFFFFF' announcements='tabletext'>(.*)<\/td>/U", $result, $post_by);
preg_match_all("/abletext'>(.*)<a href='javascript/U", $result, $is_attachment);
preg_match_all("/fe_view_announcement\((.*)\)/U", $result, $news_id);

$date_list_c = count($date_list[0]);

if(count($date_list[0]) == 0) {
	JSON('err','Invalid Token!');
	exit();
}

$news = array(); 

//echo "= Group News: ".$date_list_c ." =\n";
for($i=0;$i<$date_list_c;$i++){
	$attach = null;
	if(substr($is_attachment[1][$i],-6) !== "&nbsp;") $attach = "📎"; 
	$news[$i]['id'] = $news_id[1][$i];
	$news[$i]['date'] = $date_list[0][$i];
	$news[$i]['content'] = $attach. $news_list[1][$i];
	$news[$i]['by'] = html_entity_decode ($post_by[1][$i]);
    //echo $news_id[1][$i]. " ". $date_list[0][$i] . " " .$attach. $news_list[1][$i] . " =By= " . html_entity_decode ($post_by[1][$i]) . "\n";
}

$JSON = (object)array();
$JSON -> count = $date_list_c;
$JSON -> announcements = $news;

$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;

//var_dump($is_attachment);
//var_dump($announcements_id);


function JSON($key, $msg){
	$JSON = (object)array();
	$JSON -> $key = $msg;
	$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
	header("Content-type: application/json; charset=utf-8");
	echo $JSON;
}

*/
?>
