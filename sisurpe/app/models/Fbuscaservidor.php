<?php
  class Fbuscaservidor {
    private $db;

    public function __construct(){          
      $this->db = new Database;
    }


    //FUNÇÃO QUE EXECUTA A SQL PAGINATE PARA FUNCIONAR O BIND TEM QUE COLOCAR O  PARÂMETRO using_bound_params' => true lá no controller
    public function getServidor($page, $options){        
      /*
      cpf=&name=&escolaId=null&maiorEscolaridade=null&tipoEnsinoMedio=null&posId=4" ["using_bound_params"]=> bool(true) ["named_params"]=> array(6) { [":cpf"]=> string(0) "" [":name"]=> string(0) "" [":escolaId"]=> string(4) "null" [":maiorEscolaridade"]=> string(4) "null" [":tipoEnsinoMedio"]=> string(4) "null" [":posId"]=> string(1) "4" } }
      */  
      $bind = [];
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
        $bind += [':escolaId' => $options['named_params'][':escolaId']];      
      }
      if(!empty($options['named_params'][':maiorEscolaridade']) && $options['named_params'][':maiorEscolaridade'] != 'null'){          
        $where .= " AND  fuf.maiorEscolaridade = :maiorEscolaridade"; 
        $bind += [':maiorEscolaridade' => $options['named_params'][':maiorEscolaridade']];           
      }
      if(!empty($options['named_params'][':tipoEnsinoMedio']) && $options['named_params'][':tipoEnsinoMedio'] != 'null'){          
        $where .= " AND  fuf.tipoEnsinoMedio = :tipoEnsinoMedio";    
        $bind += [':tipoEnsinoMedio' => $options['named_params'][':tipoEnsinoMedio']];       
      }
      if(!empty($options['named_params'][':posId']) && $options['named_params'][':posId'] != 'null'){          
        $where .= " AND fup.userId = u.id AND fp.posId = fup.posId AND fup.posId = :posId"; 
        $bind += [':posId' => $options['named_params'][':posId']];          
      }
      if(!empty($options['named_params'][':cpf'])){          
        $where .= " AND u.cpf LIKE CONCAT(:cpf, '%')";   
        $bind += [':cpf' => $options['named_params'][':cpf']];           
      }
      if(!empty($options['named_params'][':name'])){
        $where .= " AND u.name LIKE CONCAT(:name, '%')";
        $bind += [':name' => $options['named_params'][':name']];       
      }      
      $order = "
        ORDER BY u.name ASC
      ";          
      $sql = $select . $from . $where . $order;   
      try{
        $this->pag = new Pagination($page,$sql,$options);              
      } catch(paginationException $e){
        echo $e;
        exit();
      }
      //bind
      foreach($bind as $key => $value){             
				$this->pag->bindParam($key, $value, PDO::PARAM_STR, 12);            
			}  
      //EXECUTA A PAGINAÇÃO
      $this->pag->execute();
      //RETORNA A PAGINAÇÃO
      return $this->pag;
    } 
  }    
?>