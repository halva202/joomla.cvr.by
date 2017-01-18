<?php
defined('_JEXEC') or die;

include("/selfCode/library/calendar/func.php");
$yearSelected=date("Y");
$monthSelected=date("m");
$daySelected=date("d");
// var_dump(result($yearSelected,$monthSelected,$daySelected)); 
$info = result($yearSelected,$monthSelected,$daySelected);
?>


<input type="date" id="calendar" value="" autofocus/><br>
<!-- <div id="div_insert"></div> -->

<p>Ближайший праздник: </p>
<div id="nearestHoliday">
	<p>Дата - <?= $info['date'] ?> </p>
	<p>Название праздника - <?= $info['title'] ?> </p>
	<p>Introduction - <?= $info['introduction'] ?> </p>
	<p>Описание - <?= $info['text'] ?> </p>
</div>

<script>
var d = new Date();
var curr_day = d.getDate();
var curr_month = d.getMonth() + 1;
	if(curr_month < 10){curr_month = "0" + curr_month;}
var curr_year = d.getFullYear();

var res = curr_year + "-" + curr_month + "-" + curr_day;
document.getElementById('calendar').value = res;

function knowDate(){
	dateValue = document.getElementById("calendar").value;
	// arr = dateValue.split('-');
		// year = arr[0];
		// month = arr[1];
		// day = arr[2];
	// document.getElementById("div_insert").innerHTML = 'Выбрана дата: ' + dateValue;
	
	jQuery.ajax({
        type: "POST",
        url: "/selfCode/calendar.php",
        //data: "sid=<?=session_id()?>&data_1="+$('#data_1').val()+"&data_2="+$('#data_2').val(),
		// data: "year=" + year + "&month=" + month,
		data: "dateSelected=" + dateValue,
        success: function(response){
            jQuery('#nearestHoliday').html(response);
        }
    });

}
document.getElementById("calendar").addEventListener("change", knowDate);
</script>


