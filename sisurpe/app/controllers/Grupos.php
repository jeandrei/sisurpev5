<?php 
  class Grupos extends Controller{
    public function __construct(){
      //vai procurar na pasta model um arquivo chamado User.php e incluir
      $this->grupoModel = $this->model('Grupo');
    }

     //Valida o id para excluir ou editar
     public function validaId($id){      
			if(!is_numeric($id)){
				flash('message', 'ID inválido.', 'error'); 
        redirect('grupos/index');
        die();			
			} else if(!$data = $this->grupoModel->getGrupoById($id)) {
        flash('message', 'Grupo inexistente.', 'error'); 
        redirect('grupos/index');
        die();	
      }
      return $data;
    }    
   
    //Carrega os grupos registrados na tabela users
		public function index() {	 
      
      $this->getPermicao('ler',$_SESSION[SE.'_user_id']);
      
			if(isset($_GET['page'])){  
				$page = $_GET['page'];  
			} else {  
				$page = 1;  
			}  			

			$options = array(
				'results_per_page' => 10,
				'url' => URLROOT . '/grupos/index.php?page=*VAR*&grupo=' . get('grupo') ,
				'using_bound_params' => true,
				'named_params' => array(
                            ':grupo' => get('grupo')														                      
                          )     
			);

			$pagination = $this->grupoModel->getGrupoPag($page,$options);       

			if($pagination->success == true){ 				
        $users = $pagination->resultset->fetchAll();
				if(!empty($users)){
					foreach($users as $row){
						$results[] = [
							'id'   => $row['id'],
							'grupo' => ($row['grupo'])
											? $row['grupo']
											: ''					
						];
					}
				}								
			} else {
				$results = false;
			}
			
			$data = [
				'pagination' => isset($pagination)
												? $pagination
												: '',
				'results' => isset($results)
												? $results
                        : '',
        'titulo'	 => 'Grupos Cadastrados'
			];
				
			$this->view('grupos/index', $data);				
		}

		//Cadastra um usuário na tabela users
		public function new(){  			

			$this->getPermicao('criar',$_SESSION[SE.'_user_id']);

			if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);						
				$data = [
					'grupo' => post('grupo'),									
					'grupo_err' => '',					
					'titulo' => 'Novo Grupo'
				];   
			
				// Validate Grupo
				if(empty($data['grupo'])){
					$data['grupo_err'] = 'Por favor informe o nome do grupo';
				}		
        
        // Verifica se já existe um grupo
        if($this->grupoModel->grupoExiste($data['grupo'])){
          $data['grupo_err'] = 'Grupo já existente';
        }
					
				// Make sure errors are empty
				if(        
					empty($data['grupo_err']) 
				){						
					try {     
						if($lastId = $this->grupoModel->register($data)){
              flash('message', 'Cadastro realizado com sucesso!'); 
							redirect('grupos');							
							die();
						} else {                        
							throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
						}
					} catch (Exception $e) {                         
						$erro = 'Erro: '.  $e->getMessage();                      
						flash('message', $erro,'error');						
						$this->view('grupos/new',$data);
						die();
					} 
				} else {
					// Load the view with errors                     
					$this->view('grupos/new', $data);
				}               
			} else { 
				$data = [
					'grupo' => '',					
					'grupo_err' => '',					
          'titulo' => 'Novo Grupo'					
				];
				
        $this->view('grupos/new', $data);
				
			} 
		}

		//Edita um grupo
		public function edit($id){

      //verifica se o usuário pode editar um grupo
      $this->getPermicao('editar',$_SESSION[SE.'_user_id']);
      //valida o id     
      $grupo = $this->validaId($id);  
      $grupoPermicoes = $this->grupoModel->getPermicoesGrupo($id);        
									
			if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
        
        /*monto um array com o id do registro da tabela grupoAcaoTabela exemplo ler = 1 quer dizer que na 
        tabela grupoAcaoTabela id 1 que é users o usuário pode ler*/
        $permicoes = [
          'ler' => isset($_POST['ler']) ? $_POST['ler'] : "",
          'editar' => isset($_POST['editar']) ? $_POST['editar'] : "",
          'criar' => isset($_POST['criar']) ? $_POST['criar'] : "",
          'apagar' => isset($_POST['apagar']) ? $_POST['apagar'] : "",
        ];        

				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'id' => $id,
					'grupo' => post('grupo'),					
					'grupo_err' => '',
          'permicoes' => $permicoes,
          'titulo' => 'Editar Grupo'					
				];                 

				// Validate grupo
				if(empty($data['grupo'])){
					$data['grupo_err'] = 'Por favor informe seu nome';
				}	   
        
        // só verifico se o grupo já existe se foi alterado o nome do grupo
        if(strcmp(strtolower($data['grupo']),strtolower($this->grupoModel->getGrupoById($id)->grupo)) !== 0){           
          // Verifica se já existe um grupo
          if($this->grupoModel->grupoExiste($data['grupo'])){
            $data['grupo_err'] = 'Grupo já existente';
          }	
        }        
        			
				if(   
					empty($data['grupo_err'])  					 					                 
				){			
					// Atualiza o grupo  
          try {     
						if($this->grupoModel->update($data)){
              flash('message', 'Grupo atualizado com sucesso!'); 
							redirect('grupos');							
							die();
						} else {                        
							throw new Exception('Ops! Algo deu errado ao tentar atualizar os dados!');
						}
					} catch (Exception $e) {                         
						$erro = 'Erro: '.  $e->getMessage();                      
						flash('message', $erro,'error');						
						redirect('grupos');
						die();
					} 
				} else {
					// Load the view with errors
					$this->view('grupos/edit', $data);
				}      			
			} else {		
				$data = [
					'id' => $id,
					'grupo' => $grupo->grupo,		
          'gruoPermicoes' => $grupoPermicoes,
					'grupo_err' => '',						
          'titulo' => 'Editar Grupo'
				];
				
				// Load view
        $this->view('grupos/edit', $data);
				
			} 
		}

		//Remove um grupo da tabela grupos
		public function delete($id){   
      
      //verifica se tem permissão para apagar
      $this->getPermicao('apagar',$_SESSION[SE.'_user_id']);
      //valida o id passado 
      $grupo = $this->validaId($id);      
      $gruposUsuario = $this->grupoModel->gruposDoUsuario($_SESSION[SE.'_user_id']);     
				
			//esse $_POST['delete'] vem lá do view('confirma');
			if(isset($_POST['delete'])){        
				try {                    
					if($this->grupoModel->delGrupoByid($id)){
						flash('message', 'Registro excluido com sucesso!'); 
						redirect('grupos/index');
					} else {
						throw new Exception('Ops! Algo deu errado ao tentar excluir os dados!');
					}
				} catch (Exception $e) {
					$erro = 'Erro: '.  $e->getMessage();
					flash('message', $erro, 'error'); 
					redirect('grupos/index');
				}                
			} 
      $data = [
        "grupo" => $grupo,
        "titulo" => 'Excluir um grupo'
      ];
      $this->view('grupos/confirma',$data);
      exit();
    }
    
    // Função que valida se o usuário pode ou não apagar um grupo
    public function getPermicao($acao,$userId){      
      if(!$this->grupoModel->getPermicao($acao,$userId,'grupos')){
        flash('message', 'Você não tem permissão para '. $acao.' na tabela grupo.', 'error'); 
        if($acao === 'ler'){
          redirect('index');
        } else {
          redirect('grupos/index');
        }        
        die();
      }    	  		
    }
  }   
?>