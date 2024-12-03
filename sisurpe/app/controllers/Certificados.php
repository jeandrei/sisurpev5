<?php
	class Certificados extends Controller{

			public function __construct(){
				// 1 Chama o model
				//$this->postModel = $this->model('Post');
			}

			public function index(){				
				$fileDirectory = 'uploads/modeloCertificados/';
				$url = URLROOT .'/'. $fileDirectory;
				$files = scandir($fileDirectory);			
				foreach ($files as $file) {
					$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					if (in_array($extension, ['jpg', 'jpeg', 'pdf'])) {	
						$modelosCertificados[] = [
							'url' => $url.$file,
							'arquivo' => $fileDirectory.$file
						]; 
					}
				}							
				$data = [
					'titulo' => 'Bem-vindo!',
					'description'=> 'O SISURPE é um sistema de centralização de registros que visa facilitar os processos internos da Secretaria de Educação, bem como auxiliar no planejamento de ações estratégicas.',
					'modelosCertificados' => $modelosCertificados
				];				
				$this->view('certificados/index', $data);
			}	

			public function add(){
				if($_SERVER['REQUEST_METHOD'] == 'POST'){
					unset($data);            
					$data = [						
						'file' => 
							(isset($_FILES['file_post']) && !empty($_FILES['file_post']))
							? $_FILES['file_post']
							: '',						
						'file_post_err' => ''         
					]; 

					if($data['file']['name'] == ''){
						$data['file_post_err'] = 'Selecione um arquivo.';
					}
										
					if(   
						empty($data['file_post_err'])
					){
						try { 
							$erro = ''                         ;
							//verifico se foi passado um arquivo
							if(!empty($_FILES['file_post']['name'])){              
									/**
								* Faz o upload do arquivo do input id=file_post 
								* Utilizando a função upload que está no arquivo helpers/functions
								* Se tiver erro vai retornar o erro em $data['errors'];
								*/ 								                             
								$file = upload($arr = [
									'file' => 'file_post',
									'path' => 'uploads/modeloCertificados/',
									'extensions' => ["jpeg","jpg"],
									'maxsize' => 2097152									
								]);        
								//ser retornou sucesso é que fez o upload do arquivo e o mesmo retorna o caminho do arquivo
								if($file['sucess']){
									redirect('certificados/index');
								} else {																		
									if($file['errors']){
										foreach($file['errors'] as $row){
										$erro .= $row . ".";
										}
									} else {
										$erro = 'Ops! Algo deu errado ao tentar fazer o upload do arquivo.';
									}                                
									throw new Exception($erro);
								}
							} else {
								$data['file'] = '';
							}  
						} catch (Exception $e) { 
							if(isset($file['file'])){
								removeFile($file['file']);
							}                            
							$erro = 'Erro: '.  $e->getMessage(); 
							flash('message', $erro,'error');              
							redirect('certificados/index'); 
							die();
						}  
					} else {				
						$this->view('certificados/index', $data);
					}
				} else {
					$data = [						
						'file_post_err' => ''          
					];       
					redirect('certificados/index');
				}
			}
			
			public function delete(){
				$arquivo = $_GET['arquivo'];	
				$data['arquivo'] = $_GET['arquivo'];
				
				if(isset($_POST['delete'])){
					removeFile($arquivo);
					redirect('certificados/index');
					die();
				} else {                  
					$this->view('certificados/confirma',$data);
					exit();
				} 				
			}
	}