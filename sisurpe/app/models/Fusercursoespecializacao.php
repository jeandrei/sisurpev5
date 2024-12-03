<?php
	class Fusercursoespecializacao {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		//retorna se já tem no banco um usuário com uma complementação cadastrada
		public function getUserEspCursoArea($_userId,$_areaId){             
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_user_pos_curso 
				WHERE 
					userId = :userId 
				AND 
					areaId = :areaId
			");				
			$this->db->bind(':userId', $_userId);
			$this->db->bind(':areaId', $_areaId);                     
			$row = $this->db->single(); 				
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}

		//Retorna todas as formações/complementações do usuário
		public function getUserEspCursos($_userId){             
			$this->db->query("
				SELECT 
					fac.area as area, 
					fupc.fupcId as fupcId, 
					fupc.areaId as areaId, 
					fupc.userId as userId, 
					fupc.anoConclusao as anoConclusao 
				FROM 
					f_user_pos_curso fupc, 
					f_areas_curso fac 
				WHERE 
					fupc.areaId = fac.areaId 
				AND 
					userId = :userId 
				ORDER BY 
					fupc.anoConclusao ASC
			");				
			$this->db->bind(':userId', $_userId);                              
			$result = $this->db->resultSet();				
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}						

		// Registra 
		public function register($data){            
			$this->db->query("
				INSERT INTO f_user_pos_curso (userId, areaId, anoConclusao) VALUES (:userId, :areaId, :anoConclusao)
			");				
			$this->db->bind(':userId',$data['userId']);
			$this->db->bind(':areaId',$data['areaId']);
			$this->db->bind(':anoConclusao',$data['anoConclusao']);				
			if($this->db->execute()){
				return $this->db->lastId;
			} else {
				return false;
			}
		}

		// Deleta um registro da tabela f_user_pos_curso
		public function delete($_fupcId){ 
			$this->db->query("
				DELETE FROM 
					f_user_pos_curso 
				WHERE 
					fupcId = :fupcId
			");
			$this->db->bind(':fupcId', $_fupcId);
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}       
	}    
?>