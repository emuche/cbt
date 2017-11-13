<?php
require_once '../core/init.php';

$user 		= new User();
$db 		= DB::getInstance();

if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('admin', $user->data()->permission);
if (Input::get('year')) {
	$year 		= Input::get('year');
	$terms	 	= $db->get('session', array('year', '=', $year));

?>
	<label for="term">Select Term</label>
		<select name="term" id="term" class="form-control term">
			<option value="">Select Term</option>
<?php		
	foreach ($terms->all() as $term) {
		echo	'<option value="'.$term->id.'">'.$term->term.'</option>';
	}
?>
		</select>
<?php
}
if (Input::get('term')) {

	$term 		= Input::get('term');
	$sections 	= $db->group_by('subject', 'section', array('session_id', '=', $term))->all();

?>
	<label for="section">Select Student Section </label>
		<select name="section" id="section" class="form-control section">
			<option value="" disabled selected>Select Student Section </option>
<?php		
	foreach ($sections as $section) {
		echo	'<option value="'.$section->section.'">'.$section->section.'</option>';
	}
?>
		</select>
<?php
}
if (Input::get('section')) {

	$section	= Input::get('section');
	$levels	 	= $db->group_by('class', 'level', array('section', '=', $section))->all();

?>
	<label for="level">Select Student Level</label>
		<select name="student_level" id="level" class="form-control level">
			<option value="">Select Student Level</option>
			

<?php		
	foreach ($levels as $level) {
		echo	'<option value="'.$level->level.'">'.$level->level.'</option>';


	}
?>
		</select>
<?php
}



if (Input::get('level')) {

	$level	= Input::get('level');
	$depts	= $db->group_by('class', 'dept', array('level', '=', $level))->all();

?>
	<label for="dept">Select Student Department</label>
		<select name="student_dept" id="dept" class="form-control dept">
			<option value="">Select Student Department</option>
			

<?php		
	foreach ($depts as $dept) {
		echo	'<option value="'.$dept->dept.'">'.$dept->dept.'</option>';
	}
?>
		</select>
<?php
}

if (Input::get('dept3') && Input::get('level3')) {

	$dept 		= Input::get('dept3');
	$level 		= Input::get('level3');
	$subjects	= $db->action('SELECT * ', 'class', array('level', '=', $level), array('AND', 'dept', '=', $dept));

?>
	<label for="class_label">Select Student Class Label</label>
		<select name="class_label" id="class_label" class="form-control class_label">
			<option value="">Select Class Label</option>
			

<?php		
	foreach ($subjects->all() as $subject) {
		echo	'<option value="'.$subject->id.'">'.$subject->class_label.'</option>';
	}
?>
		</select>
<?php
}

if (Input::get('dept') && Input::get('level2')) {

	$dept 		= Input::get('dept');
	$level 		= Input::get('level2');
	$subjects	= $db->group_by('subject', 'subject_name', array('level', '=', $level), array('AND', 'dept', '=', $dept));

?>
	<label for="subject">Select Student Subject</label>
		<select name="subject" id="subject" class="form-control subject">
			<option value="">Select Subject</option>
			

<?php		
	foreach ($subjects->all() as $subject) {
		echo	'<option value="'.$subject->id.'">'.$subject->subject_name.'</option>';


	}
?>
		</select>
<?php
}