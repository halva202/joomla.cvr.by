<?php 
$result = result($yearSelected,$monthSelected,$daySelected); 
$info = $result['nearestHoliday'];
$nearestHoliday = $result['nearestHoliday'];

$nearestHolidays = $result['nearestHolidays'];
$datePrev = $nearestHolidays['prev1']['dateformat1'];
$dateNext = $nearestHolidays['next1']['dateformat1'];
?>


<div class="selectedDate"> <font color="brown">Дата ближайшего праздника:</font> <?= $nearestHoliday['dateformat2'] ?> </div>
<br>

<div> <font color="brown">Предыдущий праздник:</font> <span class="prevDate" id="prevDate" onclick="prevDate()"><?= $datePrev ?></span></div>
<input type="hidden" id="prevDate_hidden" value="<?= $info[0] ?>">

<!-- <div class="pictureDate"><img src="/media/k2/items/treatment/!test.jpg"></div> -->
<div class="pictureDate"><img src="/media/k2/items/treatment/<?= $nearestHoliday['kind'] ?>/item_<?= $nearestHoliday['id'] ?>.jpg"></div>

<div> <font color="brown">Следующий праздник:</font> <span class="nextDate" id="nextDate" onclick="nextDate()"><?= $dateNext ?></span></div>
<input type="hidden" id="nextDate_hidden" value="<?= $info[1] ?>">

<br>
<div><?= $nearestHoliday['title'] ?> </div>

<div><?= $nearestHoliday['text'] ?> </div>



<h3> Ближайшие праздники </h3> 

<?php $nearest_holidays = $result['nearestHolidays'];$n=1; ?>


<?php foreach($nearest_holidays as $nearest_holiday): ?>
	<?php $n ==3 ? $color = 'red' : $color = 'black'; ?>
	<div>
		<font color="brown"><?= $n ?></font>
		<font color="<?= $color ?>"><?= $nearest_holiday['title'] ?></font> (<?= $nearest_holiday['dateformat3'] ?>)
	</div>
	<div class="pictureDate_small"><img src="/media/k2/items/treatment/<?= $nearest_holiday['kind'] ?>/item_<?= $nearest_holiday['id'] ?>.jpg"></div>
	<div><?= $nearest_holiday['text'] ?> </div>
	<?php $n = $n + 1; ?>
<?php endforeach; ?>