<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$db 		= DB::getInstance();
$user 		= new User();
$data 		= $user->data();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
$db 		= DB::getInstance();
$results 	= $db->get('result', array('student_id', '=', $data->id))->all();

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
						<th>Subject</th>
						<th>Session</th>
						<th>Term</th>
						<th>total Question</th>
						<th>Total Correct</th>
						<th>Exam Time</th>
						<th>Score</th>
						<th>Grade</th>
						<th>Remark</th>
					</tr>
<?php 
foreach ($results as $result) {

	$session 	= $db->get('session', array('id', '=', $result->session_id))->first();
	$subject 	= $db->get('subject', array('id', '=', $result->subject_id))->first();



?>
					<tr>
						<td><?php echo Character::organize($subject->subject_name); ?></td>
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
