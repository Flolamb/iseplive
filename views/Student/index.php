<h1><?php echo __('STUDENTS_TITLE'); ?></h1>

<?php
$i = 0;
for($year = $last_promo; $year > $last_promo-5; $year--){
	$i++;
?>
<div class="students-promo">
	<h2><?php echo __('STUDENTS_PROMO'.$i, array('year' => $year)); ?></h2>
	<?php
	if(!isset($students[$year]))
		$students[$year] = array();
	foreach($students[$year] as $student){
	?>
	<a href="<?php echo Config::URL_ROOT.Routes::getPage('student', array('username' => $student['username'])); ?>"><?php echo htmlspecialchars($student['firstname'].' '.$student['lastname']); ?></a><br />
	<?php
	}
	?>
</div>
<?php
}
?>
