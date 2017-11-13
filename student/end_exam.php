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
!Session::get('exam_table') ||
!Session::get('answer_table') ||
!Session::get('exam_answer_table') ||
!Session::get('exam_options_table') ||
!Session::get('questions_written') ||
Session::get('next') < 0 ||
Session::get('prev') < 0

) {
	Redirect::to('index.php');
	exit();
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
$exam_table 		= Session::get('exam_table');
$answer_table 		= Session::get('answer_table');
$questions_written 	= Session::get('questions_written');
$exam_answer_table 	= Session::get('exam_answer_table');
$exam_options_table = Session::get('exam_options_table');
$next 				= Session::get('next');

$class 		= $db->action('SELECT * ', 'class', array('level', '=', $level), array('AND', 'dept', '=', $dept))->first();
$answer1 	= $db->action('SELECT * ', $exam_answer_table, array('answer', '=', '1'));
$answer  	= $answer1->count();
$score1 	= ($answer/$questions_written) * 100;
$score		= round($score1);

$a = range(70, 100);
$b = range(60, 69);
$c = range(50, 59);
$d = range(45, 49);
$e = range(40, 44);
$f = range(0, 39);

switch ($score) {
	case $score === 0:
		$grade = 'F';
		$remark = 'Failed ';
		break;
	case in_array($score, $f):
		$grade = 'F';
		$remark = 'Failed ';
		break;
	case in_array($score, $e):
		$grade = 'E';
		$remark = 'Very Poor';
		break;
	case in_array($score, $d):
		$grade = 'D';
		$remark = 'Poor, you need to work harder';
		break;
	case in_array($score, $c):
		$grade = 'C';
		$remark = 'Good, but you can improve';
		break;
	case in_array($score, $b):
		$grade = 'B';
		$remark = 'Very Good';
		break;
	case in_array($score, $a):
		$grade = 'A';
		$remark = 'Excellent';
		break;	
	default:
		$grade = '';
		$remark = '';
		break;
}

try {
	$db->insert('result', array(

		'student_id'				=> $_SESSION['user'],
		'class_id'					=> $class->id,	
		'subject_id'				=> $subject_id,	
		'session_id'				=> $session_id,	
		'no_of_question'			=> $no_of_question,	
		'questions_written'			=> $questions_written,	
		'total_correct_answer'		=> $answer,
		'score'						=> $score,
		'grade'						=> $grade,
		'remark'					=> $remark,
		'total_time'				=> $time_of_exam
					
	));

	
	Session::flash('show_result', ' Exam has been Submitted');
	Redirect::to('show_result.php');
					

	
} catch (Exception $e) {
	die($e->getMessage());
	
}




?>