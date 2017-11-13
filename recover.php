<?php
require_once 'core/init.php';

if(Input::exists()){
	if(Token::check(Input::get('token'))){
include_once 'includes/overall/overall_header.php';


		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' 	=> true,
				'min'		=> 2,
				'max'		=> 20,
				)
			));
		if($validation->passed()){
			$username = Input::get('username');
			$user = new User($username);
			if(!$user->exists()){
				Character::warning('Username does not Exist');
			}else{
				Session::put('username', $username);
				Session::flash('recover_password', 'Answer your security Question correctly');
				Redirect::to('recover_password.php');
			}
		}else{
			Character::flash_error($validation->errors());
		}
	}
}
include_once 'includes/overall/overall_header.php';

?>
<title>Recover Password</title>
<h4 class="text-center">Recover Password</h4>
<div class="col-xs-10 col-sm-8 col-md-7 col-lg-5 divCenter text-center"  style="padding-top: 10px;">
	<form action="" method="post" class="form-horizontal">

		<div class="field">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control username" placeholder="Enter Username" value="<?php echo escape(Input::get('username'));?>">
		</div>
		<br>
		<input type="hidden" name="token" value="<?php echo $token ;?>">
		<button type="submit" class="btn btn-success">Check Username</button>
	</form>
</div>
		

<?php include_once 'includes/overall/overall_footer.php';?>
