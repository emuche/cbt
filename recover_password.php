<?php
require_once 'core/init.php';

?>

<title>Recover Password</title>

<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){
include_once 'includes/overall/overall_header.php';
		$username 			= Input::get('username');
		$security_answer 	= Input::get('security_answer');
		$password_new 		= Input::get('new_password');
		$password_new_again = Input::get('password_new_again');

		$user = new User($username);
		$data = $user->data();


		$validate = new Validate();
		if ($data->security_answer !== $security_answer) {
			$validate->add_error('Wrong Security Question Answer');
			Session::flash('home', 'Wrong Security Question Answer');
			//Redirect::to('index.php');
			
		}
		$validate = new Validate();

		$validation = $validate->check($_POST, array(
			'new_password' => array(
				'required' 	=> true,
				'min'		=> 6,
				'matches'	=> 'password_new_again'

				),
			'password_new_again' => array(
				'required' 	=> true,
				'min'		=> 6,
				),
			'security_answer' => array(
				'required' 	=> true,
				'max'		=> 100
				)
			));

		
		if($validation->passed()){

			if ($data->security_answer !== $security_answer) {
				Session::flash('recover_password', 'Wrong Security Question Answer');
				Session::put('username', $username);
				Redirect::to('recover_password.php');
				die();
			
			}
			$db = DB::getInstance();
			$salt = Hash::salt(32);
			$user_id = $data->id;
			$db->update('users', $user_id, array(
				'password' 	=> Hash::make($password_new, $salt),
				'salt' 		=> $salt
				));
			Session::flash('home', 'Your password has been changed!');
			Redirect::to('index.php');
			
		}else{
			Character::flash_error($validation->errors());
		}
	}
}



if (!Session::get('username')) {
	Redirect::to('index.php');			
}

include_once 'includes/overall/overall_header.php';

$username = Session::get('username');
Session::delete('username');
$user = new User($username);
$data = $user->data();
?>

	
					<div class="col-xs-11 col-sm-9 col-md-7 col-lg-6  divCenter text-center">
<?php
Character::flash('recover_password');
?>

						<form action="" method="post" class="form-horizontal">
						<br>
						<div class="col-xs-12" style="margin-bottom: 30px; z-index: 1;"><h4 class="alert alert-info col-xs-6 divCenter text-center fade in">
						<?php echo $data->security_question ;?>
							
						</h4></div>

						<br>
						<input type="hidden" name="username" value="<?php echo $username;?>">
						<div class="field">
							<label for="security_answer">Security Question Answer</label>
							<input type="text" name="security_answer" id="security_answer" class="form-control security_answer" placeholder="Enter Your Answer" maxlength="100" value="<?php echo escape(Input::get('security_answer'));?>">
						</div>
						<br>
							<div class="field">
								<label for="password_new"> New Password</label>
								<input type="password" name="new_password" id="password_new" class="form-control" placeholder="Enter New Password">
							</div>
							<br>
							<div class="field">
								<label for="password_new_again">New Password Again</label>
								<input type="password" name="password_new_again" id="password_new_again" class="form-control" placeholder="Enter New password Again">
							</div>
							<br>
							<input type="hidden" name="token" value="<?php echo $token;?>">
							<button type="submit" class="btn btn-success">Change Password</button>
						</form>
					</div>
				
<?php include_once 'includes/overall/overall_footer.php';?>
