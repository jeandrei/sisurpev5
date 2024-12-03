<?php
	class Fnivelcurso {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}
		
		public function getNivelCurso(){
			$this->db->query("
				SELECT 
					nc.nivelId as nivelId, 
					nc.nivel as nivel 
				FROM 
					f_nivel_curso nc
			");           
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		public function getNivelById($_nivelId){
			$this->db->query("
				SELECT 
					nc.nivelId as nivelId, 
					nc.nivel as nivel 
				FROM 
					f_nivel_curso nc 
				WHERE 
					nc.nivelId = :nivelId
			"); 
			$this->db->bind(':nivelId',$_nivelId); 
			$row = $this->db->single();       
			if($this->db->execute()){
				return $row;
			} else {
				return false;
			}
		}
	}    
?>