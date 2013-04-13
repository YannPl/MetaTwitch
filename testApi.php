<?php
$user = "metatrone74";
// User
//	$jsonData = file_get_contents("http://api.justin.tv/api/user/show/$user.json", 0, null, null);
// Channel
$jsonData = file_get_contents("http://api.justin.tv/api/channel/show/$user.json", 0, null, null);

$dataArray = json_decode($jsonData, true);

if ($dataArray['producer'] == 'true') {
	echo '<b style="color: green">Live online!</b>';
}
else {
	echo '<b style="color: red">Live offline!</b>';
}
echo '<pre>';
print_r($dataArray);
echo '</pre>';
?>