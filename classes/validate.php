<?php
class Validate{
	private $_passed 	= false,
			$_errors 	= array(),
			$_db 		= null;

	public function __construct(){
		$this->_db = DB::getInstance();
	}

	public function check($source, $items = array()){
		foreach ($items as $item => $rules) {
			foreach ($rules as $rule => $rule_value) {
				$value = trim($source[$item]);
				$item  = escape($item);
				$character_item = Character::organize($item);				


				if($rule === 'required' && empty($value)){
					$this->addError("{$character_item} is required");
				}elseif(!empty($value)){
					switch($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this->addError("{$character_item} must be a minimum of {$rule_value} characters.");
							}
						break;
						case 'max':
						if(strlen($value) > $rule_value){
								$this->addError("{$character_item} must be a maximum of {$rule_value} characters.");
							}
						
						break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$character_item} Must match");
							}
						break;
						case 'unique':
							$check = $this->_db->get($rule_value, array($item, '=', $value));
							if($check->count()){
								$this->addError("{$character_item} already exists");
							}
						break;
					}
				}
			}
		}
		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;
	}

	private function addError($error){
		$this->_errors[] = $error;
	}

	public function add_error($error){
		$this->_errors[] = $error;
	}

	public function errors(){
		return $this->_errors;
	}

	public function passed(){
		return $this->_passed;
	}

	public function count_array($name, $array = array() , $count){
		if(count($array) < $count){
			$this->addError("minimum of {$count} {$name} is required. ");
		}

		if(empty($this->_errors)){
			$this->_passed = true;
		}
		return $this;

	}
}
?>