<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);

$db = DB::getInstance();
$token =  Token::generate();
?>
<title>Subject List</title>

<div id="page-content-wrapper" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


<?php Character::flash('subject_list'); ?>
<div class="container col-xs-12 col-sm-8 col-md-8  text-center divCenter center-block" style="z-index: 1; clear: both;">
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
		echo '<li><a href="subject_list.php?level='.$level->level.'&dept='.$dept->dept.'">'.$level->level.'</a></li>';
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
$dept = Input::get('dept') ? Input::get('dept') : 'general';
if (Input::get('level') || Input::get('dept')) {

	$db = DB::getInstance();
	$classes = $db->action('SELECT * ','subject', array('level', '=', Input::get('level')), array('AND', 'dept', '=', $dept));


?>
				<div class=" divCenter text-center col-xs-10" style="clear: both;">
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>SUBJECT</th>						
							<th>SECTION</th>						
							<th>CLASS</th>						
							<th>DEPARTMENT</th>					
							<th>TERM</th>					
							<th>SESSION</th>					
							<th>TEACHER</th>				
							<th>DELETE</th>				
						</tr>
						
<?php

		foreach ($classes->all() as $class) {	
			$session = $db->get('session', array('id', '=', $class->session_id))->first();	

			$teacher_exist = $db->get('users', array('id', '=', $class->teacher_id));
			if ($teacher_exist->count()) {
				$teacher_first_name = $teacher_exist->first()->first_name;
				$teacher_middle_name = $teacher_exist->first()->middle_name;
				$teacher_last_name = $teacher_exist->first()->last_name;
				
			}else {
				$teacher_first_name = '';
				$teacher_middle_name = '';
				$teacher_last_name = '';
				
			}
			echo '<tr><td>'.Character::organize($class->subject_name).'</td><td>'.Character::organize($class->section).'</td><td>'.Character::organize($class->level).'</td><td>'.Character::organize($class->dept).'</td><td>'.Character::organize($session->term).'</td><td>'.Character::organize($session->year).'</td><td>'.Character::organize($teacher_last_name).' '.Character::organize($teacher_middle_name).' '.Character::organize($teacher_first_name).'</td><td>';

?>

				<form action="delete_subject.php" method="post">
					<input type="hidden" name="token" value="<?php echo $token ;?>">
					<input type="hidden" name="subject_id" value="<?php echo $class->id; ?>">
					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete">Delete</button>
					
				</form>










<?php
			echo '</td></tr>';			
		}
?>				

					</table>
				</div>

<?php } ?>

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

<?php 
$delete_confirm = 'Are you sure you want delete this Subject?';
include_once ROOT_PATH.'/includes/overall/overall_footer.php'; 
?>