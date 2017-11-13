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


				$class = $db->get('teachers', array('user_id', '=', $data->id))->first();
				Session::flash('teachers_list', 'Account Has been Modified');

				Redirect::to('teachers_list.php');	
				
			}

			if ($delete = escape(Input::get('delete'))) {
				$user = new User($delete);
				$data = $user->data();
				$class = $db->get('teachers', array('user_id', '=', $data->id))->first();
				$teachers_id 		= $class->user_id;
				$teachers_level 		= $class->level;
				$teachers_dept 		= $class->dept;
				$teachers_class_id 	= $class->class_id;


				$db->delete('users', array('id', '=', $teachers_id));
				$db->delete('result', array('student_id', '=', $teachers_id));
				$db->delete('student', array('user_id', '=', $teachers_id));
				$db->delete('teachers', array('user_id', '=', $teachers_id));
				$db->delete('management', array('user_id', '=', $teachers_id));
				$db->delete('admin', array('user_id', '=', $teachers_id));

				Session::flash('teachers_list', 'Account Has been Deleted');
				Redirect::to('teachers_list.php');	
				
			}




			

		}catch (Exception $e ){
			die($e->getMessage());
		}
	}
	Redirect::to('index.php');
}
Redirect::to('index.php');
?>