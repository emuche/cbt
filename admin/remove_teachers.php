<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db = DB::getInstance();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');

}
Redirect::permission_check('admin', $user->data()->permission);
if (!$teachers_id = Input::get('teachers_id')) {
	Redirect::to('index.php');
}

$teachers_class	= $db->get('teachers', array('user_id', '=', $teachers_id));
$teachers_dept_info =  $teachers_class->first();
if(!$teachers_class->count()){
	Redirect::to('index.php');
}
$teachers_info = new User($teachers_id);
$teachers_data = $teachers_info->data();
?>

<title>Change Teachers Dept.</title>

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
			$teachers_id	= Input::get('teachers_id');


			try{

				$db->update('users', $teachers_id, array(
					'permission' 	=> $permission, 
				));

				$db->delete('teachers', array('user_id', '=', $teachers_id));
				Session::delete('teachers_id');
				Session::flash('teachers_list', 'Teacher Removed Successfully');
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
						<input type="hidden" name="teachers_id"  id="teachers_id" value="<?php echo $teachers_id;?>">
						<div>
							<label for="permission" >Change Permission</label>
							<select name="permission" id="permission" class="form-control permission">
								<option value="">Select Permision</option>
								<option value="student">Student</option>
								<option value="teachers">Teacher</option>
								<option value="management">Management</option>
								<option value="admin">Administrator</option>
							</select>
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						<button type="submit" class="btn btn-success">Update teacher</button>					
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
