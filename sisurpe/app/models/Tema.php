<?php
  class Tema {
    private $db;

    public function __construct(){
			$this->db = new Database;        
    }
  
    public function register($data){        
      $this->db->query("
				INSERT INTO inscricoes_temas (inscricoes_id, formador, tema,carga_horaria) VALUES (:inscricoes_id, :formador, :tema, :carga_horaria)
			");      
      $this->db->bind(':inscricoes_id',$data['inscricoes_id']);
      $this->db->bind(':formador',$data['formador']);
      $this->db->bind(':tema',$data['tema']);
      $this->db->bind(':carga_horaria',$data['carga_horaria']); 
      if($this->db->execute()){
				return  true;              
      } else {
				return false;
      }
  	}

    public function deletaTema($tema_id){
      $this->db->query("
				DELETE FROM 
					inscricoes_temas 
				WHERE
					id = :id                                 
			");
			$this->db->bind(':id',$tema_id);         
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
    }   

    public function getTemasInscricoesById($id){
      $this->db->query("
				SELECT 
					itemas.id , 
					itemas.inscricoes_id,
					itemas.formador, 
					itemas.tema, 
					itemas.carga_horaria 
				FROM 
					inscricoes insc, 
					inscricoes_temas itemas 
				WHERE 
					itemas.inscricoes_id = :id 
				AND 
					itemas.inscricoes_id = insc.id 
				ORDER BY 
					itemas.tema ASC
			");
      $this->db->bind(':id',$id); 
      $result = $this->db->resultSet(); 
      if($this->db->rowCount() > 0){
				return $result;
      } else {
				return false;
      }           
  	}  

		public function deleteTema($id){  			
			$this->db->query("
				DELETE FROM 
					inscricoes_temas 
				WHERE 
					id = :id
			");			
			$this->db->bind(':id',$id); 		
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function getTotalCargaHoraria($inscricoes_id){
			$this->db->query("
				SELECT 
					SUM(carga_horaria) as carga_horaria 
				FROM 
					inscricoes_temas 
				WHERE 
					inscricoes_id = :inscricoes_id
			");
			$this->db->bind(':inscricoes_id',$inscricoes_id); 
			$row = $this->db->single();  
			if($this->db->rowCount() > 0){
					return $row->carga_horaria;
			} else {
					return false;
			}   
		}  
  }
?>