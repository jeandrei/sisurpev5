<?php

class Grupo {
    private $db;

    public function __construct(){
      //inicia a classe Database
      $this->db = new Database;
    }

    //Retorna um grupo a partir do id
		public function getGrupoById($grupoId){
			$this->db->query('SELECT * FROM grupos WHERE id = :grupoId'); 
			$this->db->bind(':grupoId', $grupoId);
			$result = $this->db->single();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

    //Retorna totos os grupos cadastrados
		public function getGrupos(){
			$this->db->query('SELECT * FROM grupos'); 			
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

    //Retorna os dados para a paginação
		public function getGrupoPag($page,$options){ 
			$sql = "SELECT * FROM grupos WHERE 1"; 				
			if(!empty($options['named_params'][':grupo'])){
				$sql .= " AND grupo LIKE '%" . $options['named_params'][':grupo']."%'";
			} 			
			$sql .= ' ORDER BY grupos.grupo ASC ';  
			//TENTA EXECUTAR A PAGINAÇÃO 
			try	{
				$this->pag = new Pagination($page,$sql, $options);  
			} catch(paginationException $e)	{
					echo $e;
					exit();
			}  
			//EXECUTA A PAGINAÇÃO
			$this->pag->execute();
			//RETORNA A PAGINAÇÃO
			return $this->pag;     
		} 

    public function gruposDoUsuario($userId){
      $this->db->query('SELECT * FROM usersGrupos WHERE userId = :userId');      
      $this->db->bind(':userId', $userId);
      $result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
    }     

    // retorna se o usuário tem permição para executar uma ação na tabela grupos
    // acao = ler, editar, apagar, criar
    public function getPermicao($acao,$userId,$tabela){  
      $gruposUsuario = $this->gruposDoUsuario($userId); 
    
      if($gruposUsuario){
        foreach($gruposUsuario as $grupo){
          $this->db->query("SELECT * FROM grupoAcaoTabela WHERE grupoId = :grupoId AND tabela = :tabela AND ".$acao." = 's'"); 
          $this->db->bind(':grupoId', $grupo->grupoId);
          $this->db->bind(':tabela', $tabela);         
          $row = $this->db->single();
          if($this->db->rowCount() > 0){            
            return true;
          }
        }
      } else {
        return false;
      }   
    }    

    //Retorna se já existe um grupo
		public function grupoExiste($grupo){
			$this->db->query('SELECT * FROM grupos WHERE grupo = :grupo'); 
			$this->db->bind(':grupo', $grupo);
			$result = $this->db->single();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}
    
    // Registra um grupo
    public function register($data){            
      $this->db->query('INSERT INTO grupos (grupo) VALUES (:grupo)');      
      $this->db->bind(':grupo',$data['grupo']); 
      if($this->db->execute()){
        $grupoId = $this->db->lastId;   
        //pega as tabelas do banco de dados    
        $tabelas = $this->db->getTables();                
        foreach($tabelas as $tabela){   
          if((isset($tabela) && $tabela !== "")){
            $this->criaAcaoTabela($grupoId, $tabela);
          }          
        }
        return $grupoId;
      } else {
        return false;
      }
    }

    //Cria todas as permições dentro da tabela grupoAcaoTabela para um determinado grupo e define todas as permições como n
    public function criaAcaoTabela($grupoId, $tabela){
      $this->db->query('INSERT INTO grupoAcaoTabela SET grupoId = :grupoId, tabela = :tabela');      
      $this->db->bind(':grupoId',$grupoId); 
      $this->db->bind(':tabela',$tabela); 
      if($this->db->execute()){       
        return true;
      } else {
        return false;
      }
    }

    //verifica se tem usuários com um determinado grupo     
    public function existeUsersGrupo($grupoId){      
      $this->db->query('SELECT * FROM usersGrupos WHERE grupoId = :grupoId');				
      $this->db->bind(':grupoId', $grupoId);
      $result = $this->db->single();		
      if($this->db->rowCount() > 0){
        return $result;
      } else {
        return false;
      }		
    }

    //Apaga todos os registros de um grupo na tabela usersGupos
    public function delUsersGrupo($grupoId){
      if(!$this->existeUsersGrupo($grupoId)){       
        return true;
      }      
      $this->db->query('DELETE FROM usersGrupos WHERE grupoId = :grupoId');				
      $this->db->bind(':grupoId', $grupoId);
      $this->db->execute();			
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }		
    }

    //verifica se tem registro de um grupo na tabela grupoAcaoTabela   
    public function existeGrupoAcaoTabela($grupoId){      
      $this->db->query('SELECT * FROM grupoAcaoTabela WHERE grupoId = :grupoId');				
      $this->db->bind(':grupoId', $grupoId);
      $result = $this->db->resultSet();	
      if($this->db->rowCount() > 0){
        return $result;
      } else {
        return false;
      }		
    }

     //Apaga todos os de um determinado grupo dentro da tabela grupoAcaoTabela
     public function delGrupoAcaoTabela($grupoId){
      if(!$this->existeGrupoAcaoTabela($grupoId)){       
        return true;
      }      
      $this->db->query('DELETE FROM grupoAcaoTabela WHERE grupoId = :grupoId');				
      $this->db->bind(':grupoId', $grupoId);
      $this->db->execute();			
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }		
    }

