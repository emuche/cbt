<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';
?>
<title>Add Session</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">

<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'year' => array(
							'required' 	=> true
						), 
						'term' => array(
							'required'  => true
						)
			));
		if($validation->passed()){
			$user = new User();

			try{

				$db = DB::getInstance();
				$db->insert('session', array(

					'year'	=> Input::get('year'),	
					'term'	=> Input::get('term')

					));

					Session::flash('home', 'Session Added successfully');
					Redirect::to('session_list.php');

			}catch (Exception $e ){
				die($e->getMessage());
			
			}

		}else{
			Character::flash_error($validation->errors());
		}
	}
}

?>
				<h1 class="text-center">Add New Session</h1>
				<br>
				<br>
				<div class="divCenter text-center col-xs-5">
						<form action="" method="post" class="form-horizontal" role="form">

							<div class="divCenter">
								<label for="year">Enter Session Year in th Correct Format "YYYY/YYYY"</label>
								<input type="text" name="year" id="year" class="form-control" placeholder='Enter Session Year in th Correct Format "YYYY/YYYY"'>
								<br>
							</div>

													
								<label for="term">Select Session Term</label>
									<select class="form-control" id="term" name="term">
										<option value="">Select Session Term</option>
										<option value="first term">First Term</option>
										<option value="second term">Second Term</option>
										<option value="third term">Third Term</option>
									</select>

								<br>	
								<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

								<button type="submit" class="btn btn-success">Add Session</button>			
							</div>
									
						</form>
					</div>


			</div>
		</div>
	</div>
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>