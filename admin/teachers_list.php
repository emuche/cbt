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
<title>Teachers List</title>

<div id="page-content-wrapper" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php
Character::flash('teachers_list');
?>
			<div class="col-xs-5"> 
			</div>
<?php

	$classes = $db->group_by('teachers','user_id', array('id', '>', '0'))->all();


?>
				<div class=" divCenter text-center col-xs-10" style="clear: both;">
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>FULL NAME</th>						
							<th>DEPARTMENT</th>	
							<th>POST HELD</th>				
							<th>USERNAME</th>				
							<th>PHONE NO.</th>				
							<th>CLASS</th>
							<th>SUBJECT</th>								
							<th>REMOVE AS TEACHER</th>				
							<th>EDIT TEACHER</th>				
							<th>DEACTIVATE</th>				
							<th>DELETE</th>				
						</tr>
						
<?php

		foreach ($classes as $class) {	
			$user = new User($class->user_id);
			$teachers_info = $user->data();
			$subjects = $db->get('subject', array('teacher_id', '=', $class->user_id))->all();
			$coarses = $db->get('class', array('teacher_id', '=', $class->user_id))->all();

			echo '<tr><td>'.Character::organize($teachers_info->last_name).' '.Character::organize($teachers_info->middle_name).' '.Character::organize($teachers_info->first_name).'</td><td>'.Character::organize($class->dept).'</td><td>'.Character::organize($class->post_held).'</td><td>'.Character::organize($teachers_info->username).'</td><td>'.Character::organize($teachers_info->phone_number).'</td><td>';
				foreach ($coarses as $coarse) {
					echo Character::organize($coarse->level).' '.Character::organize($coarse->dept).' '.Character::organize($coarse->class_label).'<br>';
				}
					echo '</td><td>';
				
				foreach ($subjects as $subject) {
					echo Character::organize($subject->subject_name).'<br>';
					
				}
?>


				</td>
				<td>
					<form action="remove_teachers.php" method="post">
						<input type="hidden" name="teachers_id" value="<?php echo $teachers_info->id;?>">

						<button type="submit" class="btn btn-warning">Remove</button>
					</form>
				</td>
				<td>
					<form action="edit_teachers.php" method="post">
						<input type="hidden" name="teachers_id" value="<?php echo $teachers_info->id;?>">

						<button type="submit" class="btn btn-info">Edit</button>
					</form>
				</td>

				<td>
					<form action="modify_teachers.php" method="post">
						<input type="hidden" name="deactivate" value="<?php echo $teachers_info->id;?>">
						<input type="hidden" name="token" value="<?php echo $token ;?>">

<?php
	if ($teachers_info->active) {
		echo '<button type="submit" class="btn btn-danger">Deactivate</button>';
		
	}else {
		echo '<button type="submit" class="btn btn-success">Activate</button>';
	}

?>

					</form>
				</td>
				<td>
					<form action="modify_teachers.php" method="post">
						<input type="hidden" name="delete" value="<?php echo $teachers_info->id;?>">
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

$delete_confirm = 'Are You sure You want delete this Teacher?';
include_once ROOT_PATH.'/includes/overall/overall_footer.php'; ?>