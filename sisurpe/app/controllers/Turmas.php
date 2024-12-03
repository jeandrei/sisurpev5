<?php 
	class Turmas extends Controller{

		public function __construct(){                  
				$this->turmaModel = $this->model('Turma');
		}						
		
		public function turmasEscola($escolaId){  
			$data = $this->turmaModel->getTurmasEscolaById($escolaId); 
			if($escolaId == 'null'){
				die("<option value='null'>Selecione a Escola</option");
			}          
			if(!$data){
				die("<option value='null'>Sem turmas para esta escola</option>");
			}
			echo ("<option value='null'>Selecione a Turma</option>");
			foreach($data as $row){
				echo "<option value=".$row->id.">".$row->descricao."</option>";
			}
		}
	}
?>