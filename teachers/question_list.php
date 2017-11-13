<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$user = new User();
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
!Session::get('answer_table')
) {
	Redirect::to('index.php');
}

$subject 		= Session::get('subject');
$subject_id		= Session::get('subject_id');
$level 			= Session::get('level');
$dept 			= Session::get('dept');
$year 			= Session::get('year');
$term 			= Session::get('term');
$session_id 	= Session::get('session_id');
$exam_table 	= Session::get('exam_table');
$answer_table 	= Session::get('answer_table');
?>



<title>Question list</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


				<div class="divCenter text-left col-xs-12 col-sm-12 col-md-11 col-lg-10">
					<h3 class="text-center"><?php echo '';?> Question List</h3>
<?php
Character::flash('question_list');
?>
				
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>No.</th>
							<th>Question</th>
							<th>Image</th>
							<th>Correct Answer(s)</th>
							<th>Edit</th>
						</tr>

						
<?php
		$db 		= DB::getInstance();
		$questions 	= $db->get($exam_table, array('session_id', '=', $session_id));
		

		$x 			= 1;
		foreach ($questions->all() as $question) {	


			if ($question->image) {
				$image_exists = 'Yes';
			}else {
				$image_exists = 'No';
			}
			$check_answer = $db->action('SELECT *', $answer_table, array('question_id', '=', $question->id), array('AND', 'correct', '=', '1'), array('AND', 'session_id', '=', $session_id));
			$answer_exists = $check_answer->count();
			$bg_color = !$answer_exists ? 'style="background-color: red;" ' : '';

			echo '<tr '.$bg_color.'><td>'.$x.'</td><td>'.$question->question.'</td><td>'.$image_exists.'</td><td>';
			

			$answers 	= $check_answer->all();

				$y = 1;
				foreach ($answers as $answer) {
					echo $y.'. '.$answer->answer.'<br>';
					$y++;
				}
			echo '</td><td>';
?>
<form action="update_question.php" method="post">
	<input type="hidden" name="question_id" value="<?php echo $question->id;?>">

	<button type="submit" class="btn btn-info">Edit</button>
</form>




<?php

			'</td></tr>';
			$x++;			
		}
?>		
					</table>

					<div class="divCenter text-center row ">
						<div class="col-xs-6">
							<form action="finish_setting_exam.php" method="post">
								<input type="hidden" name="token" value="<?php echo $token;?>">
								<button class="btn btn-success" type="submit">Done Editing</button>
							</form>
						</div>
						<div class="col-xs-6">
							<form action="add_question.php" method="post">
								<input type="hidden" name="token" value="<?php echo $token;?>">
								<button class="btn btn-success" type="submit">Add Questions</button>
							</form>
						</div>
					</div>
				</div>
				<br>
				<br>
				<br>
				<br>
			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>