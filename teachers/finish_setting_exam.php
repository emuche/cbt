<?php
require_once '../core/init.php';
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('teachers', $user->data()->permission);

$subject 		= Character::organize(Session::get('subject'));
$level 			= Character::organize(Session::get('level'));
$dept 			= Character::organize(Session::get('dept'));
$term			= Character::organize(Session::get('term'));
$year			= Character::year(Session::get('term'));




Session::flash('home', $level.' '.$dept.' '.$term.' '.$subject.' Exam has been  successfully Approved' );




Session::delete('subject');
Session::delete('level');
Session::delete('dept');
Session::delete('term');
Session::delete('term');
Session::delete('session_id');

Redirect::to('index.php');




?>