    //Deleta um grupo
		public function delGrupoByid($grupoId){
      //apaga todos os registros do grupo na tabela grupoAcaoTabela
      if(!$this->delGrupoAcaoTabela($grupoId)){
        return false;
      }  
      //apaga todos os registros do grupo na tabela usersGrupos
      if(!$this->delUsersGrupo($grupoId)){
        return false;
      }    
      $this->db->query('DELETE FROM grupos WHERE id = :grupoId');				
      $this->db->bind(':grupoId', $grupoId);
      $this->db->execute();			
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }  	
		}		

   
		public function update($data){
      $grupoId = $data['id'];
      if(!$this->apagaPermicoesGrupo($grupoId)){
        return false;
      }
			$this->db->query('UPDATE grupos SET grupo = :grupo WHERE id = :id');			
      $this->db->bind(':id',$grupoId);	
			$this->db->bind(':grupo',$data['grupo']);							
			if($this->db->execute()){
        $this->updatePermicao($data['permicoes']);
				return true;
			} else {
				return false;
			}
		}

    //Apaga todas as permições de um grupo na tabela grupoAcaoTabela
    public function apagaPermicoesGrupo($grupoId){      
			$this->db->query("UPDATE grupoAcaoTabela SET ler = 'n', editar = 'n', criar = 'n', apagar = 'n' WHERE grupoId = :grupoId");			
      $this->db->bind(':grupoId',$grupoId);									
			if($this->db->execute()){        
				return true;
			} else {
				return false;
			}
		}

    public function updateLer($grupoAcaoTabelaId){
      $this->db->query("UPDATE grupoAcaoTabela SET ler = 's' WHERE id = :id");			
      $this->db->bind(':id',$grupoAcaoTabelaId);										
			if($this->db->execute()){        
				return true;
			} else {
				return false;
			}
    }

    public function updateEditar($grupoAcaoTabelaId){
      $this->db->query("UPDATE grupoAcaoTabela SET editar = 's' WHERE id = :id");			
      $this->db->bind(':id',$grupoAcaoTabelaId);										
			if($this->db->execute()){        
				return true;
			} else {
				return false;
			}
    }

    public function updateCriar($grupoAcaoTabelaId){
      $this->db->query("UPDATE grupoAcaoTabela SET criar = 's' WHERE id = :id");			
      $this->db->bind(':id',$grupoAcaoTabelaId);										
			if($this->db->execute()){        
				return true;
			} else {
				return false;
			}
    }

    public function updateApagar($grupoAcaoTabelaId){
      $this->db->query("UPDATE grupoAcaoTabela SET apagar = 's' WHERE id = :id");			
      $this->db->bind(':id',$grupoAcaoTabelaId);										
			if($this->db->execute()){        
				return true;
			} else {
				return false;
			}
    }


    public function updatePermicao($data){
      if($data['ler']){
        foreach($data['ler'] as $id){
          $this->updateLer($id);
        }
      }
      
      if($data['editar']){
        foreach($data['editar'] as $id){
          $this->updateEditar($id);
        }
      }
      
      if($data['criar']){
        foreach($data['criar'] as $id){
          $this->updateCriar($id);
        }
      }     

      if($data['apagar']){
        foreach($data['apagar'] as $id){
          $this->updateApagar($id);
        }
      }
      
    }

    //Retorna as permições de um grupo na tabela grupoAcaoTabela
    public function getPermicoesGrupo($grupoId){
			$this->db->query('SELECT * FROM grupoAcaoTabela WHERE grupoId = :grupoId');  
      $this->db->bind(':grupoId',$grupoId);	
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}
   
//class
}
?>