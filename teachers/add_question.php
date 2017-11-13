<?php 
require_once '../core/init.php';
require_once '../ckeditor/ckeditor.php';
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

$subject	 	= Session::get('subject');
$subject_id	 	= Session::get('subject_id');
$level 			= Session::get('level');
$dept 			= Session::get('dept');
$session_id 	= Session::get('session_id');
$year			= Session::get('year');
$term			= Session::get('term');
$exam_table		= Session::get('exam_table');
$answer_table	= Session::get('answer_table');

?>


<title>School | add question</title>


<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">

<div class="row text-center"><h3>
	Set <?php echo Character::organize($subject); ?> Questions for 
	<?php echo Character::organize($level).' '.
	Character::organize($dept).' '.
	Character::organize($term).' '.
	Character::year($year); ?>
</h3></div>

<?php

if(Input::exists()){
	if(Token::check(Input::get('token'))){		

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'question' => array(
							'required' 	=> true
						)
			));

		if($validation->passed()){
			$db	= DB::getInstance();

			$question		= Input::get('question');	
			$options 		= Input::get('option');
			$correct 		= Input::get('correct');

			try{
				if ($_FILES['image']['name']) {		

						$location 				= '../images/'.$exam_table.'/';
						$image_name 			= $_FILES['image']['name'];
						$image_size				= $_FILES['image']['size'];
						$image_type 			= $_FILES['image']['type'];
						$image_tmp_name			= $_FILES['image']['tmp_name'];
						$image_name_tag			= substr(md5($image_tmp_name), 0, rand(0, 10));
						$new_image_name			= $exam_table.'_'.$image_name_tag.'.jpg';

						if (!file_exists($location)) {
							mkdir($location, 0777, true);
						}
						move_uploaded_file($image_tmp_name, $location.$new_image_name);
					}else {
						$new_image_name		= Input::get('image');	
					}

				$db->insert($exam_table, array(
					'question'		=> $question,	
					'image'			=> $new_image_name,	
					'session_id'	=> $session_id
					));

				$question_id	= $db->get($exam_table, array('session_id', '=', $session_id))->last()->id;



					
				foreach ($options as $index => $option) {
					$db->insert($answer_table, array(
							'answer'		=> $option,	
							'question_id'	=> $question_id,	
							'correct'		=> $correct[$index],	
							'session_id'	=> $session_id
						));
					
				}

				Session::flash('add_question', 'Question has been added to Exam. You can add more');

			}catch (Exception $e ){
				die($e->getMessage());
			}

		}else{
			Character::flash_error($validation->errors());
		}
	}
}
Character::flash('add_question');
?>

<div class="col-md-6 col-sm-6 col-xs-6 divCenter text-center">
	<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">

		<div>
			<label for="question">Add a Question here</label>
			<textarea name="question" id="question" class="form-control" rows="10" placeholder="Enter question here"></textarea>	
			<br>

	
		</div>
		<div class="col-md-6 divCenter">
			<label for="image">Add Picture if applicable</label>
			<input type="file" name="image" id="image" class="form-control">
			<br>
			<br>
		</div>
		<div>
			<div class="col-md-9"><label for="option">Options of the Question</label></div>
			<div class="row">
				<div class="col-md-9">Make sure atleast one of the Options is selected as the correct answer</div>
				<div class="col-md-2">Check only correct answers</div>
				<div class="col-md-1">Remove</div>
			</div>

			<div class="row option"><div class="col-md-9"><input type="text" name="option[]" id="option" class="form-control " placeholder="Add an option to select in the question"></div><div class="col-md-2"><input type="hidden" name="correct[]" value="0" id="correct" class="correct"><input type="checkbox" id="checkbox_select" class="checkbox_select"></div><br><br></div>



			<div class="row option"><div class="col-md-9"><input type="text" name="option[]" id="option" class="form-control " placeholder="Add an option to select in the question"></div><div class="col-md-2"><input type="hidden" name="correct[]" value="0" id="correct" class="correct"><input type="checkbox" id="checkbox_select" class="checkbox_select"></div><br><br></div>
			
		</div>

		<br>
		<br>
		<br>
		<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

		<div class="row col-md-9 divCenter">
			<div class="col-md-4"><button class="btn btn-info more_option"><span class="glyphicon glyphicon-plus"></span> More Options</button></div>
			<div class="col-md-4"><button type="submit" name="" id="" class="btn btn-success">Add question</button></div>
			<div class="col-md-4"><a class="btn btn-warning btn" href="question_list.php"><span class="glyphicon glyphicon-ok-circle"></span> Finish Setting Exam</a></div>

		</div>

	</form>
</div>

<br>
<br>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
<script type="text/javascript">
	CKEDITOR.replace('question');
</script>