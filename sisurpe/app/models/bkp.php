<?php
    class Fuseroutroscurso {
        private $db;

        public function __construct(){
            //inicia a classe Database
            $this->db = new Database;
        }

        public function getUserOutrosCursos($_userId){            
            $this->db->query('SELECT fuoc.userId as userId, fuoc.cursoId as cursoId, foc.curso as curso FROM f_user_outros_cursos fuoc, f_outros_cursos foc WHERE fuoc.cursoId = foc.cursoId AND fuoc.userId = :userId');
            $this->db->bind(':userId', $_userId);           
            $results = $this->db->resultSet();  
            if($this->db->rowCount() > 0){
                return $results;
            } else {
                return false;
            }  
        }

        public function deleteAllOutrosCursosUser($_userId){
            //se tem pos cadastrada
            if($this->getUserOutrosCursos($_userId)){
                $this->db->query('DELETE FROM f_user_outros_cursos WHERE userId = :userId');
                $this->db->bind(':userId', $_userId);
                $row = $this->db->execute();
                if($this->db->rowCount() > 0){
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
            
            if(!$this->deleteAllOutrosCursosUser($_userId)){
                return false;
            }

            $error = false;
            if($data){
                foreach($data as $row){
                    $this->db->query('
                                INSERT INTO f_user_outros_cursos SET                             
                                userId = :userId, 
                                cursoId = :cursoId
                                ');
                    $this->db->bind(':userId',$_userId);
                    $this->db->bind(':cursoId',$row); 
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

        
    }
    
?>