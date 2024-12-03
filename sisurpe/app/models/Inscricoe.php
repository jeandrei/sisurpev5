<?php
  class Inscricoe {
    private $db;

    public function __construct(){
			$this->db = new Database;        
    }

    public function getInscricoes(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					inscricoes 
				WHERE 
					fase != 'ARQUIVADO' 
				ORDER BY 
					data_inicio DESC
			"); 
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}           
    }
    
    public function getInscricoesArquivadas(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					inscricoes 
				WHERE 
					fase = 'ARQUIVADO' 
				ORDER BY 
					data_inicio DESC
			"); 
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}           
    }

    public function getInscricaoById($id){
			$this->db->query("
				SELECT 
					* 
				FROM 
					inscricoes 
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
  
    public function inscricaoEditavel($inscricoes_id=null){
			$this->db->query("
				SELECT 
					* 
				FROM 
					inscricoes 
				WHERE 
					id = :id
				AND
					fase != 'ARQUIVADO'
				ORDER BY 
					data_inicio 
				DESC
			"); 
			$this->db->bind(':id',$inscricoes_id);  
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){            
				return true;
			} else {
				return false;
			}           
    }

    public function inscricaoAberta($inscricoes_id=null){
			$this->db->query("
				SELECT 
					* 
				FROM 
					inscricoes 
				WHERE 
					id = :id
				AND
					fase = 'ABERTO'
			"); 
			$this->db->bind(':id',$inscricoes_id);  
			$row = $this->db->single();
			if($this->db->rowCount() > 0){					
				return true;
			} else {
				return false;
			}           
    } 

    public function register($data){         
			$this->db->query("
				INSERT INTO inscricoes (nome_curso, descricao, data_inicio,data_termino, localEvento, horario, periodo, fase, certificado) VALUES (:nome_curso, :descricao, :data_inicio, :data_termino, :localEvento, :horario, :periodo, :fase, :certificado)
			");			
			$this->db->bind(':nome_curso',$data['nome_curso']);
			$this->db->bind(':descricao',$data['descricao']);        
			$this->db->bind(':data_inicio',$data['data_inicio']);
			$this->db->bind(':data_termino',$data['data_termino']);
			$this->db->bind(':localEvento',$data['localEvento']);
			$this->db->bind(':horario',$data['horario']);
			$this->db->bind(':periodo',$data['periodo']);	
			$this->db->bind(':certificado',$data['certificado']);			
			//Se o usuário não passar a faze da inscrição definimos como Aberto
			if($data['fase'] ==''){
				$data['fase'] = 'ABERTO';
			}; 
			$this->db->bind(':fase',$data['fase']);		
			if($this->db->execute()){
				return  $this->db->lastId;              
			} else {
				return false;
			}
    }

    public function update($data){   
			$this->db->query("
				UPDATE 
					inscricoes  
				SET                                           
					nome_curso = :nome_curso,
					descricao = :descricao,
					data_inicio = :data_inicio, 
					data_termino = :data_termino,                                    
					localEvento = :localEvento,
					periodo = :periodo,
					horario = :horario,
					fase = :fase,
					certificado = :certificado  
				WHERE 
					id = :id
			");  
			$this->db->bind(':id',$data['id']);            
			$this->db->bind(':nome_curso',$data['nome_curso']);
			$this->db->bind(':descricao',$data['descricao']);        
			$this->db->bind(':data_inicio',$data['data_inicio']);
			$this->db->bind(':data_termino',$data['data_termino']);        
			$this->db->bind(':localEvento',$data['localEvento']);
			$this->db->bind(':periodo',$data['periodo']);
			$this->db->bind(':horario',$data['horario']);        
			$this->db->bind(':fase',$data['fase']); 
			$this->db->bind(':certificado',$data['certificado']);
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
    }

    public function arquivaInscricao($inscricoes_id){
			$this->db->query("
				UPDATE 
					inscricoes
				SET 
					fase = 'ARQUIVADO'  
				WHERE 
					id = :id
			");			   
			$this->db->bind(':id',$inscricoes_id);       				
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
    }

    public function getPresencasUsuarioById($user_id,$insc_id){
			$this->db->query("
				SELECT 
					abre_presenca.carga_horaria as carga_horaria_tema  
				FROM 
					presenca, 
					abre_presenca, 
					inscricoes 
				WHERE 
					presenca.abre_presenca_id = abre_presenca.id 
				AND 
					abre_presenca.inscricoes_id = inscricoes.id 
				AND 
					presenca.user_id = :user_id 
				AND 
					inscricoes.id = :insc_id
			"); 
			$this->db->bind(':user_id',$user_id);  
			$this->db->bind(':insc_id',$insc_id); 
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){					
				return $result;
			} else {
				return false;
			}           
    }

		//FUNÇÃO QUE EXECUTA A SQL PAGINATE
		public function getArquivadasPag($page, $options) { 		

			$sql = ("
				SELECT 
					*
				FROM 
					inscricoes
				WHERE 
					fase = 'ARQUIVADO' 
			"); 
			
			if (isset($options['named_params'][':nomeInscricao']) && ($options['named_params'][':nomeInscricao']) != ''){		
				$sql .= " AND nome_curso LIKE CONCAT('%', :nome_curso, '%')"; 
			}				
			$sql .= " ORDER BY nome_curso ASC"; 
			
			try
			{
				$this->pag = new Pagination($page,$sql,$options);              
			}
			catch(paginationException $e)
			{
				echo $e;
				exit();
			}

			if (isset($options['named_params'][':nomeInscricao']) && ($options['named_params'][':nomeInscricao']) != ''){	
				$this->pag->bindParam(':nome_curso', $options['named_params'][':nomeInscricao'], PDO::PARAM_STR, 12);           
			} 

			$this->pag->execute();
			return $this->pag;       
    }  

    public function reabreInscricao($inscricoes_id){
			$this->db->query("
				UPDATE 
					inscricoes
				SET 
					fase = 'ABERTO'  
				WHERE 
					id = :id
			");
			$this->db->bind(':id',$inscricoes_id); 
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
    }   

    public function getAbrePresencas($inscricoes_id){
			$this->db->query('
				SELECT 
					* 
				FROM                 
					abre_presenca
				WHERE              
					abre_presenca.inscricoes_id = :inscricoes_id            
			'); 
			$this->db->bind(':inscricoes_id',$inscricoes_id);          
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){					
				return $result;
			} else {
				return false;
			}       
    }   

    public function removeAbrePresenca($inscricoes_id){
			$this->db->query("
				DELETE FROM 
					abre_presenca 
				WHERE 
					inscricoes_id = :inscricoes_id
			");
			$this->db->bind(':inscricoes_id', $inscricoes_id);
			if($this->db->execute()){            
				return true;
			} else {
				return false;
			}
    }
    
    public function removePresencas($abre_presenca_id){              
			$this->db->query("
				DELETE FROM 
					presenca 
				WHERE 
					abre_presenca_id = :abre_presenca_id
			");
			$this->db->bind(':abre_presenca_id', $abre_presenca_id);       
			if($this->db->execute()){            
				return true;
			} else {
				return false;
			} 
    }

    public function removeInscritos($inscricoes_id){           
			$this->db->query("
				DELETE FROM 
					inscritos 
				WHERE 
					inscricoes_id = :inscricoes_id
			");
			$this->db->bind(':inscricoes_id', $inscricoes_id);   
			if($this->db->execute()){            
				return true;
			} else {
				return false;
			}
    }

    public function removeTemas($inscricoes_id){        
			$this->db->query("
				DELETE FROM 
					inscricoes_temas 
				WHERE 
					inscricoes_id = :inscricoes_id
			");
			$this->db->bind(':inscricoes_id', $inscricoes_id);   
			if($this->db->execute()){            
				return true;
			} else {
				return false;
			}
    }

    public function deletePresencas($inscricoes_id){
			//pego todas as abre presença
			$abrepresencas = $this->getAbrePresencas($inscricoes_id);
			//se não tem nenhuma abre presença retorno true pq não tem o que excluir
			if($abrepresencas){
				//para cada abrepresença removo todas as presenças        
				foreach($abrepresencas as $abrep){            
					//removo todas as presenças
					if(!$this->removePresencas($abrep->id)){                                          
						return false;
					}
					//removo a propria abrepresença
					if(!$this->removeAbrePresenca($inscricoes_id)){                
						return false;
					}
				}  
			} else {
				return true;
			}          
    return true;              
    }

    public function delete($inscricoes_id){        
			//excluir todas as presenças
			if(!$this->deletePresencas($inscricoes_id)){ 
				return false;
			} 
			//excluir todos os inscritos
			if(!$this->removeInscritos($inscricoes_id)){            
				return false;
			} 
			//excluir todos os temas  
			if(!$this->removeTemas($inscricoes_id)){            
				return false;
			} 
			//excluir o curso
			$this->db->query('DELETE FROM inscricoes WHERE id = :id');
			$this->db->bind(':id', $inscricoes_id);   
			if($this->db->execute()){            
				return true;
			} else {
				return false;
			}
    }
	}
?>