<?php
$jsonData = file_get_contents("http://api.justin.tv/api/stream/list.json?channel=the_kabal", 0, null, null);
$dataArray = json_decode($jsonData, true);

if ($dataArray[0]['name'] == 'live_user_the_kabal') {
	echo 'Live online!';
}
else {
	echo 'Live offline!';
}
echo '<pre>';
print_r($jsonData);
echo '</pre>';
?>