<?php 
require_once '../core/init.php';
require_once '../ckeditor/ckeditor.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

if (Input::get('question_id')) {
	Session::put('question_id', Input::get('question_id'));
}

if (
!Session::get('subject') ||
!Session::get('subject_id') ||
!Session::get('question_id') ||
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
$question_id	= Session::get('question_id');
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
						),
						'question_id' => array(
							'required' 	=> true
						),

			));

		if($validation->passed()){
			$db	= DB::getInstance();

			$question		= Input::get('question');	
			$question_id	= Input::get('question_id');	
			$options 		= Input::get('option');
			$correct 		= Input::get('correct');





			try{

				$image_exists = $db->get($exam_table, array('id', '=', $question_id))->first();
	
				if ($_FILES['image']['name']) {

					if ($image_exists) {
						$path = '../images/'.$exam_table.'/'.$image_exists->image;
						unlink($path);
					}

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
				

					$db->update($exam_table, $question_id, array(
						'image'			=> $new_image_name,	
						));


				}

				$db->update($exam_table, $question_id, array(
					'question'		=> $question,	
					'session_id'	=> $session_id
					));

				$db->delete($answer_table, array('question_id', '=', $question_id));

				foreach ($options as $index => $option) {
					$db->insert($answer_table, array(
							'answer'		=> $option,	
							'question_id'	=> $question_id,	
							'correct'		=> $correct[$index],	
							'session_id'	=> $session_id
						));
					
				}

				Session::flash('question_list', 'Question has been Updated.');
				Session::delete('question_id');
				Redirect::to('question_list.php');

			}catch (Exception $e ){
				die($e->getMessage());
			}

		}else{

?>
	<div class="col-xs-12" style="margin-bottom: 30px; z-index: 1;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>
<?php
		foreach($validation->errors() as $error){
			echo $error, '<br>';
		}
?>
	</h4></div>
<?php
		}
	}
}



$db = DB::getInstance();
$question = $db->action('SELECT *', $exam_table, array('id', '=', $question_id), array('AND', 'session_id', '=', $session_id))->first();
Character::flash('update_question');
$token = Token::generate();
?>

<div class=" col-lg-6 col-md-6 col-sm-10 col-xs-12 divCenter text-center">
	<div class="divCenter text-center col-lg-10">

<?php if ($question->image){ ?>
					<div class="divCenter" >
						<img src="<?php echo '../images/'.$exam_table.'/'.$question->image; ?>" class="center-block img-rounded img-responsive">
					</div>
					<form action="delete_photo.php" method="post">
					<br>
						<input type="hidden" name="question_id" value="<?php echo $question_id;?>">
						<input type="hidden" name="token" value="<?php echo $token;?>">

						<button type="submit" class="btn btn-danger">Delete Photo</button>
					</form>
					<br>
<?php } ?>
	</div>

	<form action="" method="post" class="form-horizontal" enctype="multipart/form-data" role="form">

		<div>
			<label for="question">Edit Question here</label>
			<input type="hidden" name="question_id" value="<?php echo $question_id;?>">
			<textarea name="question" id="question" class="form-control" rows="10" placeholder="Enter question here"><?php echo $question->question;?></textarea>	
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
<?php
 $answers = $db->action('SELECT *', $answer_table, array('question_id', '=', $question_id), array('AND', 'session_id', '=', $session_id))->all();

 foreach ($answers as $answer) {
 ?>
			<div class="row option"><div class="col-md-9"><input type="text" name="option[]" id="option" class="form-control" value="<?php echo $answer->answer;?>" placeholder="Add an option to select in the question"></div><div class="col-md-2"><input type="hidden" name="correct[]" value="<?php if($answer->correct){echo '1';}else{ echo '0';}?>" id="correct" class="correct"><input type="checkbox" id="checkbox_select" class="checkbox_select" <?php if ($answer->correct) { echo 'checked';}?> ></div><br><br></div>


 <?php	
 }
?>



		</div>

		<br>
		<br>
		<br>
		<input type="hidden" name="token" value="<?php echo $token;?>">

		<div class="row col-md-9 divCenter">
			<div class="col-md-4"><button class="btn btn-info more_option"><span class="glyphicon glyphicon-plus"></span> More Options</button></div>
			<div class="col-md-4"><button type="submit" name="" id="" class="btn btn-success">Update question</button></div>
			<div class="col-md-4"><a href="question_list.php" class="btn btn-warning">BackTo questions</a></div>
		</div>

	</form>
</div>

<br>
<br>
<br>
<br>
<br>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
<script type="text/javascript">
	CKEDITOR.replace('question');
</script>