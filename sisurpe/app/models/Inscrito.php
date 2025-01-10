<?php
  class Inscrito {
    private $db;

    public function __construct(){
      $this->db = new Database;        
    }
  
    public function gravaInscricao($inscricoes_id,$user_id){      
      $this->db->query("
        INSERT INTO inscritos SET inscricoes_id = :inscricoes_id, user_id = :user_id             
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);
      $this->db->bind(':user_id',$user_id);    
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function cancelaInscricao($inscricoes_id,$user_id){
      $this->db->query("
        DELETE FROM 
          inscritos 
        WHERE
          inscricoes_id = :inscricoes_id
        AND 
          user_id = :user_id             
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);
      $this->db->bind(':user_id',$user_id);    
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function estaInscrito($inscricoes_id,$user_id){
      $this->db->query("
        SELECT 
          * 
        FROM 
          inscritos 
        WHERE 
          inscricoes_id = :inscricoes_id
        AND 
          user_id = :user_id                                   
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);
      $this->db->bind(':user_id',$user_id); 
      $row = $this->db->single();        
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

    public function existeInscritos($inscricoes_id){
      $this->db->query("
        SELECT 
          * 
        FROM 
          inscritos 
        WHERE 
          inscricoes_id = :inscricoes_id
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);  
      $result = $this->db->resultSet();      
      if($this->db->rowCount() > 0){
        return $result;
      } else {
        return false;
      }
    }

    public function getInscritos($inscricoes_id){
      $this->db->query("
        SELECT 
          inscricoes.id as inscId,
          inscritos.user_id, 
          inscritos.id as inscritosId, 
          users.name, 
          users.cpf, 
          users.nascimento 
        FROM 
          inscritos, 
          users, 
          inscricoes 
        WHERE 
          inscritos.inscricoes_id = inscricoes.id 
        AND 
          inscritos.user_id = users.id 
        AND 
          inscricoes.id = :inscricoes_id
        ORDER BY 
          users.name 
        ASC
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);  
      $result = $this->db->resultSet();      
      if($this->db->rowCount() > 0){
        return $result;
      } else {
        return false;
      }
    }

    public function verificaJaInscrito($inscricoes_id, $user_id){
      $this->db->query("
        SELECT 
            * 
        FROM 
            inscritos 
        WHERE 
            inscricoes_id = :inscricoes_id
        AND
            user_id = :user_id                        
      "); 
      $this->db->bind(':inscricoes_id',$inscricoes_id);  
      $this->db->bind(':user_id',$user_id);  
      $result = $this->db->resultSet(); 
      if($this->db->rowCount() > 0){         
        return true;
      } else {
        return false;
      }           
    }
  }
?>