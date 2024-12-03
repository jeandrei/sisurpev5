<?php
  class Presenca {
    private $db;

    public function __construct(){
      $this->db = new Database;        
    }
  
    public function register($data){       
      $this->db->query("
        INSERT INTO presenca (abre_presenca_id, user_id) VALUES (:abre_presenca_id, :user_id)
      ");     
      $this->db->bind(':abre_presenca_id',$data['abre_presenca_id']);
      $this->db->bind(':user_id',$data['user_id']);  
      if($this->db->execute()){
        return  true;              
      } else {
        return false;
      }
    }

    //marca presença para todos os inscritos
    public function checkAll($data){ 
      if($data['usersIds']){
        foreach($data['usersIds'] as $userId){   
          $this->db->query("
          INSERT INTO presenca (abre_presenca_id, user_id) VALUES (:abre_presenca_id, :user_id)
          ");     
          $this->db->bind(':abre_presenca_id',$data['abre_presenca_id']);
          $this->db->bind(':user_id',$userId); 
          if(!$this->db->execute()){
            return false;
          };       
        } 
        return true;
      } else {
        return true;
      }      
    }

    //verifica se todos marcaram presença em uma abre presença
    public function todosPresentes($abre_presenca_id){
      $this->db->query("
        SELECT 
          * 
        FROM 
          abre_presenca 
        WHERE 
          id = :id                                          
      ");
      $this->db->bind(':id',$abre_presenca_id);   
      $row = $this->db->single();       
      $inscricoes_id = $row->inscricoes_id;      

      $totalInscritos = $this->totalInscritos($inscricoes_id);
      
      $totalPresentes = $this->totalPresentes($abre_presenca_id);     

      if($totalInscritos->total === $totalPresentes->total){
        return true;
      } else {
        return false;
      }      
    }

    //retorna o total de inscritos de uma inscrição
    public function totalInscritos($inscricoes_id){
      $this->db->query("
        SELECT 
          count(*) as total
        FROM 
          inscritos 
        WHERE 
          inscricoes_id = :inscricoes_id                                          
      ");
      $this->db->bind(':inscricoes_id',$inscricoes_id);     
      $total = $this->db->single();  
      if($this->db->rowCount() > 0){
        return $total;
      } else {
        return false;
      }
    }

    //retorna o total de presentes em uma abre presença
    public function totalPresentes($abre_presenca_id){
      $this->db->query("
        SELECT 
          count(*) as total
        FROM 
          presenca 
        WHERE 
          abre_presenca_id = :abre_presenca_id                                          
      ");
      $this->db->bind(':abre_presenca_id',$abre_presenca_id);     
      $total = $this->db->single();  
      if($this->db->rowCount() > 0){
        return $total;
      } else {
        return false;
      }
    }

    public function jaRegistrado($data){
      $this->db->query("
        SELECT 
          * 
        FROM 
          presenca 
        WHERE 
          abre_presenca_id = :abre_presenca_id
        AND 
          user_id = :user_id                                   
      ");
      $this->db->bind(':abre_presenca_id',$data['abre_presenca_id']);
      $this->db->bind(':user_id',$data['user_id']);
      $row = $this->db->single();        
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

    public function getPresencas($inscricoes_id=null){
      $this->db->query("
        SELECT 
          users.id,
          inscricoes.id as inscId,
          users.name, 
          users.cpf, 
          presenca.registro  
        FROM 
          inscricoes, 
          abre_presenca, 
          presenca, 
          users 
        WHERE 
          inscricoes.id = abre_presenca.inscricoes_id 
        AND  
          presenca.abre_presenca_id = abre_presenca.id 
        AND 
          users.id = presenca.user_id 
        AND 
          inscricoes.id = :id
      "); 
      $this->db->bind(':id',$inscricoes_id);  
      $result = $this->db->resultSet(); 
      if($this->db->rowCount() > 0){
        return $result;        
      } else {
        return false;
      }           
    }

    public function presente($abre_presenca_id,$user_id){
      $this->db->query("
        SELECT 
          * 
        FROM 
          presenca 
        WHERE 
          abre_presenca_id = :abre_presenca_id
        AND 
          user_id = :user_id                                   
      ");
      $this->db->bind(':abre_presenca_id',$abre_presenca_id);
      $this->db->bind(':user_id',$user_id);
      $row = $this->db->single();        
      if($this->db->rowCount() > 0){
        return true;
      } else {
        return false;
      }
    }

    public function removePresenca($abre_presenca_id,$user_id){
      $this->db->query("
        DELETE FROM 
          presenca 
        WHERE
          abre_presenca_id = :abre_presenca_id
        AND 
          user_id = :user_id             
      ");
      $this->db->bind(':abre_presenca_id',$abre_presenca_id);
      $this->db->bind(':user_id',$user_id);    
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    } 
    
    public function removeTodasPresencas($abre_presenca_id){
      $this->db->query("
        DELETE FROM 
          presenca 
        WHERE
          abre_presenca_id = :abre_presenca_id                     
      ");
      $this->db->bind(':abre_presenca_id',$abre_presenca_id);      
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }   
  }

  
?>