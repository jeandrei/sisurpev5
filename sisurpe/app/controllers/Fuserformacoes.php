<?php
	class Fuserformacoes extends Controller{

		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('users/login');
				die();
			}			
			$this->escolaModel = $this->model('Escola');
			$this->bairroModel = $this->model('Bairro');
			$this->fuserescolaModel = $this->model('Fuserescolaano');
			$this->fuserformacoesModel = $this->model('Fuserformacao');
			$this->fuserCursoSupModel = $this->model('Fusercursosuperior');
			$this->fuserPosModel = $this->model('Fuserpo');
		}

		public function index() { 				
			//se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
			if(!$this->fuserescolaModel->getEscolasUser($_SESSION[SE . '_user_id'])){
				flash('message', 'Você deve adicionar uma escola ao ano corrente primeiro!', 'error'); 
				redirect('fuserescolaanos/index');
				die();
			} 
			$formacoes = $this->fuserformacoesModel->getUserFormacoesById($_SESSION[SE . '_user_id']);
			$data = [
				'titulo' => 'Formação do usuário',
				'maiorEscolaridade' => 
					isset($formacoes->maiorEscolaridade)
					? $formacoes->maiorEscolaridade
					: 'n_definido',
				'tipoEnsinoMedio' => 
					isset($formacoes->tipoEnsinoMedio)
					? $formacoes->tipoEnsinoMedio
					: '',
				'userId' => 
					isset($_SESSION[SE . '_user_id'])
					? $_SESSION[SE . '_user_id']
					: '',
				'userformacao' => 
					($this->fuserformacoesModel->getUserFormacoesById($_SESSION[SE . '_user_id']))
					? $this->fuserformacoesModel->getUserFormacoesById($_SESSION[SE . '_user_id'])
					: '',
				'avancarLink' => 
					(isset($formacoes->maiorEscolaridade) && ($formacoes->maiorEscolaridade === 'e_superior')) 
					? URLROOT .'/fusercursosuperiores/index' 
					: URLROOT .'/fuseroutroscursos/index',
				'maiorEscolaridade_err' => '',
				'tipoEnsinoMedio_err' => ''
			]; 			
			$this->view('fuserformacoes/index',$data);
		}

		public function add(){          
			if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);  
				unset($data);
				$data = [
					'titulo' => 'Formação do usuário',   
					'userId' => $_SESSION[SE . '_user_id'],
					'userformacao' => $this->fuserformacoesModel->getUserFormacoesById($_SESSION[SE . '_user_id']),
					'maiorEscolaridade' => post('maiorEscolaridade'),
					'tipoEnsinoMedio' => post('tipoEnsinoMedio'),
					'avancarLink' => ($_POST['maiorEscolaridade'] == 'e_superior') ? URLROOT .'/fusercursosuperiores/index' : URLROOT .'/fuseroutroscursos/index',
					'maiorEscolaridade_err' => '',
					'tipoEnsinoMedio_err' => ''  
				];                      
				
				// Valida maiorEscolaridade
				if(empty($data['maiorEscolaridade']) || ($data['maiorEscolaridade'] == 'null')){
					$data['maiorEscolaridade_err'] = 'Por favor informe o nível de escolaridade.';
				}  
				
				// Valida tipoEnsinoMedio
				if(empty($data['tipoEnsinoMedio']) || ($data['tipoEnsinoMedio'] == 'null')){
					$data['tipoEnsinoMedio_err'] = 'Por favor informe tipo de ensino médio cursado.';
				}           
										
				if(                    
					empty($data['maiorEscolaridade_err'])&&
					empty($data['tipoEnsinoMedio_err'])
				){
					// Register maiorEscolaridade
					try {
						/* se o usuário informou um nível diferente de ensino superior
						e se o usuário tiver cursos de ensino superior informado tenho que apagar todos*/
						if($data['maiorEscolaridade'] != 'e_superior'){	
							//se o usuário tem curso superior informado							
							if($this->fuserCursoSupModel->getCursosUser($data['userId'])){		
								//removo todos os cursos superiores						
								if($this->fuserCursoSupModel->removeAllCursosSupUser($data['userId'])){
									//removo os cursos de pós
									if($this->fuserPosModel->deleteAllUserPosCurso($data['userId'])){
										//removo as informações de especialização
										if(!$this->fuserPosModel->deleteAllPosUser($data['userId'])){
											throw new Exception('Ops! Algo deu errado ao tentar remover a especialização! Tente novamente.');
										}
									} else {
										throw new Exception('Ops! Algo deu errado ao tentar remover os cursos de pós! Tente novamente.');
									}            			
								} else {
									throw new Exception('Ops! Algo deu errado ao tentar atualizar a formação! Tente novamente.');
								}								 
							}
						}
						if($this->fuserformacoesModel->register($data)){
							flash('message', 'Nível de escolaridade registrado com sucesso!','success');                        
							redirect('fuserformacoes/index');
						} else {                                
							throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
						} 
					} catch (Exception $e) {
						$erro = 'Erro: '.  $e->getMessage(); 
						flash('message', $erro,'error'); 
						$this->view('fuserformacoes/index',$data);       
					}  
				} else {                       
					flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
					$this->view('fuserformacoes/index', $data);
				} 
			} 
		}
	}   
?>