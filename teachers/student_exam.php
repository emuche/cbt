<?php 
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
?>

<title>Set Exam</title>
	<div id="page-content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12"  align="justify">
					


<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'year' => array(
							'required' 	=> true,
						), 
						'subject' => array(
							'required'  => true,
						), 
						'level'	=> array(
							'required'		=> true,
						),
						'class_label'	=> array(
							'required'		=> true,
						) 
			));

	if($validation->passed()){
		$db = DB::getInstance();

		$subject_id = Input::get('subject');
		$session_id = Input::get('term');
		$class_id 	= Input::get('class_label');

		$subject_info = $db->get('subject', array('id', '=', $subject_id))->first();
		$session_info = $db->get('session', array('id', '=', $session_id))->first();


		$subject 	= $subject_info->subject_name;
		$level		= $subject_info->level;
		$dept		= $subject_info->dept;
		$year		= $session_info->year;	
		$term		= $session_info->term;	

		$exam_table 	= 'question_'.$subject.'_'.$level.'_'.$dept;
		$answer_table 	= 'answer_'.$subject.'_'.$level.'_'.$dept;
	

		try{
			$db->create_exam_question_table($exam_table);
			$db->create_exam_answer_table($answer_table);
			
			Session::flash('view_student_result', 'These are the Results!');

			Session::put('subject', $subject);
			Session::put('subject_id', $subject_id);
			Session::put('level', $level);
			Session::put('dept', $dept);
			Session::put('year', $year);
			Session::put('term', $term);
			Session::put('session_id', $session_id);
			Session::put('exam_table', $exam_table);
			Session::put('answer_table', $answer_table);
			Session::put('class_id', $class_id);

			Redirect::to('view_student_result.php');

		}catch (Exception $e ){
			var_dump($e->getMessage());
			die();
		
		}
		}else {
				Character::flash_error($validation->errors());
			}
		}
	}
?>

					
					<div class=" divCenter text-center col-xs-12 col-sm-10 col-md-7 col-lg-5">
					<h3 class="text-center">Student Results</h3>
					<br>
						<form action="" method="post" class="form-horizontal">

							<div class="year_div">
								<label for="year">Select Examinination Year</label>
								<select class="form-control year" id="year" name="year">
									<option selected value="">choose Session Year</option>
<?php
$db = DB::getInstance();
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
								<select class="form-control" id="term" disabled>
									<option disabled selected>Select Term</option>
								</select>
							</div>
							<br>

							<div class="section_div">
								<label for="section">Select Student Section</label>
								<select class="form-control section" id="section" >
									<option value=""  selected>Select Student Section</option>

								</select>
							</div>
							<br>

							<div class="level_div">
								<label for="level">Select Student Level</label>
								<select class="form-control level" id="level" name="level" >
									<option value=""  selected>Select Student Level</option>
									
								</select>
							</div>
							<br>

							<div class="dept_div">
								<label for="dept">Select Student Department</label>
								<select class="form-control dept" id="dept" name="dept" >
									<option value=""  selected>Select Student Department</option>
									
								</select>								
							</div>
							<br>

							<div class="class_label_div">
								<label for="class_label">Select Student Class Label</label>
								<select class="form-control class_label" id="class_label" name="class_label" >
									<option value=""  selected>Select Student Class Label</option>
									
								</select>								
							</div>
							<br>

							<div class="subject_div">
								<label for="subject">Select Subject</label>
								<select class="form-control subject" id="subject" name="subject" >
									<option value=""  selected>Select Subject</option>
									
								</select>
							</div>
							<br>

							<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">
							<br>

							<button type="submit" class="btn btn-success">submit</button>

								
						</form>
					</div>

				</div>
				<br>
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
