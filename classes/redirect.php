<?php
class Redirect{
	public static function to($location = null){
		if($location){
			if(is_numeric($location)){
				switch ($location) {
					case 404:
						//header ('HTTP/1.0 404 Not Found');
						include 'includes/errors/404.php';
						include_once 'includes/overall/overall_footer.php';
						exit();
					break;
				}
			}
			header('Location: '.$location);
			exit();
		}
	}

	public static function permission_check($permission, $user_permission){
		if ($permission != $user_permission) {
			Redirect::to('../index.php');
			
		}
	}



}

?>