<?php
defined('_JEXEC') or die;

include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/func.php");

$yearSelected=date("Y");
$monthSelected=date("m");
$daySelected=date("d"); 
?>

<h1> Православный календарь </h1>
<input type="date" id="calendar" value="<?= $yearSelected ?>-<?= $monthSelected ?>-<?= $daySelected ?>"  autofocus/><br>


<div id="nearestHoliday">
	<?php include($_SERVER['DOCUMENT_ROOT']."/selfCode/library/calendar/calendar_forInsert.php"); ?>
</div>

<script>

// для отображения текущей даты в календаре
var d = new Date();
var curr_day = d.getDate();
	if(curr_day < 10){curr_day = "0" + curr_day;}
var curr_month = d.getMonth() + 1;
	if(curr_month < 10){curr_month = "0" + curr_month;}
var curr_year = d.getFullYear();
var res = curr_year + "-" + curr_month + "-" + curr_day;
document.getElementById('calendar').value = res;
// /для отображения текущей даты в календаре


function prevDate(){
	// var res = '2017-04-03';
	// var res = document.getElementById('prevDate').innerHTML;
	var res = jQuery("#prevDate_hidden").val();
	arr = res.split('.');
	console.log(arr);
	document.getElementById('calendar').value = arr[2] + "-" + arr[1] + "-" + arr[0];
	nearestHoliday();
}

function nextDate(){
	// var res = document.getElementById('nextDate').innerHTML;
	var res = jQuery("#nextDate_hidden").val();
	arr = res.split('.');
	document.getElementById('calendar').value = arr[2] + "-" + arr[1] + "-" + arr[0];
	nearestHoliday();
}

function nearestHoliday(){
	dateValue = document.getElementById("calendar").value;
	
	jQuery.ajax({
        type: "POST",
        url: "/selfCode/library/calendar/calendar_jquery.php",
		data: "dateSelected=" + dateValue,
        success: function(response){
            jQuery('#nearestHoliday').html(response);
        }
    });

}

document.getElementById("calendar").addEventListener("change", nearestHoliday);

</script>