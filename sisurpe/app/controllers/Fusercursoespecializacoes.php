<?php 
  class Fusercursoespecializacoes extends Controller{

    public function __construct(){      
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('users/login');
        die();
      }
      $this->userModel = $this->model('User');        
      $this->fcomplementacaoModel = $this->model('Fcomplementacao');
      $this->fareasModel = $this->model('Fareacurso');
      $this->fuserEspCursosModel = $this->model('Fusercursoespecializacao');
    }

    public function index(){        
      $data = [
        'titulo' => 'Pós-Graduações concluídas', 
        'areaId' => '',           
        'user' => 
          ($_SESSION[DB_NAME . '_user_id'])
          ? $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id'])
          : '',
        'areas' => 
          ($this->fareasModel->getAreasCurso())
          ? $this->fareasModel->getAreasCurso()
          : '', 
        'userEspCursos' => 
          ($_SESSION[DB_NAME . '_user_id'])
          ? $this->fuserEspCursosModel->getUserEspCursos($_SESSION[DB_NAME . '_user_id'])
          : '',
        'areaId_err' => '',
        'anoConclusao_err' => '',
        'avancarLink' => URLROOT . '/fuseroutroscursos/index',
        'voltarLink' => URLROOT . '/fuserpos/index',
      ];        
      $this->view('fusercursoespecializacao/index', $data);
    }

    public function add(){       
      if($_SERVER['REQUEST_METHOD'] == 'POST'){ 
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [            
          'user' => 
            ($_SESSION[DB_NAME . '_user_id'])
            ? $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id'])
            : '',            
          'areas' => 
            ($this->fareasModel->getAreasCurso())
            ? $this->fareasModel->getAreasCurso()
            : '', 
          'userEspCursos' => ($_SESSION[DB_NAME . '_user_id'])
            ? $this->fuserEspCursosModel->getUserEspCursos($_SESSION[DB_NAME . '_user_id'])
            : '',
          'avancarLink' => URLROOT . '/fuseroutroscursos/index',
          'voltarLink' => URLROOT . '/fusercursoespecializacoes/index',
          'areaId' => post('areaId'),
          'anoConclusao' => post('anoConclusao'),
          'userId' => 
            ($_SESSION[DB_NAME . '_user_id'])
            ? $this->userModel->getUserById($_SESSION[DB_NAME . '_user_id'])->id
            : '',
          'areaId_err' => '',
          'anoConclusao_err' => '',
        ];

        if($data['areaId'] == 'null'){
          $data['areaId_err'] = 'Por favor selecione uma área.';
        }
        
        if(empty($data['anoConclusao']) || ($data['anoConclusao'] == 'null')){
          $data['anoConclusao_err'] = 'Por favor informe o ano de conclusão.';
        }

        if(
          empty($data['areaId_err']) &&             
          empty($data['anoConclusao_err'])    
        ) 
        {
          try {
            //verifico se o usuário já tem o curso de especialização/pos cadastrado
            if($this->fuserEspCursosModel->getUserEspCursoArea($data['userId'],$data['areaId'])){
              throw new Exception('Ops! Área do curso já adicionada!');
            }
            if($lastId = $this->fuserEspCursosModel->register($data)){                       
              $data['lastId'] = $lastId;                       
              flash('message', 'Cadastro realizado com sucesso!','success');                     
              redirect('fusercursoespecializacoes/index');
            } else {                        
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados!');
            }
          } catch (Exception $e) {                                  
            $erro = 'Erro: '.  $e->getMessage();                      
            flash('message', $erro,'error');                 
            $this->view('fusercursoespecializacao/index',$data);
          } 
        } else {  
          flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
          $this->view('fusercursoespecializacao/index',$data);
        }
      }
    }

    public function delete($_fupcId){        
      try {
        if($this->fuserEspCursosModel->delete($_fupcId)){           
          flash('message', 'Curso de especialização removido com sucesso!','success');                     
          redirect('fusercursoespecializacoes/index');
        } else {                        
          throw new Exception('Ops! Algo deu errado ao tentar excluir os dados!');
        }
      } catch (Exception $e) {                   
        $erro = 'Erro: '.  $e->getMessage();                      
        flash('message', $erro,'error');
        redirect('fusercursoespecializacoes/index');
      }
    }
  }   
?>