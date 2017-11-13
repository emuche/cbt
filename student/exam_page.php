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

if (Input::get('exam_id')) {
	Session::put('next', Input::get('exam_id') - 1);
	Session::put('prev', Input::get('exam_id') - 3);
	$exam_question_number = Input::get('exam_id') - 1;
}else {
	Session::put('next', 0);
	Session::put('prev', 0);
	$exam_question_number = 0;

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

$delete_confirm		= '<strong>Are you sure you want to SUBMIT</strong>?';
$time_remaining 	= (int)$time_in_sec - time();


$exam 			= $db->get($exam_answer_table, array('id', '>', $exam_question_number))->first();
$examall 		= $db->get($exam_answer_table, array('id', '>', 0))->count();
$question   	= $db->get($exam_table, array('id', '=', $exam->question_id))->first();

$options  		= $db->get($exam_options_table, array('question_id', '=', $exam->question_id))->all();

?>
<title>Write Exam</title>
<br>

<?php Character::flash('exam_page'); ?>	

				<div class="row ">
					<div class="col-md-8 divCenter">
						<div class="col-xs-9 divLeft">
							<h2><?php echo strtoupper(($level).' '.Character::organize($subject).' '.$year.' exam');?></h2>			
							<input type="hidden" name="" class="exam_time" id="exam_time" value="<?php echo $time_remaining; ?>">
						</div>
						<div class="col-xs-3 divRight">
							<div id="defaultCountdown"></div>
						</div>
					</div>
				</div>
			
				<br>
				<div class="col-xs-12 divCenter exam_page" id="exam_page">

					<div class="row">
						<div class="col-xs-8 divCenter">
							<div class="col-xs-4">
								<a class="btn btn-info" href="preview_exam.php">Preview Paper</a>
							</div>
							<div class="col-xs-4 text-center well-sm alert-info">
								<p>Question No. <?php echo $exam->id;?></p>
							</div>
							<div class="col-xs-4 text-right">
								<form action="end_exam.php" method="post">
									<input type="hidden" name="jibrish" value="">
									<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmDelete">Submit Paper</button>
								</form>
							</div>
						</div>
					</div>

					
					<br>
<?php 
if ($question->image) {
?>
					
					<div class="divCenter col-md-5 col-sm-8 col-xs-12" >
						<img src="<?php echo '../images/'.$exam_table.'/'.$question->image; ?>" class="center-block img-rounded" width="550" height="300">
					</div>
					<br>
<?php } ?>
					<div class="divCenter col-md-8 well-lg alert-info text-justify container-fluid" style="padding-top: 3px; padding-bottom: 3px; clear: both;">
						<h4 style="font-family: rockwell;">
<?php
echo $question->question;
?>
						</h4>
					</div>

					<br>
					<form method="post" action="submit_option.php" class="submit_option" id="submit_option">
						<input type="hidden" name="exam_id" class="exam_id" id="exam_id" value="<?php echo $exam->id;?>">
						<input type="hidden" name="question_id" class="question_id" id="question_id" value="<?php echo $exam->question_id;?>">
						<input type="hidden" name="answer" class="answer" id="answer" value="0">	
					</form>
					<div class="list-group col-md-8 divCenter">
						<h4 style="font-family: rockwell;">
						<form action="" method="">

<?php 
$x = range('A', 'Z');
foreach ($options as $index=>$option) { ?>

<p>

	<label for="option<?php echo $x[$index]; ?>" class="col-xs-12" style="font-family: rockwell;">
						<a class="list-group-item" id="select_option">
						<span class="label label-default"><?php echo $x[$index]; ?>.</span>&ensp;&ensp;<?php echo $option->answer ?>&ensp;
						<input type="hidden" value="<?php echo $option->correct; ?>" class="correct" id="correct" >
						<input type="hidden" value="<?php echo $option->id ;?>" id="option_id" class="option_id">
						<input type="radio" name="radio" id="option<?php echo $x[$index]; ?>" class="exam_radio <?php echo $x[$index]; ?>" style="float: right;"  <?php if ($option->checked == '1') { echo 'checked'; }?> >
						</a>
	</label>
</p>
<?php } ?>
						</form>

						
						</h4>
					</div>
				<div style="clear: both;">

					<div class="divLeft" style="padding-top: 20px; padding-bottom: 100px;">
						<button class="btn btn-info prev" id="next" value="prev" <?php if(Session::get('next') <= 0) {echo 'disabled';}?>><span class="glyphicon glyphicon-chevron-left"></span> Prev</button>
					</div>

					<div class="divRight" style="padding-top: 20px; padding-bottom: 100px;">
						<button class="btn btn-info next" id="next" value="next" <?php if(Session::get('next') >= ($examall - 1)) { echo 'disabled';}?> >Next <span class="glyphicon glyphicon-chevron-right"></span></button>
					</div>
				</div>

				</div>

				<br>
				<br>
				<br>
				<br>





<?php
include_once ROOT_PATH.'/includes/overall/overall_footer.php';
include_once ROOT_PATH.'/countdown/countdown.php';
include_once ROOT_PATH.'/includes/functions/keypress.php';
?>
