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

						'section' => array(
							'required' 	=> true
						),
						'post_held' => array(
							'required' 	=> true
						),
						'manager_department' => array(
							'required' 	=> true
						),
			));

		
		if($validation->passed()){

			$post_held		= Input::get('post_held');	
			$dept			= Input::get('manager_department');	
			$manager_id		= Input::get('manager_id');




			try{

				$db->update_table('manager', array('user_id', '=', $manager_id), array(
					'dept' 			=> $dept, 
					'post_held' 	=> $post_held, 
					'user_id' 		=> $manager_id,
				));
				$db->update('users', $manager_id, array(
					'permission' 		=> 'manager'
				));
				
				Session::delete('manager_id');
				Session::flash('manager_list', 'manager Updated Successfully');
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
							<th>manager's Name</th>
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


					<form action="" method="post" class="form-horizontal">
						<input type="hidden" name="manager_id"  id="manager_id" value="<?php echo $manager_id;?>">
						<div class="divCenter manager_section_div">
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
							<label for="post_held">Post Held by manager</label>
								<input type="text" name="post_held" class="form-control post_held" id="post_held" value="<?php echo $post = Input::get('post_held') ? Input::get('post_held') : $manager_dept_info->post_held;?>">
						</div>
						<br>
						<div class="manager_department_div">
							<label for="manager_department">manager Department</label>
								<input type="text" name="manager_department" class="form-control manager_department" id="manager_department" value="<?php echo $post = Input::get('manager_department') ? Input::get('manager_department') : $manager_dept_info->dept;?>">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

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
