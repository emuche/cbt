<?php
require_once '../core/init.php';

$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

if (Input::get('question_id')) {
	Session::put('question_id', Input::get('question_id'));
}

if (
!Session::get('subject') ||
!Session::get('subject_id') ||
!Session::get('question_id') ||
!Session::get('level') ||
!Session::get('dept') ||
!Session::get('year') ||
!Session::get('term') ||
!Session::get('session_id') ||
!Session::get('exam_table') ||
!Session::get('answer_table')
) {
	Redirect::to('index.php');
}

$subject	 	= Session::get('subject');
$subject_id	 	= Session::get('subject_id');
$question_id	= Session::get('question_id');
$level 			= Session::get('level');
$dept 			= Session::get('dept');
$session_id 	= Session::get('session_id');
$year			= Session::get('year');
$term			= Session::get('term');
$exam_table		= Session::get('exam_table');
$answer_table	= Session::get('answer_table');


if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$db = DB::getInstance();
		$question = $db->get($exam_table, array('id', '=', $question_id))->first();

		$path = '../images/'.$exam_table.'/'.$question->image;
		unlink($path);

		$db->update($exam_table, $question_id, array('image' => ''));


		Session::flash('update_question', 'Photo has been deleted');
		Redirect::to('update_question.php');
		
	}
}
Reddirect::to('index.php');
?>