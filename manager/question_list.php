<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
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

$token = Token::generate();

?>



<title>Question list</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


				<div class="divCenter text-center col-xs-12 col-sm-10 col-md-8 col-lg-6">
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
							<th>View</th>
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

			echo '<tr><td>'.$x.'</td><td>'.$question->question.'</td><td>'.$image_exists.'</td><td>';
			

			$answers 	= $db->action('SELECT *', $answer_table, array('question_id', '=', $question->id), array('AND', 'correct', '=', '1'), array('AND', 'session_id', '=', $session_id))->all();

				$y = 1;
				foreach ($answers as $answer) {
					echo $y.'. '.$answer->answer.'<br>';
					$y++;
				}
			echo '</td><td>';
?>
<form action="update_question.php" method="post">
	<input type="hidden" name="question_id" value="<?php echo $question->id;?>">

	<button type="submit" class="btn btn-info">View</button>
</form>




<?php

			'</td></tr>';
			$x++;			
		}
?>		
					</table>

					<div class="divCenter text-center">
						<form action="approve_exam.php" method="post">
							<input type="hidden" name="token" value="<?php echo $token;?>">
							<input type="hidden" name="approval" value="1">
							<button class="btn btn-success" type="submit">Approve</button>
						</form>
						<br>
						<form action="approve_exam.php" method="post">
							<input type="hidden" name="token" value="<?php echo $token;?>">
							<input type="hidden" name="approval" value="0">
							<button class="btn btn-danger" type="submit">Disapprove</button>
						</form>
					</div>
				</div>
				<br>
				<br>
				<br>
			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>