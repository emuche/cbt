<?php
require_once 'core/init.php';
include_once 'includes/overall/overall_header.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');

}
?>

<title>Change Password</title>
	<div id="page-content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12"  align="justify">

<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' 	=> true,
				'min'		=> 6
				),
			'password_new' => array(
				'required' 	=> true,
				'min'		=> 6
				),
			'password_new_again' => array(
				'required' 	=> true,
				'min'		=> 6,
				'matches'	=> 'password_new'
				
				)
			));
		if($validation->passed()){
			// change of password
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
				echo 'Your current password is wrong!';
			}else{
				$salt = Hash::salt(32);
				$user->update(array(
					'password' 	=> Hash::make(Input::get('password_new'), $salt),
					'salt' 		=> $salt
					));
				Session::flash('home', 'Your password has been changed!');
				Redirect::to('index.php');
			}
		}else{
			Character::flash_error($validation->errors());
		}
	}
}
?>

	
					<div class="col-xs-11 col-sm-9 col-md-7 col-lg-6  divCenter text-center">
						<form action="" method="post" class="form-horizontal">

							<div class="">
								<br>
								<label for="password_current">Current Password</label>
								<input type="password" name="password_current" id="password_current" class="form-control" placeholder="Enter Current Password">
								<br>
							</div>

							<div class="field">
								<label for="password_new"> New Password</label>
								<input type="password" name="password_new" id="password_new" class="form-control" placeholder="Enter New Password">
								<br>
							</div>
							<div class="field">
								<label for="password_new_again">New Password Again</label>
								<input type="password" name="password_new_again" id="password_new_again" class="form-control" placeholder="Enter New password Again">
								<br>
							</div>
							<input type="hidden" name="token" value="<?php echo Token::generate();?>">
							<button type="submit" class="btn btn-success">Change Password</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php include_once 'includes/overall/overall_footer.php';?>
