<?php
	class Fareacurso {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}
		
		public function getAreasCurso(){
			$this->db->query("
				SELECT 
					fac.areaId as areaId, 
					fac.area as area 
				FROM 
					f_areas_curso fac 
				ORDER BY 
					fac.area ASC
			");           
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		public function getAreaById($_areaId){
			$this->db->query("
				SELECT 
					a.areaId as areaId, 
					a.area as area 
				FROM 
					f_areas_curso a 
				WHERE 
					a.areaId = :areaId
			"); 
			$this->db->bind(':areaId',$_areaId); 
			$row = $this->db->single();       
			if($this->db->execute()){
				return $row;
			} else {
				return false;
			}
		}
	}//Fareacurso    
?>