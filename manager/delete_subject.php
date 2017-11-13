<?php
require_once '../core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);




if(Input::exists()){

	if(Token::check(Input::get('token'))){		

		$db 			= DB::getInstance();
		$subject_id 	= Input::get('subject_id');
		$subject_info 	= $db->get('subject', array('id', '=', $subject_id))->first();
		$level 			= $subject_info->level;
		$dept 			= $subject_info->dept;
		$exam_table 	= $subject_info->exam_table;
		$answer_table 	= $subject_info->answer_table;


		try{
			$db->delete('subject', array('id', '=', $subject_id));
			$db->delete('result', array('subject_id', '=', $subject_id));
			$db->delete('teachers', array('subject_id', '=', $subject_id));
			$db->drop_table($exam_table);
			$db->drop_table($answer_table);

			Session::flash('subject_list', 'Subject has been deleted');
			Redirect::to('subject_list.php?level='.$level.'&dept='.$dept);

		}catch (Exception $e ){
			die($e->getMessage());
		}
	}
}
Redirect::to('index.php');
?>