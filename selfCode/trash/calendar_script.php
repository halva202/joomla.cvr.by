<?php 
include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/func.php");

$arr = explode("-", $_POST['dateSelected']);
$yearSelected = $arr[0];
$monthSelected = $arr[1];
$daySelected = $arr[2];

$info = result($yearSelected,$monthSelected,$daySelected);
?>

	<p class="selectedDate"> Выбранная дата: <?= $info['date2'] ?> </p>
	<p> За день до выбранной даты: <span class="prevDate" id="prevDate" onclick="prevDate()"><?= $info[0] ?></span></p>
	<!-- <p class="pictureDate"><img src="/media/k2/items/treatment/!test.jpg"></p> -->
	<p class="pictureDate"><img src="/media/k2/items/treatment/<?= $info['kind'] ?>/item_<?= $info['id'] ?>.jpg"></p>
	<p class="nextDate" id="nextDate"> Следующий день после выбранной даты: <?= $info[1] ?> </p>
	<p><?= $info['title'] ?> </p>
	<p><?= $info['text'] ?> </p>
