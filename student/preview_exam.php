<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/no_nav_header.php';
include_once ROOT_PATH.'/countdown/countdown_css.php';

$db 		= DB::getInstance();
$user 		= new User();
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
!Session::get('exam_table') ||
!Session::get('answer_table') ||
!Session::get('exam_answer_table') ||
!Session::get('exam_options_table') ||
!Session::get('questions_written') ||
Session::get('next') < 0 

) {
	Redirect::to('index.php');
}

if (Session::get('time_in_sec') < time()) {
	Redirect::to('end_exam.php');
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
$time_remaining 	= (int)$time_in_sec - time();
$delete_confirm		= 'Are You Sure You want to Submit?';


$exams 		= $db->get($exam_answer_table, array('id', '>', 0))->all();
?>
<title>Preview Exam</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">

				<h1 class="text-center"><?php echo strtoupper(($level).' '.Character::organize($subject).' '.$year.' Exam');?></h1>
				<input type="hidden" name="" class="exam_time" id="exam_time" value="<?php echo $time_remaining; ?>">

<?php Character::flash('preview_exam'); ?>				
				
				<br>
				<div class="row col-md-8 divCenter">
					<div class="col-xs-4" style="float: left;">
						<a class="btn btn-info" href="exam_page.php">Back to Exam</a>
					</div>

					<div class="col-xs-4 text-center">
						<form action="end_exam.php" method="post">
							<input type="hidden" name="jibrish" value="">
							<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmDelete">Submit Paper</button>
						</form>
					</div>
					
					<div class="col-xs-4" style="float: right;">
						<div id="defaultCountdown" style="float: right;"></div>
					</div>
				</div>
				<br>
				<br>

				<div class="col-md-12 divCenter preview_exam" id="preview_exam">


				<table class="table table-hover table-striped table-bordered table-responsive" style="font-family: rockwell;">
					<tr>
						<th>No.</th>
						<th>Question</th>
						<th>Selected Option</th>
						<th>Edit</th>
					</tr>

<?php

foreach ($exams as $exam) {

$question = $db->get($exam_table, array('id', '=', $exam->question_id))->first();
$answer_query = $db->action('SELECT * ', $exam_options_table, array('question_id', '=', $exam->question_id), array('AND', 'checked', '=', '1'));

$answers = $answer_query->count() ? $answer_query->first()->answer : '' ;

$row_color = !$answer_query->count() ? 'style = "background: #FB6C6C;"' : '' ;
?>
					<tr <?php echo $row_color; ?> >
						<td><?php echo $exam->id ;?></td>
						<td><?php echo $question->question ;?></td>
						<td><?php echo $answers ;?></td>
						<td>
							<form action="exam_page.php" method="post">
								<input type="hidden" name="exam_id" value="<?php echo $exam->id;?>">
								<button type="submit" class="btn btn-info">Edit</button>
							</form>
						</td>
						
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
			</div>
		</div>
	</div>
</div>
<?php
include_once ROOT_PATH.'/includes/overall/overall_footer.php';
include_once ROOT_PATH.'/countdown/countdown.php';
?>