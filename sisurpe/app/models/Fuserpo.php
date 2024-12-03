<?php
	class Fuserpo {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		public function getUserPos($_userId){            
			$this->db->query("
				SELECT 
					fup.userId as userId, 
					fup.posId as posId, 
					fp.pos as pos 
				FROM 
					f_user_pos fup, 
					f_pos fp 
				WHERE 
					fup.posId = fp.posId 
				AND 
					fup.userId = :userId
			");
			$this->db->bind(':userId', $_userId);           
			$results = $this->db->resultSet();  
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}  
		}

		public function deleteAllPosUser($_userId){
			//se tem pos cadastrada
			if($this->getUserPos($_userId)){
				$this->db->query("
					DELETE FROM 
						f_user_pos 
					WHERE 
						userId = :userId
				");
				$this->db->bind(':userId', $_userId);                
				if($this->db->execute()){
					return true;
				} else {
					return false;
				}   
			} else {
				return true;
			}
		}

		public function deleteAllUserPosCurso($_userId){
			//se tem pos cadastrada
			if($this->getUserPos($_userId)){								
				$this->db->query("
					DELETE FROM 
						f_user_pos_curso 
					WHERE 
						userId = :userId
				");
				$this->db->bind(':userId', $_userId);                
				if($this->db->execute()){
					return true;
				} else {
					return false;
				}   
			} else {
				return true;
			}
		}

		//Registra as pos do usuário
		public function register($data,$_userId){            
			//se for 1 quer dizer que é a opção Não tem pós-graduação concluida
			//então se o usuário tem alguma especialização registrada eu apago
			if($data[0] == 1){
				if(!$this->deleteAllUserPosCurso($_userId)){ 
					return false;
				} 
			}			
			if(!$this->deleteAllPosUser($_userId)){
				return false;
			}
			$error = false;
			if($data){
				foreach($data as $row){
					$this->db->query("
						INSERT INTO f_user_pos SET userId = :userId, posId = :posId
					");
					$this->db->bind(':userId',$_userId);
					$this->db->bind(':posId',$row); 
					if(!$this->db->execute()){
						$error = true;
					}
				}
			}             
			if($error == true){
				return false;
			} else {
				return true;
			}
		}
		
		//Retorna o total de profissionais com uma especialização de uma escola
		public function getTotalEspecEscola($_escolaId,$_posId,$_ano){
			$this->db->query("
				SELECT 
					COUNT(fup.userId) as total 
				FROM 
					f_user_pos fup, 
					f_user_escola fue 
				WHERE 
					fup.userId = fue.userId 
				AND 
					fup.posId = :posId 
				AND 
					fue.escolaId = :escolaId 
				AND 
					fue.ano = :ano
			");
			$this->db->bind(':escolaId',$_escolaId);
			$this->db->bind(':posId',$_posId);
			$this->db->bind(':ano',$_ano);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}

		public function getUsersPos($_escolaId,$_ano){ 
			$bind = [];           
			if($_escolaId == 'null'){
				$sql = "
					SELECT 
						u.name as nome, 
						u.cpf as cpf, 
						fp.pos as pos, 
						e.nome as escola, 
						fup.userId as userId, 
						fup.posId as posId, 
						fue.escolaId as escolaId, 
						fue.ano as ano 
					FROM 
						users u, 
						f_pos fp, 
						escola e, 
						f_user_pos fup, 
						f_user_escola fue 
					WHERE 
						u.id = fup.userId 
					AND 
						fup.userId = fue.userId 
					AND 
						fp.posId = fup.posId 
					AND 
						e.id = fue.escolaId 
					AND 
						fue.ano = :ano 
					ORDER BY 
						e.nome, 
						u.name, 
						fp.pos ASC
				";
				$bind += [':ano' => $_ano]; 
			} else {
				$sql = "
					SELECT 
						u.name as nome, 
						u.cpf as cpf, 
						fp.pos as pos, 
						e.nome as escola, 
						fup.userId as userId, 
						fup.posId as posId, 
						fue.escolaId as escolaId, 
						fue.ano as ano 
					FROM 
						users u, 
						f_pos fp, 
						escola e, 
						f_user_pos fup, 
						f_user_escola fue 
					WHERE 
						u.id = fup.userId 
					AND 
						fup.userId = fue.userId 
					AND 
						fp.posId = fup.posId 
					AND 
						e.id = fue.escolaId 
					AND 
						fue.escolaId = :escolaId 
					AND 
						fue.ano = :ano 
					ORDER BY 
						u.name, 
						fp.pos ASC
				";
				$bind += [':escolaId' => $_escolaId]; 
				$bind += [':ano' => $_ano]; 
			}		
			$this->db->query($sql);
      foreach($bind as $key => $value){             
        $this->db->bind($key, $value, PDO::PARAM_STR, 12);            
      } 				
			$results = $this->db->resultSet();  
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}
		}

		public function getUsersSemRespostaPos($_escolaId,$_ano){      
			$bind = []; 
			if($_escolaId == 'null'){
				$sql = "
					SELECT 
						u.name as nome, 
						e.nome as escola, 
						fue.ano as ano 
					FROM 
						users u, 
						escola e, 
						f_user_escola fue, 
						f_user_formacao fuf 
					WHERE 
						u.id = fue.userId 
					AND 
						fue.escolaId = e.id 
					AND 
						fuf.userId = fue.userId 
					AND 
						fuf.maiorEscolaridade = 'e_superior' 
					AND 
						fue.ano = :ano 
					AND 
						NOT EXISTS (SELECT * FROM f_user_pos fup WHERE fup.userId = fue.userId) 
					ORDER BY 
						e.nome, 
						u.name ASC
				";
				$bind += [':ano' => $_ano];        
			} else {
				$sql = "
					SELECT 
						u.name as nome, 
						e.nome as escola, 
						fue.ano as ano 
					FROM 
						users u, 
						escola e, 
						f_user_escola fue, 
						f_user_formacao fuf 
					WHERE 
						u.id = fue.userId 
					AND 
						fue.escolaId = e.id 
					AND 
						fue.escolaId = :escolaId 
					AND 
						fuf.userId = fue.userId 
					AND 
						fuf.maiorEscolaridade = 'e_superior' 
					AND 
						fue.ano = :ano 
					AND 
						NOT EXISTS (SELECT * FROM f_user_pos fup WHERE fup.userId = fue.userId) 
					ORDER BY 
						e.nome, 
						u.name ASC
				";
				$bind += [':escolaId' => $_escolaId]; 
				$bind += [':ano' => $_ano];
			}      
			$this->db->query($sql);
      foreach($bind as $key => $value){             
				$this->db->bind($key, $value, PDO::PARAM_STR, 12);            
			} 				         
			$results = $this->db->resultSet();  
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}
		}			
	}    
?>