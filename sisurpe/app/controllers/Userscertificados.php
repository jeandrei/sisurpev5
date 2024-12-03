<?php
	class Userscertificados extends Controller{

			public function __construct(){				
				$this->userCertModel = $this->model('Userscertificado');
				$this->inscricaoModel = $this->model('Inscricoe');
				$this->temaModel = $this->model('Tema');
				$this->userModel = $this->model('User');		
			}

			public function index($_userId){	
				if($inscricoes = $this->userCertModel->getPresencasByUsuarioId($_userId)){					
					foreach($inscricoes as $inscricao){
						$certificados[] = [
							'curso' => $this->inscricaoModel->getInscricaoById($inscricao->inscricoes_id),
							'temas' => $this->temaModel->getTemasInscricoesById($inscricao->inscricoes_id),
							'usuario' =>$this->userModel->getUserById($_userId),
							'presencas' =>$this->inscricaoModel->getPresencasUsuarioById($_userId,$inscricao->inscricoes_id)
						];
					}
				} else {
					$certificados = 'null';
				}		
				$data = [
					'title' => 'Meus Certificados',
					'certificados' => $certificados
				];				
				$this->view('userscertificados/index', $data);
			}			

			
	}