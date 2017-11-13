<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
$token = Token::generate();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('admin', $user->data()->permission);

$db = DB::getInstance();
?>
<title>Admin List</title>

<div id="page-content-wrapper" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php
Character::flash('admin_list');
?>
			<div class="col-xs-5"> 
			</div>
<?php

	$classes = $db->get('admin', array('id', '>', '0'))->all();

?>
				<div class=" divCenter text-center col-xs-10" style="clear: both;">
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>FULL NAME</th>						
							<th>DEPARTMENT</th>	
							<th>POST HELD</th>				
							<th>USERNAME</th>				
							<th>GENDER</th>				
							<th>PHONE NO.</th>				
							<th>DATE OF BIRTH</th>				
							<th>REMOVE AS admin</th>				
							<th>EDIT admin</th>				
							<th>DEACTIVATE</th>				
							<th>DELETE</th>				
						</tr>
						
<?php

		foreach ($classes as $class) {	
			$user = new User($class->user_id);
			$admin_info = $user->data();
			echo '<tr><td>'.Character::organize($admin_info->last_name).' '.Character::organize($admin_info->middle_name).' '.Character::organize($admin_info->first_name).'</td><td>'.Character::organize($class->dept).'</td><td>'.Character::organize($class->post_held).'</td><td>'.Character::organize($admin_info->username).'</td><td>'.Character::organize($admin_info->gender).'</td><td>'.Character::organize($admin_info->phone_number).'</td><td>'.Character::organize($admin_info->date_of_birth).'</td>'
?>
				<td>
					<form action="remove_admins.php" method="post">
						<input type="hidden" name="admin_id" value="<?php echo $admin_info->id;?>">

						<button type="submit" class="btn btn-warning">Remove</button>
					</form>
				</td>
				<td>
					<form action="edit_admins.php" method="post">
						<input type="hidden" name="admin_id" value="<?php echo $admin_info->id;?>">

						<button type="submit" class="btn btn-info">Edit</button>
					</form>
				</td>

				<td>
					<form action="modify_admins.php" method="post">
						<input type="hidden" name="deactivate" value="<?php echo $admin_info->id;?>">
						<input type="hidden" name="token" value="<?php echo $token ;?>">

<?php
	if ($admin_info->active) {
		echo '<button type="submit" class="btn btn-danger">Deactivate</button>';
		
	}else {
		echo '<button type="submit" class="btn btn-success">Activate</button>';
	}

?>

					</form>
				</td>
				<td>
					<form action="modify_admin.php" method="post">
						<input type="hidden" name="delete" value="<?php echo $admin_info->id;?>">
						<input type="hidden" name="token" value="<?php echo $token;?>">

						<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete">Delete</button>
					</form>
				</td>
			</td></tr>

<?php } ?>

					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<?php

$delete_confirm = 'Are You sure You want delete this admin?';
include_once ROOT_PATH.'/includes/overall/overall_footer.php'; ?>