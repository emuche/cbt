<?php require_once '../core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
include_once ROOT_PATH.'/includes/overall/overall_header.php';

?>

	<title>Student | Home</title>



	<div id="page-content-wrapper">
		<div class="container-fluid">
			<div class="">
				<div class="col-xs-12"  align="justify">
				
<?php
Character::flash('home');
?>
					Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students Students 
				</div>
			
			</div>
			
		</div>
	</div>


	

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
