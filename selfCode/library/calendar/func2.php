<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/selfCode/library/calendar/!bd.php');

/* function result($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] <= $nearest_holiday_static['mktime'] ? $result = $nearest_holiday_dynamic : $result = $nearest_holiday_static;
	$yesterday = yesterday($yearSelected,$monthSelected,$daySelected);
		$yesterday2 = yesterday2($yearSelected,$monthSelected,$daySelected);
	$tomorrow = tomorrow($yearSelected,$monthSelected,$daySelected);
		$tomorrow2 = tomorrow2($yearSelected,$monthSelected,$daySelected);
	array_push($result, $yesterday);
	array_push($result, $tomorrow);
	array_push($result, $yesterday2);
	array_push($result, $tomorrow2);
	return $result;
} */
function result($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] <= $nearest_holiday_static['mktime'] ? $nearestHoliday = $nearest_holiday_dynamic : $nearestHoliday = $nearest_holiday_static;
	
	$yesterday = yesterday($yearSelected,$monthSelected,$daySelected);
		$yesterday2 = yesterday2($yearSelected,$monthSelected,$daySelected);
	$tomorrow = tomorrow($yearSelected,$monthSelected,$daySelected);
		$tomorrow2 = tomorrow2($yearSelected,$monthSelected,$daySelected);
	array_push($nearestHoliday, $yesterday);
	array_push($nearestHoliday, $tomorrow);
	array_push($nearestHoliday, $yesterday2);
	array_push($nearestHoliday, $tomorrow2);
	
	$result = [
		'nearestHoliday' => $nearestHoliday,
		// 'nearestHoliday_beforeANDafter' => $nearestHoliday_beforeANDafter,
		// 'nearestHolidays' => $nearestHolidays,
	];
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
			// $id = $row['id'];
			$id = 999;
			$title = $row['title'];
			$introduction = $row['introduction'];
			$text = $row['text'];
			$dayOfYear_holiday = $dayOfYear_Easter + $difference_holiday_Easter;
		}
		
		$year = $year + 1;
		$month = 1;
		$day = 1;
	}
	
	$mktime = $Easter['mktime']+$difference_holiday_Easter*24*60*60;
	
	$array = [
		'id' => $id,
		'kind' => 'dynamic',
		'mktime' => $mktime,
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
	];
	$nearest_holiday_dynamic = holiday_info($array);
	/* $nearest_holiday_dynamic = [
		'id' => $id,
		'kind' => 'dynamic',
		'mktime' => $mktime,
		'date' => date("d-m-Y", $mktime,
		'dateformat2' => dateformat2($mktime), 
		'date3' => dateformat3($mktime,
		'datetime' => date("d-m-Y H:i:s", $mktime,
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
		'difference_holiday_Easter' => $difference_holiday_Easter,
	]; */
	
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
		$id = $row['id'];
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
			$id = $row['id'];
			$month = $row['month'];
			$day = $row['day'];
			$title = $row['title'];
			$introduction = $row['introduction'];
			$text = $row['text'];
		}
	}
	
	$mktime = mktime(0, 0, 0, $month, $day, $year);
	$dayOfYear_holiday = date("z", $mktime);
	
	$array = [
		'id' => $id,
		'kind' => 'static',
		'mktime' => $mktime,
		// 'date' => date("d-m-Y", $mktime),
		// 'dateformat2' => dateformat2($mktime),
		// 'date3' => dateformat3($mktime),
		// 'datetime' => date("d-m-Y H:i:s", $mktime),
		'title' => $title,
		'introduction' => $introduction,
		'text' => $text,
	];
	$nearest_holiday_static = holiday_info($array);
	
	
	
	// $nearest_holiday_static = [
		// 'id' => $id,
		// 'kind' => 'static',
		// 'mktime' => $mktime,
		// 'date' => date("d-m-Y", $mktime),
		// 'dateformat2' => dateformat2($mktime),
		// 'date3' => dateformat3($mktime),
		// 'datetime' => date("d-m-Y H:i:s", $mktime),
		// 'title' => $title,
		// 'introduction' => $introduction,
		// 'text' => $text,
	// ];
	
	return $nearest_holiday_static;
}
function holiday_info($array){
	$holiday_info = [
		'id' => $array['id'],
		'kind' => $array['kind'],
		'mktime' => $array['mktime'],
		'date' => date("d-m-Y", $array['mktime']),
		'dateformat2' => dateformat2($array['mktime']),
		'date3' => dateformat3($array['mktime']),
		'datetime' => date("d-m-Y H:i:s", $array['mktime']),
		'title' => $array['title'],
		'introduction' => $array['introduction'],
		'text' => $array['text'],
	];
	return $holiday_info;
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

// Формат: Понедельник, 28 ноября 2016 года
function dateformat2($mktime){
	$phrasebook = phrasebook($mktime);
	$dayOfMonth = $phrasebook['dayOfMonth'];
	$month_text = $phrasebook['month_text'];
	$year = $phrasebook['year'];
	$dayOfWeek_text = $phrasebook['dayOfWeek_text'];
	
	$dateformat = "$dayOfWeek_text, $dayOfMonth $month_text $year года";
	return $dateformat;
}

// Формат: 28 ноября 2016 года, понедельник
function dateformat3($mktime){
	$phrasebook = phrasebook($mktime);
	$dayOfMonth = $phrasebook['dayOfMonth'];
	$month_text = $phrasebook['month_text'];
	$year = $phrasebook['year'];
	$dayOfWeek_text = mb_strtolower($phrasebook['dayOfWeek_text']);
	$dateformat = "$dayOfMonth $month_text $year года, $dayOfWeek_text";
	return $dateformat;
}

// Формат: 27 ноября
function yesterday2($yearSelected,$monthSelected,$daySelected){
	$mktime = mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected)-24*3600;
	// $dayOfYear_selected = date("z", mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected));
	$phrasebook = phrasebook($mktime);
	$dayOfMonth = $phrasebook['dayOfMonth'];
	$month_text = $phrasebook['month_text'];
	
	$dateformat = "$dayOfMonth $month_text";
	return $dateformat;
}

// Формат: 29 ноября
function tomorrow2($yearSelected,$monthSelected,$daySelected){
	$mktime = mktime(0, 0, 0, $monthSelected, $daySelected, $yearSelected)+24*3600;
	$phrasebook = phrasebook($mktime);
	$dayOfMonth = $phrasebook['dayOfMonth'];
	$month_text = $phrasebook['month_text'];
	
	$dateformat = "$dayOfMonth $month_text";
	return $dateformat;
}

function phrasebook($mktime){
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
	
	$phrasebook = [
		'dayOfMonth' => $dayOfMonth,
		'month_text' => $month_text,
		'year' => $year,
		'dayOfWeek_text' => $dayOfWeek_text,
	];
	return $phrasebook;
}
?>