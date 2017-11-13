<?php
require_once '../core/init.php';

$user 	= new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('student', $user->data()->permission);
$db 		= DB::getInstance();
$data		= $user->data();

$subject_id	 		= Session::get('subject_id');
$level 				= Session::get('level');
$section 			= Session::get('section');
$dept 				= Session::get('dept');
$session_id 		= Session::get('session_id');
$subject 			= $db->get('subject', array('id', '=', $subject_id))->first();
$session 			= $db->get('session', array('id', '=', $session_id))->first();
$subject_name		= $subject->subject_name;
$year 				= $session->year;
$time_of_exam		= $subject->total_time_of_exam;
$no_of_question		= $subject->total_no_of_question;

$exam_table 		= 'question_'.$subject_name.'_'.$level.'_'.$dept;
$answer_table 		= 'answer_'.$subject_name.'_'.$level.'_'.$dept;


$username			= $data->username;
$exam_answer_table	= $username.'_questions';
$exam_options_table	= $username.'_options';
$time_in_sec 		= ($time_of_exam * 60) + (time() + 2);




if (!$db->table_exists($exam_table) || !$db->table_exists($answer_table)) {
	Session::flash('write_exam', 'Exam is not yet ready. Contact Teacher');
	Redirect::to('write_exam.php');
}


$db->drop_table($exam_answer_table);
$db->drop_table($exam_options_table);


if (!$db->table_exists($exam_answer_table)) {
	$db->create_exam_table($exam_answer_table);
}

if (!$db->table_exists($exam_options_table)) {
	$db->create_exam_option_table($exam_options_table);
}


Session::put('subject', $subject_name);
Session::put('year', $year);
Session::put('time_of_exam', $time_of_exam);
Session::put('time_in_sec', $time_in_sec);
Session::put('no_of_question', $no_of_question);
Session::put('exam_table', $exam_table);
Session::put('answer_table', $answer_table);
Session::put('exam_answer_table', $exam_answer_table);
Session::put('exam_options_table', $exam_options_table);
Session::put('next', 0);
Session::put('prev', 0);




$options 	= $db->order_by_limit($answer_table, ' RAND()', null, null, array('session_id', '=', $session_id))->all();
foreach ($options as $option) {
	$db->insert($exam_options_table, array('answer' => $option->answer, 'question_id' => $option->question_id, 'correct' => $option->correct));
	
}

$questions 	= $db->order_by_limit($exam_table, ' RAND()', $no_of_question, null, array('session_id', '=', $session_id))->all();
foreach ($questions as $question) {

	$db->insert($exam_answer_table, array('question_id'	=> $question->id));
}

$questions_written = $db->get($exam_answer_table, array('id', '>' , 0))->count();
Session::put('questions_written', $questions_written);





Session::flash('exam_page', 'Your Exam has Started and will last for '.$time_of_exam.' minute');
Redirect::to('exam_page.php');

?>