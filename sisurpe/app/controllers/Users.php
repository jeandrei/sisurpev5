<?php 
	class Users extends Controller{

		public function __construct(){
				//vai procurar na pasta model um arquivo chamado User.php e incluir
				$this->userModel = $this->model('User');
				$this->escolaModel = $this->model('Escola');
        $this->grupoModel = $this->model('Grupo');
		}

		public function register(){				            
			if($_SERVER['REQUEST_METHOD'] == 'POST'){					
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'name' => strtoupper(post('name')),
					'cpf' => post('cpf'),
					'email' => strtolower(post('email')),
					'password' => post('password'),
					'confirm_password' => post('confirm_password'),
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				]; 
			

				// Validate Email
				if(empty($data['email'])){
					$data['email_err'] = 'Por favor informe seu email';
				} else {
					if(!validaemail($data['email'])){
						$data['email_err'] = 'Email inválido';  
					} else {							
						if($this->userModel->findUserByEmail($data['email'])){
							$data['email_err'] = 'Email já cadastrado'; 
						}
					}                    
				}

				// Validate Name
				if(empty($data['name'])){
					$data['name_err'] = 'Por favor informe o nome';
				}

				//valida cpf
				if(empty($data['cpf'])){
					$data['cpf_err'] = 'CPF é obrigatório';
				} elseif(!validaCPF($data['cpf'])){
					$data['cpf_err'] = 'CPF inválido';    
				} elseif($this->userModel->cpfCadastrado($data['cpf'])){
					$data['cpf_err'] = 'CPF já cadastrado';       
				} else {
					$data['cpf_err'] = '';
				} 

				// Validate Password
				if(empty($data['password'])){
					$data['password_err'] = 'Por favor informe a senha';
				} elseif (strlen($data['password']) < 6){
					$data['password_err'] = 'Senha deve ter no mínimo 6 caracteres';
				}

				// Validate Confirm Password
				if(empty($data['confirm_password'])){
					$data['confirm_password_err'] = 'Por favor confirme a senha';
				} else {
					if($data['password'] != $data['confirm_password']){
						$data['confirm_password_err'] = 'Senha e confirmação de senha diferentes';    
					}
				}

				// Make sure errors are empty
				if(                    
						empty($data['email_err']) &&
						empty($data['name_err']) && 
						empty($data['password_err']) &&
						empty($data['cpf_err']) &&
						empty($data['confirm_password_err']) 
				){		
					// Hash Password criptografa o password
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
					// Register User
					if($this->userModel->register($data)){
						// Cria a menságem antes de chamar o view va para 
						// views/users/login a segunda parte da menságem
						flash('register_success', 'Você está registrado e pode efetuar o login');                        
						redirect('users/login');
					} else {
						die('Ops! Algo deu errado.');
					}
				} else {
					// Load the view with errors
					$this->view('users/register', $data);
				}			
			} else {
				// Init data
				$data = [
					'name' => '',
					'email' => '',
					'cpf' => '',
					'password' => '',
					'confirm_password' => '',
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => ''
				];
				// Load view
				$this->view('users/register', $data);
			}
		}

		public function edit($user_id){	 
			$user = $this->userModel->getUserById($user_id); 				           
			if($_SERVER['REQUEST_METHOD'] == 'POST'){					
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [
					'user_id' => getData($user_id),
					'name' => strtoupper(post('name')),
					'email' => getData($user->email),
					'cpf' => getData($user->cpf),
					'usertype' => post('usertype'),
					'password' => post('password'),
					'confirm_password' => post('confirm_password'),
					'name_err' => '',
					'password_err' => '',
					'confirm_password_err' => '',
					'escolaId_err' => ''
				]; 				

				// Validate Name
				if(empty($data['name'])){
					$data['name_err'] = 'Por favor informe o nome';
				}

				// Validate Password
				if(!empty($data['password'])){                    
					if(strlen($data['password']) < 6){
						$data['password_err'] = 'Senha deve ter no mínimo 6 caracteres';
					}
				}

				// Validate Confirm Password
				if(!empty($data['password'])){    
					if(empty($data['confirm_password'])){
						$data['confirm_password_err'] = 'Por favor confirme a senha';
					} else {
						if($data['password'] != $data['confirm_password']){
							$data['confirm_password_err'] = 'Senha e confirmação de senha diferentes';    
						}
					}
				}				
					// Make sure errors are empty
					if(           
						empty($data['name_err']) && 
						empty($data['password_err']) &&                    
						empty($data['confirm_password_err']) 
					){
						// Hash Password criptografa o password
						if((!empty($data['password'])) && (!is_null($data['password'])) && ($data['password'] != "")){
							$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
						} else {
							$data['password'] = null;						}
																	
						// Update User
						if($this->userModel->update($data)){
							// Cria a menságem antes de chamar o view va para 
							// views/users/login a segunda parte da menságem
							flash('mensagem', 'Usuário atualizado com sucesso!');                        
							redirect('adminusers/index');
						} else {
							die('Ops! Algo deu errado.');
						}								
					} else {
						// Load the view with errors
						$this->view('users/edit', $data);
					}			
			} else {
				// Init data
				$data = [
					'user_id' => getData($user_id),
					'name' => strtoupper(getData($user->name)),
					'email' => getData($user->email),
					'cpf' => getData($user->cpf),					
					'escolas' => $this->escolaModel->getEscolas(),
					'password' => '',
					'confirm_password' => '',
					'name_err' => '',
					'password_err' => '',
					'confirm_password_err' => '',
					'escolaId_err' => ''
				];
				// Load view
				$this->view('users/edit', $data);
			}
		}

		public function login(){  
			if($_SERVER['REQUEST_METHOD'] == 'POST'){				
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				$data = [                    
					'email' => post('email'),
					'password' => post('password'),  
					'email_err' => '',
					'password_err' => ''
				];      

				// Validate Email
				if(empty($data['email'])){
					$data['email_err'] = 'Por favor informe seu email';
				} else {						
					if(!$this->userModel->findUserByEmail($data['email'])){
						$data['email_err'] = 'Usuário não encontrado';
					} 
				}	

				// Validate Password
				if(empty($data['password'])){
					$data['password_err'] = 'Por favor informe sua senha';
				} 					
												
				// Make sure errors are empty
				if(                    
					empty($data['email_err']) &&                     
					empty($data['password_err'])                     
				){
					//Validate
					// 1 Check and set loged in user
					// 2 models/User login();
					$loggedInUser = $this->userModel->login($data['email'], $data['password']);
					if($loggedInUser){
						// Create Session 
						// função no final desse arquivo
						$this->createUserSession($loggedInUser);
					} else {
						$data['password_err'] = 'Senha incorreta';
						$this->view('users/login', $data);
					}
				} else {
					// Load the view with errors
					$this->view('users/login', $data);
				} 			
			} else {
				// Init data
				$data = [
					'name' => '',
					'email' => '',
					'password' => '',
					'confirm_password' => '',
					'name_err' => '',
					'email_err' => '',
					'password_err' => '',
					'confirm_password_err' => '',
					'escolaId_err' => ''
				];
				// Load view
				$this->view('users/login', $data);
			}
		}


		public function createUserSession($user){
			// $user->id vem do model na função login() retorna a row com todos os campos
      // da consulta na tabela users 
      // SE é uma constante que vem lá do config\config.php
      // para evitar que dois sistemas diferentes fiquem logados com o mesmo login  
      $userPermit = $this->userModel->getUserPermitions($user->id);
      foreach($userPermit as $permicao){
        $permitArr[$permicao->tabela] = 
        [          
          'ler' => $permicao->ler,
          'editar' => $permicao->editar,
          'criar' => $permicao->criar,
          'apagar' => $permicao->apagar
        ]; 
      }

      $_SESSION[SE.'_user_permit'] = $permitArr;
			$_SESSION[SE.'_user_id'] = $user->id;
			$_SESSION[SE.'_user_email'] = $user->email;
			$_SESSION[SE.'_user_name'] = $user->name;			
			redirect('pages/main');
		}

		public function logout(){
			unset($_SESSION[SE.'_user_id']);
			unset($_SESSION[SE.'_user_email']);
			unset($_SESSION[SE.'_user_name']);
			session_destroy();
			redirect('pages/login'); 
		}

		public function isLoggedIn(){
			if(isset($_SESSION[SE . '_user_id'])){
				return true;
			} else {
				return false;
			}
		}

		public function enviasenha(){				           
			if($_SERVER['REQUEST_METHOD'] == 'POST'){  
					$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
					$data = [                
						'email' => post('email')               
					];  

					// Validate Email
					if(empty($data['email'])){
						$data['email_err'] = 'Por favor informe seu email';
					} else {
						if(!validaemail($data['email'])){
							$data['email_err'] = 'Email inválido';  
						} else {								
							if(!$this->userModel->findUserByEmail($data['email'])){
								$data['email_err'] = 'Email ainda não cadastrado'; 
							}
						}                    
					}
				
					if(                    
						empty($data['email_err'])                  
					){
						//ENVIA O EMAIL								
						// CRIA UMA NOVA SENHA RANDOMICAMENTE
						$password = RandomPassword();
						// Hash Password CRIPTOGRAFA O PASSWORD
						$data['password'] = password_hash($password, PASSWORD_DEFAULT);
						try {
							// ATUALIZA O PASSWORD NO BANCO DE DADOS
							if($this->userModel->updatepassword($data)){									
								//MANDE O EMAIL COM A SENHE
								if($this->userModel->sendemail($data['email'], $password)){
									flash('mensagem', 'Email enviado com sucesso!');                     
									redirect('users/login');
								} else {
									flash('mensagem', 'Erro no envio do email! Tente novamente mais tarde.','alert-danger');                     
									redirect('users/enviasenha');
								}                   
							}
						} catch (Exception $e) {
							die('Ops! Algo deu errado.');  
						} 	
					} else {
						// Load the view with errors
						$this->view('users/enviasenha', $data);
					} 
			} else {
				// Init data
				$data = [               
						'email' => ''                
				];
				// Load view            
				$this->view('users/enviasenha', $data);
			} 		
		} 


		public function alterasenha(){			           
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$data = [                
					'password' => post('password'),
					'confirm_password' => post('confirm_password'),
					'password_err' => '',
					'confirm_password_err' => ''               
				]; 
				// Validate Password
				if(empty($data['password'])){
					$data['password_err'] = 'Por favor informe a senha';
				} elseif (strlen($data['password']) < 6){
					$data['password_err'] = 'Senha deve ter no mínimo 6 caracteres';
				}

				// Validate Confirm Password
				if(empty($data['confirm_password'])){
					$data['confirm_password_err'] = 'Por favor confirme a senha';
				} else {
					if($data['password'] != $data['confirm_password']){
						$data['confirm_password_err'] = 'Senha e confirmação de senha diferentes';    
					}
				} 

				if(  
					empty($data['password_err']) &&
					empty($data['confirm_password_err']) 
				){
					// Hash Password criptografa o password
					$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);                     
					$data['email'] = $_SESSION[SE . '_user_email'];                     
					// Register User
					if($this->userModel->updatePassword($data)){
						// Cria a menságem antes de chamar o view va para 
						// views/users/login a segunda parte da menságem
						flash('message', 'Senha atualizada com Sucesso!','success'); 						                    
						redirect('pages/index');  
						die();
					} else {
						die('Ops! Algo deu errado.');
					}								
				} else {
					$this->view('users/alterasenha', $data);   
				}

			} else {
				$data = [                
					'password' => '',
					'confirm_password' => '',
					'password_err' => '',
					'confirm_password_err' => ''               
				];     
				$this->view('users/alterasenha', $data);
			}
		}	

		public function admin(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('pages/index');
				die();
			} else if ((!isAdmin()) && (!isSec())){                
				flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
				redirect('pages/index'); 
				die();
			} 
			$limit = 10;
			$data = [
				'titulo' => 'Busca por Usuários',
				'description' => 'Busca por registros de Usuários'          
			]; 

			//mudei estava o antigo tem que arrumar
			if(isset($_GET['page'])){  
				$page = $_GET['page'];  
			} else {  
				$page = 1;  
			}  								
			$options = array(
				'results_per_page' => 10,
				'url' => URLROOT . '/adminusers/index.php?page=*VAR*&name=' . $name,
				'named_params' => array(
																	':name' => $name
															)     
			);		
			$paginate = $this->userModel->getUsers($page, $options); 						
			if($paginate->success == true) {            
				// $data['paginate'] é só a parte da paginação tem que passar os dois arraya paginate e result
				$data['paginate'] = $paginate;
				// $result são os dados propriamente dito depois eu fasso um foreach para passar
				// os valores como posição que utilizo um métido para pegar
				$results = $paginate->resultset->fetchAll(); 
			}  
			$data['results'] =  $results;        
			//FIM PARTE PAGINAÇÃO RETORNANDO O ARRAY $data['paginate']  QUE VAI PARA A VARIÁVEL $paginate DO VIEW NESSE CASO O INDEX					
			$this->view('adminusers/index', $data);
		}

		public function getUsersCpf($cpf){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('pages/index');
				die();
			} else if ((!isAdmin()) && (!isSec())){                
				flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
				redirect('pages/index'); 
				die();
			}   
			try {
				$user_id = $this->userModel->getUserIdByCpf($cpf);
				echo json_encode($user_id);         
			} catch (Exception $e) {
				return false;
			}
		}

    public function grupos($userId){      
      $gruposCadastrados = $this->grupoModel->getGrupos();      
      if($userGrupos = $this->grupoModel->gruposDoUsuario($userId)){
        foreach($userGrupos as $row){
          $gruposUsuario[] = [
            'id' => $row->id,
            'userId' => $row->userId,
            'grupoId' => $row->grupoId,
            'grupo' => $this->grupoModel->getGrupoById($row->grupoId)->grupo    
          ];
        }
      } else {
        $gruposUsuario = [];
      }
      
      if($_SERVER['REQUEST_METHOD'] == 'POST'){             
        //SANITIZE POST impede códigos maliciosos        
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [ 
          'userId' => $userId,
          'usuario' => $this->userModel->getUserById($userId)->name,
          'grupoId' => post('grupoId'),
          'gruposUsuario' => $gruposUsuario,
          'gruposCadastrados' => $gruposCadastrados,
          'titulo' => 'Grupos do Usuário',
          'grupoId_err' => ''
        ];

        if($data['grupoId'] == 'null'){
          $data['grupoId_err'] = 'Você deve selecionar um grupo';          
        }

        if(
          empty($data['grupoId_err'])
        ){
          try {
            if($this->userModel->userPertenceGrupo($data['userId'], $data['grupoId'])){
              throw new Exception('Ops! O usuário já está neste grupo!');
            } else {
              if($this->userModel->addGrupo($data['userId'], $data['grupoId'])){
                flash('message', 'Dados atualizados com sucesso!');                     
                redirect('users/grupos/'.$userId);
              } else {
                throw new Exception('Ops! Algo deu errado ao tentar adicionar o grupo!');
              }
            }     
          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(). "\n";
            flash('message', $erro,'error');
            $this->view('users/grupos',$data);
          }   
        } else {
          $this->view('users/grupos',$data);
        }      

      } else {        
        $data = [ 
          'userId' => $userId,
          'usuario' => $this->userModel->getUserById($userId)->name,
          'grupoId' => '',
          'gruposUsuario' => $gruposUsuario,
          'gruposCadastrados' => $gruposCadastrados,
          'titulo' => 'Grupos do Usuário',
          'grupoId_err' => ''
        ];
        $this->view('users/grupos',$data);
      }
    }

    public function deleteGrupo(){      
      $userId = get('userId');
      $grupoId = get('grupoId');
      //echo "userId = " . $userId . " grupoId = " . $grupoId;
      //die();
      try {
        if($this->userModel->deleteGrupoUsuario($userId, $grupoId)){
          flash('message', 'Grupo removido com sucesso!');                     
          redirect('users/grupos/'.$userId);
        } else {
          throw new Exception('Ops! Algo deu errado ao tentar excluir o grupo!');          
        }     
      } catch (Exception $e) {
        $erro = 'Erro: '.  $e->getMessage(). "\n";
        flash('message', $erro,'error');
        $this->view('users/grupos',$data);
      }      
    }
	}   
?>