<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';

$db = DB::getInstance();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');

}
Redirect::permission_check('admin', $user->data()->permission);

if (!$manager_id = Input::get('manager_id')) {
	Redirect::to('index.php');
}

$manager_class	= $db->get('manager', array('user_id', '=', $manager_id));
$manager_dept_info =  $manager_class->first();
if(!$manager_class->count()){
	Redirect::to('index.php');
}
$manager_info = new User($manager_id);
$manager_data = $manager_info->data();
?>

<title>Change manager Dept.</title>

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
			$manager_id	= Input::get('manager_id');


			try{

				$db->update('users', $manager_id, array(
					'permission' 	=> $permission, 
				));

				$db->delete('manager', array('user_id', '=', $manager_id));
				Session::delete('manager_id');
				Session::flash('manager_list', 'manager Removed Successfully');
				Redirect::to('manager_list.php');	
			

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
							<th>MANAGERS's Name</th>
							<th>Permission</th>
							<th>Gender</th>
							<th>Phone No.</th>
							<th>State of Origin</th>
						</tr>
						<tr>
							<td><?php echo $manager_data->username;?></td>
							<td><?php echo $manager_data->last_name.' '.$manager_data->middle_name.' '.$manager_data->first_name;?></td>
							<td><?php echo $manager_data->permission;?></td>
							<td><?php echo $manager_data->gender;?></td>
							<td><?php echo $manager_data->phone_number;?></td>
							<td><?php echo $manager_data->state_of_origin;?></td>
						</tr>
					</table>
				</div>
				<br>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="manager_id"  id="manager_id" value="<?php echo $manager_id;?>">
						<div>
							<label for="permission" >Change Permission</label>
							<select name="permission" id="permission" class="form-control permission">
								<option value="">Select Permision</option>
								<option value="student">Student</option>
								<option value="manager">manager</option>
								<option value="management">Management</option>
								<option value="admin">Administrator</option>
							</select>
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo Token::generate();?>">
						<button type="submit" class="btn btn-success">Update manager</button>					
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
