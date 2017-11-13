<?php require_once '../core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);
include_once ROOT_PATH.'/includes/overall/overall_header.php';
?>

	<title>Teacher's | Home</title>



	<div id="page-content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12"  align="justify">

<?php	
Character::flash('home');
?>
					Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers Teachers

				</div>
			
			</div>
			
		</div>
		






	</div>

</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>