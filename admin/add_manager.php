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
<title>Add manager</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){
	
		$manager 		= Input::get('manager_username');
		$validate 		= new validate();
		$manager_info 	= new user($manager);
		$data 			= $manager_info->data();

		$manager_id = $manager_info->exists() ? $data->id : ' ';
		$manager_class 	= $db->get('manager', array('user_id', '=', $manager_id))->count(); 

		if($manager_class){
			$validate->add_error('Manager has been added already');
		}
		if(!$manager_info->exists()){
			$validate->add_error('Manager does not Exist');
		}

		$validation 	= $validate->check($_POST, array(

						'manager_username' => array(
							'required' 	=> true
						)
			));
		if($validation->passed()){
			try{
				Session::put('manager_id', $manager_id);
				Redirect::to('add_manager_class.php');	

			}catch (Exception $e ){
				die($e->getMessage());
			}
		}else{
			Character::flash_error($validation->errors());
		}
	}
}
?>
				<div class=" divCenter text-center col-xs-11 col-sm-9 col-md-7 col-lg-5">
					<form action="" method="post" class="form-horizontal">

						<div>
							<label for="manager_username">manager Username</label>
							<input type="text" name="manager_username" class="form-control" id="manager_username" value="<?php echo escape(Input::get('manager_username'));?>" placeholder="Enter manager Username Here">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Check manager</button>	
						<br>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
