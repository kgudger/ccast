<?php
/** 
* @file ccastpage.php
* Purpose: Logged in users can update their shows
* Extends MainPage Class
*
* @author Keith Gudger
* @copyright  (c) 2022, Keith Gudger, all rights reserved
* @license    MIT
* @version    Release: 0.1
* @package    ccast
*
* @note Has processData and showContent, 
* main and checkForm in MainPage class not overwritten.
* 
*/

require_once("mainpage.php");
include_once __DIR__ . '/api_key.php'; // for ccast info

$slots = array("Tuesday 4/19/22 7:30 PM" => "",
	"Tuesday 4/26/22 7:30 PM" => "",
	"Tuesday 5/3/22 7:30 PM" => "",
	"Tuesday 5/10/22 7:30 PM" => "",
	"Tuesday 5/17/22 7:30 PM" => "",
	"Tuesday 5/24/22 7:30 PM" => "",
	"Tuesday 5/31/22 7:30 PM" => "");
$media = array("Episode 1", "Episode 2", "Episode 3", "Episode 4", "Episode 5");

/**
 * Child class of MainPage used for user preferrences page.
 *
 * Implements processData and showContent
 */

class ccastPage extends MainPage {

/**
 * Process the data and insert / modify database.
 *
 * @param $uid is user id passed by reference.
 */
function processData(&$uid) {
	    // Process the verified data here.
	global $slots;
	global $media;
	$selectList = $this->formL->getValues("Slots", "");
	$checked = $this->formL->getValue("Media", "");
	$i = 0;
	foreach($slots as $slot=>$medium) {
	    if (in_array($i, $selectList)) {
		    $slots[$slot] = $media[intval($checked)-1];
	    }
	    $i++;
	}
}

/**
 * Display the content of the page.
 *
 * @param $title is page title.
 * @param $uid is user id passed by reference.
 */
function showContent($title, &$uid) {
	global $slots;
	global $media;
	$retpage = "";
	$retpage .= <<<EOT
<div id='cc_main'>
EOT;

    $API_key = get_API_key();
    $URL_key = get_URL_key();
    $current_user = wp_get_current_user();
    $retpage .= <<<EOT
<div id="cheader"><h3>Welcome $current_user->user_login
<span style="float:right"> <button type="button" onclick="uploadMedia()">Upload Media</button>
</h3></span></div>
<div id="instruct">Choose one media file (left column) and as many slots as you like
 (right column) and then click "Submit" to schedule your media.</div>
<form method="POST" action="" name="mediasched",enctype="multipart/form-data" id="mediaschedule">
<table id="media_table">
<tr><th>Slots - Assigned Media</th><th>Media</th></tr>
EOT;
/* This table has the upcoming slots as select boxes and
 * media as a radio button list. This way the user can select
 * one media to assign to multiple slots
 */
    $retpage .= "<tr><td>";
    $i = 0; // index into slots array
    foreach ($slots as $label=>$value) {
        $retpage .= "<input type=\"checkbox\" name=\"Slots[]\" value=\"$i\"";
        if ($selectList AND in_array($value, $selectList)) {
            $retpage .= " checked";
        }
        $retpage .= "> $label - $value<br>";
	$i++ ;
    }
    $retpage .= "</td><td>";
    $retpage .= $this->formL->makeRadioGroup("Media",
	    array("Episode 1"=>"1", "Episode 2"=>"2", "Episode 3"=>"3"));
    $retpage .= "</td></tr>";
/* the script below is to handle the "Upload Media" button
 */
    $retpage .= <<<EOT
</table>
<br>
<input class="subbutton" type="Submit" name="Submit", value="Submit", id="submedia">
<br><br>
<script>
	function uploadMedia() {
	  let text;
	  let media  = prompt("Please enter the drive file for your media:", "");
	  if (media == null || media == "") {
	    text = "No media entered.";
	  } else {
	    text = media + " will be uploaded soon";
	  }
	  alert(text);
	}
</script>
EOT;

    $retpage .= $this->formL->finish(); // closes form
//    $current_user->ID;
    $retpage .= "</div>";
    return $retpage;
}
}
?>
