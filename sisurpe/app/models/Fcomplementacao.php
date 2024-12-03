<?php
	class Fcomplementacao {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}		

		// RETORNA TODAS AS COMPLEMENTAÇÕES CADASTRADAS
		public function getComplementacoes() {
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_complementacao_pedagogica 
				ORDER BY 
					complementacao ASC
			");
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			} 
		}

		// RETORNA UMA COMPLEMENTAÇÃO POR ID
		public function getComplementacaoById($_cpId){
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_complementacao_pedagogica 
				WHERE 
					cpId = :cpId
			");		
			$this->db->bind(':cpId', $_cpId);
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}
	}//final    
?>