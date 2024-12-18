<?php
	class Escolas extends Controller{
		
		public function __construct(){			 
      $this->escolaModel = $this->model('Escola');
      $this->bairroModel = $this->model('Bairro');
      $this->grupoModel = $this->model('Grupo');
		}

    //Valida o id para excluir ou editar
    public function validaId($id){      
			if(!is_numeric($id)){
				flash('message', 'ID inválido.', 'error'); 
        redirect('escolas/index');
        die();			
			} else if(!$data = $this->escolaModel->getEscolaById($id)) {
        flash('message', 'Escola inexistente.', 'error'); 
        redirect('escolas/index');
        die();	
      }
      return $data;
    }    

		public function index() {
      
      $this->getPermicao('ler',$_SESSION[SE.'_user_id']);

			if($escolas = $this->escolaModel->getEscolas()){													
				foreach($escolas as $row){                    
					$escolasArray[] = [
						'id' => getData($row->id),
						'nome' => strtoupper(getData($row->nome)),
						'bairro_id' => getData($row->bairro_id),
						'bairro' => 
							($this->bairroModel->getBairroById($row->bairro_id))
							? $this->bairroModel->getBairroById($row->bairro_id)->bairro
							: '',
						'logradouro' => strtoupper(getData($row->logradouro)),                    
						'numero' => getData($row->numero),
						'emAtividade' => 
							($row->emAtividade == 1) 
							? 'Sim' 
							: 'Não'
					];       
				}  
				$data = [
					'escolas' => $escolasArray,
          'titulo' => 'Escolas'
				];            
				$this->view('escolas/index', $data);
			} else {                                 
				$this->view('escolas/index');
			}   
		}

		public function new(){
      
      $this->getPermicao('criar',$_SESSION[SE.'_user_id']);
			
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
				$data = [
					'nome' => strtoupper(post('nome')),
					'bairro_id' => post('bairro_id'),
					'logradouro' => strtoupper(post('logradouro')),                    
					'numero' => post('numero'),
					'emAtividade' => post('emAtividade'),
					'bairros' => 
						($this->bairroModel->getBairros())
						? $this->bairroModel->getBairros()
						: '',
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => '',
					'numero_err' => '',
          'titulo' => 'Adicionar escola'
				];		
				// Valida nome
				if(empty($data['nome'])){
					$data['nome_err'] = 'Por favor informe o nome da escola php';
				} 

				// Valida logradouro
				if(empty($data['logradouro'])){
					$data['logradouro_err'] = 'Por favor informe o logradouro.';
				} 

				// Valida bairro
				if((empty($data['bairro_id'])) || ($data['bairro_id'] == 'null')){                    
					$data['bairro_id_err'] = 'Por favor informe o bairro.';
				} 				

				// Valida emAtividade
				if((($data['emAtividade'])=="") || ($data['emAtividade'] <> '0') && ($data['emAtividade'] <> '1')){
					$data['emAtividade_err'] = 'Por favor informe se deseja manter a escola ativa.';
				} 
			
				if(                    
					empty($data['nome_err']) &&
					empty($data['logradouro_err']) && 
					empty($data['bairro_id_err']) &&  
					empty($data['emAtividade_err'])
				){	
					try {
            if($this->escolaModel->register($data)){
              flash('message', 'Escola registrada com sucesso!','success');                        
              redirect('escolas/index');
            } else {                                
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
            } 

          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(); 
            flash('message', $erro,'error');                       
            redirect('escolas/index');      
          } 					
				} else {					
					if(!empty($data['erro'])){
						flash('message', $data['erro'], 'error');
					}
					$this->view('escolas/new', $data);
				}       			
			} else {					
				$data = [
					'nome' => '',
					'bairro_id' => '',
					'bairros' => $this->bairroModel->getBairros(),
					'logradouro' => '',
					'numero' => '',
					'emAtividade' => '',
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => '',
					'numero_err' => '',
          'titulo' => 'Adicionar escola'
				];					
				$this->view('escolas/new', $data);
			} 
		}

    public function edit($id){ 

      $this->getPermicao('editar',$_SESSION[SE.'_user_id']);

      $escola = $this->validaId($id);			

			if($_SERVER['REQUEST_METHOD'] == 'POST'){		
				$data = [
					'id' => getData($id),
					'nome' => strtoupper(post('nome')),
					'bairro_id' => post('bairro_id'),
					'logradouro' => strtoupper(post('logradouro')),                    
					'numero' => post('numero'),
					'emAtividade' => post('emAtividade'),
					'bairros' => 
						($this->bairroModel->getBairros())
						? $this->bairroModel->getBairros()
						: '',
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => '',
					'numero_err' => '',
          'titulo' => 'Editar escola'
				];		
					
				// Valida nome
				if(empty($data['nome'])){
					$data['nome_err'] = 'Por favor informe o nome da escola.';
				} 

				// Valida logradouro
				if(empty($data['logradouro'])){
					$data['logradouro_err'] = 'Por favor informe o logradouro.';
				} 

				// Valida bairro
				if((empty($data['bairro_id'])) || ($data['bairro_id'] == 'null')){                    
					$data['bairro_id_err'] = 'Por favor informe o bairro';
				} 

				// Valida emAtividade
				if((($data['emAtividade'])=="") || ($data['emAtividade'] <> '0') && ($data['emAtividade'] <> '1')){
					$data['emAtividade_err'] = 'Por favor informe se deseja manter a escola ativa.';
				}  				
					
				if(                    
					empty($data['nome_err']) &&
					empty($data['logradouro_err']) && 
					empty($data['bairro_id_err']) && 
					empty($data['emAtividade_err'])
				){							
					if($this->escolaModel->update($data)){						
						flash('message', 'Escola atualizada com sucesso!','success');                        
						redirect('escolas/index');
					} else {
						die('Ops! Algo deu errado.');
					}		
				} else {					
					if(!empty($data['erro'])){
						flash('message', $data['erro'], 'error');
					}
					$this->view('escolas/edit', $data);
				}     			
			} else {					
				$escola = $this->escolaModel->getEscolaByid($id);		
				$data = [
					'id' => getData($id),
					'nome' => strtoupper(getData($escola->nome)),
					'bairro_id' => getData($escola->bairro_id),
					'bairros' => $this->bairroModel->getBairros(),
					'logradouro' => strtoupper(getData($escola->logradouro)),
					'numero' => ($escola->numero) ? $escola->numero : '',
					'emAtividade' => getData($escola->emAtividade),
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => '',
					'numero_err' => '',
          'titulo' => 'Editar escola'                   
				]; 
				$this->view('escolas/edit', $data);
			} 
		}

		public function delete($id){ 
      
      $this->getPermicao('apagar',$_SESSION[SE.'_user_id']);

      $escolaDelete = $this->validaId($id);	

			if($escolas = $this->escolaModel->getEscolas()){													
				foreach($escolas as $row){                    
					$escolas = [
						'id' => getData($row->id),
						'nome' => getData($row->nome),
						'bairro_id' => getData($row->bairro_id),
						'bairro' => $this->bairroModel->getBairroById($row->bairro_id)->bairro,
						'logradouro' => getData($row->logradouro),                    
						'numero' => ($row->numero) ? $row->numero : '',
						'emAtividade' => ($row->emAtividade == 1) ? 'Sim' : 'Não'            
					];       
				}  
			}			
			//esse $_POST['delete'] vem lá do view('confirma');
			if(isset($_POST['delete'])){  
				if(isset($erro)){
					flash('message', $erro , 'error');                     
					$this->view('escolas/index',$data);
					die();
				}   
				try { 
					if($this->escolaModel->delete($id)){                        
						flash('message', 'Registro excluido com sucesso!', 'success'); 
						redirect('escolas/index');
					} else {
						throw new Exception('Ops! Algo deu errado ao tentar excluir os dados!');
					}
				} catch (Exception $e) {                    
					$erro = 'Erro: '.  $e->getMessage();                   
					flash('message', $erro,'error');                    
					redirect('escolas/index',$data);
					die();
				}                
			} else {	
				$data = [
					'escolas' => $escolas,
					'escola' => $escolaDelete,
          'titulo' => 'Excluir escola'
				];
				$this->view('escolas/confirma',$data);
				die();
			}                 
		}

		public function atualizasituacao(){ 
			try{
				if($this->escolaModel->atualizaSituacao($_POST['escolaId'],$_POST['situacao'])){
					//para acessar esses valores no jquery
					//exemplo responseObj.message
					$json_ret = array(
						'classe'=>'success', 
						'message'=>'Dados gravados com sucesso',
						'error'=>false
					);       
					echo json_encode($json_ret); 
				}     
			} catch (Exception $e) {
				$json_ret = array(
					'classe'=>'error', 
					'message'=>'Erro ao gravar os dados',
					'error'=>$data
				);                     
				echo json_encode($json_ret); 
			}
		} 
    
    // Função que valida se o usuário pode ou não apagar um grupo
    public function getPermicao($acao,$userId){      
      if(!$this->grupoModel->getPermicao($acao,$userId,'escola')){
        flash('message', 'Você não tem permissão para '. $acao.' na tabela grupo.', 'error'); 
        if($acao === 'ler'){
          redirect('index');
        } else {
          redirect('pages/index');
        }        
        die();
      }    	  		
    }

	}   
?>