<?php
	class Foutroscurso {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}
		
		public function getOutrosCursos(){            
			$this->db->query("
				SELECT 
					foc.cursoId as cursoId, 
					foc.curso as curso 
				FROM 
					f_outros_cursos foc
			");           
			$results = $this->db->resultSet();  
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}  
		} 
		
		public function getOutrosCursosById($_cursoId){            
			$this->db->query("
				SELECT 
					foc.cursoId as cursoId, 
					foc.curso as curso 
				FROM 
					f_outros_cursos foc 
				WHERE 
					foc.cursoId = :cursoId
			"); 
			$this->db->bind(':cursoId',$_cursoId); 
			$row = $this->db->single();       
			if($this->db->execute()){
				return $row;
			} else {
				return false;
			}
		}
	}    
?>