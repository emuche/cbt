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



		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'username' => array(
							'required' 	=> true,
							'min'		=> 2,
							'max'		=> 20,
							'unique'	=> 'users'
						), 
						'password' => array(
							'required'  => true,
							'min'		=> 6
						), 
						'password_again'	=> array(
							'required'		=> true,
							'matches'		=> 'password'
						), 
						'first_name' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'middle_name' => array(
							'min' 		=> 2,
							'max'		=> 50
						),
						'last_name' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'date_of_birth' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'phone_number' => array(
							'required' 	=> true,
							'min' 		=> 11,
							'max'		=> 11
						),
						'email' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'state_of_origin' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'gender' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 50
						),
						'security_question' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 100
						),
						'security_answer' => array(
							'required' 	=> true,
							'min' 		=> 2,
							'max'		=> 100
						)

			));
		if($validation->passed()){
			$user = new User();
			$salt = Hash::salt(32);


			try{
				$user->create(array(
					'username'				=> Input::get('username'),	
					'password'				=> Hash::make(Input::get('password'), $salt),
					'salt'					=> $salt,	
					'first_name'			=> Input::get('first_name'),
					'middle_name'			=> Input::get('middle_name'),
					'last_name'				=> Input::get('last_name'),
					'gender'				=> Input::get('gender'),
					'date_of_birth'			=> Input::get('date_of_birth'),
					'phone_number'			=> Input::get('phone_number'),
					'email'					=> Input::get('email'),
					'security_question'		=> Input::get('security_question'),
					'security_answer'		=> Input::get('security_answer'),
					'state_of_origin'		=> Input::get('state_of_origin'),
					'joined'				=> date('Y-m-d H:i:s'),	
					'group'					=> 1,	



					));
					Session::flash('home', ' You have been registered and can now log in!');
					Redirect::to('index.php');

			}catch (Exception $e ){
				die($e->getMessage());
			
			}

		}else{
			Character::flash_error($validation->errors());
		}
	}
}
	include_once 'includes/overall/overall_header.php';

?>
<title>Register</title>
<h3 class="text-center">Registration</h3>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 divCenter text-center"  style="padding-top: 10px;">

	<form action="" method="post" class="form-horizontal">
		<div class="field">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" placeholder="Username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
		</div>
		<br>
		<div class="field">
		<label for="password">Choose a Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" placeholder="Enter Password" >
		</div>
		<br>
		<div class="field">
			<label for="password_again">Choose a Password</label>
			<input type="password" name="password_again" id="password_again" class="form-control" value="" placeholder="Enter Password Again" >
		</div>
		<br>
		<div class="field">
			<label for="first_name">First Name</label>
			<input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter First Name" value="<?php echo escape(Input::get('first_name'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="middle_name">Middle Name</label>
			<input type="text" name="middle_name" id="middle_name" class="form-control" placeholder="Enter Middle Name" value="<?php echo escape(Input::get('middle_name'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="last_name">Last Name</label>
			<input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" value="<?php echo escape(Input::get('last_name'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="gender">Gender</label>
			<select name="gender" class="form-control" id="gender">
				<option value="" selected>Select Gender</option>
				<option value="female">Female</option>
				<option value="male">Male</option>
			</select>
		</div>
		<br>
		<div class="field">
			<label for="date_of_birth">Date of Birth</label>
			<input type="text" name="date_of_birth" id="date_of_birth" class="form-control date_picker" placeholder="Select Date of Birth" value="<?php echo escape(Input::get('date_of_birth'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="phone_number">Phone Number</label>
			<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="Enter Phone Number" value="<?php echo escape(Input::get('phone_number'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="email">Email Address</label>
			<input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" value="<?php echo escape(Input::get('email'));?>" >
		</div>
		<br>
		<div class="field">
			<label for="state_of_origin">State of Origin</label>
			<select name="state_of_origin" id="state_of_origin" class="form-control">
				<option value="">Select State</option>
				<option value="abuja">Abuja</option>
				<option value="abia">Abia</option>
				<option value="adamawa">Adamawa</option>
				<option value="akwa ibom">Akwa Ibom</option>
				<option value="anambra">Anambra</option>
				<option value="bauchi">Bauchi</option>
				<option value="bayelsa">Bayelsa</option>
				<option value="benue">Benue</option>
				<option value="borno">Borno</option>
				<option value="cross rivers">Cross rivers</option>
				<option value="delta">Delta</option>
				<option value="ebonyi">Ebonyi</option>
				<option value="edo">Edo</option>
				<option value="ekiti">Ekiti</option>
				<option value="enugu">Enugu</option>
				<option value="gombe">Gombe</option>
				<option value="imo">Imo</option>
				<option value="jigawa">Jigawa</option>
				<option value="kaduna">Kaduna</option>
				<option value="kano">Kano</option>
				<option value="katsina">Katsina</option>
				<option value="kebbi">Kebbi</option>
				<option value="kogi">Kogi</option>
				<option value="kwara">Kwara</option>
				<option value="lagos">Lagos</option>
				<option value="nasarawa">Nasarawa</option>
				<option value="niger">Niger</option>
				<option value="ogun">Ogun</option>
				<option value="ondo">Ondo</option>
				<option value="osun">Osun</option>
				<option value="oyo">Oyo</option>
				<option value="plateau">Plateau</option>
				<option value="rivers">Rivers</option>
				<option value="sokoto">Sokoto</option>
				<option value="taraba">Taraba</option>
				<option value="yobe">Yobe</option>
				<option value="zamfara">Zamfara</option>
			</select>
		</div>
		<br>
		<div class="field">
			<label for="security_question">Security Question</label>
			<select name="security_question" class="form-control security_question" id="security_question">
				<option value="" selected >Select Security Question</option>									
				<option value="Who is your favourite female teacher ?">Who is your favourite female teacher ?</option>									
				<option value="Who is your favourite male teacher ?">Who is your favourite male teacher ?</option>									
				<option value="When is your mother's birthday ?">When is your mother's birthday ?</option>									
				<option value="When is your father's birthday ?">When is your father's birthday ?</option>									
				<option value="Which hospital were you born ?">Which hospital were you born ?</option>									
				<option value="Where did you grow up ?">Where did you grow up ?</option>									
				<option value="What is your future ambition ?">What is your future ambition ?</option>									
				<option value="What is the name of you favourite uncle ?">What is the name of you favourite uncle ?</option>									
				<option value="What is the name of you favourite auntie ?">What is the name of you favourite auntie ?</option>									
				<option value="What is your favourite subject ?">What is your favourite subject ?</option>									
			</select>
		</div>
		<br>
		<div class="field">
			<label for="security_answer">Security Question Answer</label>
			<input type="text" name="security_answer" id="security_answer" class="form-control security_answer" placeholder="Enter Your Answer" maxlength="100" value="<?php echo escape(Input::get('security_answer'));?>">
		</div>
		<br>
		<input type="hidden" name="token" value="<?php echo $token;?>">
		<button type="submit" class="btn btn-success">Register</button>

	</form>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
</div>


<?php include_once 'includes/overall/overall_footer.php';?>
