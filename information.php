<?php
require_once 'core/init.php';
include_once 'includes/overall/overall_header.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');

}
?>

<title>Announcement</title>
	<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12"  align="justify">













				</div>
			</div>
		</div>
	</div>

<?php include_once 'includes/overall/overall_footer.php';?>
