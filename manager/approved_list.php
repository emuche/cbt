<?php 
require_once '../core/init.php';
include_once ROOT_PATH.'/includes/overall/overall_header.php';


$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}
Redirect::permission_check('manager', $user->data()->permission);
?>

<title>Edit Exam</title>
	<div id="page-content-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12"  align="justify">
					


<?php
$user = new User();
if(!$user->isLoggedIn()){
	Redirect::to('../index.php');
}

if(Input::exists()){
	if(Token::check(Input::get('token'))){

		$validate 		= new validate();
		$validation 	= $validate->check($_POST, array(

						'year' => array(
							'required' 	=> true,
						), 
						'level'	=> array(
							'required'		=> true,
						),

						'dept'	=> array(
							'required'		=> true,
						),

			));

	if($validation->passed()){
		$db = DB::getInstance();

		$session_id = Input::get('term');
		$level 		= Input::get('level');
		$dept 		= Input::get('dept');

		try{
			
			Session::flash('approval_list', 'The following subjects are ready!');
			Redirect::to('approval_list.php?level='.$level.'&dept='.$dept.'&session_id='.$session_id);

		}catch (Exception $e ){
			var_dump($e->getMessage());
			die();
		
		}
		}else {
				Character::flash_error($validation->errors());
			}
		}
	}
?>

					<div class=" divCenter text-center col-xs-12 col-sm-10 col-md-7 col-lg-5">
					<h3>Edit Exam Questions</h3>
					<br>
						<form action="" method="post" class="form-horizontal">

							<div class="year_div">
								<label for="year">Select Examinination Year</label>
								<select class="form-control year" id="year" name="year">
									<option disabled selected value="">choose Session Year</option>
<?php
$db = DB::getInstance();
$years = $db->group_by('session', 'year')->all();
foreach ($years as $year) {
	echo '<option value="'.$year->year.'">'.$year->year.'</option>';
}
?>
								</select>
							</div>
							<br>

							<div class="term_div">
								<label for="term1">Select Term</label>
								<select class="form-control term1" id="term1" name="term">
									<option  selected>Select Term</option>
								</select>
							</div>
							<br>

							<div class="section_div">
								<label for="section">Select Student Section</label>
								<select class="form-control section" id="section"  name="section">
									<option value=""  selected>Select Student Section</option>

								</select>
							</div>
							<br>

							<div class="level_div">
								<label for="level">Select Student Level</label>
								<select class="form-control level" id="level" name="level">
									<option value=""  selected>Select Student Level</option>
									
								</select>
							</div>
							<br>

							<div class="dept_div">
								<label for="dept">Select Student Department</label>
								<select class="form-control dept" id="dept" name="dept">
									<option value=""  selected>Select Student Department</option>
									
								</select>								
							</div>
							<br>

							<input type="hidden" name="token" value="<?php echo $token = Token::generate();?>">
							<br>

							<button type="submit" class="btn btn-success">submit</button>

								
						</form>
					</div>
				</div>
				<br>
				<br>
				<br>
			</div>		
		</div>
	</div>
</div>
<?php include_once ROOT_PATH.'/includes/overall/overall_footer.php';?>
