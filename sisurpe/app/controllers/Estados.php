<?php 
	class Estados extends Controller{

		public function __construct(){               
			$this->estadoModel = $this->model('Estado');
		}		
			
		//RETORNA O CÓDIGO HTML PARA ADICIONAR NO SELECT DE ESTADOS
		public function estadosRegiao($idRegiao){ 
			/**
			 * IMPORTANTE o echo dado aqui na função é retornado no arquivo index
			 * no jquery load $('#estadoId').load(
			 */

			//Pego os estados da região através do métdodo
			$data = $this->estadoModel->getEstadosRegiaoById($idRegiao);  
			
			//Se acaso vier null é pq o usuário selecionou a primeira opção novamente Selecione um ...
			if($idRegiao == 'null'){
				die("<option value='null'>Selecione a Região</option");
			}

			//O método getEstadosRegiaoById retorna false se não tiver nenhum registro no bd
			//dessa forma se retornar falso imprimo sem estados para a região
			if(!$data){
				die("<option value='null'>Sem estados para esta região</option>");
			}

			/**
			* Esse priemeiro option é para sempre adicionar no início do select, caso contário 
			* Ele vai sepmpre pegar o primeiro valor que tiver no option no caso o primeiro estado
			*/
			echo ("<option value='null'>Selecione o Estado</option>");

			//Faz o foreach para cada estado dentro do array $data
			//O que for dado echo vai ser retornado lá para o index no jquery $('#estadoId').load(
			foreach($data as $row){
				echo "<option value=".$row->id.">".$row->estado."</option>";
			}
		}
	}
?>