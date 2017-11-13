<?php
require_once '../core/init.php';


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('admin', $user->data()->permission);

if(Input::exists()){
	if(Token::check(Input::get('token'))){		

		$db	= DB::getInstance();

		try{

			if ($deactivate = escape(Input::get('deactivate'))) {
				$user = new User($deactivate);
				$data = $user->data();
				$active = $data->active ? '0' : '1';

				$user->update(array(
					'active' => $active
				), $data->id);


				$class = $db->get('admin', array('user_id', '=', $data->id))->first();
				Session::flash('admin_list', 'Account Has been Modified');

				Redirect::to('admin_list.php');	
				
			}

			if ($delete = escape(Input::get('delete'))) {
				$user = new User($delete);
				$data = $user->data();
				$class = $db->get('admin', array('user_id', '=', $data->id))->first();
				$admin_id 		= $class->user_id;
				$admin_level 		= $class->level;
				$admin_dept 		= $class->dept;
				$admin_class_id 	= $class->class_id;


				$db->delete('users', array('id', '=', $admin_id));
				$db->delete('result', array('student_id', '=', $admin_id));
				$db->delete('student', array('user_id', '=', $admin_id));
				$db->delete('admin', array('user_id', '=', $admin_id));
				$db->delete('management', array('user_id', '=', $admin_id));
				$db->delete('admin', array('user_id', '=', $admin_id));

				Session::flash('admin_list', 'Account Has been Deleted');
				Redirect::to('admin_list.php');	
				
			}




			

		}catch (Exception $e ){
			die($e->getMessage());
		}
	}
	Redirect::to('index.php');
}
Redirect::to('index.php');
?>