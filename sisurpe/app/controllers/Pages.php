<?php
	class Pages extends Controller{

			public function __construct(){
				// 1 Chama o model
				//$this->postModel = $this->model('Post');
			}

			public function index(){				
				// Posso passar valores aqui pois no view está definido um array para isso
				// public function view($view, $data = []){
				// 2 Chama um método
				//$posts = $this->postModel->getPosts();				
				// 3 coloca os valores no array
				$data = [
					'titulo' => 'Bem-vindo!',
					'description'=> 'O SISURPE é um sistema de centralização de registros que visa facilitar os processos internos da Secretaria de Educação, bem como auxiliar no planejamento de ações estratégicas.'
				];
				// 4 Chama o view passando os dados
				$this->view('pages/index', $data);
			}

			public function about(){
				$data = [
					'titulo' => 'Sobre Nós',
					'description'=> 'Sistema Unificado de Registros de Penha - SISURPE'
				];
				$this->view('pages/about', $data);           
			}		
      
      public function leitorqr(){
				$data = [
					'titulo' => 'Leitor de QR Code',
					'description'=> 'Para marcar sua presença, aponte para o código QR.'
				];
				$this->view('pages/leitorqr', $data);           
			}	

			public function modelo_pagina(){
				$data = [
					'titulo' => 'Página de modelo',
					'description'=> 'Modelo de página simples'
				];
				$this->view('pages/modelo_pagina', $data);           
			}

			//Exibe as configurações do php
			public function phpinfo(){            
				$this->view('pages/phpinfo');           
			}
	}