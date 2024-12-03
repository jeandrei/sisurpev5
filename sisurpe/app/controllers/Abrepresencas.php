<?php 
  class Abrepresencas extends Controller {
    public function __construct() {
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('pages/index');
        die();
      } 
      $this->abrePresencaModel = $this->model('Abrepresenca');    
      $this->inscricaoModel = $this->model('Inscricoe');
      $this->inscritoModel = $this->model('Inscrito');
      $this->temaModel = $this->model('Tema');
      $this->userModel = $this->model('User');      
    }
      
    public function index($inscricoes_id){ 
      if($this->inscricaoModel->getInscricaoById($inscricoes_id)->fase == 'FECHADO'){
        $data = [                
          'titulo' => 'Abrir presença',
          'description'=> 'Abrir presença para o curso',
          'curso' => isset($inscricoes_id)
                    ? $this->inscricaoModel->getInscricaoById($inscricoes_id)
                    : '',
          'presenca_em_andamento' => ($this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id))
                    ? $this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id)
                    : ''
        ];   
        $this->view('abrepresencas/index', $data);
      } else {
        die('Esta inscrição não está fechada!');
      }            
    }  

    public function add(){ 
      $inscricoes_id = post('inscricoes_id');
      if($_SERVER['REQUEST_METHOD'] == 'POST') { 
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
        $data = [              
          'inscricoes_id' => getData($inscricoes_id),              
          'carga_horaria' => 
            ($_POST['carga_horaria'] ? $_POST['carga_horaria'] : 0),            
          'total_carga_horaria_temas' => 
            ($this->temaModel->getTotalCargaHoraria($_POST['inscricoes_id']))
            ? $this->temaModel->getTotalCargaHoraria($_POST['inscricoes_id']) : '',            
          'total_carga_horaria_presencas' => 
            ($this->abrePresencaModel->getTotalCargaHorariaPresencas($_POST['inscricoes_id']))
            ? $this->abrePresencaModel->getTotalCargaHorariaPresencas($_POST['inscricoes_id']): '',
          'curso' => 
            ($this->inscricaoModel->getInscricaoById($_POST['inscricoes_id']))
            ? $this->inscricaoModel->getInscricaoById($_POST['inscricoes_id']) : '',   
          'presenca_em_andamento' => 
            ($this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id))
            ? $this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id) : ''             
        ];       

        if(empty($data['carga_horaria'])){
          $data['carga_horaria_err'] = 'Por favor informe a carga horária';
        } 
       
        if((intval($data['total_carga_horaria_presencas']) + intval($data['carga_horaria'])) > intval($data['total_carga_horaria_temas'])) {            
          $err = 'Total de carga horária não pode ser maior que o total de carga horária de todos os temas somados! O total de carga horária dos temas atual é de ' . $data['total_carga_horaria_temas'] . ' Horas.';          
          if(intval($data['total_carga_horaria_presencas']) > 0){                
            $err .= ' O total de carga horária já lançada para presença é de ' . $data['total_carga_horaria_presencas'] . 'Horas.' ;
          } 
          $data['carga_horaria_err'] = $err; 
        }
                  
        // Make sure errors are empty
        if(     
            empty($data['carga_horaria_err'])
        ){                   
          try {
            if($lastId = $this->abrePresencaModel->register($data)){              
              flash('message', 'Dados registrados com sucesso');                        
              redirect('abrepresencas/index/' . $data['inscricoes_id']);                   
            } else {
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
            }                 
          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(). "\n";
            flash('message', $erro,'alert alert-danger');
            $this->view('abrepresencas/index', $data);
          }
        } else {                  
          // Load the view with errors
          $this->view('abrepresencas/index', $data);
        } 
      } else {                   
        $data = [  
          'carga_horaria' => ''             
        ];                  
        $this->view('abrepresencas/index/', $data);
      }     
    }//add
      
  }