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
<title>Add Administrator</title>

<div id="page-content-wrapper" style="clear:both">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12"  align="justify">
<?php
if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$admin 			= Input::get('admin_username');
		$validate 		= new validate();
		$admin_info 	= new user($admin);
		$data 			= $admin_info->data();

		$admin_id = $admin_info->exists() ? $data->id : ' ';

		$admin_class 	= $db->get('admin', array('user_id', '=', $admin_id))->count(); 

		if($admin_class){
			$validate->add_error('admin has Class already');
		}
		if(!$admin_info->exists()){
			$validate->add_error('admin does not Exist');
		}

		$validation 	= $validate->check($_POST, array(

						'admin_username' => array(
							'required' 	=> true
						)
			));

		
		if($validation->passed()){

			try{

				Session::put('admin_id', $admin_id);
				Redirect::to('add_admin_class.php');
				
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
							<label for="admin_username">Administrator Username</label>
							<input type="text" name="admin_username" class="form-control" id="admin_username" value="<?php echo escape(Input::get('admin_username'));?>" placeholder="Enter admin Username Here">
						</div>
						<br>
						<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">

						<button type="submit" class="btn btn-success">Check Admin</button>	
						<br>
				</div>
			</div>
		</div>
	</div>
	
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
