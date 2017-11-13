<?php
class db{
	private static $_instance = null;
	private $_pdo,
			$_query,
			$_error = false,
			$_error_message = null,
			$_results,
			$_count = 0;
			
	private function __construct(){
		try{
			$this->_pdo = new PDO('mysql:host='.config::get('mysql/host').';dbname=' .config::get('mysql/db'), config::get('mysql/username'), config::get('mysql/password'));
			//$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
				//PDO::ERRMODE_EXCEPTION);
			
		}catch(PDOException $e){
			die($e->getMessage());
		}
	}

	public static function getInstance(){
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		return self::$_instance;
	}
	public function query($sql, $params = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo->prepare($sql)){
			$x =1;
			if(count($params)){
				foreach ($params as $param) {
				$this->_query->bindValue($x , $param);
				$x++;	
				}
				
			}
			if($this->_query->execute()){
				$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
				$this->_count = $this->_query->rowCount();
			}else{
				echo $this->_error = true;
				die('query error: check query');
			}
		}
		return $this;
	}

	public function action($action, $table, $where = array(), $where2 = array(), $where3 = array()){
		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field		 	= $where[0];
			$operator		= $where[1];
			$value 			= $where[2];
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} '{$value}' ";
				if (count($where2) === 4) {
					$operators2 = array('=','>','<','>=','<=');
					$bools2 = array('AND','OR');

					$bool2			= $where2[0];
					$field2		 	= $where2[1];
					$operator2		= $where2[2];
					$value2			= $where2[3];
					

					if(in_array($operator2, $operators2) && in_array($bool2, $bools2)){
						$sql .= " {$bool2} {$field2} {$operator2} '{$value2}' ";
						if (count($where3) === 4) {
							$operators3 = array('=','>','<','>=','<=');
							$bools3 = array('AND','OR');

							$bool3			= $where3[0];
							$field3		 	= $where3[1];
							$operator3		= $where3[2];
							$value3			= $where3[3];
							

							if(in_array($operator3, $operators3) && in_array($bool3, $bools3)){
								$sql .= " {$bool3} {$field3} {$operator3} '{$value3}' ";						
							}
						}						
					}
				}


				if(!$this->query($sql, array($value))->error()){
					return $this;
				}
			}
		}
		return false;
	}

	
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);

	}

	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = array()){

		$keys 		= array_keys($fields);
		$values 	= '';
		$x			= 1;

		foreach ($fields as $field) {
			$values .='?';
			if($x < count($fields)){
				$values .= ', ';
			}
			$x++;

		}

		$sql = "INSERT INTO {$table} (`".implode('`, `', $keys)."`) VALUES ({$values})";
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	public function update($table, $id, $fields){
		$set 	= '';
		$x 		= 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ? ";
			if($x < count($fields)){
				$set .= ', ';
			}
			$x++;
		}
		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	public function update_table($table, $where = array(), $fields){

		$set 	= '';
		$x 		= 1;

		foreach ($fields as $name => $value) {
			$set .= "{$name} = ? ";
			if($x < count($fields)){
				$set .= ', ';
			}
			$x++;
		}
		
		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field		 	= $where[0];
			$operator		= $where[1];
			$value 			= $where[2];
			if(in_array($operator, $operators)){

				$sql = "UPDATE {$table} SET {$set} WHERE {$field} {$operator} '{$value}' ";


			}
		}

		
		if (!$this->query($sql, $fields)->error()) {
			return true;
		}
		return false;
	}

	public function create_exam_question_table($table){

	
		$sql = "CREATE TABLE IF NOT EXISTS {$table}(
			id int(11) NOT NULL AUTO_INCREMENT,
			question TEXT NOT NULL,
			image VARCHAR(255) NOT NULL,
			session_id int(11) NOT NULL,
			PRIMARY KEY(id)
		)";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;

	} 

	public function create_exam_answer_table($table){
				
		$sql = "CREATE TABLE IF NOT EXISTS {$table}(
				id int(11) NOT NULL AUTO_INCREMENT,
				answer TEXT NOT NULL,
				question_id int(11) NOT NULL, 
				correct int(1) NOT NULL DEFAULT '0',
				session_id int(11) NOT NULL, 
				PRIMARY KEY(id)
				)";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;

	}

	public function create_exam_table($table){
				
		$sql = "CREATE   TABLE IF NOT EXISTS {$table}(
				id int(11) NOT NULL AUTO_INCREMENT,
				question_id int(11) NOT NULL, 
				answer int(1) NOT NULL DEFAULT '0',
				PRIMARY KEY(id)
				)";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;

	}

	public function create_exam_option_table($table){

		$sql = "CREATE   TABLE IF NOT EXISTS {$table}(
				id int(11) NOT NULL AUTO_INCREMENT,
				answer TEXT NOT NULL,
				question_id int(11) NOT NULL,
				correct int(1) NOT NULL DEFAULT '0', 
				checked int(1) NOT NULL DEFAULT '0', 
				PRIMARY KEY(id)
				)";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;


	}

	public function group_by($table, $column, $where = array(), $where2 = array()){
		$sql = "SELECT * FROM {$table} GROUP BY {$column} ";

		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field		 	= $where[0];
			$operator		= $where[1];
			$value 			= $where[2];
			if(in_array($operator, $operators)){
				$sql = "SELECT * FROM {$table} WHERE {$field} {$operator} '{$value}' GROUP BY {$column} ";
			

				if(count($where2) === 4){
					$operators 	= array('=','>','<','>=','<=');
					$bools2 	= array('AND','OR');

					$bool2		 	= $where2[0];
					$field2		 	= $where2[1];
					$operator2		= $where2[2];
					$value2			= $where2[3];
					if(in_array($operator, $operators) && in_array($bool2, $bools2)){
						$sql = "SELECT * FROM {$table} WHERE {$field} {$operator} '{$value}' {$bool2} {$field2} {$operator2} '{$value2}' GROUP BY {$column} ";
					}
				}
			}
		}

		if (!$this->query($sql)->error()) {
			return $this;
		}
		return false;
	}

	public function order_by_limit($table, $order = 'ASC', $limit1 = null, $limit2 = null, $where = array()){

		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field		 	= $where[0];
			$operator		= $where[1];
			$value 			= $where[2];
			if(in_array($operator, $operators)){
				$sql = "SELECT * FROM {$table} WHERE {$field} {$operator} '{$value}' ORDER BY {$order} ";	
			}
		}else {
			$sql = "SELECT * FROM {$table} ORDER BY {$order} ";	
		}

		if($limit1){
			$sql .= " LIMIT {$limit1} ";
			if ($limit2) {
			$sql .= " , {$limit2} ";
			}
		}

		if (!$this->query($sql)->error()) {
			return $this;
		}
		return false;
	}

	public function get_limit($table, $where = array(), $limit1 = null, $limit2 = null){
		$sql = "SELECT * FROM {$table} ";

		if(count($where) === 3){
			$operators = array('=','>','<','>=','<=');

			$field		 	= $where[0];
			$operator		= $where[1];
			$value 			= $where[2];
			if(in_array($operator, $operators)){
				$sql .= " WHERE {$field} {$operator} '{$value}' ";
			}
		}

		if($limit1){
			$sql .= " LIMIT {$limit1} ";
			if ($limit2) {
			$sql .= " , {$limit2} ";
			}
		}

		if (!$this->query($sql)->error()) {
			return $this;
		}
		return false;
	}

	public function table_exists($table){
		$sql = $this->query("SHOW TABLES LIKE '{$table}' ");
		if ($this->_count > 0) {
			return true;
		}else {
			return false;
		}
	}

	public function drop_table($table){
		$sql 	= "DROP   TABLE IF EXISTS {$table}";

		if (!$this->query($sql)->error()) {
			return true;
		}
		return false;

	}


	public function results(){
		return $this->_results;
	}

	public function first(){
		return $this->results()[0];
	}

	public function next($next){
		$x = (int)$next;
		return $this->results()[$x];
	}

	public function last(){
		$count = $this->_count;
		$x = $count - 1;
		$result = $this->results()[$x];
		return $result;
	}

	public function all(){
		$count = $this->_count;
		$all_results = array();
		$x = 0;
		while ($x < $count) {
		    $all_results[] = $this->results()[$x]; 
		    $x++;
		}
		return $all_results;
	}

	public function error(){
		return $this->_error;
	}

	public function error_message(){
		return $this->_error_message;
	}

	public function count(){
		return $this->_count;
	}

}
?>