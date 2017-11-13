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
<title>Student List</title>

<div id="page-content-wrapper" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


<?php Character::flash('student_list'); ?>





<div class="container col-sm-6 col-md-6  text-center divCenter center-block" style="z-index: 1; clear: both;">
	<div class="row">
		<ul id="nav">
<?php
$sections = $db->group_by('class', 'section', array('id', '>', 0))->all();
foreach ($sections as $section) {
	echo '<li><a>'.$section->section.' &emsp;&emsp;<span class="glyphicon glyphicon-menu-down"></span></a>';	
	echo '<ul>';
	$depts = $db->group_by('class', 'dept', array('section', '=', $section->section))->all();
	foreach ($depts as $dept) {
		echo '<li><a>'.$dept->dept.'&emsp;&emsp;&emsp;&emsp;<span class="glyphicon glyphicon-menu-right"></span></a>';
		echo '<ul>';
		$levels = $db->group_by('class', 'level', array('dept', '=', $dept->dept), array('AND', 'section', '=', $section->section))->all();
		foreach ($levels as $level) {
			echo '<li><a>'.$level->level.'&emsp;&emsp;&emsp;&emsp;<span class="glyphicon glyphicon-menu-right"></span></a>';
			echo '<ul>';
			$class_labels = $db->action('SELECT *', 'class', array('level', '=', $level->level), array('AND', 'dept', '=', $dept->dept))->all();

			foreach ($class_labels as $class_label) {
				echo '<li><a href="student_list.php?level='.$level->level.'&dept='.$dept->dept.'&class_label='.$class_label->id.'">'.$class_label->class_label.'</a></li>';
				
			}
			echo '</ul></li>'; 
		}
		echo '</ul></li>';
	}
	echo '</ul></li>';
}

?>
		</ul>
	</div>
</div>
<br>


<?php
$dept = Input::get('dept') ? Input::get('dept') : 'none';

if ((Input::get('level') || Input::get('dept')) && (Input::get('class_label'))) {

	$classes = $db->get('student', array('class_id', '=', Input::get('class_label')))->all();


?>
				<div class=" divCenter text-center col-xs-11" style="clear: both;">
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>FULL NAME</th>						
							<th>LEVEL</th>						
							<th>DEPARTMENT</th>	
							<th>CLASS</th>											
							<th>POST HELD</th>				
							<th>USERNAME</th>				
							<th>GENDER</th>				
							<th>PHONE NO.</th>				
							<th>DATE OF BIRTH</th>
							<th>REMOVE AS STUDENT</th>								
							<th>CHANGE CLASS</th>				
							<th>DEACTIVATE</th>				
							<th>DELETE</th>				
						</tr>
						
<?php

		foreach ($classes as $class) {	
			$user = new User($class->user_id);
			$student_info = $user->data();
			echo '<tr><td>'.Character::organize($student_info->last_name).' '.Character::organize($student_info->middle_name).' '.Character::organize($student_info->first_name).'</td><td>'.Character::organize($class->level).'</td><td>'.Character::organize($class->dept).'</td><td>'.Character::organize($class->class_label).'</td><td>'.Character::organize($class->post_held).'</td><td>'.Character::organize($student_info->username).'</td><td>'.Character::organize($student_info->gender).'</td><td>'.Character::organize($student_info->phone_number).'</td><td>'.Character::organize($student_info->date_of_birth).'</td>'
?>
				<td>
					<form action="remove_student.php" method="post">
						<input type="hidden" name="student_id" value="<?php echo $student_info->id;?>">

						<button type="submit" class="btn btn-warning">Remove</button>
					</form>
				</td>
				<td>
					<form action="edit_student.php" method="post">
						<input type="hidden" name="student_id" value="<?php echo $student_info->id;?>">

						<button type="submit" class="btn btn-info">Edit</button>
					</form>
				</td>

				<td>
					<form action="modify_student.php" method="post">
						<input type="hidden" name="deactivate" value="<?php echo $student_info->id;?>">
						<input type="hidden" name="token" value="<?php echo $token ;?>">

<?php
	if ($student_info->active) {
		echo '<button type="submit" class="btn btn-danger">Deactivate</button>';
		
	}else {
		echo '<button type="submit" class="btn btn-success">Activate</button>';
	}

?>

					</form>
				</td>
				<td>
					<form action="modify_student.php" method="post">
						<input type="hidden" name="delete" value="<?php echo $student_info->id;?>">
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
}
$delete_confirm = 'Are You sure you want delete this student?';
include_once ROOT_PATH.'/includes/overall/overall_footer.php'; ?>