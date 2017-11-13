<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
$token = Token::generate();


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);

if (!Input::get('level') || !Input::get('dept') || !Input::get('session_id')) {
	Redirect::to('index.php');
}
$level 			= Input::get('level');
$dept 			= Input::get('dept');
$session_id 	= Input::get('session_id');


$db 		= DB::getInstance();
$subjects 	= $db->action('SELECT *', 'subject', array('level', '=', $level), array('AND', 'dept', '=', $dept), array('AND', 'session_id', '=', $session_id))->all();
$session 	= $db->get('session', array('id', '=', $session_id))->first();
?>
<title>Subject List</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


				<div class="divCenter text-center col-xs-12">
					<h3 class="text-center">Subject List</h3>


<?php
Character::flash('approval_list');
?>
				
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>Subject Name</th>
							<th>Level</th>
							<th>Dept.</th>
							<th>Term</th>
							<th>Year</th>
							<th>View Exam</th>
							<th>Approved</th>

						</tr>

						
<?php
		
		foreach ($subjects as $subject) {		
			echo '<tr><td>'.Character::organize($subject->subject_name).'</td><td>'.Character::organize($subject->level).'</td><td>'.Character::organize($subject->dept).'</td><td>'.Character::organize($session->term).'</td><td>'.Character::organize($session->year).'</td><td>';
?>
							<form action="check_exam.php" method="post">
								<input type="hidden" name="token" value="<?php echo $token;?>">	
								<input type="hidden" name="session_id" value="<?php echo $session_id;?>">
								<input type="hidden" name="level" value="<?php echo $subject->level;?>">
								<input type="hidden" name="dept" value="<?php echo $subject->dept;?>">
								<input type="hidden" name="subject_name" value="<?php echo $subject->subject_name;?>">
								<button type="submit" class="btn btn-warning">View Subject </button>	
							</form>
							<td>
<?php
if ($subject->approved) {
	echo '<button type="button" class="btn btn-success">Approved</button>';
}else {
	echo '<button type="button" class="btn btn-danger">Not Approved</button>';
}
?>								
							</td></tr>		
<?php } ?>		
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>