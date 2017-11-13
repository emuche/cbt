<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
?>
<title>Add Session</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


				<div class="divCenter text-center col-xs-12 col-sm-10 col-md-8 col-lg-6">
					<h3 class="text-center">Session List</h3>


<?php

if(Session::exists('home')){

?>
  <div class="col-xs-12" style="margin-bottom: 30px; z-index: 1;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>
<?php
	echo Session::flash('home');
?>
  </h4></div>
<?php
}
?>
				
					<table class="table table-striped table-hover table-bordered table-responsive table-inverse">
						<tr>
							<th>Year</th>
							<th>Term</th>
						</tr>

						
<?php
		$db 		= DB::getInstance();
		$sessions 	= $db->get('session', array('id', '>', 0));

		foreach ($sessions->all() as $session) {		
			echo '<tr><td>'.$session->year.'</td><td>'.Character::organize($session->term).'</td></tr>';			
		}
?>		
					</table>

				</div>
			</div>
		</div>
	</div>
</div>

<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>