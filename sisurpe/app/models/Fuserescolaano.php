<?php
	class Fuserescolaano {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		// Registra uma escola e ano do usuário
		public function register($data){            
			$this->db->query("
				INSERT INTO f_user_escola (escolaId, userId) VALUES (:escolaId, :userId)
			");			
			$this->db->bind(':escolaId',$data['escolaId']);
			$this->db->bind(':userId',$data['userId']);			
			if($this->db->execute()){
				return $this->db->lastId;
			} else {
				return false;
			}
		}

		// RETORNA TODAS AS ESCOLAS DO USUÁRIO NA TABELA f_user_escola
		public function getEscolasUser($user_id) {          
			$this->db->query("
				SELECT 
					escola.id as escolaId, 
					escola.nome as escolaNome, 
					f_user_escola.escolaId as fuEscolaId,
          f_user_escola.ano as ano  
				FROM 
					escola, 
					f_user_escola 
				WHERE 
					escola.id = f_user_escola.escolaId 
				AND 
					f_user_escola.userId = :userId
        AND ano = YEAR(CURDATE())
        ORDER BY
          ano DESC
			");
			$this->db->bind(':userId',$user_id);
			$result = $this->db->resultSet();			
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		// Deleta um registro da tabela f_user_escola
		public function delete($_escolaId,$_userId){         
			$this->db->query("
				DELETE FROM 
					f_user_escola 
				WHERE 
					escolaId = :escolaId 
				AND 
					userId = :userId
			");
			$this->db->bind(':escolaId', $_escolaId);
			$this->db->bind(':userId', $_userId);		       
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}         

		// Update Escola
		public function update($data){    
			$this->db->query("
				UPDATE 
					escola 
				SET 
					nome = :nome, 
					bairro_id = :bairro_id, 
					logradouro = :logradouro, 
					numero = :numero, 
					emAtividade = :emAtividade 
				WHERE 
					id = :id
			");		
			$this->db->bind(':id',$data['id']);
			$this->db->bind(':nome',$data['nome']);            
			$this->db->bind(':bairro_id',$data['bairro_id']);
			$this->db->bind(':logradouro',$data['logradouro']);
			$this->db->bind(':numero',$data['numero']);
			$this->db->bind(':emAtividade',$data['emAtividade']);	
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		// RETORNA O NOME DE UMA ESCOLA A PARTIR DE UM ID
		public function getNomeEscola($id) {
			$this->db->query("
				SELECT 
					nome 
				FROM 
					escola 
				WHERE 
					id = :id
			");
			$this->db->bind(':id', $id);    
			$row = $this->db->single(); 
			if($this->db->rowCount() > 0){
				return $row->nome;
			} else {
				return false;
			} 
		}
		// Busca etapa por id
		public function getEscolaByid($id){
			$this->db->query("
				SELECT 
					* 
				FROM 
					escola 
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
					
		public function atualizaSituacao($id,$situacao){  
			if($situacao == 'true')          {
				$sql = "
					UPDATE 
						escola 
					SET 
						emAtividade = 1 
					WHERE 
						id = :id
				";
			} else {
				$sql = "
					UPDATE 
						escola 
					SET 
						emAtividade = 0 
					WHERE 
						id = :id
				";
			}									
			$this->db->query($sql);				
			$this->db->bind(':id',$id);  				
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function getUserEscolaAno($_userId,$_escolaId,$_ano){             
			$this->db->query("
				SELECT 
					* 
				FROM 
					f_user_escola 
				WHERE 
					userId = :userId 
				AND 
					escolaId = :escolaId 
				AND 
					ano = :ano
			");			
			$this->db->bind(':userId', $_userId);
			$this->db->bind(':escolaId', $_escolaId);
			$this->db->bind(':ano', $_ano);            
			$row = $this->db->single();			
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
		}
	}    
?>