<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
?>
<title>Add Class</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'section' => array(
							'required' 	=> true,
						), 
						'class_label' => array(
							'required'  => true,
							'max'		=> 50
						), 
						'level'	=> array(
							'required'		=> true,
						)
			));
		if($validation->passed()){
			$user = new User();

			try{

				$db = DB::getInstance();
				$db->insert('class', array(

					'section'			=> Input::get('section'),	
					'class_label'		=> strtoupper(Input::get('class_label')),
					'level'				=> Input::get('level'),
					'dept'				=> Input::get('dept'),
					'teacher_id'		=> $_SESSION['user']

					));

					Session::flash('class_list', ' Class Added successfully');
					Redirect::to('class_list.php?level='.Input::get('level').'&dept='.Input::get('dept'));

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

						<div class="divCenter student_section_div">
							<label for="student_section">Select Student Section</label>
							<select class="form-control student_section" id="student_section" name="section">
								<option value="" disabled selected>Select Student Section</option>
								<option value="senior high">Senior High</option>
								<option value="junior high">Junior High</option>
								<option value="primary">Primary</option>
								<option value="nursery">Nursery</option>
							</select>
						</div>
						<br>
						<div class="divCenter student_level_div">
							<label for="student_level">Select Student Level</label>
							<select name="level" class="form-control student_level" id="student_level">
								<option selected value="">Select Student Level</option>
							</select>
						</div>
						<br>
						<div class="divCenter student_dept_div">
							<label for="">Select Student Department</label>
							<select name="dept" id="dept" class="form-control dept">
								<option selected value="">Select Student Department</option>
							</select>
						</div>
						<br>												
						<div class="divCenter ">
							<label for="">Enter Student Class Label</label>
							<input type="text" name="class_label" id="" class="form-control" maxlength="50">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Add Class</button>					
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
