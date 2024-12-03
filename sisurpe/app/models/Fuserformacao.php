<?php
	class Fuserformacao {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		// Registra a formacao na tabela f_user_formacao 
		public function register($data){                    
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_user_formacao 
				WHERE 
					userId = :userId
			");
			$this->db->bind(':userId',$data['userId']);
			$result = $this->db->resultSet();            
			//verifico se já tem cadastro, se não tem registro se tem atualizo            
			if($this->db->rowCount() > 0){
				//update
				$sql = "
					UPDATE 
						f_user_formacao 
					SET 
						maiorEscolaridade = :maiorEscolaridade, 
						tipoEnsinoMedio = :tipoEnsinoMedio 
					WHERE 
						userId = :userId
				";
			} else {
				//insert
				$sql = "
					INSERT INTO f_user_formacao (userId, maiorEscolaridade,tipoEnsinoMedio) VALUES (:userId, :maiorEscolaridade,:tipoEnsinoMedio)
				";
			}			
			$this->db->query($sql);			
			$this->db->bind(':userId',$data['userId']);
			$this->db->bind(':maiorEscolaridade',$data['maiorEscolaridade']);
			$this->db->bind(':tipoEnsinoMedio',$data['tipoEnsinoMedio']);			
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function getUserFormacoesById($_userId){            
			$this->db->query("
				SELECT 
					fuf.userId as userId, 
					fuf.maiorEscolaridade as maiorEscolaridade, 
					fuf.tipoEnsinoMedio as tipoEnsinoMedio 
				FROM 
					f_user_formacao fuf 
				WHERE 
					fuf.userId = :userId
			");
			$this->db->bind(':userId',$_userId);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}			
	}    
?>