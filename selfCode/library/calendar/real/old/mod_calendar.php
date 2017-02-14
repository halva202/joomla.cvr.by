<?php
defined('_JEXEC') or die;

// include("/selfCode/library/calendar/func.php");
include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/func.php");
$yearSelected=date("Y");
$monthSelected=date("m");
$daySelected=date("d"); 
$info = result($yearSelected,$monthSelected,$daySelected);
?>

<h1> Православный календарь </h1>

<input type="date" id="calendar" value="" autofocus/><br>

<p>Ближайший праздник: </p>
<div id="nearestHoliday">
	<p class="selectedDate"> Выбранная дата: <?= $info['date2'] ?> </p>
	<p class="prevDate"> За день до выбранной даты: <?= $info[0] ?> </p>
	<p class="pictureDate"><img src="/media/k2/items/treatment/<?= $info['kind'] ?>/item_<?= $info['id'] ?>.jpg"></p>
	<p class="nextDate"> Следующий день после выбранной даты: <?= $info[1] ?> </p>
	<p><?= $info['title'] ?> </p>
	<p><?= $info['text'] ?> </p>
</div>

<script>
var d = new Date();
var curr_day = d.getDate();
	if(curr_day < 10){curr_day = "0" + curr_day;}
var curr_month = d.getMonth() + 1;
	if(curr_month < 10){curr_month = "0" + curr_month;}
var curr_year = d.getFullYear();

var res = curr_year + "-" + curr_month + "-" + curr_day;
document.getElementById('calendar').value = res;

function knowDate(){
	dateValue = document.getElementById("calendar").value;
	
	jQuery.ajax({
        type: "POST",
        url: "/selfCode/calendar.php",
		data: "dateSelected=" + dateValue,
        success: function(response){
            jQuery('#nearestHoliday').html(response);
        }
    });

}
document.getElementById("calendar").addEventListener("change", knowDate);
</script>

