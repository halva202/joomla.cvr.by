<?php 
include("../selfCode/library/calendar/func.php");

$arr = explode("-", $_POST['dateSelected']);
$yearSelected = $arr[0];
$monthSelected = $arr[1];
$daySelected = $arr[2];

// var_dump(result($yearSelected,$monthSelected,$daySelected));
$info = result($yearSelected,$monthSelected,$daySelected);
?>
<p> прием - <?= $yearSelected.$monthSelected.$daySelected ?></p>

	<p>Дата - <?= $info['date'] ?> </p>
	<p>Название праздника - <?= $info['title'] ?> </p>
	<p>Introduction - <?= $info['introduction'] ?> </p>
	<p>Описание - <?= $info['text'] ?> </p>
	
	
<?php 
/* function result($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['dayOfYear'] <= $nearest_holiday_static['dayOfYear'] ? $result = $nearest_holiday_dynamic : $result = $nearest_holiday_static;
	return $result;
}

function nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected){
	$count = 0;
	// $year = date("Y");
	$year = $yearSelected;
	while($count != 1){
		$Easter = Easter($year);
		$dayOfYear_Easter = $Easter['dayOfYear'];
		$dayOfYear_daySelected = date("z", mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected));
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
	}
	
	$nearest_holiday_dynamic = [
		'dayOfYear' => $dayOfYear_holiday,
		'date' => date("d-m-Y", $Easter['mktime']+$row['difference']*24*60*60),
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
	];
	
	return $nearest_holiday_dynamic;
}

function nearest_holiday_static($yearSelected,$monthSelected,$daySelected){
	$count = 0;
	// $year = date("Y");
	// $month = date("m");
	// $day = date("d");
	$year = $yearSelected;
	$month = $monthSelected;
	$day = $daySelected;
	
	$select = select($table = 'calendar_static', $conditions = 'month='.$month.' AND day>='.$day.' ORDER BY day ASC LIMIT 1');
	while($row = mysql_fetch_array($select)){
		$count = $count + 1;
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
			$day = $row['day'];
			$title = $row['title'];
			$introduction = $row['introduction'];
			$text = $row['text'];
		}
	}
	
	$dayOfYear_holiday = date("z", mktime(0, 0, 0, $month, $day, $year));
	
	$nearest_holiday_static = [
		'dayOfYear' => $dayOfYear_holiday,
		'date' => date("d-m-Y", mktime(0, 0, 0, $month, $day, $year)),
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
	$user = 'root';
	$pass = '';
	$database = "halva202_github_calendar";
	$db = mysql_connect($server, $user, $pass);
	if(!mysql_select_db($database)){
		echo 'bd is absent<br>';
		die(mysql_error());
	};
	mysql_set_charset( 'utf8' );

	$select = mysql_query('SELECT * FROM '.$table.' where '.$conditions);
	return $select;
} */
?>