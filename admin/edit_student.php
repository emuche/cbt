<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db = DB::getInstance();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');

}
Redirect::permission_check('admin', $user->data()->permission);
if (!$student_id = Input::get('student_id')) {
	Redirect::to('index.php');
}

$student_class 	= $db->get('student', array('user_id', '=', $student_id)); 
$student_class_info = $student_class->first(); 
if(!$student_class->count()){
	Redirect::to('index.php');
}
$student_info = new User($student_id);
$student_data = $student_info->data();

?>



<title>Change Student Class</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">

<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'student_section' => array(
							'required' 	=> true
						),
						'student_level' => array(
							'required' 	=> true
						),
						'student_dept' => array(
							'required' 	=> true
						),
						'class_label' => array(
							'required' 	=> true
						)
			));

		
		if($validation->passed()){

			$section		= Input::get('student_section');	
			$student_id		= Input::get('student_id');	
			$level			= Input::get('student_level');	
			$dept 			= Input::get('student_dept');	
			$class_id		= Input::get('class_label');	
			$post_held		= Input::get('post_held');	


			$class 		 			= $db->get('student', array('user_id', '=', $student_id))->first();
			$class_label			= $class->class_label;
			$student_table_id		= $class->id;

			try{

				$db->update('student', $student_table_id, array(
					'section' 		=> $section, 
					'level' 		=> $level, 
					'dept' 			=> $dept, 
					'class_label' 	=> $class_label, 
					'class_id' 		=> $class_id, 
					'post_held' 	=> $post_held, 
					'user_id' 		=> $student_id 
				));
				$db->update('users', $student_id, array(
					'permission' 		=> 'student'
				));

				Session::delete('student_id');
				Session::flash('student_list', 'Student class Updated Successfully');
				Redirect::to('student_list.php?level='.$level.'&dept='.$dept.'&class_label='.$class_id);	
			

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

?>

				<div>
					<table class="table table-hover table-responsive table-bordered">
						<tr>
							<th>Username</th>
							<th>Student Name</th>
							<th>Permission</th>
							<th>Gender</th>
							<th>Phone No.</th>
							<th>State of Origin</th>
						</tr>
						<tr>
							<td><?php echo $student_data->username;?></td>
							<td><?php echo $student_data->last_name.' '.$student_data->middle_name.' '.$student_data->first_name;?></td>
							<td><?php echo $student_data->permission;?></td>
							<td><?php echo $student_data->gender;?></td>
							<td><?php echo $student_data->phone_number;?></td>
							<td><?php echo $student_data->state_of_origin;?></td>
						</tr>
					</table>
				</div>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">


					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="student_id"  id="student_id" value="<?php echo $student_id;?>">
						<div class="divCenter student_section_div">
							<label for="student_section">Student Section</label>
							<select class="form-control section" id="section" name="student_section">
								<option value="" selected="" disabled>Select Student Section</option>

<?php
$sections = $db->group_by('class', 'section')->all();
foreach ($sections as $section) {
	echo '<option value= "'.$section->section.'">'.$section->section.'</option>';
	
}

?>
							</select>
							<br>
						</div>

						<div class="level_div">
							<label for="level">Select Student Level</label>
							<select class="form-control level" id="level" name="level">
								<option value="" selected>Select Student Level</option>
								
							</select>
						</div>
						<br>
						<div class="dept_div">
							<label for="dept">Select Student Department</label>
							<select class="form-control dept" id="dept" name="dept">
								<option value=""  selected>Select Student Department</option>
								
							</select>								
						</div>
						<br>				
						<div class="class_label_div">
							<label for="class_label">Select Student Class Label</label>
							<select class="form-control class_label" id="class_label" name="class_label">
								<option value=""  selected>Student Class Label</option>
								
							</select>								
						</div>
						<br>
						<div>
							<label for="post_held">Post Held by Student</label>
								<input type="text" name="post_held" class="form-control post_held" id="post_held" value="<?php echo $post = Input::get('post_held') ? Input::get('post_held') : $student_class_info->post_held;?>">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Update Student</button>					
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
