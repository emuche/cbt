<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
?>
<title>Add Subject</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php

Character::flash('add_subject');
$user 	= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'subject_name' 			=> array(
							'required' 	=> true,
						), 
						'level'					=> array(
							'required'	=> true,
						),
						'term'					=> array(
							'required'  => true
						),
						'dept'					=> array(
							'required'  => true
						),
						'class_id'				=> array(
							'required'  => true
						)
			));
		if($validation->passed()){
			$user = new User();

			try{

				$db = DB::getInstance();
				$db->insert('subject', array(

					'subject_name'				=> Character::replace_(Input::get('subject_name')),	
					'level'						=> Input::get('level'),	
					'dept'						=> Input::get('dept'),	
					'section'					=> Input::get('section'),	
					'class_id'					=> Input::get('class_id'),
					'session_id'				=> Input::get('term')
					

					));

					Session::flash('subject_list', ' Subject Added successfully');
					Redirect::to('subject_list.php?level='.Input::get('level').'&dept='.Input::get('dept'));
					
			}catch (Exception $e ){
				die($e->getMessage());
			}

		}else{
			Character::flash_error($validation->errors());
		}
	}
}
?>
				<div class=" divCenter text-center col-xs-5">
					<form action="" method="post" class="form-horizontal">

						<div class="">
							<label for="">Enter Subject Name</label>
							<input type="text" name="subject_name" id="" class="form-control" placeholder="Enter Subject Name" value="<?php echo escape(Input::get('subject_name'));?>">

							<br>	
						</div>

						<div class="year_div">
							<label for="year">Select Examinination Year</label>
							<select class="form-control year" id="year">
								<option value="" selected disabled>Select Examinination Year</option>
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
							<select id="term" class="form-control term" disabled>
							<option value="" selected>Select Term</option>

					
							</select>
						</div>
						<br>

						<div class="ssection_div">
							<label for="section">Select Student Section</label>
							<select class="form-control section" id="section" name="section" >
								<option value="" disabled selected>Select Student Section</option>
<?php
$sections = $db->group_by('class', 'section')->all();
foreach ($sections as $section) {
	echo '<option value="'.$section->section.'">'.$section->section.'</option>';
}
?>	
							</select>
						</div>
						<br>
						<div class="level_div">
							<label for="level">Select Student Level</label>
							<select class="form-control level2" id="level" disabled>
								<option value="" selected>Select Student Level</option>
							</select>
						</div>
						<br>
						<div class="dept_div">
							<label for="dept">Select Student Department</label>
							<select class="form-control dept" id="dept" disabled>
								<option value="" selected>Select Student Department</option>
							</select>
						</div>
						<input type="hidden" name="class_id" class="class_id" id="class_id">
						<br>			
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">
						<button type="submit" class="btn btn-success">Add subject</button>					
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
