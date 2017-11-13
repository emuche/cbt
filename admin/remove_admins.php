<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db = DB::getInstance();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('admin', $user->data()->permission);
if (!$admin_id = Input::get('admin_id')) {
	Redirect::to('index.php');
}

$admin_class	= $db->get('admin', array('user_id', '=', $admin_id));
$admin_dept_info =  $admin_class->first();
if(!$admin_class->count()){
	Redirect::to('index.php');
}
$admin_info = new User($admin_id);
$admin_data = $admin_info->data();
?>

<title>Change admin Dept.</title>

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
			$admin_id	= Input::get('admin_id');


			try{

				$db->update('users', $admin_id, array(
					'permission' 	=> $permission, 
				));

				$db->delete('admin', array('user_id', '=', $admin_id));
				Session::delete('admin_id');
				Session::flash('admin_list', 'admin Removed Successfully');
				Redirect::to('admin_list.php');	
			

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
							<th>adminS's Name</th>
							<th>Permission</th>
							<th>Gender</th>
							<th>Phone No.</th>
							<th>State of Origin</th>
						</tr>
						<tr>
							<td><?php echo $admin_data->username;?></td>
							<td><?php echo $admin_data->last_name.' '.$admin_data->middle_name.' '.$admin_data->first_name;?></td>
							<td><?php echo $admin_data->permission;?></td>
							<td><?php echo $admin_data->gender;?></td>
							<td><?php echo $admin_data->phone_number;?></td>
							<td><?php echo $admin_data->state_of_origin;?></td>
						</tr>
					</table>
				</div>
				<br>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="admin_id"  id="admin_id" value="<?php echo $admin_id;?>">
						<div>
							<label for="permission" >Change Permission</label>
							<select name="permission" id="permission" class="form-control permission">
								<option value="">Select Permision</option>
								<option value="student">Student</option>
								<option value="admin">admin</option>
								<option value="management">Management</option>
								<option value="admin">Administrator</option>
							</select>
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						<button type="submit" class="btn btn-success">Update admin</button>					
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
