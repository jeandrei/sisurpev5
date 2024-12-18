<?php
	class Userescolacoleta {
		private $db;

		public function __construct(){					
			$this->db = new Database;
		}

		public function getUserEscolaById($userId, $escolaId){
			$this->db->query("
				SELECT 
					*                          
				FROM 
						user_escola_coleta 
				WHERE 
						userId = :userId 
				AND
						escolaId = :escolaId                           
			");
			$this->db->bind(':userId', $userId);
			$this->db->bind(':escolaId', $escolaId);			
			$results = $this->db->resultSet();   
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}         
		}

		// Vincula o usuário a user_escola_coleta
		public function register($data){									
			$this->db->query("
				INSERT INTO user_escola_coleta (escolaId, userId) VALUES (:escolaId, :userId)
			");			
			$this->db->bind(':escolaId',$data['escolaId']);     
			$this->db->bind(':userId',$data['userId']); 			
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		//Retorna as escolas vinculadas ao usuário coleta
		public function getEscolaColetaUserById($userId){     
			$this->db->query("
				SELECT 
						uec.id as uecId, 
						e.id as escolaId, 
						emAtividade as emAtividade,
						e.nome as nome 
				FROM 
						user_escola_coleta uec, 
						escola e 
				WHERE 
						uec.escolaId = e.id 
				AND 
						userId = :userId
			");
			$this->db->bind(':userId', $userId);				
			$results = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}         
		}
			

		public function delete($id){        
			$this->db->query("
				DELETE FROM user_escola_coleta WHERE id = :id
			");			
			$this->db->bind(':id',$id);
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function updateTipo($userId){
			$this->db->query("
					UPDATE 
						users 
					SET 
						type = 'interno'              
					WHERE 
						id = :id                      
			");
			$this->db->bind(':id',$userId);				
			if($this->db->execute()){ 
					return $userId;
			} else {
					return false;
			}   
		}   
	}
?>