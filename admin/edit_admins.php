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

						'section' => array(
							'required' 	=> true
						),
						'post_held' => array(
							'required' 	=> true
						),
						'admin_department' => array(
							'required' 	=> true
						),
			));

		
		if($validation->passed()){

			$post_held		= Input::get('post_held');	
			$dept			= Input::get('admin_department');	
			$admin_id		= Input::get('admin_id');




			try{

				$db->update_table('admin', array('user_id', '=', $admin_id), array(
					'dept' 			=> $dept, 
					'post_held' 	=> $post_held, 
					'user_id' 		=> $admin_id,
				));
				$db->update('users', $admin_id, array(
					'permission' 		=> 'admin'
				));

				
				Session::delete('admin_id');
				Session::flash('admin_list', 'admin Updated Successfully');
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
							<th>admin's Name</th>
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


					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="admin_id"  id="admin_id" value="<?php echo $admin_id;?>">
						<div class="divCenter admin_section_div">
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
						<div class="post_held_div">
							<label for="post_held">Post Held by admin</label>
								<input type="text" name="post_held" class="form-control post_held" id="post_held" value="<?php echo $post = Input::get('post_held') ? Input::get('post_held') : $admin_dept_info->post_held;?>">
						</div>
						<br>
						<div class="admin_department_div">
							<label for="admin_department">admin Department</label>
								<input type="text" name="admin_department" class="form-control admin_department" id="admin_department" value="<?php echo $post = Input::get('admin_department') ? Input::get('admin_department') : $admin_dept_info->dept;?>">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

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
