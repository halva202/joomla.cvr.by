<?php 
include_once($_SERVER['DOCUMENT_ROOT'].'/selfCode/library/calendar/!bd.php');

function result($yearSelected,$monthSelected,$daySelected){
	$nearestHoliday = nearestHoliday($yearSelected,$monthSelected,$daySelected);
	$nearestHolidays = nearestHolidays($yearSelected,$monthSelected,$daySelected);
	
	$result = [
		'nearestHoliday' => $nearestHoliday,
		'nearestHolidays' => $nearestHolidays,
	];
	return $result;
}

function nearestHoliday($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamic($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_static($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] <= $nearest_holiday_static['mktime'] ? $nearestHoliday_minInfo = $nearest_holiday_dynamic : $nearestHoliday_minInfo = $nearest_holiday_static;
	
	$nearestHoliday = holiday_info($nearestHoliday_minInfo);
	
	return $nearestHoliday;
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
				$id = $row['id'];
				// $id = 999;
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
		
		$nearest_holiday_dynamic = [
			'id' => $id,
			'kind' => 'dynamic',
			'mktime' => $mktime,
			'title' => $title,
			'introduction' => $introduction,
			'text' => $text,
		];
		
		return $nearest_holiday_dynamic;
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
		
		$nearest_holiday_static = [
			'id' => $id,
			'kind' => 'static',
			'mktime' => $mktime,
			'title' => $title,
			'introduction' => $introduction,
			'text' => $text,
		];
		
		return $nearest_holiday_static;
	}

		function holiday_info($nearestHoliday_minInfo){
			$holiday_info = [
				'id' => $nearestHoliday_minInfo['id'],
				'kind' => $nearestHoliday_minInfo['kind'],
				'mktime' => $nearestHoliday_minInfo['mktime'],
				'date' => date("d-m-Y", $nearestHoliday_minInfo['mktime']),
				'date2' => date("d.m.Y", $nearestHoliday_minInfo['mktime']),
				'year' => date("Y", $nearestHoliday_minInfo['mktime']),
				'month' => date("m", $nearestHoliday_minInfo['mktime']),
				'day' => date("d", $nearestHoliday_minInfo['mktime']),
				'datetime' => date("d-m-Y H:i:s", $nearestHoliday_minInfo['mktime']),
				'dateformat1' => dateformat1($nearestHoliday_minInfo['mktime']),
				'dateformat2' => dateformat2($nearestHoliday_minInfo['mktime']),
				'dateformat3' => dateformat3($nearestHoliday_minInfo['mktime']),
				'title' => $nearestHoliday_minInfo['title'],
				'introduction' => $nearestHoliday_minInfo['introduction'],
				'text' => $nearestHoliday_minInfo['text'],
			];
			return $holiday_info;
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
			// Формат: 27 ноября
			function dateformat1($mktime){
				$phrasebook = phrasebook($mktime);
				$dayOfMonth = $phrasebook['dayOfMonth'];
				$month_text = $phrasebook['month_text'];
				
				$dateformat = "$dayOfMonth $month_text";
				return $dateformat;
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
				$dayOfWeek_text = mb_strtolower($phrasebook['dayOfWeek_text'],'utf8');
				$dateformat = "$dayOfMonth $month_text $year года, $dayOfWeek_text";
				return $dateformat;
			}	


function nearestHolidays($yearSelected,$monthSelected,$daySelected){
	$nearest = nearestHoliday($yearSelected,$monthSelected,$daySelected);
	
	// for prev1
	$mktimePrev1 = $nearest['mktime'] - 24 * 3600;
		$mktime = $mktimePrev1;
		$dayOfMonth = date("j", $mktime);
		$month = date("n", $mktime);
		$year = date("Y", $mktime);
	$prev1 = nearestHolidayLast($year,$month,$dayOfMonth);
	// for prev2
	$mktimePrev2 = $prev1['mktime'] - 24 * 3600;
		$mktime = $mktimePrev2;
		$dayOfMonth = date("j", $mktime);
		$month = date("n", $mktime);
		$year = date("Y", $mktime);
	$prev2 = nearestHolidayLast($year,$month,$dayOfMonth);
	
	// for next1
	$mktimeNext1 = $nearest['mktime'] + 24 * 3600;
		$mktime = $mktimeNext1;
		$dayOfMonth = date("j", $mktime);
		$month = date("n", $mktime);
		$year = date("Y", $mktime);
	$next1 = nearestHoliday($year,$month,$dayOfMonth);
	// for next2
	$mktimeNext2 = $next1['mktime'] + 24 * 3600;
		$mktime = $mktimeNext2;
		$dayOfMonth = date("j", $mktime);
		$month = date("n", $mktime);
		$year = date("Y", $mktime);
	$next2 = nearestHoliday($year,$month,$dayOfMonth);
	
	$nearestHolidays = [
		'prev2' => $prev2,
		'prev1' => $prev1,
		'nearest' => $nearest,
		'next1' => $next1,
		'next2' => $next2,
	];
	
	return $nearestHolidays;
}

function nearestHolidayLast($yearSelected,$monthSelected,$daySelected){
	$nearest_holiday_dynamic = nearest_holiday_dynamicLast($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_static = nearest_holiday_staticLast($yearSelected,$monthSelected,$daySelected);
	$nearest_holiday_dynamic['mktime'] >= $nearest_holiday_static['mktime'] ? $nearestHoliday_minInfo = $nearest_holiday_dynamic : $nearestHoliday_minInfo = $nearest_holiday_static;
	
	$nearestHoliday = holiday_info($nearestHoliday_minInfo);
	
	return $nearestHoliday;
}
	function nearest_holiday_dynamicLast($yearSelected,$monthSelected,$daySelected){
		$count = 0;
		$year = $yearSelected;
		$month = $monthSelected;
		$day = $daySelected;
		while($count == 0){
			$Easter = Easter($year);
			$dayOfYear_Easter = $Easter['dayOfYear'];
			$dayOfYear_daySelected = date("z", mktime(0, 0, 0, $month, $day, $year));
			$difference_Easter_daySelected = $dayOfYear_daySelected - $dayOfYear_Easter;
			$select = select($table = 'calendar_dynamic', $conditions = 'difference <= '.$difference_Easter_daySelected.' ORDER BY difference DESC LIMIT 1');
			
			while($row = mysql_fetch_array($select)){
				$count = $count + 1;
				$difference_holiday_Easter = $row['difference'];
				$id = $row['id'];
				// $id = 999;
				$title = $row['title'];
				$introduction = $row['introduction'];
				$text = $row['text'];
				// $dayOfYear_holiday = $dayOfYear_Easter + $difference_holiday_Easter;
			}
			
			$year = $year - 1;
			$month = 12;
			$day = 31;
		}
		
		$mktime = $Easter['mktime']+$difference_holiday_Easter*24*60*60;
		
		$nearest_holiday_dynamic = [
			'id' => $id,
			'kind' => 'dynamic',
			'mktime' => $mktime,
			'title' => $title,
			'introduction' => $introduction,
			'text' => $text,
		];
		
		return $nearest_holiday_dynamic;
	}
	function nearest_holiday_staticLast($yearSelected,$monthSelected,$daySelected){
		$count = 0;
		$year = $yearSelected;
		$month = $monthSelected;
		$day = $daySelected;
		
		$select = select($table = 'calendar_static', $conditions = 'month='.$month.' AND day<='.$day.' ORDER BY day DESC LIMIT 1');
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
			$month = $month - 1;
			if($month == 0){
				$month = 12;
				$year = $year - 1;
			}
			$select = select($table = 'calendar_static', $conditions = 'month='.$month.' ORDER BY day DESC LIMIT 1' );
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
		
		$nearest_holiday_static = [
			'id' => $id,
			'kind' => 'static',
			'mktime' => $mktime,
			'title' => $title,
			'introduction' => $introduction,
			'text' => $text,
		];
		
		return $nearest_holiday_static;
	}
	
?>