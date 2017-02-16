<?php 
$result = result($yearSelected,$monthSelected,$daySelected); 
$info = $result['nearestHoliday'];
$nearestHoliday = $result['nearestHoliday'];

$nearestHolidays = $result['nearestHolidays'];
$datePrev = $nearestHolidays['prev1']['dateformat1'];
$datePrevHidden = $nearestHolidays['prev1']['date2'];
$dateNext = $nearestHolidays['next1']['dateformat1'];
$dateNextHidden = $nearestHolidays['next1']['date2'];
?>


<div class="selectedDate"> <font color="brown">Дата ближайшего праздника:</font> <?= $nearestHoliday['dateformat2'] ?> </div>
<br>

<div> <font color="brown">Предыдущий праздник:</font> <span class="prevDate" id="prevDate" onclick="prevDate()"><?= $datePrev ?></span></div>
<input type="hidden" id="prevDate_hidden" value="<?= $datePrevHidden ?>">

<!-- <div class="pictureDate"><img src="/media/k2/items/treatment/!test.jpg"></div> -->
<div class="pictureDate"><img src="/media/k2/items/treatment/<?= $nearestHoliday['kind'] ?>/item_<?= $nearestHoliday['id'] ?>.jpg"></div>

<div> <font color="brown">Следующий праздник:</font> <span class="nextDate" id="nextDate" onclick="nextDate()"><?= $dateNext ?></span></div>
<input type="hidden" id="nextDate_hidden" value="<?= $dateNextHidden ?>">

<br>
<div><?= $nearestHoliday['title'] ?> </div>

<div><?= $nearestHoliday['text'] ?> </div>



<h3> Ближайшие праздники </h3> 

<?php 
$nearest_holidays = $result['nearestHolidays'];
$n=1; 
$u =&JFactory::getURI(); 
?>

<?php foreach($nearest_holidays as $nearest_holiday): ?>
	<?php $n ==3 ? $color = 'red' : $color = 'black'; ?>
	
	<div>
		<font color="brown"><?= $n ?></font>
		<a href="<?= $u ?>?year=<?= $nearest_holiday['year']?>&month=<?= $nearest_holiday['month'] ?>&day=<?= $nearest_holiday['day'] ?>"><font color="<?= $color ?>"><?= $nearest_holiday['title'] ?></font></a> (<?= $nearest_holiday['dateformat3'] ?>)
	</div>
	
	<div class="pictureDate_small"><img src="/media/k2/items/treatment/<?= $nearest_holiday['kind'] ?>/item_<?= $nearest_holiday['id'] ?>.jpg"></div>
	
	<div><?= substr($nearest_holiday['text'], 0, strrpos(substr($nearest_holiday['text'], 0,300), ' ')).' ...' ?> </div>
	
	<?php $n = $n + 1; ?>
<?php endforeach; ?>