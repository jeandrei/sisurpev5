<?php
	class Regiao {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		//Encontra regiões by id
		public function getRegiaoById($id){
			$this->db->query("
				SELECT 
					regioes 
				FROM 
					regiao 
				WHERE 
					id = :id
			");        
			$this->db->bind(':id', $id);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row->regiao;
			} else {
				return false;
			}
		}

		//Retorna todos as regiões
		public function getRegioes(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					regioes 
				ORDER BY 
					regiao ASC
			");
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}
	}
?>