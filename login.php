<?php
require_once 'core/init.php';



$user = new User();
if($user->isLoggedIn()){
	$user_permission = $user->data()->permission;
	Redirect::to($user_permission);
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		include_once 'includes/overall/overall_header.php';
		echo '<br>';


		$validate 		= new Validate();
		$validation 	= $validate->check($_POST, array(
			'username'	=> array('required' => true),
			'password'	=> array('required' => true) 
			));
		if($validation->passed()){
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false ;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);
			if($login){
				$active = $user->data()->active;
				if (!$active) {
					Session::flash('home', 'Your Account has not been Activated!');
					Redirect::to('logout.php');	
				}
				$user_permission = $user->data()->permission;
				Redirect::to($user_permission);
			}else{

Character::warning('Sorry, we couldn\'t log you in <br> Incorrect Username or/and Password');
			}
		}else{
			Character::flash_error($validation->errors());
		}

	}
}
include_once 'includes/overall/overall_header.php';

?>
<title>Login Page</title>
<div class="col-xs-10 col-sm-8 col-md-6 col-lg-5 divCenter text-center" style="padding-top: 10px;">

	<form action="" method="post" class="form-horizontal">
		<div class="field">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" autocomplete="off">
			<br>
		</div>
		<div class="field">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" autocomplete="off">
			<br>
		</div>
		<div class="field row">
			<div class="col-sm-6">
				
				<label > <input type="checkbox" name="remember" > Remember Me</label>
			</div>
			<div class="col-sm-6">
				<label for="recover"><a href="recover.php" class="recover" id="recover" style="color: #333; text-decoration: none;">Forgot Password?</a></label>
			</div>
		</div>
		<br>
		
		<input type="hidden" name="token" value="<?php echo  $token;?>">
		<button class="btn btn-success" type="submit">Log In</button>
		
	</form>

</div>
<?php include_once 'includes/overall/overall_footer.php';?>
