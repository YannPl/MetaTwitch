<?php
/**
 * GENERAL INFORMATIONS :
 * I know that use of globals should be avoided when possible, but here, the aim
 * is to have an efficient latency and to reduce the number of requests to the API because
 * each request is about 200-500ms. So once data is fetch, no need to do it again and
 * it should be accessible from everywhere in the script.
 */

/*******************************************************
 ******************* SETTINGS **************************
*******************************************************/
$LIST_STREAMS = array("the_kabal", "sucettalanis");
$MAIN_STREAM = "metatrone74";
$URL_IMG_OFFLINE = "/images/Metatrone/Guilde/offline60s.png";
$WIDTH = "100%"; $HEIGHT = "100%";
$AUTO_PLAY = "true";

/*******************************************************
 ******************* GLOBALS ***************************
*******************************************************/
$LIST_RESULT = array(); // Pas touche à ça Zae! ;)

/*******************************************************
 ******************* MAIN ALGORITHM ********************
*******************************************************/

$streamOnline = false;
// Here the mainStream is displayed if needed. Else, let's load the others!
if(displayMainStreamIfEnabled()){
	$streamOnline = true;
}

// Loop over all streams to display the first one which is online
foreach($LIST_STREAMS as $streamName){
	// If no stream displayed yet AND this one is online, display
	if(!$streamOnline && isOnline($streamName)){
		displayStream($streamName);
		$streamOnline = true;
	}
	// If one stream is already displayed and this one is online too
	// Display a link
	else if(isOnline($streamName)){
		echo '<br/><a href="http://www.twitch.tv/'.$streamName.'" title="'.$streamName.'" class="link_stream">'.$streamName.' est en ligne aussi!</a>';
	}
}

if(!$streamOnline){
	echo '<img src="'.$URL_IMG_OFFLINE.'" alt="Stream offline" title="Stream Offline"/>';
}


/*******************************************************
 ******************* UTILITIES FUNCTIONS ***************
*******************************************************/
/**
 * If the main stream is online, the player is displayed and true is returned.
 * If the main stream is offline, returns false.
 *
 * @return: <b>true</b> if the main stream is online, <b>false</b> if not.
 */
function displayMainStreamIfEnabled(){
	global $MAIN_STREAM;

	if(isOnline($MAIN_STREAM)){
		displayStream($MAIN_STREAM);
		return true;
	}
	else{
		return false;
	}
}

/**
 * Display the stream specified in parameter.
 * @param String $streamName : Name of the stream to display
 */
function displayStream($streamName){
	global $HEIGHT, $WIDTH, $AUTO_PLAY;
	$search = array('height="xxx"', 'width="xxx"', "auto_play=false");
	$replace = array('height="'.$HEIGHT.'"', 'width="'.$WIDTH.'"', "auto_play=$AUTO_PLAY");
	echo str_replace($search, $replace,	file_get_contents("http://api.justin.tv/api/channel/embed/$streamName?width=xxx&height=xxx"));
}

/**
 * Check stream's status and return true or false
 * @param String $streamName : Name of the stream
 * @return : true or false according to the stream status.
 */
function isOnline($streamName){
	global $LIST_RESULT, $MAIN_STREAM;

	if(!isset($LIST_RESULT[$streamName])){
		getStreamDetail($streamName);
	}
	return !empty($LIST_RESULT[$streamName]);
}

/**
 * Fetch channel informations and store it in the LIST_RESULT global array.
 * @param String $streamName
 */
function getStreamDetail($streamName){
	global $LIST_RESULT;
	$LIST_RESULT[$streamName] = json_decode(file_get_contents("http://api.justin.tv/api/stream/list.json?channel=$streamName", 0, null, null), true);
}
?>