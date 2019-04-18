<html>
<style>

td.calendar-day, td.calendar-day-np { 
	width:120px; 
	padding:5px 25px 5px 5px; 
	border-bottom:1px solid #999; 
	border-right:1px solid #999; 
}
</style>
<body>

<?php
$mnth = $_POST['month'];
$yr = $_POST['year'];

function draw_calendar($month,$year,$events = array()){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$calendar.= '<td class="calendar-day"><div style="position:relative;height:100px;">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';
			
			$event_day = $year.'-'.$month.'-'.$list_day;
			$event_day = ordinal($event_day);
			if(isset($events[$event_day])) {
				foreach($events[$event_day] as $event) {
					$calendar.= '<div class="event">'.$event['event'].'</div>';
				}
			}
			else {
				$calendar.= str_repeat('<p>&nbsp;</p>',2);
			}
		$calendar.= '</div></td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np">&nbsp;</td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';
	

	/* end the table */
	$calendar.= '</table>';

	/** DEBUG **/
	$calendar = str_replace('</td>','</td>'."\n",$calendar);
	$calendar = str_replace('</tr>','</tr>'."\n",$calendar);
	
	/* all done, return result */
	return $calendar;
}

function random_number() {
	srand(time());
	return (rand() % 7);
}

function ordinal($input_number){
	$number = (string) $input_number;  
	$last_digit = substr($number, -1);
	$second_last_digit = substr($number, -2, 1); 
	$suffix = 'th';
	if ($second_last_digit != '1'){
		switch ($last_digit){
			case '1':    
			$suffix = 'st'; 
			break;    
			case '2':
			$suffix = 'nd';
			break;      
			case '3':  
			$suffix = 'rd';   
			break;      
			default:  
			break;    
			}  
			} 
			if ((string) $number === '1')
				$suffix = 'st';
			return $number.$suffix; 
			}


$con=mysqli_connect("localhost","root","");
	if(!$con){
		echo "UNABLE TO CONNECT";
	}
	if(!mysqli_select_db($con,'events')){
			echo "INVALID DATABASE";
			}
$events = array();
$query = "SELECT event, DATE_FORMAT(date,'%Y-%m-%D') AS event_date FROM calevents WHERE date LIKE '$yr-$mnth%'";
$result = $con->query($query) or die($con->error);
while($row=$result->fetch_assoc()) {

	$events[$row['event_date']][] = $row;
}

/* sample usages */
echo '<h2>'.$mnth.'/';
echo $yr.'</h2>';
echo draw_calendar($mnth,$yr,$events);

?>

</body>
</html>