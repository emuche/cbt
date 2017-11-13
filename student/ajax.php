<?php
require_once '../core/init.php';

$user 		= new User();
$db 		= DB::getInstance();

if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
if (Input::get('year')) {

	$year 		= Input::get('year');
	$terms	 	= $db->get('session', array('year', '=', $year));

?>
	<label for="term">Select Term</label>
		<select name="term" id="write_exam_term term" class="form-control write_exam_term">
			<option value="">Select Term</option>
			

<?php		
	foreach ($terms->all() as $term) {
		echo	'<option value="'.$term->id.'">'.$term->term.'</option>';


	}
?>
		</select>
<?php
}



if (Input::get('session_id')) {

	$session_id 	= Input::get('session_id');

	$subjects	= $db->action('SELECT * ', 'subject', array('session_id', '=', $session_id), array('AND', 'level', '=', $_SESSION['level']), array('AND', 'dept', '=', $_SESSION['dept']));


?>
	<label for="subject">Select Subject</label>
		<select name="subject" id="subject" class="form-control subject">
			<option value="">Select Subject</option>
			

<?php		
	foreach ($subjects->all() as $subject) {
		echo	'<option value="'.$subject->id.'">'.Character::organize($subject->subject_name).'</option>';


	}
?>
		</select>
<?php
}
?>
