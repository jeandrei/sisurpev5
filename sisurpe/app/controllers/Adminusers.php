<?php 
	class Adminusers extends Controller{
		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('pages/index');
				die();
			} 
			$this->userModel = $this->model('User');
		}     

		public function index(){             
			$limit = 10;			
			$data = [
				'title' => 'Busca por Usuários',
				'description' => 'Busca por registros de Usuários'          
			]; 
			if(isset($_GET['page'])){
				$page = $_GET['page'];  
			} else {  
				$page = 1;  
			} 			
			$options = array(
				'results_per_page' => 10,
				'url' => URLROOT . '/adminusers/index.php?page=*VAR*&cpf=' .  get('cpf') .'&name=' . get('name'), 
				'using_bound_params' => true,
				'named_params' => array(
																':cpf' => get('cpf'),
																':name' => get('name')																
																)     
			);            
			$paginate = $this->userModel->getUsers($page, $options);                     
			if($paginate->success == true) 
			{            
				// $data['paginate'] é só a parte da paginação tem que passar os dois arraya paginate e result
				$data['paginate'] = $paginate;
				// $result são os dados propriamente dito depois eu fasso um foreach para passar
				// os valores como posição que utilizo um métido para pegar
				$results = $paginate->resultset->fetchAll();  
			}  
			$data['results'] =  $results;  
			$this->view('adminusers/index', $data);
		}   
	}   
?>