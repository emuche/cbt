<?php
require_once '../core/init.php';

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
Session::get('next') < 0 ||
Session::get('prev') < 0

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
$exam_table 		= Session::get('exam_table');
$answer_table 		= Session::get('answer_table');
$exam_answer_table 	= Session::get('exam_answer_table');
$exam_options_table = Session::get('exam_options_table');
$next 				= Session::get('next');


$time_remaining 	= (int)$time_in_sec - time();
$examall 	= $db->get(Session::get('exam_answer_table'), array('id', '>', 0))->count();

if (Input::get('next')) {
	$next = Session::get('next') + 1;
	if ($next > ($examall - 1)) {
		$next = $examall - 1;
	}
}else if (Input::get('prev')) {
	$next = Session::get('next') - 1;
	if ($next < 0) {
		$next = 0;
	}
}else{
	$next = 0;
}

Session::put('next', $next);

$exam 		= $db->get(Session::get('exam_answer_table'), array('id', '>', 0))->next($next);
$question   = $db->get(Session::get('exam_table'), array('id', '=', $exam->question_id))->first();

$options  	= $db->get($exam_options_table, array('question_id', '=', $exam->question_id))->all();


?>
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
						<h4>
						<form action="" method="">

<?php 
$x = range('A', 'Z');
foreach ($options as $index=>$option) { ?>

<p>

	<label for="option<?php echo $x[$index]; ?>" class="col-xs-12" style="font-family: rockwell;">
						
							<a class="list-group-item" id="select_option"><span class="label label-default"><?php echo $x[$index]; ?>.</span>&ensp;&ensp;<?php echo $option->answer ?>&ensp;
							<input type="hidden" value="<?php echo $option->correct; ?>" class="correct" id="correct">
							<input type="hidden" value="<?php echo $option->id ;?>" id="option_id" class="option_id">
							<input type="radio" name="radio" id="option<?php echo $x[$index]; ?>" class="exam_radio <?php echo $x[$index]; ?>" style="float: right;" <?php if ($option->checked == '1') { echo 'checked'; }?>>
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
