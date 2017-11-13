<?php
require_once '../core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);


$subject 		= Character::organize(Session::get('subject'));
$level 			= Character::organize(Session::get('level'));
$dept 			= Character::organize(Session::get('dept'));
$term			= Character::organize(Session::get('term'));
$subject_id		= Session::get('subject_id');

$session_id		= Session::get('session_id');



Session::flash('approved_list', $level.' '.$term.' '.$subject.' Exam has been  successfully Approved' );

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$db = DB::getInstance();
		$approval = Input::get('approval') ? Input::get('approval') : '0'; 

		try {
			$db->update('subject', $subject_id, array(
				'approved' => $approval
			));
			
		} catch (Exception $e) {
				die($e->getMessage());
		}
	}
}



Session::delete('subject');
Session::delete('level');
Session::delete('dept');
Session::delete('term');
Session::delete('session_id');

Session::delete('subject_id');
Session::delete('session_id');
Session::delete('exam_table');
Session::delete('answer_table');


Redirect::to('approval_list.php?level='.$level.'&dept='.$dept.'&session_id='.$session_id);




?>