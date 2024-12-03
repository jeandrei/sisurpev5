<?php
	class Turma {
		private $db;

		public function __construct(){			
			$this->db = new Database;
		}

		// Retorna uma turma a partir do id
		public function getTurmaById($id){       
			$this->db->query("
				SELECT 
					descricao 
				FROM 
					turma 
				WHERE id = :id
			");        
			$this->db->bind(':id', $id);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}

    //Retorna todas as turmas
    public function getTurmas(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					turma 
				ORDER BY 
					descricao ASC
			");
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
    }

    //Retorna as turmas de uma escola
    public function getTurmasEscolaById($escolaId){        
			$this->db->query("
				SELECT 
					*                          
				FROM 
					turma 
				WHERE 
					escolaId = :escolaId                                               
				ORDER BY 
					descricao ASC
			");
			$this->db->bind(':escolaId', $escolaId);        
			$results = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			} 
    }  
	}
?>