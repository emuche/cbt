<?php
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$db 	= DB::getInstance();
$user 	= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('admin', $user->data()->permission);
?>
<title>Add Student</title>
<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">


<?php

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		
		$student 		= Input::get('student_username');
		$validate 		= new validate();
		$student_info 	= new user($student);
		$data 			= $student_info->data();


		if($student_info->exists()){
			$student_id 	= $data->id;
		}else {
			$student_id = '';
		}
		$student_class 	= $db->get('student', array('user_id', '=', $student_id))->count(); 

		if($student_class){
			$validate->add_error('Student has Class already');
		}
		if(!$student_info->exists()){
			$validate->add_error('Student does not Exist');
		}

		$validation 	= $validate->check($_POST, array(

						'student_username' => array(
							'required' 	=> true
						)
			));

		
		if($validation->passed()){

			try{

				Session::put('student_id', $student_id);
				Redirect::to('add_student_class.php');	
			

			}catch (Exception $e ){
				die($e->getMessage());
			
			}

		}else{

?>
	<div class="col-xs-12" style="margin-bottom: 30px; z-index: 3; clear: both;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>
<?php
			foreach($validation->errors() as $error){
				echo $error, '<br>';
			}
?>
	</h4></div>
<?php
		}
	}
}

?>

				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">

						<div>
							<label for="student_username">Student Username</label>
							<input type="text" name="student_username" class="form-control" id="student_username" value="<?php echo escape(Input::get('student_username'));?>" placeholder="Enter Student Username Here">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Check Student</button>	
						<br>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
