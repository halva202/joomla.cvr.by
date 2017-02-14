<?php 
// include("../selfCode/library/calendar/func.php");

$arr = explode("-", $_POST['dateSelected']);
$yearSelected = $arr[0];
$monthSelected = $arr[1];
$daySelected = $arr[2];

$info = result($yearSelected,$monthSelected,$daySelected);
?>

	<p class="selectedDate"> Выбранная дата: <?= $info['date2'] ?> </p>
	<p class="prevDate"> За день до выбранной даты: <?= $info[0] ?> </p>
	<p class="pictureDate"><img src="/media/k2/items/treatment/test.jpg"></p>
	<p class="nextDate"> Следующий день после выбранной даты: <?= $info[1] ?> </p>
	<p><?= $info['title'] ?> </p>
	<p><?= $info['text'] ?> </p>
	
<?php 

function result($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] <= $nearest_holiday_static['mktime'] ? $result = $nearest_holiday_dynamic : $result = $nearest_holiday_static;
	$yesterday = yesterday($yearSelected,$monthSelected,$daySelected);
	$tomorrow = tomorrow($yearSelected,$monthSelected,$daySelected);
	array_push($result, $yesterday);
	array_push($result, $tomorrow);
	return $result;
}

function yesterday($yearSelected,$monthSelected,$daySelected){
	$dayOfYear_selected = date("z", mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected));
	$yesterday = date('d.m.Y', strtotime( date("$yearSelected-$monthSelected-$daySelected") . "- 1 day" ));
	return $yesterday;
}
function tomorrow($yearSelected,$monthSelected,$daySelected){
	$dayOfYear_selected = date("z", mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected));
	$tomorrow = date('d.m.Y', strtotime( date("$yearSelected-$monthSelected-$daySelected") . "+ 1 day" ));
	return $tomorrow;
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
		'date2' => dateformat($mktime = $Easter['mktime']+$difference_holiday_Easter*24*60*60),
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
		'date2' => dateformat($mktime = mktime(0, 0, 0, $month, $day, $year)),
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

function dateformat($mktime){
	$dayOfWeek = date("N", $mktime);
	$dayOfMonth = date("j", $mktime);
	$month = date("n", $mktime);
	$year = date("Y", $mktime);
	switch ($dayOfWeek) {
		case 1:
			$dayOfWeek_text = 'Понедельник';
			break;
		case 2:
			$dayOfWeek_text = 'Вторник';
			break;
		case 3:
			$dayOfWeek_text = 'Среда';
			break;
		case 4:
			$dayOfWeek_text = 'Четверг';
			break;
		case 5:
			$dayOfWeek_text = 'Пятница';
			break;
		case 6:
			$dayOfWeek_text = 'Суббота';
			break;
		case 7:
			$dayOfWeek_text = 'Воскресенье';
			break;
	}
	switch ($month) {
		case 1:
			$month_text = 'Января';
			break;
		case 2:
			$month_text = 'Февраля';
			break;
		case 3:
			$month_text = 'Марта';
			break;
		case 4:
			$month_text = 'Апреля';
			break;
		case 5:
			$month_text = 'Мая';
			break;
		case 6:
			$month_text = 'Июня';
			break;
		case 7:
			$month_text = 'Июля';
			break;
		case 8:
			$month_text = 'Августа';
			break;
		case 9:
			$month_text = 'Сентября';
			break;
		case 10:
			$month_text = 'Октября';
			break;
		case 11:
			$month_text = 'Ноября';
			break;
		case 12:
			$month_text = 'Декабря';
			break;
	}
	
	$dateformat = "$dayOfWeek_text, $dayOfMonth $month_text $year года";
	return $dateformat;
}
?>