<?php 
	class Municipios extends Controller{

		public function __construct(){                     
				$this->municipioModel = $this->model('Municipio');
				$this->estadoModel = $this->model('Estado');
				$this->bairroModel = $this->model('Bairro');
		}

		/* Lista todos os municípios */
		public function index(){  
			if(isset($_GET['page'])){  
					$page = $_GET['page'];  
			} else {  
					$page = 1;  
			}  
			$options = array(
					'results_per_page' => 10,
					'url' => URLROOT . '/municipios/index.php?page=*VAR*&nomeMunicipio=' . $_GET['nomeMunicipio'] .'&estadoId=' . $_GET['estadoId'], 
					'using_bound_params' => true,
					'named_params' => array(
																	':nomeMunicipio' => $_GET['nomeMunicipio'],
																	':estadoId' => $_GET['estadoId']
																	)     
			);				
			$pagination = $this->municipioModel->getMunicipiosPag($page,$options);  
			unset($data);
			if($pagination->success == true){					
				$data['pagination'] = $pagination;
				$results = $pagination->resultset->fetchAll(); 								
				//Monto o array data['results'][] com os resultados
				if(!empty($results)){
					foreach($results as $row){
						$data['results'][] = [
							'id' => $row['id'],
							'nomeMunicipio' => $row['nomeMunicipio'],
							'estadoId' => $row['estadoId'],
							'nomeEstado' => $this->estadoModel->getEstadoById($row['estadoId'])->estado
						];
					}
				}								
			} else {       
				$data['results'] = false;
			}
			$data = [
					'titulo' => 'Municípios',
					'estados' => $this->estadoModel->getEstados()
			]; 
			$this->view('municipios/index',$data);
		}

		/* Adiciona um município */
		public function add(){                       
			if($_SERVER['REQUEST_METHOD'] == 'POST'){                
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				unset($data);
				$data = [
					'titulo' => 'Novo Município',
					'nomeMunicipio' => post('nomeMunicipio'),
					'estadoId' => post('estadoId'),
					'estados' => $this->estadoModel->getEstados()
				];                
				
				//valida nomeMunicipio   
				$data['nomeMunicipio_err'] = 
				validate($data['nomeMunicipio'], 
					$options = [
						'obrigatorio'=>true,
						'tipo'=>'string',                        
						'min' => 5                        
					]                    
				);  

				//valida estadoId  
				$data['estadoId_err'] = 
				validate($data['estadoId'], 
					$options = [
						'obrigatorio'=>true,
						'tipo'=>'select'        
					]                    
				);  

				if(
					empty($data['nomeMunicipio_err'])&&
					empty($data['estadoId_err'])
				){
					try {						
						if($this->municipioModel->municipioExistente($data['nomeMunicipio'],$data['estadoId'])){                            
							throw new Exception('Ops! Já existe um município cadastrado com esse nome!');
						}
						if($this->municipioModel->register($data)){
							flash('message', 'Cadastro realizado com sucesso!','success');                     
							redirect('municipios/index');
							die();
						} else {
							throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
						}
					} catch (Exception $e) {                             
						$erro = 'Erro: '.  $e->getMessage();                      
						flash('message', $erro,'error');
						$this->view('municipios/add',$data);
						die();
					}                   
				} else {
					//Validação falhou
					flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
					$this->view('municipios/add',$data);
					die();
				} 
			} else {
				//limpo o array $data
				unset($data);
				$data = [ 
					'titulo' => 'Novo Município',                   
					'estados' => $this->estadoModel->getEstados()                    
				];
				$this->view('municipios/add', $data);
			}     
		}

		/* Editar um município */
		public function edit($_municipioId){  
			if($_SERVER['REQUEST_METHOD'] == 'POST'){                                
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
				unset($data);
				$data = [
					'titulo' => 'Editar Município',
					'nomeMunicipio' => html($_POST['nomeMunicipio']),
					'municipioId' => $_municipioId,
					'estadoId' => $this->municipioModel->getEstadoMunicipio($_municipioId)->id,
					'estados' => $this->estadoModel->getEstados()
				];
												
				//valida nomeMunicipio  
				$data['nomeMunicipio_err'] = 
				validate($data['nomeMunicipio'], 
					$options = [
						'obrigatorio'=>true,
						'tipo'=>'string',                        
						'min' => 5                        
					]                    
				);   
						
				if(
					empty($data['nomeMunicipio_err'])                    
				){
					try {							
						if($this->municipioModel->municipioExistente($data['nomeMunicipio'],$data['estadoId'])){                            
							throw new Exception('Ops! Já existe um município cadastrado com esse nome!');
						}						
						if($this->municipioModel->update($data)){
							flash('message', 'Cadastro realizado com sucesso!','success');                     
							redirect('municipios/index');
							die();
						} else {
							throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
						}
					} catch (Exception $e) {   
						unset($data);
						$data = [
							'titulo' => 'Editar Município',
							'municipioId' => $_municipioId,
							'nomeMunicipio' => $this->municipioModel->getMunicipioById($_municipioId)->nomeMunicipio,
							'bairros' => $this->bairroModel->getBairrosMunicipioById($_municipioId)
						];                          
						$erro = 'Erro: '.  $e->getMessage();                      
						flash('message', $erro,'error');
						$this->view('municipios/edit',$data);
						die();
					}                   
				} else {
					//Validação falhou
					flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
					$this->view('municipios/edit',$data);
					die();
				}   
			} else {
				//limpo o array $data
				unset($data);
				$data = [
					'titulo' => 'Editar Município',
					'municipioId' => $_municipioId,
					'nomeMunicipio' => $this->municipioModel->getMunicipioById($_municipioId)->nomeMunicipio,
					'bairros' => $this->bairroModel->getBairrosMunicipioById($_municipioId)
				];
				$this->view('municipios/edit', $data);
			} 
		}    
		
		//RETORNA O CÓDIGO HTML PARA ADICIONAR NO SELECT MUNICÍPIOS
		public function municipiosEstado($idEstado){   
			$data = $this->municipioModel->getMunicipiosEstadoById($idEstado); 			
			if($idEstado == 'null'){
				die("<option value='null'>Selecione um Estado</option");
			}			
			if(!$data){
				die("<option value='null'>Sem municípios para este estado</option>");
			} 	
			echo ("<option value='null'>Selecione o Município</option>");			
			foreach($data as $row){
				echo "<option value=".$row->id.">".mb_strtoupper($row->nomeMunicipio)."</option>";
			}
		}

		/* Deleta um município */
		public function delete($_municipioId){        
			if(!is_numeric($_municipioId)){
				flash('message', 'Id inválido!', 'error'); 
				$this->view('imoveis/index');
				die();
			}        
			if(isset($_POST['delete'])){
				if($this->municipioModel->delete($_municipioId)){
					flash('message', 'Município excluido com sucessso!', 'success'); 
					redirect('municipios/index');
				} else {
					flash('message', 'Houve um erro ao tentar excluir o município, tente excluir novamente.', 'error'); 
					redirect('municipios/index');
				}                    
			} else {
				$data = $this->municipioModel->getMunicipioById($_municipioId);           
				$this->view('municipios/confirm',$data);
				die();
			}     
		}
	}//fim classe    
?>