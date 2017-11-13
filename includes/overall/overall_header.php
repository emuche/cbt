<!DOCTYPE html>
<html lang="en">

<?php include_once ROOT_PATH.'/includes/head.php'; ?>

<body class="main_body" style="">

<?php  
$user = new User();
$data = $user->data();
if (Session::exists(Config::get('session/session_name'))){
		$user_permission = $data->permission;
?>
<div id="wrapper">
	<nav class="navbar navbar-inverse navbar-fixed-topP" style="margin-bottom: 0px;">
		<div class="container-fluid">
			
			<div class="navbar-header">

				<a href="#" class=" dropdown dropdown-toggle navbar-brand" data-toggle="dropdown" id="menu_toggle">
					<span class="glyphicon glyphicon-list"></span>
				</a>

				<a href="index.php" class="navbar-brand"><span class="glyphicon glyphicon-home"></span> <?php echo $webname; ?></a>


				<button class="navbar-toggle" data-toggle="collapse" data-target="#mainNavBar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<div class="navbar-right">
					<a  class="navbar-brand navbar-right" href="<?php echo $root_path.escape($user->data()->username);?>">Welcome <?php echo  escape($user->data()->username);?>! </a>
				</div>

			</div>


			<div class="collapse navbar-collapse navbar-right" id="mainNavBar">
				<ul class="nav navbar-nav">

<?php		include_once ROOT_PATH.'/includes/menus/'.$user_permission.'_menu.php';?>
				</ul>
			</div>

		</div>
	</nav>
	<div id="sidebar-wrapper">
		<ul class="sidebar-nav">
			<li><a href="<?php echo $root_path.escape($user->data()->username);?>"><span class="glyphicon glyphicon-user"></span>&ensp;&ensp; My Profile</a></li>
			<li><a href="<?php echo $root_path;?>information.php"><span class="glyphicon glyphicon-info-sign"></span>&ensp;&ensp; Information</a></li>
			<li><a href="<?php echo $root_path;?>changepassword.php"><span class="glyphicon glyphicon-asterisk"></span>&ensp;&ensp; Change Password</a></li>
			<li><a href="<?php echo $root_path;?>update.php"><span class="glyphicon glyphicon-pencil"></span>&ensp;&ensp; Change Security</a></li>
			<li><a href="<?php echo $root_path;?>logout.php"><span class="glyphicon glyphicon-log-out"></span>&ensp;&ensp; LogOut</a></li>
		</ul>
	</div>



<?php	}else{ ?>



<nav class="navbar navbar-inverse navbar-fixed-topP" style="margin-bottom: 0px;">
	<div class="container-fluid">
		<div class="navbar-header">
			<a href="index.php" class="navbar-brand"><span class="glyphicon glyphicon-home"></span> <?php echo $webname; ?></a>

			<button class="navbar-toggle" data-toggle="collapse" data-target="#mainNavBar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>


		</div>

		<div class="collapse navbar-collapse navbar-right" id="mainNavBar">
			<ul class="nav navbar-nav">

<?php		include_once ROOT_PATH.'/includes/menus/index_menu.php';?>

			</ul>
		</div>
		
	</div>
</nav>
<?php include_once ROOT_PATH.'/includes/widgets/login_widget.php'; }?>


