<?php 
include("../selfCode/library/calendar/func.php");

$arr = explode("-", $_POST['dateSelected']);
$yearSelected = $arr[0];
$monthSelected = $arr[1];
$daySelected = $arr[2];

$info = result($yearSelected,$monthSelected,$daySelected);
?>

<div><?= $info['date'] ?> </div>
<div><?= $info['title'] ?> </div>
<div><?= $info['introduction'] ?> </div>
<div><?= $info['text'] ?> </div>