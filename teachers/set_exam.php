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
		$db = DB::getInstance();


		$subject_id = Input::get('subject');
		$session_id = Input::get('term');

		$subject_info = $db->get('subject', array('id', '=', $subject_id))->first();
		$session_info = $db->get('session', array('id', '=', $session_id))->first();


		$subject 	= $subject_info->subject_name;
		$level		= $subject_info->level;
		$dept		= $subject_info->dept;
		$year		= $session_info->year;	
		$term		= $session_info->term;	

		$exam_table 	= 'question_'.$subject.'_'.$level.'_'.$dept;
		$answer_table 	= 'answer_'.$subject.'_'.$level.'_'.$dept;
		$validate 		= new validate();
		

		$tabel_exist = $db->query("SHOW TABLES LIKE '{$exam_table}'")->count();
		if ($tabel_exist) {
			$validate->add_error('This Exam has Already Been set!');
			
		}
	

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
						'total_no_of_question'	=> array(
							'required'		=> true,
						),
						'total_time_of_exam'	=> array(
							'required'		=> true,
						) 
			));

		

	if($validation->passed()){


		try{
			
			$db->update('subject', $subject_id, array(
				'teacher_id'  			=> $_SESSION['user'],
				'total_no_of_question' 	=> Input::get('total_no_of_question'),
				'total_time_of_exam'  	=> Input::get('total_time_of_exam'),
				'exam_table'			=> $exam_table,
				'answer_table'			=> $answer_table	
			));

			$db->create_exam_question_table($exam_table);
			$db->create_exam_answer_table($answer_table);
			

			Session::put('subject', $subject);
			Session::put('subject_id', $subject_id);
			Session::put('level', $level);
			Session::put('dept', $dept);
			Session::put('year', $year);
			Session::put('term', $term);
			Session::put('session_id', $session_id);
			Session::put('exam_table', $exam_table);
			Session::put('answer_table', $answer_table);

			Session::flash('home', 'You can now add questions to the Exam!');
			Redirect::to('add_question.php');

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
					<h3 class="text-center">Set Examination</h3>
					<br>
						<form action="" method="post" class="form-horizontal">

							<div class="year_div">
								<label for="year">Select Examinination Year</label>
								<select class="form-control year" id="year" name="year">
									<option disabled selected value="">choose Session Year</option>
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
								<select class="form-control section" id="section" disabled>
									<option value="" disabled selected>Select Student Section</option>

								</select>
							</div>
							<br>

							<div class="level_div">
								<label for="level">Select Student Level</label>
								<select class="form-control level" id="level" disabled>
									<option value="" disabled selected>Select Student Level</option>
									
								</select>
							</div>
							<br>

							<div class="dept_div">
								<label for="dept">Select Student Department</label>
								<select class="form-control dept" id="dept" disabled>
									<option value="" disabled selected>Select Student Department</option>
									
								</select>								
							</div>
							<br>

							<div class="subject_div">
								<label for="subject">Select Subject</label>
								<select class="form-control subject" id="subject" disabled>
									<option value="" disabled selected>Select Subject</option>
									
								</select>
							</div>
							<br>

							<div>
								<label for="total_no_of_question">Total No. Of Questions</label>
								<input type="number" name="total_no_of_question" id="total_no_of_question" class="form-control" maxlength="3" placeholder="Total No. Of Questions" value="<?php echo escape(Input::get('total_no_of_question'));?>">

							</div>
							<br>	


							<div>
								<label for="total_time_of_exam">Total Exam Time in <strong>"Minutes"</strong></label>
								<input type="number" name="total_time_of_exam" id="total_time_of_exam" class="form-control" maxlength="3" placeholder='Total Exam Time in "Minutes"' value="<?php echo escape(Input::get('total_time_of_exam'));?>">

								<br>	
							</div>
									

							<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">
							<br>

							<button type="submit" class="btn btn-success">submit</button>

								
						</form>
					</div>

				</div>

			</div>		
		</div>
	</div>
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
