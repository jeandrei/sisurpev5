<?php
    class Fbuscaservidor {
        private $db;

        public function __construct(){
            //inicia a classe Database
            $this->db = new Database;
        }


    //FUNÇÃO QUE EXECUTA A SQL PAGINATE PARA FUNCIONAR O BIND TEM QUE COLOCAR O  PARÂMETRO using_bound_params' => true lá no controller
    public function getServidor($page, $options){ 
          // debug($options);
      /*
      cpf=&name=&escolaId=null&maiorEscolaridade=null&tipoEnsinoMedio=null&posId=4" ["using_bound_params"]=> bool(true) ["named_params"]=> array(6) { [":cpf"]=> string(0) "" [":name"]=> string(0) "" [":escolaId"]=> string(4) "null" [":maiorEscolaridade"]=> string(4) "null" [":tipoEnsinoMedio"]=> string(4) "null" [":posId"]=> string(1) "4" } }
      */  
      
      
        $select = "
                  SELECT 
                  u.id as userId,
                  u.name as nome,
                  e.nome as escola,
                  fuf.maiorEscolaridade as maiorEscolaridade,
                  fuf.tipoEnsinoMedio as tipoEnsinoMedio
        ";

        if(!empty($options['named_params'][':posId']) && $options['named_params'][':posId'] != 'null'){          
          $select.="
                ,fp.pos as pos                
          ";      
        }

        $from = "
                FROM 
                users u,	
                f_user_escola fue,
                escola e,
                f_user_formacao fuf
        ";

        if(!empty($options['named_params'][':posId']) && $options['named_params'][':posId'] != 'null'){          
          $from.="
                ,f_user_pos fup
                ,f_pos fp
          ";      
        }


        $where = "
                WHERE
                  e.id = fue.escolaId 
                AND 
                  u.id = fue.userId 
                AND 
                  u.id = fuf.userId
        ";

        if(!empty($options['named_params'][':escolaId']) && $options['named_params'][':escolaId'] != 'null'){          
          $where .= " AND  fue.escolaId = :escolaId";           
        }

        if(!empty($options['named_params'][':maiorEscolaridade']) && $options['named_params'][':maiorEscolaridade'] != 'null'){          
          $where .= " AND  fuf.maiorEscolaridade = :maiorEscolaridade";           
        }

        if(!empty($options['named_params'][':tipoEnsinoMedio']) && $options['named_params'][':tipoEnsinoMedio'] != 'null'){          
          $where .= " AND  fuf.tipoEnsinoMedio = :tipoEnsinoMedio";           
        }

        if(!empty($options['named_params'][':posId']) && $options['named_params'][':posId'] != 'null'){          
          $where .= " AND fup.userId = u.id AND fp.posId = fup.posId AND fup.posId = :posId";           
        }

        if(!empty($options['named_params'][':cpf'])){          
          $where .= " AND u.cpf LIKE CONCAT(:cpf, '%')";           
        }

        if(!empty($options['named_params'][':name'])){
          $where .= " AND u.name LIKE CONCAT(:name, '%')";
        }
       
        $order = "
                ORDER BY u.name ASC
        ";

              
        $sql = $select . $from . $where . $order;
      

        

        
          
        //die($sql);
        
        try
        {
            $this->pag = new Pagination($page,$sql,$options);              
        }
        catch(paginationException $e)
        {
            echo $e;
            exit();
        }


        //bind

        if(!empty($options['named_params'][':escolaId']) && $options['named_params'][':escolaId'] != 'null'){
          $this->pag->bindParam(':escolaId', $options['named_params'][':escolaId'], PDO::PARAM_STR, 12);           
        } 

        if(!empty($options['named_params'][':maiorEscolaridade']) && $options['named_params'][':maiorEscolaridade'] != 'null'){
          $this->pag->bindParam(':maiorEscolaridade', $options['named_params'][':maiorEscolaridade'], PDO::PARAM_STR, 12);           
        }

        if(!empty($options['named_params'][':tipoEnsinoMedio']) && $options['named_params'][':tipoEnsinoMedio'] != 'null'){
          $this->pag->bindParam(':tipoEnsinoMedio', $options['named_params'][':tipoEnsinoMedio'], PDO::PARAM_STR, 12);           
        }

        if(!empty($options['named_params'][':posId']) && $options['named_params'][':posId'] != 'null'){
          $this->pag->bindParam(':posId', $options['named_params'][':posId'], PDO::PARAM_STR, 12);           
        }

        if(!empty($options['named_params'][':cpf'])){
            $this->pag->bindParam(':cpf', $options['named_params'][':cpf'], PDO::PARAM_STR, 12);           
        } 

        if(!empty($options['named_params'][':name'])){
            $this->pag->bindParam(':name', $options['named_params'][':name'], PDO::PARAM_STR, 12);           
        } 
       

        //EXECUTA A PAGINAÇÃO
        $this->pag->execute();
        //RETORNA A PAGINAÇÃO
        return $this->pag;
    } 
}
    
?>