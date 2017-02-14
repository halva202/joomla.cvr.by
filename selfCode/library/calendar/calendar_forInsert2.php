<?php 
$result = result($yearSelected,$monthSelected,$daySelected); 
$info = $result['nearestHoliday'];
$nearestHoliday = $result['nearestHoliday'];
?>


<div class="selectedDate"> Дата ближайшего праздника: <?= $nearestHoliday['dateformat2'] ?> </div>

<div> За день до выбранной даты: <span class="prevDate" id="prevDate" onclick="prevDate()"><?= $info[2] ?></span></div>
<input type="hidden" id="prevDate_hidden" value="<?= $info[0] ?>">

<!-- <div class="pictureDate"><img src="/media/k2/items/treatment/!test.jpg"></div> -->
<div class="pictureDate"><img src="/media/k2/items/treatment/<?= $nearestHoliday['kind'] ?>/item_<?= $nearestHoliday['id'] ?>.jpg"></div>

<div> Следующий день после выбранной даты: <span class="nextDate" id="nextDate" onclick="nextDate()"><?= $info[3] ?></span></div>
<input type="hidden" id="nextDate_hidden" value="<?= $info[1] ?>">

<div><?= $nearestHoliday['title'] ?> </div>

<div><?= $nearestHoliday['text'] ?> </div>



<h3> Ближайшие праздники </h3> 

<?php $nearest_holidays = $info['nearestHolidays']; ?>

<?php foreach($nearest_holidays as $nearest_holiday): ?>
	<div><?= $nearest_holiday['title'] ?> (<?= $nearest_holiday['date3'] ?>)</div>
	<div class="pictureDate_small"><img src="/media/k2/items/treatment/<?= $nearest_holiday['kind'] ?>/item_<?= $nearest_holiday['id'] ?>.jpg"></div>
	<div><?= $nearest_holiday['text'] ?> </div>
<?php endforeach; ?>


<div><?= $info['title'] ?> (<?= $info['date3'] ?>)</div>
<div class="pictureDate_small"><img src="/media/k2/items/treatment/<?= $info['kind'] ?>/item_<?= $info['id'] ?>.jpg"></div>
<div><?= $info['text'] ?> </div>