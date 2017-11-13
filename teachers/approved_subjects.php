<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
$token = Token::generate();

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

$db 			= DB::getInstance();
$teacher_infos 	= $db->get('teachers', array('user_id', '=', $_SESSION['user']))->all();
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
							<th>Approved</th>

						</tr>

						
<?php
		
		foreach ($teacher_infos as $teacher_info) {
			$subjects 		= $db->action('SELECT *', 'subject', array('id', '=', $teacher_info->subject_id), array('AND', 'approved', '=', '1'))->all();
			foreach ($subjects as $subject) {
				$session 		= $db->get('session', array('id', '=', $subject->session_id))->first();


			echo '<tr><td>'.Character::organize($subject->subject_name).'</td><td>'.Character::organize($subject->level).'</td><td>'.Character::organize($subject->dept).'</td><td>'.Character::organize($session->term).'</td><td>'.Character::organize($session->year);
?>
							<form action="check_exam.php" method="post">
								<input type="hidden" name="token" value="<?php echo $token;?>">	
								<input type="hidden" name="session_id" value="<?php echo $session_id;?>">
								<input type="hidden" name="level" value="<?php echo $subject->level;?>">
								<input type="hidden" name="dept" value="<?php echo $subject->dept;?>">
								<input type="hidden" name="subject_name" value="<?php echo $subject->subject_name;?>">
							</form>
							<td>
<?php
if ($subject->approved) {
	echo '<button type="button" class="btn btn-success">Approved</button>';
}else {
	echo '<button type="button" class="btn btn-danger">Not Approved</button>';
}
		

				
			} ?>								
							</td></tr>		
<?php } ?>		
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>