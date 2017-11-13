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

$student_class	= $db->get('student', array('user_id', '=', $student_id));
$student_dept_info =  $student_class->first();
if(!$student_class->count()){
	Redirect::to('index.php');
}
$student_info = new User($student_id);
$student_data = $student_info->data();
?>

<title>Change student Dept.</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'permission' => array(
							'required' 	=> true
						)
			));

		
		if($validation->passed()){

			$permission		= Input::get('permission');	
			$student_id		= Input::get('student_id');


			try{

				$db->update('users', $student_id, array(
					'permission' 	=> $permission, 
				));

				$db->delete('student', array('user_id', '=', $student_id));
				Session::delete('student_id');
				Session::flash('student_list', 'Student permission Changed Successfully');
				Redirect::to('student_list.php');	
			

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
							<th>Student's Name</th>
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
				<br>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="student_id"  id="student_id" value="<?php echo $student_id;?>">
						<div>
							<label for="permission" >Change Permission</label>
							<select name="permission" id="permission" class="form-control permission">
								<option value="">Select Permision</option>
								<option value="student">Student</option>
								<option value="student">Teacher</option>
								<option value="management">Management</option>
								<option value="admin">Administrator</option>
							</select>
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						<button type="submit" class="btn btn-success">Update Student</button>					
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
