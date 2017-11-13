<?php
require_once '../core/init.php';
$db 		= DB::getInstance();
$user 		= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
if ( 
!Session::get('subject_id') ||
!Session::get('level') ||
!Session::get('section') ||
!Session::get('dept') ||
!Session::get('session_id') ||
!Session::get('subject') ||
!Session::get('year') ||
!Session::get('time_of_exam') ||
!Session::get('time_in_sec') ||
!Session::get('no_of_question') ||
!Session::get('questions_written') ||
!Session::get('exam_table') ||
!Session::get('answer_table') ||
!Session::get('exam_answer_table') ||
!Session::get('exam_options_table') ||
Session::get('next') < 0 ||
Session::get('prev') < 0


) {
	Redirect::to('index.php');
}


$subject_id 		= Session::get('subject_id');
$level				= Session::get('level');
$section			= Session::get('section');
$dept 				= Session::get('dept');
$session_id 		= Session::get('session_id');
$subject 			= Session::get('subject');
$year 				= Session::get('year');
$time_of_exam 		= Session::get('time_of_exam');
$time_in_sec 		= Session::get('time_in_sec');
$no_of_question 	= Session::get('no_of_question');
$questions_written 	= Session::get('questions_written');
$exam_table 		= Session::get('exam_table');
$answer_table 		= Session::get('answer_table');
$exam_answer_table 	= Session::get('exam_answer_table');
$exam_options_table = Session::get('exam_options_table');
$next 				= Session::get('next');



if(Input::get('answer') || Input::get('exam_id')){
	

	$answer 	= empty(Input::get('answer')) ? '0' : Input::get('answer');
	$exam_id	= Input::get('exam_id');

	try {

		$db->update_table($exam_answer_table, array('id', '=', $exam_id), array('answer' => $answer));
		
	} catch (Exception $e) {
		die($e->getMessage());
	}
}

if (Input::get('question_id')) {
	
	$question_id	= Input::get('question_id');
	try {

		$db->update_table($exam_options_table, array('question_id', '=', $question_id), array('checked' => '0'));

	} catch (Exception $e) {
		die($e->getMessage());
	}
}


if (Input::get('option_id')) {

	$option_id		= Input::get('option_id');
	try {

		$db->update_table($exam_options_table, array('id', '=', $option_id), array('checked' => '1'));
		
	} catch (Exception $e) {
		die($e->getMessage());	
	}
}

?>