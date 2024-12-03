<?php
	class Municipio {
    private $db;

    public function __construct(){       
			$this->db = new Database;
    }

		//Retorna um município a partir do id
		public function getMunicipioById($id){
			$this->db->query("
				SELECT 
					* 
				FROM 
					municipios 
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

    //Retorna o estado de um município
    public function getEstadoMunicipio($municipioId){
			$this->db->query("
				SELECT 
					estados.id, 
					estados.regiaoId, 
					estados.estado 
				FROM 
					estados, 
					municipios 
				WHERE 
					estados.id = municipios.estadoId 
				AND 
					municipios.id = :id
			");        
			$this->db->bind(':id', $municipioId);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
    }

    //Retorna todos os municipios do banco de dados
    public function getMunicipios(){
			$this->db->query("
				SELECT 
					* 
				FROM 
					municipios 
				ORDER BY 
					nomeMunicipio ASC
			");
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
    }

    //Retorna todos os municípios de um estado
    public function getMunicipiosEstadoById($estadoId){        
			$this->db->query("
				SELECT 
					*                          
				FROM 
					municipios 
				WHERE 
					estadoId = :estadoId                                               
				ORDER BY 
					municipios.nomeMunicipio 
				ASC
			");
			$this->db->bind(':estadoId', $estadoId);        
			$results = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){
				return $results;
			} else {
				return false;
			}  
    }

    //Retorna os a busca para a paginação
    public function getMunicipiosPag($page,$options){
			$bind = [];        
			//$options['named_params'][':nomeMunicipio'] = '1';
			//$options['named_params'][':estadoId'] = '1';
			$sql = "
				SELECT *
			";
			
			$from = " 
				FROM municipios
			";  
			
			$where = "                
				WHERE 1
			";  
			//NOME DO MUNICIPIO
			if(!empty($options['named_params'][':nomeMunicipio']) && $options['named_params'][':nomeMunicipio'] != 'null'){            
				$where .= " AND nomeMunicipio LIKE CONCAT(:nomeMunicipio, '%')";
				$bind += [':nomeMunicipio' => $options['named_params'][':nomeMunicipio']];  
			} 
			//ESTADO ID
			if(!empty($options['named_params'][':estadoId']) && $options['named_params'][':estadoId'] != 'null'){  
				$where .= " AND estadoId = :estadoId";
				$bind += [':estadoId' => $options['named_params'][':estadoId']]; 
			} 
			$order = " ORDER BY nomeMunicipio ASC";                    
			//monta a sql        
			$sql .= $from . $where .$order; 
			//TENTA EXECUTAR A PAGINAÇÃO 
			try
			{
				$this->pag = new Pagination($page,$sql, $options);  
			}	catch(paginationException $e)	{
				echo $e;
				exit();
			}
			//Bind		
      foreach($bind as $key => $value){             
				$this->pag->bindParam($key, $value, PDO::PARAM_STR, 12);            
			}  
			//EXECUTA A PAGINAÇÃO
			$this->pag->execute();
			//RETORNA A PAGINAÇÃO
			return $this->pag; 
    } 

    //Registra um município
    public function register($data){        
			$this->db->query("
				INSERT INTO municipios SET estadoId = :estadoId, nomeMunicipio = :nomeMunicipio
			");         
			$this->db->bind(':estadoId',$data['estadoId']);
			$this->db->bind(':nomeMunicipio',$data['nomeMunicipio']); 
			if($this->db->execute()){
				return  $this->db->lastId;
			} else {
				return false;
			}
    }

    //Deleta um município
    public function update($data){
			$this->db->query("
				UPDATE 
					municipios 
				SET
					nomeMunicipio = :nomeMunicipio                      
				WHERE 
					id = :id                      
			");
			$this->db->bind(':id',$data['municipioId']);
			$this->db->bind(':nomeMunicipio',$data['nomeMunicipio']);
			if($this->db->execute()){ 
				return $data['municipioId'];
			} else {
				return false;
			}             
    }

    //Retorna um município pelo nome
    public function getMunicipioByName($_nomeMunicipio){
			$this->db->query("
				SELECT 
					* 
				FROM 
					municipios 
				WHERE 
					nomeMunicipio = :nomeMunicipio
			");        
			$this->db->bind(':nomeMunicipio', $_nomeMunicipio);
			$row = $this->db->single();       
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
    }

    //Verifica se um município já está cadastrado
    public function municipioExistente($_nomeMunicipio,$_estadoId){
			$this->db->query("
				SELECT 
					* 
				FROM 
					municipios 
				WHERE 
					nomeMunicipio = :nomeMunicipio 
				AND 
					estadoId = :estadoId
			");        
			$this->db->bind(':nomeMunicipio', $_nomeMunicipio);
			$this->db->bind(':estadoId', $_estadoId);
			$row = $this->db->single();        
			if($this->db->rowCount() > 0){
				return $row;
			} else {
				return false;
			}
    }

    //Deleta todos os bairros de um município
    public function deletaBairrosMunicipio($_municipioId){
			$this->db->query("
				DELETE FROM 
					bairros 
				WHERE 
					municipioId = :municipioId
			");
			$this->db->bind(':municipioId', $_municipioId);
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
    }

    //Deleta um municipio
    public function delete($_municipioId){
			$error = false;        
			if(!$this->deletaBairrosMunicipio($_municipioId)){
				$error = true;   
			}			
			if($error == true){
				return false;
			} else {
				$this->db->query("
					DELETE FROM 
						municipios 
					WHERE 
						id = :id
				");            
				$this->db->bind(':id', $_municipioId);				
				if($this->db->execute()){
					return true;
				} else {
					return false;
				}
			}
    }
	}
?>