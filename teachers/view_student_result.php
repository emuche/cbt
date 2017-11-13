<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db 		= DB::getInstance();
$user 		= new User();
$data 		= $user->data();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

if ( 
!Session::get('subject') ||
!Session::get('subject_id') ||
!Session::get('level') ||
!Session::get('dept') ||
!Session::get('year') ||
!Session::get('term') ||
!Session::get('session_id') ||
!Session::get('exam_table') ||
!Session::get('answer_table') ||
!Session::get('class_id')

) {
	Redirect::to('index.php');
}

$subject 			= Session::get('subject');
$subject_id 		= Session::get('subject_id');
$level				= Session::get('level');
$dept 				= Session::get('dept');
$year 				= Session::get('year');
$term 				= Session::get('term');
$session_id 		= Session::get('session_id');
$exam_table 		= Session::get('exam_table');
$answer_table 		= Session::get('answer_table');
$class_id 			= Session::get('class_id');

//Session::delete('subject');
//Session::delete('subject_id');
//Session::delete('level');
//Session::delete('dept');
//Session::delete('year');
//Session::delete('session_id');
//Session::delete('exam_table');
//Session::delete('answer_table');
//Session::delete('class_id');


$results 	= $db->action('SELECT *', 'result', array('subject_id', '=', $subject_id), array('AND', 'class_id', '=', $class_id))->all();
?>

<title>View Results</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
			<div class="col-lg-10 col-md-12 col-sm-12 text-center divCenter">
				<h3>Here are your Exams  and Results</h3>

				<table class="table table-hover table-striped table-responsive table text-left" >
					<tr>
						<th>Student Name</th>
						<th>Subject</th>
						<th>Section</th>
						<th>Level</th>
						<th>Dept</th>
						<th>Class Label</th>
						<th>Session</th>
						<th>Term</th>
						<th>Total Question</th>
						<th>Total Correct</th>
						<th>Exam Time</th>
						<th>Score</th>
						<th>Grade</th>
						<th>Remark</th>
					</tr>
<?php 
foreach ($results as $result) {
	
	$user 		= new User($result->student_id);
	$data 		= $user->data();

	$session 	= $db->get('session', array('id', '=', $result->session_id))->first();
	$subject 	= $db->get('subject', array('id', '=', $result->subject_id))->first();
	$class 		= $db->get('class', array('id', '=', $class_id))->first();



?>
					<tr>
						<td><?php echo $data->last_name.' '.$data->middle_name.' '.$data->first_name; ?></td>
						<td><?php echo Character::organize($subject->subject_name); ?></td>
						<td><?php echo $class->section; ?></td>
						<td><?php echo $class->level; ?></td>
						<td><?php echo $class->dept; ?></td>
						<td><?php echo $class->class_label; ?></td>
						<td><?php echo $session->year; ?></td>
						<td><?php echo $session->term; ?></td>
						<td><?php echo $result->no_of_question; ?></td>
						<td><?php echo $result->total_correct_answer; ?></td>
						<td><?php echo $result->total_time; ?> Mins</td>
						<td><?php echo $result->score; ?>%</td>
						<td><?php echo $result->grade; ?></td>
						<td><?php echo $result->remark; ?></td>
					</tr>
<?php	
}
?>
				</table>
			</div>

			<br>
			<br>
			<br>
			<br>
			<br>





			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
