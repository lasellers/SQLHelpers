<?php

class simpledb {
	
	private $dbconfig;
	private $db;
	private $sanitize;
	private $validate;
	
	public function __construct($dbconfig,$sanitize,$validate)
	{
		$this->dbconfig=$dbconfig;
		$this->sanitize=$sanitize;
		$this->validate=$validate;
		
		$this->db = new PDO('mysql:host=localhost;dbname='.$this->dbconfig['name'].';charset=utf8mb4', $this->dbconfig['username'], $this->dbconfig['password']);
		
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
	
	public function printRows($rows)
	{
		echo '<table border=1>';
		
		if(is_array($rows) && count($rows)) {
			$r=array_values($rows);
			
			if(isset($r[0]))
				$columns=array_keys($r[0]);
			else if($r!==null)
				$columns=array_keys($r);
			
			echo '<tr>';
			foreach($columns as $column) {
				echo '<th>'.$column . "</th>";
			}
			echo '</tr>';
			
			foreach($rows as $index=>$row) {
				echo '<tr>';
				foreach($row as $key=>$value) {
					echo '<td>'.$value . '</td>';
				}
				echo '</tr>';
			}
		}
		
		echo '</table>';
	}
	
	public function sanitize(array $datums,array $types): array
	{
		if(count($datums)==0 || count($datums)!=count($types)) {
			return $datums;
		}
		
		foreach($types as $index=>$type) {
			$value=$datums[$index];
			if(!$this->validate->byType($type,$value))																			
            {
				$datums[$index]="";
			}
			else {
				$datums[$index]=$this->sanitize->byType($type,$value);
			}
		}
		
		return $datums;
	}
	
	public function query(string $sql, array $prepared=[], array $types=[]): array
	{
		if(count($prepared)>0) {
			$prepared=$this->sanitize($prepared,$types);
		}
		
		$rows=null;
		
		try {
			$result = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$result->execute($prepared);
			$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $ex) {
			echo "PDO query error: ".$ex->getMessage()."<br>";
			// 			exit;
		}
		
		return $rows;
	}
	
	
	public function exec(string $sql, array $prepared=[], array $types=[]): array
	{
		if(count($prepared)>0) {
			$prepared=$this->sanitize($prepared,$types);
		}
		
        $ret=[
		'result'=>0,
        'rowCount'=>0
        ];
		
		try {
			$result = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			$ret['result'] = $result->execute($prepared);
            $ret['rowCount'] = $result->rowCount();
		}
		catch(PDOException $ex) {
			echo "PDO exec error: ".$ex->getMessage()."<br>";
			// 			exit;
		}
	
		return [$ret];
	}
	
}
