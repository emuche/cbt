<?php
require_once 'core/init.php';
include_once 'includes/overall/overall_header.php';


if(!$username = Input::get('user')){
	Redirect::to('index.php');

}else{
	$user = new User($username);



	if(!$user->exists()){
		include_once 'includes/overall/overall_footer.php';

		Redirect::to(404);

	}else{
		$data = $user->data();



	}
?>
<title><?php echo  $username;?>'s  Profile</title>
<div id="page-content-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6">

				<h3><?php echo escape(Character::organize($data->username));?>'s Profile</h3>

				<br>
				<br>
				<br>
				<table class="table table-responsive table-hover">
					<tr>
						<th>Username</th>
						<td><?php echo escape(Character::organize($data->username));?></td>
					</tr>
					<tr>
						<th>First Name</th>
						<td><?php echo escape(Character::organize($data->first_name));?></td>
					</tr>
					<tr>
						<th>Middle Name</th>
						<td><?php echo escape(Character::organize($data->middle_name));?></td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td><?php echo escape(Character::organize($data->last_name));?></td>
					</tr>
					<tr>
						<th>Gender</th>
						<td><?php echo escape(Character::organize($data->gender));?></td>
					</tr>
					<tr>
						<th>Phone Number</th>
						<td><?php echo escape(Character::organize($data->phone_number));?></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><?php echo escape(Character::organize($data->email));?></td>
					</tr>
					<tr>
						<th>State of Origin</th>
						<td><?php echo escape(Character::organize($data->state_of_origin));?></td>
					</tr>
<?php
if ($data->permission == 'student') {
	$db = DB::getInstance();
	$student_infos = $db->get('student', array('user_id', '=', $data->id))->all();
	foreach ($student_infos as $student_info) {
?>	
	
					<tr>
						<th>Privillege</th>
						<td><?php echo escape(Character::organize($data->permission));?></td>
					</tr>
					<tr>
						<th>Section</th>
						<td><?php echo escape(Character::organize($student_info->section));?></td>
					</tr>
					<tr>
						<th>Level</th>
						<td><?php echo escape(Character::organize($student_info->level));?></td>
					</tr>
					<tr>
						<th>Department</th>
						<td><?php echo escape(Character::organize($student_info->dept));?></td>
					</tr>
					<tr>
						<th>Class Label</th>
						<td><?php echo escape(Character::organize($student_info->class_label));?></td>
					</tr>



<?php		
	}	
}
if ($data->permission == 'teachers') {
?>
					<tr>
						<th>Privillege</th>
						<td><?php echo escape(Character::organize('teacher'));?></td>
					</tr>
<?php
	$db = DB::getInstance();
	$query = $db->get('teachers', array('user_id', '=', $data->id));
	$check = $query->count();
	if ($check) {
		$student_infos = $query->all();
?>	
					<tr>
						<th>Post Held</th>
						<td>
<?php 

	foreach ($student_infos as $student_info) {
		echo escape(Character::organize($student_info->post_held).' || ');
	}
?>
		
						</td>
					</tr>
					<tr>
						<th>Department</th>
						<td>
<?php 
	foreach ($student_infos as $student_info) {
		echo escape(Character::organize($student_info->dept).' || ');
	}
?>
	
						</td>
					</tr>
					<tr>
						<th>Class</th>
						<td>
<?php 
	foreach ($student_infos as $student_info) {
		$classes = $db->get('class', array('id', '=', $student_info->class_id))->all();
		foreach ($classes as $class) {
			echo escape(Character::organize($class->level)).' '.escape(Character::organize($class->dept)).' '.escape(Character::organize($class->class_label)).' || ';
		}
	}
?>
							
						</td>
					</tr>
					<tr>
						<th>Subject(s)</th>
						<td>
<?php
	foreach ($student_infos as $student_info) { 
		$subjects = $db->get('subject', array('id', '=', $student_info->subject_id))->all();
		foreach ($subjects as $subject) {
			echo escape(Character::organize($subject->subject_name)).' For '.escape(Character::organize($subject->level)).' || ';
		}
	}	
?>

						</td>
					</tr>
<?php 
	}
}
if (($data->permission == 'admin')) {
?>
					<tr>
						<th>Privillege</th>
						<td><?php echo escape(Character::organize($data->permission));?></td>
					</tr>
<?php
	$db = DB::getInstance();
	$query = $db->get('admin', array('user_id', '=', $data->id));
	$check = $query->count();
	if ($check) {
		$student_info = $query->first();
?>	
					<tr>
						<th>Department</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->dept));
?>
						</td>
					</tr>
					<tr>
						<th>Post Held</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->post_held));
?>
						</td>
					</tr>
					<tr>
						<th>Section</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->section));
?>
						</td>
					</tr>

<?php 
	}
} 

if (($data->permission == 'manager')) {
?>
					<tr>
						<th>Privillege</th>
						<td><?php echo escape(Character::organize($data->permission));?></td>
					</tr>
<?php
	$db = DB::getInstance();
	$query = $db->get('manager', array('user_id', '=', $data->id));
	$check = $query->count();
	if ($check) {
		$student_info = $query->first();
?>	
					<tr>
						<th>Department</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->dept));
?>
						</td>
					</tr>
					<tr>
						<th>Post Held</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->post_held));
?>
						</td>
					</tr>
					<tr>
						<th>Section</th>
						<td>
<?php 
		echo escape(Character::organize($student_info->section));
?>
						</td>
					</tr>

<?php 
	}
} 
?>
				</table>



			</div>
		</div>
	</div>
</div>
	


<?php
}
include_once 'includes/overall/overall_footer.php';?>
