<?php

///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////imail_List/////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
include('functions.php');

getCheck('token');

$ch = curl_init('http://eclass.chonghwakl.edu.my/home/imail/viewfolder.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIE, "PHPSESSID=".$_GET['token']);
curl_setopt($ch,CURLOPT_USERAGENT,'CHKL');
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "pageNo=1");

$result = curl_exec($ch);

$result = preg_replace('/\s+/', ' ', $result); // make sure there aren't multiple spaces //
//var_dump($result);
//preg_match_all('/indextabimaillist">(.*)<\/a>/U', $result, $imail_list);
//preg_match_all("/fe_eimail\('(.*)'\)/U", $result, $imail_id);

$regex = preg_match_all('/iMailsender(.*)\' >(.*)<\/span>(?:.*)CampusMailID=(.*)&(?:.*)\' >(.*)<\/a>(?:.*)">(.*)</U', $result, $data);



if(!$regex) {
	$json = new JSON();
	$json->alert('err', 'Invalid Token!');
}

$JSON = (object)array();
foreach($data[1] as $key => $status)
{
	$status = empty($status) ? 'read' : 'unread';
	$imail[$key]['id'] = $data[3][$key];
	$imail[$key]['status'] = $status;
    $imail[$key]['name'] = strip_tags($data[2][$key]);
    $imail[$key]['title'] = str_replace(" <img src='/images/2009a/iMail/icon_attachment.gif' border='0' align='absmiddle' alt='附件' >",'📎',html_entity_decode($data[4][$key]));
    $imail[$key]['date'] = $data[5][$key];
}

var_dump($imail);

$JSON -> count = count($imail);
$JSON -> imail = $imail;
$JSON = json_encode($JSON, JSON_UNESCAPED_UNICODE);
header("Content-type: application/json; charset=utf-8");
echo $JSON;
var_dump($JSON);

?>