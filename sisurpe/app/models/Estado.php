<?php
	class Estado {
			private $db;

			public function __construct(){				
				$this->db = new Database;
			}

		// Retorna um estado a partir do id
		public function getEstadoById($id){       
			$this->db->query("
				SELECT 
					estado 
				FROM 
					estados 
				WHERE 
					id = :id
			");        
			$this->db->bind(':id', $id);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}

		//Retorna todos os estados do banco de dados
		public function getEstados(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					estados 
				ORDER BY 
					estado ASC
			");
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		//Retorna os estados de uma região
		public function getEstadosRegiaoById($regiaoId){        
			$this->db->query("
				SELECT 
					*                          
				FROM 
					estados 
				WHERE 
					regiaoId = :regiaoId                                               
				ORDER BY 
					estados.estado ASC
			");
			$this->db->bind(':regiaoId', $regiaoId);        
			$results = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			} 
		}  
	}
?>