
<a href="<?php echo Config::URL_ROOT.Routes::getPage('events', array('group' => isset($calendar_group) ? $calendar_group : null, 'year' => $calendar_month==1 ? $calendar_year-1 : $calendar_year, 'month' => str_pad($calendar_month==1 ? 12 : $calendar_month-1, 2, '0', STR_PAD_LEFT))); ?>" id="calendar-prev-month"><?php echo __('CALENDAR_PREV_MONTH'); ?></a>
<a href="<?php echo Config::URL_ROOT.Routes::getPage('events', array('group' => isset($calendar_group) ? $calendar_group : null, 'year' => $calendar_month==12 ? $calendar_year+1 : $calendar_year, 'month' => str_pad($calendar_month==12 ? 1 : $calendar_month+1, 2, '0', STR_PAD_LEFT))); ?>" id="calendar-next-month"><?php echo __('CALENDAR_NEXT_MONTH'); ?></a>
<div id="calendar-current-month">
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('events', array('group' => isset($calendar_group) ? $calendar_group : null, 'year' => $calendar_year, 'month' => str_pad($calendar_month, 2, '0', STR_PAD_LEFT))); ?>"><?php echo Date::getMonthByNum($calendar_month).' '.$calendar_year; ?></a>
</div>

<table>
	<tr>
<?php
$first_day = (int) __('WEEK_FIRST_DAY');
for($i=0; $i < 7; $i++){
$day = ($i + $first_day)%7;
?>
		<th><?php echo substr(Date::getDayByNum($day), 0, 2); ?></th>
<?php
}
?>
	</tr>
<?php
$first_date_of_the_month = mktime(0, 0, 0, $calendar_month, 1, $calendar_year);
$number_of_days = (int) date('t', $first_date_of_the_month);
$first_day_of_the_month = (int) date('w', $first_date_of_the_month);
$n = 0;

// Empty cases at the beginning
for($i=$first_day; $i < $first_day_of_the_month; $i++){
	if($i == $first_day){
?>
	<tr>
<?php } ?>
		<td>&nbsp;</td>
<?php
}

$current_day = date('Y-n-j');

// Day cases
for($i=1; $i <= $number_of_days; $i++){
	$day_in_the_week = ($i+$first_day_of_the_month-1) % 7;
	$day_time = mktime(0, 0, 0, $calendar_month, $i, $calendar_year);
	
	if($day_in_the_week == $first_day){
?>
	<tr>
<?php
	}
	
	$titles = array();
	for($j = 0; $j < count($events); $j++){
		$event = & $events[$j];
		if(($event['date_start'] >= $day_time && $event['date_start'] <= $day_time+86400-1)
			|| ($event['date_end'] >= $day_time && $event['date_end'] <= $day_time+86400-1 && !(date('H', $event['date_end']) < 12 && date('Y-m-d', $event['date_end']) !=date('Y-m-d', $event['date_start'])))){
			$titles[] = $event['title'];
		}
	}
	
	if(count($titles) == 0){
?>
		<td<?php if($current_day == $calendar_year.'-'.$calendar_month.'-'.$i) echo ' class="current-day";'; ?>>
			<?php echo $i; ?>
		</td>
<?php
	}else{
?>
		<td<?php if($current_day == $calendar_year.'-'.$calendar_month.'-'.$i) echo ' class="current-day";'; ?>>
			<a href="<?php echo Config::URL_ROOT.Routes::getPage('events', array('group' => isset($calendar_group) ? $calendar_group : null, 'year' => $calendar_year, 'month' => str_pad($calendar_month, 2, '0', STR_PAD_LEFT), 'day' => str_pad($i, 2, '0', STR_PAD_LEFT))); ?>" title="<?php echo Date::dateMonth($day_time); foreach($titles as $title) echo ' :: '.htmlspecialchars($title); ?>">
				<?php echo $i; ?>
			</a>
		</td>
<?php
	}

	if($day_in_the_week == (6+$first_day)%7){
?>
	</tr>
<?php
	}
}

// Empty cases at the end
if($day_in_the_week != (6+$first_day)%7){
	for($i=$day_in_the_week+1; $i <= 6+$first_day; $i++){
?>
		<td>&nbsp;</td>
<?php
		if($i == 6+$first_day){
?>
	</tr>
<?php
		}
	}
}
?>
</table>

<br />
<a href="<?php echo Config::URL_ROOT.Routes::getPage('ical_official', isset($calendar_group) ? array('group' => $calendar_group) : null); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/event.png" alt="" class="icon" /> <?php echo __('CALENDAR_ICAL_OFFICIAL'); ?></a><br />
<?php if($is_student){ ?>
<a href="<?php echo Config::URL_ROOT.Routes::getPage('ical_non_official', isset($calendar_group) ? array('group' => $calendar_group) : null); ?>"><img src="<?php echo Config::URL_STATIC; ?>images/icons/event.png" alt="" class="icon" /> <?php echo __('CALENDAR_ICAL_NON_OFFICIAL'); ?></a>
<?php } ?>
