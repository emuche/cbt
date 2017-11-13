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

if ( 
!Session::get('subject_id') ||
!Session::get('level') ||
!Session::get('section') ||
!Session::get('dept') ||
!Session::get('session_id') ||
!Session::get('subject') ||
!Session::get('year') ||
!Session::get('time_of_exam') ||
!Session::get('time_in_sec') ||
!Session::get('no_of_question') ||
!Session::get('questions_written') ||
!Session::get('exam_table') ||
!Session::get('answer_table') ||
!Session::get('exam_answer_table') ||
!Session::get('exam_options_table') ||
Session::get('next') < 0 ||
Session::get('prev') < 0



) {
	Redirect::to('view_results.php');
}


$subject_id 		= Session::get('subject_id');
$level				= Session::get('level');
$section			= Session::get('section');
$dept 				= Session::get('dept');
$session_id 		= Session::get('session_id');
$subject 			= Session::get('subject');
$year 				= Session::get('year');
$time_of_exam 		= Session::get('time_of_exam');
$time_in_sec 		= Session::get('time_in_sec');
$no_of_question 	= Session::get('no_of_question');
$questions_written 	= Session::get('questions_written');
$exam_table 		= Session::get('exam_table');
$answer_table 		= Session::get('answer_table');
$exam_answer_table 	= Session::get('exam_answer_table');
$exam_options_table = Session::get('exam_options_table');
$next 				= Session::get('next');




Session::delete('subject_id');
Session::delete('level');
Session::delete('section');
Session::delete('dept');
Session::delete('session_id');
Session::delete('subject');
Session::delete('year');
Session::delete('time_of_exam');
Session::delete('time_in_sec');
Session::delete('no_of_question');
Session::delete('questions_written');
Session::delete('exam_table');
Session::delete('answer_table');
Session::delete('exam_answer_table');
Session::delete('exam_options_table');
Session::delete('next');
Session::delete('prev');







$db 		= DB::getInstance();
$session 	= $db->get('session', array('id', '=', $session_id))->first();
$result 	= $db->action('SELECT * ','result', array('student_id', '=', $data->id), array(' AND ', 'session_id', '=', $session_id), array(' AND ', 'subject_id', '=', $subject_id))->last();

?>

<title>Exam Result</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
			<div class="col-lg-10 col-md-12 col-sm-12 text-center divCenter">
				<h3>Your <?php echo Character::organize($subject);?> Exam Has Ended. Result is as follows</h3>

<?php
Character::flash('show_result');
?>

				<table class="table table-hover table-striped table-responsive table text-left">
					<tr>
						<th>Student</th>
						<th>Level</th>
						<th>Department</th>
						<th>Subject</th>
						<th>Session</th>
						<th>Term</th>
						<th>total Question</th>
						<th>total Question written</th>
						<th>Total Correct</th>
						<th>Exam Time</th>
						<th>Score</th>
						<th>Grade</th>
						<th>Remark</th>
					</tr>
					<tr>
						<td><?php echo $data->first_name.' '.$data->last_name.' '.$data->middle_name;?></td>
						<td><?php echo $level; ?></td>
						<td><?php echo $dept; ?></td>
						<td><?php echo Character::organize($subject); ?></td>
						<td><?php echo $session->year; ?></td>
						<td><?php echo $session->term; ?></td>
						<td><?php echo $result->no_of_question; ?></td>
						<td><?php echo $result->questions_written; ?></td>
						<td><?php echo $result->total_correct_answer; ?></td>
						<td><?php echo $result->total_time; ?> Mins</td>
						<td><?php echo $result->score; ?>%</td>
						<td><?php echo $result->grade; ?></td>
						<td><?php echo $result->remark; ?></td>
					</tr>

				</table>
				<br>

				<h3>Exam Review</h3>
				<br>
				<table class="table table-hover table-striped table-responsive table text-left">				
					<tr>
						<th>No.</th>
						<th>Question</th>
						<th>Correct Answer</th>
						<th>Chosen Answer</th>
						<th>Checked</th>
					</tr>
<?php
$questions = $db->action('SELECT *', $exam_answer_table, array('id', '>', 0))->all();
foreach ($questions as $question) {
	$exam_question = $db->get($exam_table, array('id', '=', $question->question_id))->first();
	$correct_option_query = $db->action('SELECT *', $exam_options_table, array('question_id', '=', $question->question_id), array('AND', 'correct', '=', '1'));

	$correct_option = $correct_option_query->count() ? $correct_option_query->first()->answer : '' ;

	$chosen 		= $db->action('SELECT *', $exam_options_table, array('question_id', '=', $question->question_id), array('AND', 'checked', '=', '1'));
	$check_chosen 	= $chosen->count();
	$chosen_option 	= $check_chosen == '0' ? ' ' : $chosen->first()->answer ;


	$mark = $question->answer == '1' ? '<div class="text-success"><span class="glyphicon glyphicon-ok"></span></div>' : '<div class="text-danger"><span class="glyphicon glyphicon-remove"></span></div>';
	$row_color = !$chosen->count() ? 'style = "background-color: #FB6C6C;"': '';

?>

	<tr <?php echo $row_color; ?>>
		<td><?php echo $question->id; ?></td>
		<td><?php echo $exam_question->question; ?></td>
		<td><?php echo $correct_option; ?></td>
		<td><?php echo $chosen_option; ?></td>
		<td><?php echo $mark; ?></td>
	</tr>
	
<?php } ?>
				</table>
				<a class="btn btn-success" href="view_results.php">View Results</a>
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

<?php 

$db->drop_table($exam_answer_table);
$db->drop_table($exam_options_table);


include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
