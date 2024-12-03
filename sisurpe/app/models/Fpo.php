<?php
	class Fpo {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}
		
		public function getPos(){            
			$this->db->query("
				SELECT 
					fp.posId as posId, 
					fp.pos as pos 
				FROM 
					f_pos fp
			");           
			$results = $this->db->resultSet();  
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}  
		}        
	}    
?>