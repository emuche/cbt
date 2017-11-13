<?php
require_once 'core/init.php';
include_once 'includes/overall/overall_header.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('index.php');

}
?>

<title>Change Security</title>
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
			'new_answer' => array(
				'required' 	=> true,
				'max'		=> 100
				),
			'new_question' => array(
				'required' 	=> true,
				'max'		=> 100,
				
				)
			));
		if($validation->passed()){
			if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password){
				Session::flash('home', 'Your Password is wrong');
				Redirect::to('logout.php');
			}
				$user->update(array(
					'security_question' 	=> escape(Input::get('new_question')),
					'security_answer' 		=> escape(Input::get('new_answer')),
					));
				Session::flash('home', 'Your Security Question has been changed!');
				Redirect::to('index.php');
		}else{
			Character::flash_error($validation->errors());
		}
	}
}
Character::warning('If your not sure of the Current Password pls leave this page');
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
								<label for="new_question"> New Question</label>
								<input type="text" name="new_question" id="new_question" class="form-control new_question" placeholder="Enter New Question">
								<br>
							</div>
							<div class="field">
								<label for="new_answer">New Answer</label>
								<input type="text" name="new_answer" id="new_answer" class="form-control new_answer" placeholder="Enter New Answer">
								<br>
							</div>
							<input type="hidden" name="token" value="<?php echo Token::generate();?>">
							<button type="submit" class="btn btn-success">Change Question</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php include_once 'includes/overall/overall_footer.php';?>
