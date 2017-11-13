<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db = DB::getInstance();
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');

}
Redirect::permission_check('admin', $user->data()->permission);
if (!$teachers_id = Session::get('teachers_id')) {
	Redirect::to('index.php');
}
$teachers_info = new User($teachers_id);
$teachers_data = $teachers_info->data();
?>
<title>Add Teacher</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'student_level' => array(
							'required' 	=> true
						),
						
						'class_label' => array(
							'required' 	=> true
						),
						'subject' => array(
							'required' 	=> true
						),
						'post_held' => array(
							'required' 	=> true
						),
						'teachers_department' => array(
							'required' 	=> true
						),
			));

		
		if($validation->passed()){

			$subject_id		= Input::get('subject');	
			$class_id		= Input::get('class_label');	
			$post_held		= Input::get('post_held');	
			$dept			= Input::get('teachers_department');	
			$teachers_id	= Input::get('teachers_id');	



			try{

				$db->insert('teachers', array(
					'subject_id' 	=> $subject_id, 
					'dept' 			=> $dept, 
					'class_id' 		=> $class_id, 
					'post_held' 	=> $post_held, 
					'user_id' 		=> $teachers_id,
				));

				$db->update('users', $teachers_id, array(
					'permission' => 'teachers'));

				$db->update('subject', $subject_id, array(
					'teacher_id' => $teachers_id,
					'class_id'	 => $class_id

				$db->update('class', $class_id, array(
					'teacher_id' => $teachers_id,

				));

				Session::delete('teachers_id');
				Session::flash('teachers_list', 'Teacher Added Successfully');
				Redirect::to('teachers_list.php');	
			

			}catch (Exception $e ){
				die($e->getMessage());
			
			}

		}else{
			Character::flash_error($validation->errors());
		}
	}
}

?>

				<div>
					<table class="table table-hover table-responsive table-bordered">
						<tr>
							<th>Username</th>
							<th>Teacher's Name</th>
							<th>Permission</th>
							<th>Gender</th>
							<th>Phone No.</th>
							<th>State of Origin</th>
						</tr>
						<tr>
							<td><?php echo $teachers_data->username;?></td>
							<td><?php echo $teachers_data->last_name.' '.$teachers_data->middle_name.' '.$teachers_data->first_name;?></td>
							<td><?php echo $teachers_data->permission;?></td>
							<td><?php echo $teachers_data->gender;?></td>
							<td><?php echo $teachers_data->phone_number;?></td>
							<td><?php echo $teachers_data->state_of_origin;?></td>
						</tr>
					</table>
				</div>
				<br>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">


					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="teachers_id"  id="teachers_id" value="<?php echo $teachers_id;?>">
						<div class="divCenter teachers_section_div">
							<label for="section">Student Section</label>
							<select class="form-control section" id="section" name="section">
								<option value="" selected>Select Student Section</option>

<?php
$sections = $db->group_by('class', 'section')->all();
foreach ($sections as $section) {
	echo '<option value= "'.$section->section.'">'.$section->section.'</option>';
	
}

?>
							</select>
						</div>
						<br>


						<div class="level_div">
							<label for="level">Select Student Level</label>
							<select class="form-control level" id="level" name="student_level">
								<option value=""  selected>Select Student Level</option>
								
							</select>
						</div>
						<br>
						<div class="dept_div">
							<label for="teachers_dept">Select Student Department</label>
							<select class="form-control student_dept" id="student_dept" name="student_dept" >
								<option value="" selected>Select Student Department</option>
								
							</select>								
						</div>
						<br>			
						<div class="class_label_div">
							<label for="label">Select Student Class Label</label>
							<select name="class_label" class="form-control class_label" id="class_label" >
								<option value="" selected >Select Class Label</option>
							</select>
						</div>
						<br>
						<div class="subject_div">
							<label for="subject">Select Student Subject</label>
							<select class="form-control subject" id="subject" name="subject" >
								<option value=""  selected>Select Subject</option>
								
							</select>
						</div>
						<br>	
						<div class="post_held_div">
							<label for="post_held">Post Held by Teachers</label>
								<input type="text" name="post_held" class="form-control post_held" id="post_held" value="<?php echo Input::get('post_held')?>">
						</div>
						<br>
						<div class="teachers_department_div">
							<label for="teachers_department">Teacher Department</label>
								<input type="text" name="teachers_department" class="form-control teachers_department" id="teachers_department" value="<?php echo Input::get('teachers_department')?>">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Add teachers</button>					
					</form>
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
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
