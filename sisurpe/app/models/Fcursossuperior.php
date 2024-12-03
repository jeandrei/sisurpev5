<?php
	class Fcursossuperior {
		private $db;

		public function __construct(){					
			$this->db = new Database;
		}
		
		public function getCursosSup(){
			$this->db->query("
				SELECT 
					cs.cursoId as cursoId, 
					cs.curso as curso 
				FROM 
					f_curso_superior cs
			");           
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		public function getCursoById($_cursoId){
			$this->db->query("
				SELECT 
					c.cursoId as cursoId, 
					c.curso as curso 
				FROM 
					f_curso_superior c 
				WHERE 
					c.cursoId = :cursoId
			"); 
			$this->db->bind(':cursoId',$_cursoId); 
			$row = $this->db->single();       
			if($this->db->execute()){
				return $row;
			} else {
				return false;
			}
		}

		public function getCursosAreaById($_areaId){
			$this->db->query("
				SELECT 
					c.cursoId as cursoId, 
					c.curso as curso 
				FROM 
					f_curso_superior c 
				WHERE 
					c.areaId = :areaId 
				ORDER BY 
					c.curso ASC
			"); 
			$this->db->bind(':areaId',$_areaId); 
			$result = $this->db->resultSet();     
			if($this->db->execute()){
				return $result;
			} else {
				return false;
			}
		}
	}    
?>