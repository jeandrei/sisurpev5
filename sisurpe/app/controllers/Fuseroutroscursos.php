<?php
  class Fuseroutroscursos extends Controller{

    public function __construct(){
      if((!isLoggedIn())){ 
        flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
        redirect('users/login');
        die();
      }   
      $this->fuserposModel = $this->model('Fuserpo');
      $this->foutrosCursosModel = $this->model('Foutroscurso');
      $this->fuseroutrosCursosModel = $this->model('Fuseroutroscurso');
      $this->fuserFormacoes = $this->model('Fuserformacao');
    }

    public function index() {  
      //se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
      if(!$this->fuserposModel->getUserPos($_SESSION[SE . '_user_id']) && $this->fuserFormacoes->getUserFormacoesById($_SESSION[SE . '_user_id'])=='e_superior'){
        flash('message', 'Você deve adicionar um curso de pós graduação primeiro!', 'error'); 
        redirect('fuserpos/index');
        die();
      }   
      $formacoes = $this->fuserFormacoes->getUserFormacoesById($_SESSION[SE . '_user_id']);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);  
        
        if(empty($_POST['outros'])){
          $data['outros_err'] = 'Por favor informe ao menos uma opção, se não tem nenhum curso informe a opção Nenhum.';
        } 

        if(                    
          empty($data['outros_err'])
        ){
          try {
            if($this->fuseroutrosCursosModel->register($_POST['outros'],$_SESSION[SE . '_user_id'])){
              flash('message', 'Pós registrada com sucesso!','success');                        
              redirect('fuseroutroscursos/index');
            } else {                                
              throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
            } 

          } catch (Exception $e) {
            $erro = 'Erro: '.  $e->getMessage(); 
            flash('message', $erro,'error');                       
            redirect('fuseroutroscursos/index');        
          }  
        }else {   
          $data['outrosCursos'] = $this->foutrosCursosModel->getOutrosCursos();                                   
          $this->view('fuseroutroscursos/index', $data);
        }           
      } else {
        if($userOutrosCursos = $this->fuseroutrosCursosModel->getUserOutrosCursos($_SESSION[SE . '_user_id'])){
          foreach($userOutrosCursos as $row){
            $userOutrosCursosIdArray[] = $row->cursoId;
          } 
        } else {
          $userOutrosCursosIdArray = 'null';
        }  
        $data = [
          'userFormacao' => $this->fuserFormacoes->getUserFormacoesById($_SESSION[SE . '_user_id']),
          'outrosCursos' => $this->foutrosCursosModel->getOutrosCursos(),
          'useroutrosCursosId' => $userOutrosCursosIdArray,
          'outros_err' => '',
          'voltarLink' => ($formacoes->maiorEscolaridade == 'e_superior') ? URLROOT .'/fuserpos/index' : URLROOT .'/fuserformacoes/index'              
        ];          
        $this->view('fuseroutroscursos/index',$data);
      }  
    } 
  }   
?>