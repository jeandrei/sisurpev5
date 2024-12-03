<?php 
  class Fusercomplementacoes extends Controller{

    public function __construct(){        
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('users/login');
        die();
      }
      $this->userModel = $this->model('User');        
      $this->fcomplementacaoModel = $this->model('Fcomplementacao');
      $this->fusercomplementacoesModel = $this->model('Fusercomplementacao');
    }

    public function index(){   
      $data = [
        'titulo' => 'Formação/Complementação pedagógica ',            
        'user' => $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id']),
        'complementacoes' => $this->fcomplementacaoModel->getComplementacoes(), 
        'userComplementacoes' => $this->fusercomplementacoesModel->getUserComplementacoes($_SESSION[DB_NAME . '_user_id']),
        'avancarLink' => URLROOT . '/fuserpos/index',
        'voltarLink' => URLROOT . '/fusercursosuperiores/index',
        'cpId' => '',
        'cpId_err' => ''
      ];        
      $this->view('fusercomplementacao/index', $data);
    }

    public function add(){       
      if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [            
          'user' => $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id']),            
          'complementacoes' => $this->fcomplementacaoModel->getComplementacoes(),
          'userComplementacoes' => $this->fusercomplementacoesModel->getUserComplementacoes($_SESSION[DB_NAME . '_user_id']),
          'avancarLink' => URLROOT . '/fuserpos/index',
          'voltarLink' => URLROOT . '/fusercursosuperiores/index',
          'cpId' => post('cpId'),
          'userId' => $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id'])->id
        ];

        if($data['cpId'] == 'null'){
          $data['cpId_err'] = 'Por favor selecione uma formação.';
        }

        if(
          empty($data['cpId_err'])             
        ) 
        {
          try {                               
            //verifico se o usuário já tem a complementação cadastrada
            if($this->fusercomplementacoesModel->getUserComplementacao($data['userId'],$data['cpId'])){
              throw new Exception('Ops! Formação/Complementação já adicionada!');
            }
            if($lastId = $this->fusercomplementacoesModel->register($data)){                       
              $data['lastId'] = $lastId;                       
              flash('message', 'Cadastro realizado com sucesso!','success');                     
              redirect('fusercomplementacoes/index');
            } else {                        
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
            }
          } catch (Exception $e) {                                  
            $erro = 'Erro: '.  $e->getMessage();                      
            flash('message', $erro,'error');                 
            $this->view('fusercomplementacao/index',$data);
          } 
        } else {           
          //Validação falhou            
          flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
          $this->view('fusercomplementacao/index',$data);
        }
      }
    }

    public function delete($_fucpId){        
      try {
        if($this->fusercomplementacoesModel->delete($_fucpId,$_SESSION[DB_NAME . '_user_id'])){           
          flash('message', 'Formação/Complementação removida com sucesso!','success');                     
          redirect('fusercomplementacoes/index');
        } else {                        
          throw new Exception('Ops! Algo deu errado ao tentar excluir os dados!');
        }
      } catch (Exception $e) {                   
        $erro = 'Erro: '.  $e->getMessage();                      
        flash('message', $erro,'error');
        redirect('fusercomplementacoes/index');
      }
    }
  }   
?>