<?php
	class Coleta {
		private $db;

		public function __construct(){				
			$this->db = new Database;
		}

		//Retorna os alunos a serem coletados informações de uma turma
		public function coletaTurmaById($turmaId){
			$this->db->query('SELECT * FROM coleta WHERE turmaId = :turmaId ORDER BY nome ASC');
			$this->db->bind(':turmaId', $turmaId);
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}  

		public function update($data){        
			$sql = 'UPDATE coleta SET ';
			switch ($data['act']) {
				case 'kitInverno':
					$sql .= 'kit_inverno = :kit_inverno';
					break;
				case 'kitVerao':
					$sql .= 'kit_verao = :kit_verao';
					break;
				case 'tamCalcado':
					$sql .= 'tam_calcado = :tam_calcado';
					break;
				case 'transporte1':
					$sql .= 'transporte1 = :transporte1';
					break;
				case 'transporte2':
					$sql .= 'transporte2 = :transporte2';
					break;
				case 'transporte3':
					$sql .= 'transporte3 = :transporte3';
					break;            
			}
			$sql .= ' WHERE id = :id';			
			$this->db->query($sql);				
			switch ($data['act']) {
				case 'kitInverno':
					$this->db->bind(':kit_inverno',$data['val']);
					break;
				case 'kitVerao':
					$this->db->bind(':kit_verao',$data['val']);
					break;
				case 'tamCalcado':
					$this->db->bind(':tam_calcado',$data['val']);
					break;
				case 'transporte1':
					$this->db->bind(':transporte1',$data['val']);
					break;
				case 'transporte2':
					$this->db->bind(':transporte2',$data['val']);
					break;
				case 'transporte3':
					$this->db->bind(':transporte3',$data['val']);
					break;            
			}
			$this->db->bind(':id',$data['id']); 
			if($this->db->execute()){
				return true;
			} else {
				return false;
			}
		}

		public function getColetaEscola($escolaId){
			$this->db->query('
				SELECT 
					escola.id as escolaId, 
					escola.nome as escolaNome, 
					escola.bairro_id as bairro_id, 
					escola.numero as numero, 
					escola.emAtividade as emAtividade, 
					coleta.id as coletaId, 
					coleta.nome as coletaNome, 
					coleta.turmaId as turmaId, 
					coleta.turno as turno, 
					coleta.nascimento as nascimento, 
					coleta.sexo as sexo, 
					coleta.kit_inverno as kit_inverno, 
					coleta.tam_calcado as tam_calcado, 
					coleta.transporte1 as transporte1, 
					coleta.transporte2 as transporte2, 
					coleta.transporte3 as transporte3 
				FROM
					coleta, 
					escola,
					 turma 
				WHERE 
					coleta.turmaId = turma.id 
				AND 
					turma.escolaId = escola.id 
				AND 
					escola.id = :escolaId 
				ORDER BY 
					turma.id, 
					coleta.nome 
				ASC
			');
			$this->db->bind(':escolaId', $escolaId);
			$result = $this->db->resultSet();
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}

		public function getColetaByTurma($turmaId){         
			$this->db->query('
				SELECT 
					coleta.id as coletaId,
					coleta.nome as coletaNome, 
					coleta.turmaId as turmaId, 
					coleta.turno as turno, 
					coleta.nascimento as nascimento, 
					coleta.sexo as sexo, 
					coleta.kit_inverno as kit_inverno,
					coleta.kit_verao as kit_verao, 
					coleta.tam_calcado as tam_calcado, 
					coleta.transporte1 as transporte1, 
					coleta.transporte2 as transporte2, 
					coleta.transporte3 as transporte3 
				FROM 
					coleta 
				WHERE 
					coleta.turmaId = :turmaId 
				ORDER BY 
					coleta.nome 
				ASC
			');
			$this->db->bind(':turmaId', $turmaId);
			$result = $this->db->resultSet();        
			if($this->db->rowCount() > 0){
				return $result;
			} else {
				return false;
			}
		}
/*
		public function getColetaEscola($escolaId){
				$this->db->query('select * from coleta, escola, turma WHERE coleta.turmaId = turma.id AND turma.escolaId = escola.id and escola.id = :escolaId ORDER BY turma.id, coleta.nome ASC');
				$this->db->bind(':escolaId', $escolaId);
				$result = $this->db->resultSet();
				if($this->db->rowCount() > 0){
						return $result;
				} else {
						return false;
				}
		}
*/

		/* public function getColetaByTurma($turmaId){         
				$this->db->query('select * from coleta WHERE coleta.turmaId = :turmaId ORDER BY coleta.nome ASC');
				$this->db->bind(':turmaId', $turmaId);
				$result = $this->db->resultSet();        
				if($this->db->rowCount() > 0){
						return $result;
				} else {
						return false;
				}
		} */

		// Função para retornar total de um item como kit de inverto de uma turma e tamanho
		public function getTotal($turmaId,$tamanho,$campoCalcular){
			$sql = "
				SELECT 
					COUNT(id) AS total 
				FROM 
					coleta 
				WHERE 
					coleta.turmaId = :turmaId
			";
			$bind = [];
			switch ($campoCalcular) {
				case 'kit_inverno':                
					$sql.= " AND kit_inverno = :kit_inverno";
					$bind = ':kit_inverno';
					break;
				case 'kit_verao':
					$sql.= " AND kit_verao = :kit_verao";
					$bind = ':kit_verao';
					break;
				case 'tam_calcado':
					$sql.= " AND tam_calcado = :tam_calcado";
					$bind = ':tam_calcado';
					break;
			}	
			$this->db->query($sql); 
			$this->db->bind($bind, $tamanho); 
			$this->db->bind(':turmaId', $turmaId);			
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row->total;
			} else {
				return false;
			}  
		}

		// Retorna os totais de uma linha a partir de uma escola		
		public function getTotaisLinhasEscola($escolaId,$valor){			
			$sql = "
				SELECT 
					COUNT(coleta.id) as total 
				FROM 
					coleta, 
					escola, 
					turma 
				WHERE 
					coleta.turmaId = turma.id 
				AND 
					turma.escolaId = escola.id 
				AND 
					escola.id = :escolaId
				AND
					(
						coleta.transporte1 = :valor
					OR
						coleta.transporte2 = :valor
					OR 
						coleta.transporte3 = :valor
					)
			";								
			$this->db->query($sql); 
			$this->db->bind(':escolaId', $escolaId);
			$this->db->bind(':valor', $valor);
			$row = $this->db->single();
			if($this->db->rowCount() > 0){
				return $row->total;
			} else {
				return false;
			}  
		}

		// Retorna o total de uma linha a partir de uma turma
		public function getTotaisLinhasTurma($turmaId,$valor){
			
			$sql = "
				SELECT 
					COUNT(id) AS total 
				FROM 
					coleta 
				WHERE 
					coleta.turmaId = :turmaId
			";
			
			if(isset($valor)){
				if($valor === 'NÃO UTILIZA'){
					$sql .= " AND transporte1 = :valor";
				} else {					
					$sql .= " AND (transporte1 = :valor OR transporte2 = :valor OR transporte3 = :valor)";
				}				
			} else {
				return false;
			}					
			$this->db->query($sql); 
			$this->db->bind(':turmaId', $turmaId);
			$this->db->bind(':valor', $valor);					
			$row = $this->db->single();
			if($this->db->rowCount() > 0){			
				return $row->total;
			} else {
				return false;
			}  
		}

		//Retorna os totais de transporte por turma
		public function totaisTransporte($turmaId){ 		
			$arrayLinhas='';
			$arrayLinhas = getLinhas();
			foreach($arrayLinhas as $linha){					
				$result[$linha] = $this->getTotaisLinhasTurma($turmaId,$linha);            
			}			
			return $result;
		}

		//Retorna os totais de uniforme por turma
		public function totaisUniforme($turmaId,$campoCalcular){ 
			$arrayTamanhos='';
			$arrayTamanhos = getArrayTamanhos();
			foreach($arrayTamanhos as $tamanho){
				$result[$tamanho] = $this->getTotal($turmaId,$tamanho,$campoCalcular);            
			}
			return $result;
		}

		//Retorna os totais de uniforme por turma
		public function totaisCalcado($turmaId,$campoCalcular){ 
			$arrayTamanhos='';
			$arrayTamanhos = getTamanhosCalcados();
			foreach($arrayTamanhos as $tamanho){
				$result[$tamanho] = $this->getTotal($turmaId,$tamanho,$campoCalcular);            
			}				
			return $result;
		}

		public function getTotaisEscola($escolaId,$tamanho,$campoCalcular){
			$sql = "
				SELECT 
					COUNT(coleta.id) as total 
				FROM 
					coleta, 
					escola, 
					turma 
				WHERE 
					coleta.turmaId = turma.id 
				AND 
					turma.escolaId = escola.id 
				AND 
					escola.id = :escolaId
			";
			switch ($campoCalcular) {
				case 'kit_inverno':                
					$sql.= " AND kit_inverno = :kit_inverno";
					$bind = ':kit_inverno';
					break;
				case 'kit_verao':
					$sql.= " AND kit_verao = :kit_verao";
					$bind = ':kit_verao';
					break;
				case 'tam_calcado':
					$sql.= " AND tam_calcado = :tam_calcado";
					$bind = ':tam_calcado';
					break;
			}  
			$this->db->query($sql);  
			$this->db->bind($bind, $tamanho);
			$this->db->bind(':escolaId', $escolaId);
			$row = $this->db->single();   
			if($this->db->rowCount() > 0){
				return $row->total;
			} else {
				return false;
			}         
		}

		//retorna os totais de uniforme por escola
		public function totaisEscolaUniforme($escolaId){
			$arrayTamanhos='';
			$arrayTamanhos = getArrayTamanhos();
			foreach($arrayTamanhos as $tamanho){            
				$result['kit_inverno'][$tamanho] = $this->getTotaisEscola($escolaId,$tamanho,'kit_inverno'); 
				$result['kit_verao'][$tamanho] = $this->getTotaisEscola($escolaId,$tamanho,'kit_verao'); 
				$result['tam_calcado'][$tamanho] = $this->getTotaisEscola($escolaId,$tamanho,'tam_calcado');
			} 
			return $result;       
		}

		//retorna os totais de calçados por escola
		public function totaisEscolaCalcado($escolaId){
			$arrayTamanhos='';
			$arrayTamanhos = getTamanhosCalcados();
			foreach($arrayTamanhos as $tamanho){ 
				$result['tam_calcado'][$tamanho] = $this->getTotaisEscola($escolaId,$tamanho,'tam_calcado');
			} 
			return $result;       
		}
	}
?>