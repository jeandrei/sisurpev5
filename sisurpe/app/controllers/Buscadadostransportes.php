<?php
 class Buscadadostransportes extends Controller {

    public function __construct(){    
			if(!isLoggedIn()){
				redirect('users/login');
			}       
			$this->buscadadostransportesModel = $this->model('Buscadadostransporte');
			$this->anualModel = $this->model('Anual');
			$this->dataModel = $this->model('Datauser');
    }
   
    public function index(){         
			$limit = 10;
			$data = [
				'titulo' => 'Busca por daos de Transporte',
				'description' => 'Busca por registros anuais do Transporte Escolar'          
			];        
			if(isset($_GET['page'])) {  
				$page = $_GET['page'];  
			} else {  
				$page = 1;  
			}      
			$options = array(
				'results_per_page' => 10,
				'url' => URLROOT . '/buscadadostransportes/index.php?page=*VAR*&nome=' . $_GET['nome_aluno'] . '&ano=' . $_GET['ano'] . '&linha_id=' . $_GET['linha_id'] . '&escola_id=' . $_GET['escola_id'] . '&etapa_id=' . $_GET['etapa_id'] . '&turno=' . $_GET['turno'],
				'named_params' => array(
																':nome' => $_GET['nome_aluno'],
																':linha_id' => $_GET['linha_id'],                                  
																':escola_id' => $_GET['escola_id'],
																':etapa_id' => $_GET['etapa_id'],
																':turno' => $_GET['turno'],
																':ano' => $_GET['ano']
																		)     
			);        
			$paginate = $this->buscadadostransportesModel->getDados($page, $options); 
			if($paginate->success == true) {   
				$data['paginate'] = $paginate;
				$results = $paginate->resultset->fetchAll();            
			}          
			$data['results'] =  $results; 
			//SE O BOTÃO CLICADO FOR O IMPRIMIR EU CHAMO A FUNÇÃO getDados($page, $options,1) ONDE 1 É QUE É PARA IMPRIMIR E 0 É PARA LISTAR NA PAGINAÇÃO
			if($_GET['botao'] == "Imprimir"){  
				$data = $this->buscadadostransportesModel->getDados($page, $options,1);  
				// E AQUI CHAMO O RELATÓRIO          
				$this->view('relatorios/reTransporte' ,$data);
			} else if($_GET['botao'] == "Imprimir Totais") {
				// E AQUI CHAMO O RELATÓRIO TOTAIS CHAMO O RELATÓRIO DE TOTAIS
				$data = $this->buscadadostransportesModel->getTotais();            
				$this->view('relatorios/reTransporteTotais' ,$data);
			} else {
				// SE NÃO FOR IMPRIMIR CHAMO O INDEX COM OS DADOS NOVAMENTE
				$this->view('buscadadostransportes/index' ,$data);   
			}        
    }

    public function ver($id){ 
			$data = $this->dataModel->getAlunoById($id);        
			$data = [ 
			'aluno_id' => $data->aluno_id,
			'nome_aluno' => $data->nome_aluno,
			'nascimento' => date('Y-d-m', strtotime($data->nascimento)),          
			'sexo' => $data->sexo,
			'telefone_aluno' => $data->telefone_aluno,
			'email_aluno' =>  $data->email_aluno,
			'nome_pai' => $data->nome_pai,
			'telefone_pai' => $data->telefone_pai,
			'nome_mae' => $data->nome_mae,
			'telefone_mae' => $data->telefone_mae,
			'nome_responsavel' => $data->nome_responsavel,
			'telefone_resp' => $data->telefone_resp,
			'naturalidade' => $data->naturalidade,
			'nacionalidade' => $data->nacionalidade,
			'rg' => $data->rg,
			'uf_rg' => $data->uf_rg,
			'orgao_emissor' => $data->orgao_emissor,
			'titulo_eleitor' => $data->titulo_eleitor,
			'zona' => $data->zona,
			'secao' => $data->secao,
			'certidao' => $data->certidao,
			'uf_cert' => $data->uf_cert,
			'cartorio_cert' => $data->cartorio_cert,
			'modelo' => $data->modelo,
			'numero' => $data->numero,
			'folha' => $data->folha,
			'livro' => $data->livro,
			'data_emissao_cert' =>  date('Y-d-m', strtotime($data->data_emissao_cert)),
			'municipio_cert' => $data->municipio_cert,
			'cpf' => $data->cpf,
			'tipo_sanguineo' => $data->tipo_sanguineo,
			'fazUsoMed' => $data->fazUsoMed,
			'medicamentos' => $data->medicamentos,
			'alergias' =>  $data->alergias,
			'deficiencias' => $data->deficiencias,
			'restric_alimentos' => $data->restric_alimentos
    ];       
			$this->view('buscaalunos/ver', $data);
    } 
}//class