<?php
	class Fcursosuperiores extends Controller{
			
		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('users/login');
				die();
			}           
			$this->fcursossupModel = $this->model('Fcursossuperior');
		}

		//RETORNA O CÓDIGO HTML PARA ADICIONAR NO SELECT CURSO
		public function cursosArea($_areaId){ 				
			$data = $this->fcursossupModel->getCursosAreaById($_areaId); 				
			if($idEstado == 'null'){
				die("<option value='null'>Selecione uma área</option");
			}				
			if(!$data){
				die("<option value='null'>Sem cursos para esta área</option>");
			} 
			echo ("<option value='null'>Selecione o curso</option>");				
			foreach($data as $row){
				echo "<option value=".$row->cursoId.">".mb_strtoupper($row->curso)."</option>";
			}
		}
	}   
?>