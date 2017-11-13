<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

?>
<title>Write Exam</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">

				<h1 class="text-center">Select Exam Page</h1>
				<br>

<?php
$user 	= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
$db 		= DB::getInstance();
$permission = $user->data()->permission;
$student_id = $_SESSION['user'];
$student 	= $db->get($permission, array('user_id', '=', $student_id))->first();

$level		= $student->level;
$section	= $student->section;
$dept		= $student->dept;

Session::put('level', $level);
Session::put('section', $section);
Session::put('dept', $dept);

$session_id = Input::get('term');
$subject_id = Input::get('subject');




if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();

		$result_exists = $db->action('SELECT *', 'result', array('subject_id', '=', $subject_id), array('AND', 'student_id', '=', $student_id), array('AND', 'session_id', '=', $session_id))->count();
		$subject_approved = $db->action('SELECT *', 'subject', array('id', '=', $subject_id), array('AND', 'approved', '=', '1'))->count();
		
		if ($result_exists) {
			$validate->add_error('You have already written this Exam');	
		}

		if (!$subject_approved) {
			$validate->add_error('This Exam is not ready yet');
		}

		$validation 	= $validate->check($_POST, array(

						'year' => array(
							'required' 	=> true
						), 
						'term' => array(
							'required'  => true
						),
						'subject'	=> array(
							'required'  => true
						)
			));
		if($validation->passed()){
			$user = new User();

			try{

				
				
				Session::put('session_id', $session_id);
				Session::put('subject_id', $subject_id);

				Session::flash('exam_page', 'Your Exam Has Started Goodluck');
				Redirect::to('start_exam.php');

			}catch (Exception $e ){
				die($e->getMessage());
			
			}

		}else{

?>
	<div class="col-xs-12" style="margin-bottom: 30px; z-index: 3; clear: both;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>
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
Character::flash('write_exam');
?>

				<div class="divCenter text-center col-xs-12 col-sm-8 col-md-6 ">
				<form action="" method="post" class="form-horizontal" role="form">


					<div class="year_div">
						<label for="year">Select Session Year</label>
						<select class="form-control year" id="year" name="year">
							<option value=""  selected>Select Year</option>
<?php
$years = $db->group_by('session', 'year')->all();
foreach ($years as $year) {
	echo '<option value="'.$year->year.'">'.$year->year.'</option>';
}
?>
						</select>
					</div>
					<br>

					<div class="term_div">				
					<label for="term">Select Term</label>
						<select class="form-control write_exam_term" id="write_exam_term" name="term" >
							<option value=""  selected>Choose Session Term</option>
							
						</select>
					</div>
					<br>

					<div class="subject_div">
						<label for="subject">Select Subject</label>
						<select class="form-control subject" id="subject" name="subject">
							<option value=""  selected>Select Subject</option>
							
						</select>
					</div>
					<br>

			
					
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Start Exam</button>	

<br>
<br>
<br>
<br>
<br>		
					</div>
							
				</form>
			</div>

			</div>
		</div>
	</div>
</div>



<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
