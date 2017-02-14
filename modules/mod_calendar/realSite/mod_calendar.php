<?php
defined('_JEXEC') or die;

// include("/selfCode/library/calendar/func.php");
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
	<div><?= $info['date'] ?> </div>
	<div><?= $info['title'] ?> </div>
	<div><?= $info['introduction'] ?> </div>
	<div><?= $info['text'] ?> </div>
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

<?php 
// $yearSelected=date("Y");
// $monthSelected=date("m");
// $daySelected=date("d");

// function test(){
	// return 'test';
// }

function result($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] <= $nearest_holiday_static['mktime'] ? $result = $nearest_holiday_dynamic : $result = $nearest_holiday_static;
	return $result;
}

function nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected){
	$count = 0;
	$year = $yearSelected;
	$month = $monthSelected;
	$day = $daySelected;
	while($count == 0){
		$Easter = Easter($year);
		$dayOfYear_Easter = $Easter['dayOfYear'];
		$dayOfYear_daySelected = date("z", mktime(0, 0, 0, $month, $day, $year));
		$difference_Easter_daySelected = $dayOfYear_daySelected - $dayOfYear_Easter;
		$select = select($table = 'calendar_dynamic', $conditions = 'difference >= '.$difference_Easter_daySelected.' ORDER BY difference ASC LIMIT 1');
		
		while($row = mysql_fetch_array($select)){
			$count = $count + 1;
			$difference_holiday_Easter = $row['difference'];
			$title = $row['title'];
			$introduction = $row['introduction'];
			$text = $row['text'];
			$dayOfYear_holiday = $dayOfYear_Easter + $difference_holiday_Easter;
		}
		
		$year = $year + 1;
		$month = 1;
		$day = 1;
	}
	
	$nearest_holiday_dynamic = [
		'mktime' => $Easter['mktime']+$difference_holiday_Easter*24*60*60-24*60*60,
		'date' => date("d-m-Y", $Easter['mktime']+$difference_holiday_Easter*24*60*60),
		'datetime' => date("d-m-Y H:i:s", $Easter['mktime']+$difference_holiday_Easter*24*60*60),
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
		'difference_holiday_Easter' => $difference_holiday_Easter,
	];
	
	return $nearest_holiday_dynamic;
}

function nearest_holiday_static($yearSelected,$monthSelected,$daySelected){
	$count = 0;
	$year = $yearSelected;
	$month = $monthSelected;
	$day = $daySelected;
	
	$select = select($table = 'calendar_static', $conditions = 'month='.$month.' AND day>='.$day.' ORDER BY day ASC LIMIT 1');
	while($row = mysql_fetch_array($select)){
		$count = $count + 1;
		$month = $row['month'];
		$day = $row['day'];
		$title = $row['title'];
		$introduction = $row['introduction'];
		$text = $row['text'];
	}
	
	while($count == 0){
		$month = $month + 1;
		if($month == 13){
			$month = 1;
			$year = $year+1;
		}
		$select = select($table = 'calendar_static', $conditions = 'month='.$month.' ORDER BY day ASC LIMIT 1' );
		while($row = mysql_fetch_array($select)) {
			$count = $count + 1;
			$month = $row['month'];
			$day = $row['day'];
			$title = $row['title'];
			$introduction = $row['introduction'];
			$text = $row['text'];
		}
	}
	
	$dayOfYear_holiday = date("z", mktime(0, 0, 0, $month, $day, $year));
	
	$nearest_holiday_static = [
		'mktime' => mktime(0, 0, 0, $month, $day, $year),
		'date' => date("d-m-Y", mktime(0, 0, 0, $month, $day, $year)),
		'datetime' => date("d-m-Y H:i:s", mktime(0, 0, 0, $month, $day, $year)),
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
	];
	
	return $nearest_holiday_static;
}

function Easter($year){
	$a = $year % 19;
	$b = $year % 4;
	$c = $year % 7;
	$d = (19*$a + 15) % 30;
	$e = (2*$b + 4*$c + 6*$d + 6) % 7;

	if($d + $e < 10){
		$dEaster = 22 + $d + $e + 13; $mEaster = 3; $mEaster_spec = '03';
		if ($dEaster > 31){
			$dEaster = $dEaster - 31; $mEaster = 4; $mEaster_spec = '04';
		}
	}
	else{
		$dEaster = $d + $e - 9 + 13; $mEaster = 4; $mEaster_spec = '04';
		if ($dEaster > 30){
			$dEaster = $dEaster - 30; $mEaster = 5; $mEaster_spec = '05';
		}
	}
	
	$Easter = [
		'dayOfYear' => date("z", mktime(0, 0, 0, $mEaster, $dEaster, $year)),
		'date' => date("$year-$mEaster_spec-$dEaster"),
		'mktime' => mktime(0, 0, 0, $mEaster, $dEaster, $year),
	];
	
	return $Easter;
}

function select($table, $conditions){
	header( 'Content-Type: text/html; charset=utf-8' );
	$server = "localhost";
	// $user = 'root';
	// $pass = '';
	// $database = "halva202_github_calendar";
	$user = 'iconmast_calend';
	$pass = 'Nsm%)+z2IV@5';
	$database = "iconmast_calendar";
	
	$db = mysql_connect($server, $user, $pass);
	if(!mysql_select_db($database)){
		echo 'bd is absent<br>';
		die(mysql_error());
	};
	mysql_set_charset( 'utf8' );

	$select = mysql_query('SELECT * FROM '.$table.' where '.$conditions);
	return $select;
}
?>




