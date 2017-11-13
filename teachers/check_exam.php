<?php
require_once '../core/init.php';

$user 	= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}

Redirect::permission_check('teachers', $user->data()->permission);


if (!Input::get('level') || !Input::get('dept') || !Input::get('session_id') || !Input::get('subject_name')) {
	Redirect::to('index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){



		$level 				= Input::get('level');
		$dept 				= Input::get('dept');
		$subject_name 		= Input::get('subject_name');
		$session_id 		= Input::get('session_id');

		$exam_table 		= 'question_'.$subject_name.'_'.$level.'_'.$dept;
		$answer_table 		= 'answer_'.$subject_name.'_'.$level.'_'.$dept;

		$db 			= DB::getInstance();
		$check_table	= $db->query("SHOW TABLES LIKE '{$exam_table}'")->count();
		if (!$check_table) {
			Session::flash('await_approval', 'This Exam has not been set yet');
			Redirect::to('await_approval.php');
		}else {
			$check_table2 = $db->get($exam_table, array('session_id', '=', $session_id))->count();
			
			if (!$check_table2) {
				Session::flash('await_approval', 'This Exam has not been set yet');
				Redirect::to('await_approval.php');
			}

			try {
				$subject_info = $db->action('SELECT *', 'subject', array('subject_name', '=', $subject_name), array('AND', 'session_id', '=', $session_id))->first();
				$session_info = $db->get('session', array('id', '=', $session_id))->first();

				Session::put('subject', $subject_name);
				Session::put('subject_id', $subject_info->id);
				Session::put('level', $level);
				Session::put('dept', $dept);
				Session::put('year', $session_info->year);
				Session::put('term', $session_info->term);
				Session::put('session_id', $session_id);
				Session::put('exam_table', $exam_table);
				Session::put('answer_table', $answer_table);

				Session::flash('question_list','These are the questions!');
				Redirect::to('question_list.php');

				
			} catch (Exception $e) {
				var_dump($e->getMessage());
			}
			
		}
	}
}

?>