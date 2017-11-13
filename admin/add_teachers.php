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
<title>Add Teacher</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		
		$teachers 		= Input::get('teachers_username');
		$validate 		= new validate();
		$teachers_info 	= new user($teachers);
		$data 			= $teachers_info->data();


		if($teachers_info->exists()){
			$teachers_id 	= $data->id;
		}else {
			$teachers_id = '';
		}
		$teachers_class 	= $db->get('teachers', array('user_id', '=', $teachers_id))->count(); 

		
		if(!$teachers_info->exists()){
			$validate->add_error('Teacher does not Exist');
		}

		$validation 	= $validate->check($_POST, array(

						'teachers_username' => array(
							'required' 	=> true
						)
			));

		
		if($validation->passed()){

			try{

				Session::put('teachers_id', $teachers_id);
				Redirect::to('add_teachers_class.php');	
			

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
							<label for="teacher_username">Teacher Username</label>
							<input type="text" name="teachers_username" class="form-control" id="teacher_username" value="<?php echo escape(Input::get('teacher_username'));?>" placeholder="Enter Teacher's Username Here">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Check Teacher</button>	
						<br>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
