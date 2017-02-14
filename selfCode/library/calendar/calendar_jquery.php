<?php 
include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/func.php");

$arr = explode("-", $_POST['dateSelected']);
$yearSelected = $arr[0];
$monthSelected = $arr[1];
$daySelected = $arr[2];

include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/calendar_forInsert.php");
?>