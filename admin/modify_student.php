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


				$class = $db->get('student', array('user_id', '=', $data->id))->first();
				Session::flash('student_list', 'Account Has been Modified');

				Redirect::to('student_list.php?level='.$class->level.'&dept='.$class->dept.'&class_label='.$class->class_id);	
				
			}

			if ($delete = escape(Input::get('delete'))) {
				$user = new User($delete);
				$data = $user->data();
				$class = $db->get('student', array('user_id', '=', $data->id))->first();
				$student_id 		= $class->user_id;
				$student_level 		= $class->level;
				$student_dept 		= $class->dept;
				$student_class_id 	= $class->class_id;


				$db->delete('users', array('id', '=', $student_id));
				$db->delete('result', array('student_id', '=', $student_id));
				$db->delete('student', array('user_id', '=', $student_id));
				$db->delete('teachers', array('user_id', '=', $teachers_id));
				$db->delete('management', array('user_id', '=', $teachers_id));
				$db->delete('admin', array('user_id', '=', $teachers_id));


				Session::flash('student_list', 'Account Has been Deleted');
				Redirect::to('student_list.php?level='.$student_level.'&dept='.$student_dept.'&class_label='.$student_class_id);	
				
			}




			

		}catch (Exception $e ){
			die($e->getMessage());
		}
	}
	Redirect::to('index.php');
}
Redirect::to('index.php');
?>