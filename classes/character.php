<?php
class Character{

	public static function success($string){
		echo '$string';
	}

	public static function danger($string){

	}

	public static function warning($message){
		echo '<div class="col-xs-12" style="margin-bottom: 30px; z-index: 1;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>'.$message.'</h4></div>';
	}

	public static function info($string){
		
	}

	public static function organize($string){
		$normal = str_replace( '_', ' ', $string);
		$capitalise = ucwords($normal) ;
		return $capitalise;

	}

	public static function replace_($string){
		$replace = str_replace( ' ', '_', $string);
		return $replace;

	}

	public static function year($string){
		$normal = str_replace( '_', '/', $string);
		return $normal;

	}

	public static function flash($session){
		if(Session::exists($session)){
			echo '<div class="col-xs-12" style="margin-bottom: 30px; z-index: 1;"><h4 class="alert alert-warning col-xs-12 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>'.Session::flash($session).'</h4></div>';
		}
	}

	public static function flash_error($errors = array()){
		echo '<div class="col-xs-12" style="margin-bottom: 30px;"><h4 class="alert alert-warning col-xs-6 divCenter text-center fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close"><span class="glyphicon glyphicon-remove"></span></a>';
			foreach($errors as $error){
				echo $error, '<br>';
			}
		echo '</h4></div>';
	}
}
?>